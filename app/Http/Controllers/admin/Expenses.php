<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriesExpenses;
use App\Models\Expenses as ModelsExpenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Expenses extends Controller
{
    public function expenses()
    {
        $data['function_key'] = __FUNCTION__;
        $data['expensesday'] = ModelsExpenses::select(DB::raw("SUM(price)as total"))->whereDay('date', date('d'))->first();
        $data['expensesmouth'] = ModelsExpenses::select(DB::raw("SUM(price)as total"))->whereMonth('date', date('m'))->first();
        $data['expensesyear'] = ModelsExpenses::select(DB::raw("SUM(price)as total"))->whereYear('date', date('Y'))->first();
        return view('expenses.index', $data);
    }

    public function expenseslistData()
    {
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $category = ModelsExpenses::with('category')->get();

        if (count($category) > 0) {
            $info = [];
            foreach ($category as $rs) {
                $action = '<a href="' . route('ExpensesEdit', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-edit-alt"></i></a>
                <button type="button" data-id="' . $rs->id . '" class="btn btn-sm btn-outline-danger deleteCategory" title="ลบ"><i class="bx bxs-trash"></i></button>';
                $info[] = [
                    'name' => $rs->name,
                    'category' => $rs['category']->name,
                    'price' => $rs->price,
                    'date' => $this->DateThai($rs->date),
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

    public function ExpensesCreate()
    {
        $data['function_key'] = 'expenses';
        $data['category'] = CategoriesExpenses::get();
        return view('expenses.create', $data);
    }

    public function ExpensesEdit($id)
    {
        $function_key = 'expenses';
        $info = ModelsExpenses::find($id);
        $category = CategoriesExpenses::get();

        return view('expenses.edit', compact('info', 'function_key', 'category'));
    }

    public function ExpensesSave(Request $request)
    {
        $input = $request->input();
        if (!isset($input['id'])) {
            $expenses = new ModelsExpenses();
            $expenses->name = $input['name'];
            $expenses->category_id = $input['category_id'];
            $expenses->price = $input['price'];
            $expenses->date = $input['date'];
            if ($expenses->save()) {
                return redirect()->route('expenses')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        } else {
            $expenses = ModelsExpenses::find($input['id']);
            $expenses->name = $input['name'];
            $expenses->name = $input['name'];
            $expenses->category_id = $input['category_id'];
            $expenses->price = $input['price'];
            $expenses->date = $input['date'];
            if ($expenses->save()) {
                return redirect()->route('expenses')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        }
        return redirect()->route('expenses')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    public function ExpensesDelete(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ลบข้อมูลไม่สำเร็จ',
        ];
        $id = $request->input('id');
        if ($id) {
            $delete = ModelsExpenses::find($id);
            if ($delete->delete()) {
                $data = [
                    'status' => true,
                    'message' => 'ลบข้อมูลเรียบร้อยแล้ว',
                ];
            }
        }

        return response()->json($data);
    }

    function DateThai($strDate)
    {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
}
