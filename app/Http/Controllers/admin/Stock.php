<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LogStock;
use App\Models\MenuStock;
use App\Models\Stock as ModelsStock;
use Illuminate\Http\Request;

class Stock extends Controller
{
    public function stock()
    {
        $data['function_key'] = __FUNCTION__;
        return view('stock.index', $data);
    }

    public function stocklistData()
    {
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $stock = ModelsStock::get();

        if (count($stock) > 0) {
            $info = [];
            foreach ($stock as $rs) {
                $action = '<a href="' . route('stockEdit', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-edit-alt"></i></a>
                <a href="' . route('stockDetail', $rs->id) . '" class="btn btn-sm btn-outline-success" title="ความเคลื่อนไหว"><i class="bx bx-stats"></i></a>';
                $amount_alert = '';
                if ($rs->amount < $rs->amount_alert) {
                    $amount_alert = ' <i class="bx bxs-error-circle" style="color:red;font-size:25px;"></i>';
                }
                $info[] = [
                    'name' => $rs->name . $amount_alert,
                    'unit' => $rs->unit,
                    'amount' => $rs->amount,
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

    public function stockCreate()
    {
        $data['function_key'] = 'stock';
        return view('stock.create', $data);
    }

    public function stockEdit($id)
    {
        $function_key = 'stock';
        $info = ModelsStock::find($id);

        return view('stock.edit', compact('info', 'function_key'));
    }

    public function stockSave(Request $request)
    {
        $input = $request->input();
        if (!isset($input['id'])) {
            $stock = new ModelsStock();
            $stock->name = $input['name'];
            $stock->amount = $input['amount'];
            $stock->unit = $input['unit'];
            $stock->amount_alert = $input['amount_alert'];
            if ($stock->save()) {
                $log_stock = new LogStock();
                $log_stock->stock_id = $stock->id;
                $log_stock->amount = $input['amount'];
                $log_stock->status = 1;
                if ($log_stock->save()) {
                    return redirect()->route('stock')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
                }
            }
        } else {
            $stock = ModelsStock::find($input['id']);
            $stock->name = $input['name'];
            $stock->amount = $input['amount'];
            $stock->unit = $input['unit'];
            $stock->amount_alert = $input['amount_alert'];
            if ($stock->save()) {
                $log_stock = new LogStock();
                $log_stock->stock_id = $stock->id;
                $log_stock->amount = $input['amount'];
                $log_stock->status = 1;
                if ($log_stock->save()) {
                    return redirect()->route('stock')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
                }
            }
        }
        return redirect()->route('stock')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    public function stockDelete(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ลบข้อมูลไม่สำเร็จ',
        ];
        $id = $request->input('id');
        if ($id) {
            $delete = ModelsStock::find($id);
            if ($delete->delete()) {
                $data = [
                    'status' => true,
                    'message' => 'ลบข้อมูลเรียบร้อยแล้ว',
                ];
            }
        }

        return response()->json($data);
    }

    public function menuOptionStock($id)
    {
        $data['function_key'] = 'menu';
        $data['id'] = $id;
        return view('stock.menuStock', $data);
    }

    public function menustocklistData(Request $request)
    {
        $input = $request->input();
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $stock = MenuStock::where('menu_option_id', $input['id'])->get();

        if (count($stock) > 0) {
            $info = [];
            foreach ($stock as $rs) {
                $stock = ModelsStock::find($rs->stock_id);
                $action = '<a href="' . route('menuStockedit', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-edit-alt"></i></a>
                <button type="button" data-id="' . $rs->id . '" class="btn btn-sm btn-outline-danger deletemenuStock" title="ลบ"><i class="bx bxs-trash"></i></button>';
                $info[] = [
                    'name' => $stock->name,
                    'unit' => $stock->unit,
                    'amount' => $rs->amount,
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

    public function menuStockcreate($id)
    {
        $data['function_key'] = 'menu';
        $data['stock'] = ModelsStock::get();
        $data['id'] = $id;
        return view('stock.menuStockcreate', $data);
    }

    public function menuStockedit($id)
    {
        $data['function_key'] = 'menu';
        $data['stock'] = ModelsStock::get();
        $data['info'] = MenuStock::find($id);
        return view('stock.menuStockedit', $data);
    }

    public function menustockSave(Request $request)
    {
        $input = $request->input();
        if (!isset($input['id'])) {
            $menu = new MenuStock();
            $menu->stock_id = $input['stock_id'];
            $menu->menu_option_id = $input['menu_option_id'];
            $menu->amount = $input['amount'];
            if ($menu->save()) {
                return redirect()->route('menuOptionStock', $input['menu_option_id'])->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        } else {
            $menu = MenuStock::find($input['id']);
            $menu->stock_id = $input['stock_id'];
            $menu->amount = $input['amount'];
            if ($menu->save()) {
                return redirect()->route('menuOptionStock', $menu->menu_option_id)->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        }
        return redirect()->route('menu', $input['menu_option_id'])->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    public function menustockDelete(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ลบข้อมูลไม่สำเร็จ',
        ];
        $id = $request->input('id');
        if ($id) {
            $delete = MenuStock::find($id);
            if ($delete->delete()) {
                $data = [
                    'status' => true,
                    'message' => 'ลบข้อมูลเรียบร้อยแล้ว',
                ];
            }
        }

        return response()->json($data);
    }

    public function stockDetail($id)
    {
        $function_key = 'stock';
        $stock = ModelsStock::find($id);
        $info = LogStock::where('stock_id', $id)->get();
        $item_key = [];
        $item_list = [];
        foreach ($info as $key => $value) {
            $item_key[] = $key + 1;
            if ($value->status == 1) {
                $item_list[] = $value->amount;
            } else {
                $item_list[] = $value->old_amount - $value->amount;
            }
        }
        return view('stock.detail', compact('info', 'function_key', 'item_key', 'item_list', 'stock'));
    }
}
