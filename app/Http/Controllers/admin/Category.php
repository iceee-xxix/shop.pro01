<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Categories_files;
use Illuminate\Http\Request;

class Category extends Controller
{
    public function category()
    {
        $data['function_key'] = __FUNCTION__;
        return view('category.index', $data);
    }

    public function categorylistData()
    {
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $category = Categories::get();

        if (count($category) > 0) {
            $info = [];
            foreach ($category as $rs) {
                $action = '<a href="' . route('CategoryEdit', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-edit-alt"></i></a>
                <button type="button" data-id="' . $rs->id . '" class="btn btn-sm btn-outline-danger deleteCategory" title="ลบ"><i class="bx bxs-trash"></i></button>';
                $info[] = [
                    'name' => $rs->name,
                    'icon' => $rs->icon,
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

    public function CategoryCreate()
    {
        $data['function_key'] = 'category';
        return view('category.create', $data);
    }

    public function CategorySave(Request $request)
    {
        $input = $request->input();
        if (!isset($input['id'])) {
            $category = new Categories();
            $category->name = $input['name'];
            if ($category->save()) {
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('image', $filename, 'public');

                    $categories_file = new Categories_files();
                    $categories_file->categories_id = $category->id;
                    $categories_file->file = $path;
                    $categories_file->save();
                }
                return redirect()->route('category')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        } else {
            $category = Categories::find($input['id']);
            $category->name = $input['name'];
            if ($category->save()) {
                if ($request->hasFile('file')) {
                    $categories_file = Categories_files::where('categories_id', $input['id'])->delete();
                    
                    $file = $request->file('file');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('image', $filename, 'public');

                    $categories_file = new Categories_files();
                    $categories_file->categories_id = $category->id;
                    $categories_file->file = $path;
                    $categories_file->save();
                }
                return redirect()->route('category')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        }
        return redirect()->route('category')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    public function CategoryEdit($id)
    {
        $function_key = 'category';
        $info = Categories::with('files')->find($id);

        return view('category.edit', compact('info', 'function_key'));
    }

    public function CategoryDelete(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ลบข้อมูลไม่สำเร็จ',
        ];
        $id = $request->input('id');
        if ($id) {
            $delete = Categories::find($id);
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
