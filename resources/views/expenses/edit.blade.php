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
                        <form action="{{route('ExpensesSave')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    แก้ไขค่าใช้จ่าย
                                    <hr>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">หมวดหมู่ค่าใช้จ่าย : </label>
                                            <select class="form-control" name="category_id" id="category_id" required>
                                                <option value="" disabled selected>กรุณาเลือก</option>
                                                @foreach($category as $rs)
                                                <option value="{{$rs->id}}" {{($info->category_id == $rs->id) ? 'selected' : ''}}>{{$rs->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">ชื่อค่าใช้จ่าย : </label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $info->name) }}">
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="price" class="form-label">จำนวน (บาท) : </label>
                                            <input type="text" class="form-control" id="price" name="price" value="{{ old('price', $info->price) }}">
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="price" class="form-label">วันที่ใช้จ่าย : </label>
                                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $info->date) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <button type="submit" class="btn btn-outline-primary">บันทึก</button>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{ old('id', $info->id) }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection