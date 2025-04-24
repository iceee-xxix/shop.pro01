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
                        <form action="{{route('promotionSave')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    เพิ่มโปรโมชั่น
                                    <hr>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">ชื่อโปรโมชั่น : </label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{$info->name}}" required>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label for="start_date" class="form-label">ตั้งแต่ : </label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{$info->start_date}}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="end_date" class="form-label">สิ้นสุด : </label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{$info->end_date}}" required>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="file" class="form-label">รูปภาพ : </label>
                                            <div class="input-group mb-3">
                                                <input class="form-control" type="file" id="file" name="file">
                                                <a href="{{($info->image) ? url('storage/'.$info->image) : 'javascript:void(0);'}}"
                                                    {{($info->image) ? 'target="_blank" ' : ''}}
                                                    class="btn btn-outline-secondary" type="button"><i class="bx bx-search-alt-2"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <label class="form-check-label" for="status">สถานะการใช้งาน</label>
                                            <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{($info->is_status == 1) ? 'checked' : ''}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <button type="submit" class="btn btn-outline-primary">บันทึก</button>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{$info->id}}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection