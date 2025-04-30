<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\LogStock;
use App\Models\Menu;
use App\Models\MenuStock;
use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Models\Promotion;
use App\Models\Stock;
use App\Models\User;
use App\Models\UsersAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Delivery extends Controller
{
    public function index(Request $request)
    {
        $table_id = $request->input('table');
        if ($table_id) {
            session(['table_id' => $table_id]);
        }
        $promotion = Promotion::where('is_status', 1)->get();
        $category = Categories::has('menu')->with('files')->get();
        return view('delivery.main_page', compact('category', 'promotion'));
    }

    public function login()
    {
        return view('userslogin');
    }

    public function detail($id)
    {
        $menu = Menu::where('categories_id', $id)->with('files', 'option')->orderBy('created_at', 'asc')->get();
        return view('delivery.detail_page', compact('menu'));
    }

    public function order()
    {
        $address = [];
        if (Session::get('user')) {
            $address = UsersAddress::where('users_id', Session::get('user')->id)->get();
        }
        return view('delivery.list_page', compact('address'));
    }

    public function SendOrder(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'สั่งออเดอร์ไม่สำเร็จ',
        ];
        if (Session::get('user')) {
            $orderData = $request->input('orderData');
            $remark = $request->input('remark');
            $item = array();
            $total = 0;
            foreach ($orderData as $order) {
                foreach ($order as $rs) {
                    $item[] = [
                        'id' => $rs['id'],
                        'price' => $rs['price'],
                        'option' => $rs['option'],
                        'qty' => $rs['qty'],
                    ];
                    $total = $total + ($rs['price'] * $rs['qty']);
                }
            }

            if (!empty($item)) {
                $info = UsersAddress::where('is_use', 1)->where('users_id', Session::get('user')->id)->first();
                if ($info != null) {
                    $order = new Orders();
                    $order->users_id = Session::get('user')->id;
                    $order->address_id = $info->id;
                    $order->total = $total;
                    $order->remark = $remark;
                    $order->status = 1;
                    if ($order->save()) {
                        foreach ($item as $rs) {
                            $orderdetail = new OrdersDetails();
                            $orderdetail->order_id = $order->id;
                            $orderdetail->menu_id = $rs['id'];
                            $orderdetail->option_id = $rs['option'];
                            $orderdetail->quantity = $rs['qty'];
                            $orderdetail->price = $rs['price'];
                            if ($orderdetail->save()) {
                                $menuStock = MenuStock::where('menu_option_id', $rs['option'])->get();
                                foreach ($menuStock as $stock_rs) {
                                    $stock = Stock::find($stock_rs->stock_id);
                                    $stock->amount = $stock->amount - ($stock_rs->amount * $rs['qty']);
                                    if ($stock->save()) {
                                        $log_stock = new LogStock();
                                        $log_stock->stock_id = $stock_rs->stock_id;
                                        $log_stock->order_id = $order->id;
                                        $log_stock->menu_option_id = $rs['option'];
                                        $log_stock->old_amount = $stock->amount;
                                        $log_stock->amount = ($stock_rs->amount * $rs['qty']);
                                        $log_stock->status = 2;
                                        $log_stock->save();
                                    }
                                }
                            }
                        }
                    }
                    event(new OrderCreated(['📦 มีออเดอร์ใหม่']));
                    $data = [
                        'status' => true,
                        'message' => 'สั่งออเดอร์เรียบร้อยแล้ว',
                    ];
                } else {
                    $data = [
                        'status' => false,
                        'message' => 'กรุณาเพิ่มที่อยู่จัดส่ง',
                    ];
                }
            }
        } else {
            $data = [
                'status' => false,
                'message' => 'กรุณาล็อกอินเพื่อสั่งอาหาร',
            ];
        }
        return response()->json($data);
    }

    public function sendEmp()
    {
        event(new OrderCreated(['ลูกค้าเรียกจากโต้ะที่ ' . session('table_id')]));
    }

    public function users()
    {
        $address = UsersAddress::where('users_id', Session::get('user')->id)->get();
        return view('delivery.users', compact('address'));
    }

    public function createaddress()
    {
        return view('delivery.address');
    }

    public function addressSave(Request $request)
    {
        $input = $request->post();

        if (!isset($input['id'])) {
            $address = new UsersAddress();
            $address->users_id = Session::get('user')->id;
            $address->name = $input['name'];
            $address->lat = $input['lat'];
            $address->long = $input['lng'];
            $address->tel = $input['tel'];
            $address->detail = $input['detail'];
            $address->is_use = 0;
            if ($address->save()) {
                return redirect()->route('delivery.users')->with('success', 'เพิ่มที่อยู่เรียบร้อยแล้ว');
            }
        } else {
            $address = UsersAddress::find($input['id']);
            $address->name = $input['name'];
            $address->lat = $input['lat'];
            $address->long = $input['lng'];
            $address->tel = $input['tel'];
            $address->detail = $input['detail'];
            if ($address->save()) {
                return redirect()->route('delivery.users')->with('success', 'แก้ไขที่อยู่เรียบร้อยแล้ว');
            }
        }

        return redirect()->route('delivery.users')->with('error', 'ไม่สามารถเพิ่มที่อยู่ได้');
    }

    public function change(Request $request)
    {
        $input = $request->post();
        $address = UsersAddress::where('users_id', Session::get('user')->id)->get();
        foreach ($address as $rs) {
            $rs->is_use = 0;
            $rs->save();
        }
        $address = UsersAddress::find($input['id']);
        $address->is_use = 1;
        $address->save();
    }

    public function editaddress($id)
    {
        $info = UsersAddress::find($id);
        return view('delivery.editaddress', compact('info'));
    }

    public function usersSave(Request $request)
    {
        $input = $request->post();
        $users = User::find(Session::get('user')->id);
        $users->name = $input['name'];
        $users->email = $input['email'];
        if ($users->save()) {
            Session::put('user', $users);
            return redirect()->route('delivery.users')->with('success', 'เพิ่มที่อยู่เรียบร้อยแล้ว');
        }
        return redirect()->route('delivery.users')->with('error', 'ไม่สามารถเพิ่มที่อยู่ได้');
    }

    public function listorder()
    {
        $orderlist = [];
        if (Session::get('user')) {
            $orderlist = Orders::select('orders.*', 'users.name', 'users.tel')
                ->where('users_id', Session::get('user')->id)
                ->leftJoin('rider_sends', 'orders.id', '=', 'rider_sends.order_id')
                ->leftJoin('users', 'rider_sends.rider_id', '=', 'users.id')
                ->get();
        }
        return view('delivery.order', compact('orderlist'));
    }

    public function listOrderDetail(Request $request)
    {
        $orders = OrdersDetails::select('menu_id')
            ->where('order_id', $request->input('id'))
            ->groupBy('menu_id')
            ->get();

        if (count($orders) > 0) {
            $info = '';
            foreach ($orders as $key => $value) {
                $order = OrdersDetails::where('order_id', $request->input('id'))
                    ->where('menu_id', $value->menu_id)
                    ->with('menu', 'option')
                    ->get();
                $info .= '<div class="card text-white bg-primary mb-3"><div class="card-body"><h5 class="card-title text-white">' . $order[0]['menu']->name . '</h5><p class="card-text">';
                foreach ($order as $rs) {
                    $info .= '' . $rs['menu']->name . ' (' . $rs['option']->type . ') จำนวน ' . $rs->quantity . ' ราคา ' . ($rs->quantity * $rs->price) . ' บาท <br>';
                }
                $info .= '</p></div></div>';
            }
        }
        echo $info;
    }

    public function register()
    {
        return view('usersRegister');
    }

    public function UsersRegister(Request $request)
    {
        $input = $request->input();
        $users = new User;
        $users->name = $input['name'];
        $users->tel = $input['tel'];
        $users->email = $input['email'];
        $users->password = Hash::make($input['password']);
        $users->email_verified_at = now();
        if ($users->save()) {
            return redirect()->route('delivery.login')->with('success', 'สมัครสมาชิกเรียบร้อยแล้ว');
        }
        return redirect()->route('delivery.register')->with('error', 'สมัครสมาชิกไม่สำเร็จ');
    }
}
