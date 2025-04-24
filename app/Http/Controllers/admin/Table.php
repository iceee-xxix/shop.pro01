<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Table as ModelsTable;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Table extends Controller
{
    public function table()
    {
        $data['function_key'] = __FUNCTION__;
        return view('table.index', $data);
    }

    public function tablelistData()
    {
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $table = ModelsTable::get();

        if (count($table) > 0) {
            $info = [];
            foreach ($table as $rs) {
                $qr_code = '<button data-id="' . $rs->id . '" type="button" class="btn btn-sm btn-outline-primary modalQr"><i class="bx bx-search-alt-2"></i></button>';
                $action = '<a href="' . route('tableEdit', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-edit-alt"></i></a>
                <button type="button" data-id="' . $rs->id . '" class="btn btn-sm btn-outline-danger deleteTable" title="ลบ"><i class="bx bxs-trash"></i></button>';
                $info[] = [
                    'number' => $rs->table_number,
                    'qr_code' => $qr_code,
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

    public function tableCreate()
    {
        $data['function_key'] = 'table';
        return view('table.create', $data);
    }

    public function tableEdit($id)
    {
        $function_key = 'category';
        $info = ModelsTable::find($id);

        return view('table.edit', compact('info', 'function_key'));
    }

    public function tableSave(Request $request)
    {
        $input = $request->input();
        if (!isset($input['id'])) {
            $table = new ModelsTable();
            $table->table_number = $input['table_number'];
            $table->qr_code = QrCode::size(200)->generate(url('?table=' . $input['table_number']));
            if ($table->save()) {
                return redirect()->route('table')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        } else {
            $table = ModelsTable::find($input['id']);
            $table->table_number = $input['table_number'];
            $table->qr_code = QrCode::size(200)->generate(url('?table=' . $input['table_number']));
            if ($table->save()) {
                return redirect()->route('table')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        }
        return redirect()->route('table')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
    }

    public function QRshow(Request $request)
    {
        $info = '';
        $id = $request->input('id');
        $table = ModelsTable::find($request->input('id'));

        if ($id) {
            $info = $table->qr_code;
        }
        echo $info;
    }

    public function tableDelete(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ลบข้อมูลไม่สำเร็จ',
        ];
        $id = $request->input('id');
        if ($id) {
            $delete = ModelsTable::find($id);
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
