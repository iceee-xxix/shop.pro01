@extends('admin.layout')
@section('style')
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row d-flex justify-content-center">
                    <div class="col-8">
                        <form action="{{route('menustockSave')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    เพิ่มสต็อก
                                    <hr>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">ชื่อสต็อก : </label>
                                            <select class="form-control" name="stock_id" id="stock_id" required>
                                                <option value="" disabled>กรุณาเลือก</option>
                                                @foreach($stock as $rs)
                                                <option value="{{$rs->id}}" {{($info->stock_id == $rs->id) ? 'selected' : ''}}>{{$rs->name}} ({{$rs->unit}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="amount" class="form-label">จำนวนที่ใช้ : </label>
                                            <input type="text" class="form-control" id="amount" name="amount" required value="{{ old('amount', $info->amount) }}">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{$info->id}}">
                                <div class="card-footer d-flex justify-content-end">
                                    <button type="submit" class="btn btn-outline-primary">บันทึก</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection