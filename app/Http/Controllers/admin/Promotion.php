<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion as ModelsPromotion;
use Illuminate\Http\Request;

class Promotion extends Controller
{
    public function promotion()
    {
        $data['function_key'] = __FUNCTION__;
        return view('promotion.index', $data);
    }

    public function promotionlistData()
    {
        $data = [
            'status' => false,
            'message' => '',
            'data' => []
        ];
        $promotion = ModelsPromotion::get();

        if (count($promotion) > 0) {
            $info = [];
            foreach ($promotion as $rs) {
                $image = '<a href="' . url('storage/' . $rs->image) . '" target="_blank" class="btn btn-sm btn-outline-secondary" type="button"><i class="bx bx-search-alt-2"></i></a>';
                $checked = '';
                if ($rs->is_status == 1) {
                    $checked = 'checked';
                }
                $status = '<div class="form-check form-switch d-flex justify-content-center"><input class="form-check-input" type="checkbox" role="switch" data-id="' . $rs->id . '" ' . $checked . '></div>';
                $action = '<a href="' . route('promotionEdit', $rs->id) . '" class="btn btn-sm btn-outline-primary" title="แก้ไข"><i class="bx bx-edit-alt"></i></a>
                <button type="button" data-id="' . $rs->id . '" class="btn btn-sm btn-outline-danger deletePromotion" title="ลบ"><i class="bx bxs-trash"></i></button>';
                $info[] = [
                    'name' => $rs->name,
                    'image' => $image,
                    'status' => $status,
                    'start_date' => $this->DateThai($rs->start_date),
                    'end_date' => $this->DateThai($rs->end_date),
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

    public function promotionCreate()
    {
        $data['function_key'] = 'promotion';
        return view('promotion.create', $data);
    }

    public function promotionSave(Request $request)
    {
        $input = $request->input();
        if (!isset($input['id'])) {
            $promotion = new ModelsPromotion();
            $promotion->name = $input['name'];
            $promotion->start_date = $input['start_date'];
            $promotion->end_date = $input['end_date'];
            $promotion->is_status = isset($input['status']) == 'on' ? 1 : 0;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('image', $filename, 'public');

                $promotion->image = $path;
            }
            if ($promotion->save()) {
                return redirect()->route('promotion')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        } else {
            $promotion = ModelsPromotion::find($input['id']);
            $promotion->name = $input['name'];
            $promotion->start_date = $input['start_date'];
            $promotion->end_date = $input['end_date'];
            $promotion->is_status = isset($input['status']) == 'on' ? 1 : 0;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('image', $filename, 'public');
                $promotion->image = $path;
            }
            if ($promotion->save()) {
                return redirect()->route('promotion')->with('success', 'บันทึกรายการเรียบร้อยแล้ว');
            }
        }
        return redirect()->route('promotion')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
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

    public function promotionEdit($id)
    {
        $function_key = 'promotion';
        $info = ModelsPromotion::find($id);

        return view('promotion.edit', compact('info', 'function_key'));
    }

    public function promotionDelete(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ลบข้อมูลไม่สำเร็จ',
        ];
        $id = $request->input('id');
        if ($id) {
            $delete = ModelsPromotion::find($id);
            if ($delete->delete()) {
                $data = [
                    'status' => true,
                    'message' => 'ลบข้อมูลเรียบร้อยแล้ว',
                ];
            }
        }

        return response()->json($data);
    }

    public function changeStatusPromotion(Request $request)
    {
        $data = [
            'status' => false,
            'message' => 'ลบข้อมูลไม่สำเร็จ',
        ];
        $id = $request->input('id');
        $value = $request->input('value');
        if ($id) {
            $update = ModelsPromotion::find($id);
            $update->is_status = ($value == 'true') ? 1 : 0;
            if ($update->save()) {
                $data = [
                    'status' => true,
                    'message' => 'ลบข้อมูลเรียบร้อยแล้ว',
                ];
            }
        }
        return response()->json($data);
    }
}
