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
                        <form action="{{route('menuOptionSave')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    เพิ่มราคาอาหาร
                                    <hr>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">ประเภทราคา : </label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="เช่น ธรรมดา พิเศษ" required>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="price" class="form-label">ราคา(บาท) : </label>
                                            <input type="text" class="form-control" id="price" name="price" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <button type="submit" class="btn btn-outline-primary">บันทึก</button>
                                </div>
                            </div>
                            <input type="hidden" name="menu_id" value="{{$id}}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection