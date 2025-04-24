@extends('admin.layout')
@section('style')
<style>
    .btn-custom {
        background: linear-gradient(to right, <?= $config->color1 ?>, <?= $config->color2 ?>);
        color: white;
    }

    #demo-font {
        color: <?= $config->color_font ?>;
    }

    #demo-category {
        color: <?= $config->color_category ?>;
    }
</style>
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row d-flex justify-content-center">
                    <div class="col-10">
                        <form action="{{route('ConfigSave')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    ตั้งค่าเว็บไซต์
                                    <hr>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">ชื่อเว็บไซต์ : </label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{$config->name}}">
                                        </div>
                                    </div>
                                    <h6>สีของปุ่ม</h6>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <label for="color1" class="form-label">สีที่ 1 : </label>
                                            <input type="color" class="form-control" id="color1" name="color1" title="เลือกสี" value="{{$config->color1}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="color2" class="form-label">สีที่ 2 : </label>
                                            <input type="color" class="form-control" id="color2" name="color2" title="เลือกสี" value="{{$config->color2}}">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="demo-color" class="form-label">ปุ่มตัวอย่าง : </label>
                                            <button type="button" class="form-control btn-custom" id="demo-color">ปุ่มตัวอย่าง</button>
                                        </div>
                                    </div>
                                    <h6>สีข้อความบนเว็บไซต์</h6>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <label for="color_font" class="form-label">สีข้อความ : </label>
                                            <button type="button" class="btn btn-secondary mb-2" id="demo-font">ตัวอย่างข้อความ</button>
                                            <input type="color" class="form-control" id="color_font" name="color_font" title="เลือกสี" value="{{$config->color_font}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="color_category" class="form-label">สีข้อความของรายการหมวดหมู่ : </label>
                                            <button type="button" class="btn btn-secondary mb-2" id="demo-category">ตัวอย่างข้อความ</button>
                                            <input type="color" class="form-control" id="color_category" name="color_category" title="เลือกสี" value="{{$config->color_category}}">
                                        </div>
                                    </div>
                                    <hr>
                                    <h6>พื้นหลัง</h6>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <div class="input-group mb-3">
                                                <input class="form-control" type="file" id="image_bg" name="image_bg">
                                                <a href="{{($config->image_bg) ? url('storage/'.$config->image_bg) : 'javascript:void(0);'}}"
                                                    {{($config->image_bg) ? 'target="_blank" ' : ''}}
                                                    class="btn btn-outline-secondary" type="button"><i class="bx bx-search-alt-2"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <h6>พร้อมเพย์</h6>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <div class="input-group mb-3">
                                                <input class="form-control" type="text" id="image_qr" name="image_qr" maxlength="13" value="{{$config->image_qr}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <button type="submit" class="btn btn-outline-primary">บันทึก</button>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{$config->id}}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        const color1 = document.getElementById('color1');
        const color2 = document.getElementById('color2');
        const colorf = document.getElementById('color_font');
        const colorfc = document.getElementById('color_category');
        const button = document.getElementById('demo-color');
        const button_font = document.getElementById('demo-font');
        const button_category = document.getElementById('demo-category');

        function updateGradient() {
            const c1 = color1.value;
            const c2 = color2.value;
            const cf = colorf.value;
            button.style.background = `linear-gradient(to right, ${c1}, ${c2})`;
            button.style.color = cf;
        }

        function updatefont() {
            const cf = colorf.value;
            button_font.style.color = cf;
        }

        function updateCategory() {
            const cf = colorfc.value;
            button_category.style.color = cf;
        }

        color1.addEventListener('input', updateGradient);
        color2.addEventListener('input', updateGradient);
        colorf.addEventListener('input', updatefont);
        colorfc.addEventListener('input', updateCategory);
    });
</script>
@endsection