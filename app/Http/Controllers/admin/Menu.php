<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Menu as ModelsMenu;
use App\Models\MenuFiles;
use App\Models\MenuOption;
use Illuminate\Http\Request;

class Menu extends Controller
{
    public function menu()
    {
        $data['function_key'] = __FUNCTION__;
        return view('menu.index', $data);
    }


    public function menulistData()
    {
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $menu = ModelsMenu::with('category')->get();

        if (count($menu) > 0) {
            $info = [];
            foreach ($menu as $rs) {
                $price = '<a href="' . route('menuOption', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-list-ol"></i></a>';
                $action = '<a href="' . route('menuEdit', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-edit-alt"></i></a>
                <button type="button" data-id="' . $rs->id . '" class="btn btn-sm btn-outline-danger deleteMenu" title="ลบ"><i class="bx bxs-trash"></i></button>';
                $info[] = [
                    'name' => $rs->name,
                    'category' => $rs['category']->name,
                    'price' => $price,
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

    public function MenuCreate()
    {
        $data['function_key'] = 'menu';
        $data['category'] = Categories::get();
        return view('menu.create', $data);
    }

    public function menuSave(Request $request)
    {
        $input = $request->input();
        if (!isset($input['id'])) {
            $menu = new ModelsMenu();
            $menu->name = $input['name'];
            $menu->categories_id = $input['categories_id'];
            $menu->detail = $input['detail'];
            if ($menu->save()) {
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('image', $filename, 'public');

                    $categories_file = new MenuFiles();
                    $categories_file->menu_id = $menu->id;
                    $categories_file->file = $path;
                    $categories_file->save();
                }
                return redirect()->route('menu')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        } else {
            $menu = ModelsMenu::find($input['id']);
            $menu->name = $input['name'];
            $menu->categories_id = $input['categories_id'];
            $menu->detail = $input['detail'];
            if ($menu->save()) {
                if ($request->hasFile('file')) {
                    $categories_file = MenuFiles::where('menu_id', $input['id'])->delete();

                    $file = $request->file('file');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('image', $filename, 'public');

                    $categories_file = new MenuFiles();
                    $categories_file->menu_id = $menu->id;
                    $categories_file->file = $path;
                    $categories_file->save();
                }
                return redirect()->route('menu')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        }
        return redirect()->route('menu')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    public function menuEdit($id)
    {
        $function_key = 'menu';
        $info = ModelsMenu::with('files', 'category')->find($id);
        $category = Categories::get();

        return view('menu.edit', compact('info', 'function_key', 'category'));
    }

    public function menuDelete(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ลบข้อมูลไม่สำเร็จ',
        ];
        $id = $request->input('id');
        if ($id) {
            $delete = ModelsMenu::find($id);
            if ($delete->delete()) {
                $data = [
                    'status' => true,
                    'message' => 'ลบข้อมูลเรียบร้อยแล้ว',
                ];
            }
        }

        return response()->json($data);
    }

    public function menuOption($id)
    {
        $data['function_key'] = 'menu';
        $data['id'] = $id;
        $data['info'] = ModelsMenu::find($id);
        return view('menu.option.index', $data);
    }

    public function menulistOption(Request $request)
    {
        $id = $request->input('id');
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $menuOption = MenuOption::where('menu_id', $id)->get();

        if (count($menuOption) > 0) {
            $info = [];
            foreach ($menuOption as $rs) {
                $action = '<a href="' . route('menuOptionEdit', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-edit-alt"></i></a>';
                $info[] = [
                    'name' => $rs->type,
                    'price' => $rs->price . ' บาท',
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

    public function menulistOptionCreate($id)
    {
        $data['function_key'] = 'menu';
        $data['id'] = $id;
        return view('menu.option.create', $data);
    }

    public function menuOptionSave(Request $request)
    {
        $input = $request->input();
        $menu = new menuOption();
        $menu->type = $input['name'];
        $menu->price = $input['price'];
        $menu->menu_id = $input['menu_id'];
        if ($menu->save()) {
            return redirect()->route('menuOption', $input['menu_id'])->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
        }
        return redirect()->route('menuOption', $input['menu_id'])->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    public function menuOptionEdit($id)
    {
        $function_key = 'menu';
        $info = menuOption::find($id);

        return view('menu.option.edit', compact('info', 'function_key'));
    }

    public function menuOptionUpdate(Request $request)
    {
        $input = $request->input();
        $menu = menuOption::find($input['id']);
        $menu->type = $input['name'];
        $menu->price = $input['price'];
        if ($menu->save()) {
            return redirect()->route('menuOption', $input['id'])->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
        }
        return redirect()->route('menuOption', $input['id'])->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }
}
