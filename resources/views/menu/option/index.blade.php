@extends('admin.layout')
@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row d-flex justify-content-between">
                            <div class="col-6">
                                {{$info->name}}
                            </div>
                            <div class="col-auto">
                                <a href="{{route('menulistOptionCreate',$id)}}" class="btn btn-sm btn-outline-success d-flex align-items-center" style="font-size:14px">เพิ่มราคาอาหาร&nbsp;<i class="bx bxs-plus-circle"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ประเภทราคา</th>
                                    <th class="text-center">ราคา</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script>
    var language = '{{asset("assets/js/datatable-language.js")}}';
    $(document).ready(function() {
        $("#myTable").DataTable({
            language: {
                url: language,
            },
            processing: true,
            ajax: {
                url: "{{route('menulistOption')}}",
                data: {
                    id: '{{$id}}'
                },
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            },
            columns: [{
                    data: 'name',
                    class: 'text-left',
                    width: '60%'
                },
                {
                    data: 'price',
                    class: 'text-center',
                    width: '20%',
                },
                {
                    data: 'action',
                    class: 'text-center',
                    width: '20%',
                    orderable: false
                },
            ]
        });
    });
</script>
@endsection