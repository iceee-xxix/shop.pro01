<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriesExpenses;
use Illuminate\Http\Request;

class CategoryExpenses extends Controller
{
    public function category_expenses()
    {
        $data['function_key'] = __FUNCTION__;
        return view('category_expenses.index', $data);
    }

    public function categoryexpenseslistData()
    {
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $category = CategoriesExpenses::get();

        if (count($category) > 0) {
            $info = [];
            foreach ($category as $rs) {
                $action = '<a href="' . route('CategoryExpensesEdit', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-edit-alt"></i></a>
                <button type="button" data-id="' . $rs->id . '" class="btn btn-sm btn-outline-danger deleteCategory" title="ลบ"><i class="bx bxs-trash"></i></button>';
                $info[] = [
                    'name' => $rs->name,
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

    public function CategoryExpensesCreate()
    {
        $data['function_key'] = 'category_expenses';
        return view('category_expenses.create', $data);
    }

    public function CategoryExpensesEdit($id)
    {
        $function_key = 'category_expenses';
        $info = CategoriesExpenses::find($id);

        return view('category_expenses.edit', compact('info', 'function_key'));
    }

    public function CategoryExpensesSave(Request $request)
    {
        $input = $request->input();
        if (!isset($input['id'])) {
            $category = new CategoriesExpenses();
            $category->name = $input['name'];
            if ($category->save()) {
                return redirect()->route('category_expenses')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        } else {
            $category = CategoriesExpenses::find($input['id']);
            $category->name = $input['name'];
            if ($category->save()) {
                return redirect()->route('category_expenses')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        }
        return redirect()->route('category_expenses')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    public function CategoryExpensesDelete(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ลบข้อมูลไม่สำเร็จ',
        ];
        $id = $request->input('id');
        if ($id) {
            $delete = CategoriesExpenses::find($id);
            if ($delete->delete()) {
                $data = [
                    'status' => true,
                    'message' => 'ลบข้อมูลเรียบร้อยแล้ว',
                ];
            }
        }

        return response()->json($data);
    }
}
