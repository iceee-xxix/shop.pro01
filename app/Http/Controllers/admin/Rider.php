<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Pay;
use App\Models\PayGroup;
use App\Models\RiderSend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Rider extends Controller
{
    public function rider()
    {
        $data['function_key'] = __FUNCTION__;
        return view('rider.index', $data);
    }

    public function riderlistData()
    {
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $table = User::where('is_rider', 1)->get();

        if (count($table) > 0) {
            $info = [];
            foreach ($table as $rs) {
                $action = '<a href="' . route('riderEdit', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-edit-alt"></i></a>
                <button type="button" data-id="' . $rs->id . '" class="btn btn-sm btn-outline-danger deleteTable" title="ลบ"><i class="bx bxs-trash"></i></button>';
                $info[] = [
                    'name' => $rs->name,
                    'email' => $rs->email,
                    'tel' => $rs->tel,
                    'action' => $action
                ];
            }
            $data = [
                'data' => $info,
                'status' => true,
                'message' => 'success'
            ];
        }
        return response()->json($data);
    }

    public function riderDelete(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ลบข้อมูลไม่สำเร็จ',
        ];
        $id = $request->input('id');
        if ($id) {
            $delete = User::find($id);
            if ($delete->delete()) {
                $data = [
                    'status' => true,
                    'message' => 'ลบข้อมูลเรียบร้อยแล้ว',
                ];
            }
        }

        return response()->json($data);
    }

    public function riderCreate()
    {
        $data['function_key'] = 'rider';
        return view('rider.create', $data);
    }

    public function riderEdit($id)
    {
        $function_key = 'rider';
        $info = User::find($id);

        return view('rider.edit', compact('info', 'function_key'));
    }

    public function riderSave(Request $request)
    {
        $input = $request->input();
        if (!isset($input['id'])) {
            $table = new User();
            $table->name = $input['name'];
            $table->email = $input['email'];
            $table->tel = $input['tel'];
            $table->role = 'admin';
            $table->email_verified_at = now();
            $table->password = Hash::make('123456789');
            $table->remember_token = null;
            $table->is_rider = 1;
            if ($table->save()) {
                return redirect()->route('rider')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        } else {
            $table = User::find($input['id']);
            $table->name = $input['name'];
            $table->email = $input['email'];
            $table->tel = $input['tel'];
            if ($table->save()) {
                return redirect()->route('rider')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        }
        return redirect()->route('rider')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    public function OrderRider()
    {
        $data['function_key'] = 'OrderRider';
        return view('rider.OrderRider', $data);
    }

    public function OrderRiderlistData()
    {
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $table = RiderSend::select('rider_sends.*', 'users.name', 'users_addresses.lat', 'users_addresses.long', 'users_addresses.tel', 'users_addresses.detail', 'orders.total')
            ->where('rider_sends.status', 0)
            ->where('rider_id', Session::get('user')->id)
            ->join('orders', 'orders.id', '=', 'rider_sends.order_id')
            ->join('users', 'users.id', '=', 'orders.users_id')
            ->join('users_addresses', 'users_addresses.id', '=', 'orders.address_id')
            ->get();

        if (count($table) > 0) {
            $info = [];
            foreach ($table as $rs) {
                $pay = '<button data-id="' . $rs->order_id . '" data-total="' . $rs->total . '" type="button" class="btn btn-sm btn-outline-success modalPay">ชำระเงิน</button>';
                $action = '<button data-id="' . $rs->order_id . '" type="button" class="btn btn-sm btn-outline-primary modalShow m-1">รายละเอียด</button>' . $pay;
                $info[] = [
                    'name' => $rs->name,
                    'tel' => $rs->tel,
                    'location' => "<a class='btn btn-sm btn-outline-primary m-1' href='https://www.google.com/maps?q=" . $rs->lat . "," . $rs->long . "' target='_blank'>เปิดแผนที่</a>",
                    'detail' => $rs->detail,
                    'action' => $action
                ];
            }
            $data = [
                'data' => $info,
                'status' => true,
                'message' => 'success'
            ];
        }
        return response()->json($data);
    }

    public function Riderconfirm_pay(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ชำระเงินไม่สำเร็จ',
        ];
        $id = $request->input('id');
        if ($id) {
            $order = Orders::find($id);
            $order->status = 3;
            if ($order->save()) {
                $pay = new Pay();
                $pay->payment_number = $this->generateRunningNumber();
                $pay->total = $order->total;
                if ($pay->save()) {
                    $order = Orders::where('id', $id)->get();
                    foreach ($order as $rs) {
                        $rs->status = 3;
                        if ($rs->save()) {
                            $paygroup = new PayGroup();
                            $paygroup->pay_id = $pay->id;
                            $paygroup->order_id = $rs->id;
                            $paygroup->save();
                        }
                    }
                    $data = [
                        'status' => true,
                        'message' => 'ชำระเงินเรียบร้อยแล้ว',
                    ];
                }

                $rider = RiderSend::where('order_id', $id)->first();
                $rider->status = 1;
                if ($rider->save()) {
                    $data = [
                        'status' => true,
                        'message' => 'ชำระเงินเรียบร้อยแล้ว',
                    ];
                }
            }
        }
        return response()->json($data);
    }

    function generateRunningNumber($prefix = '', $padLength = 7)
    {
        $latest = Pay::orderBy('id', 'desc')->first();

        if ($latest && isset($latest->payment_number)) {
            $number = (int) ltrim($latest->payment_number, '0');
            $next = $number + 1;
        } else {
            $next = 1;
        }

        return $prefix . str_pad($next, $padLength, '0', STR_PAD_LEFT);
    }
}
