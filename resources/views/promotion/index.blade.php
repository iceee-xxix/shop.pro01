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
                    <div class="card-header d-flex justify-content-end">
                        <a href="{{route('promotionCreate')}}" class="btn btn-sm btn-outline-success d-flex align-items-center" style="font-size:14px">เพิ่มโปรโมชั่น&nbsp;<i class="bx bxs-plus-circle"></i></a>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ชื่อโปรโมชั่น</th>
                                    <th class="text-center">รูปภาพ</th>
                                    <th class="text-center">ตั้งแต่</th>
                                    <th class="text-center">สิ้นสุด</th>
                                    <th class="text-center">สถานะการใช้งาน</th>
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
                url: "{{route('promotionlistData')}}",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },

            columns: [{
                    data: 'name',
                    class: 'text-left',
                    width: '30%'
                },
                {
                    data: 'image',
                    class: 'text-center',
                    width: '10%'
                },
                {
                    data: 'start_date',
                    class: 'text-center',
                    width: '15%'
                },
                {
                    data: 'end_date',
                    class: 'text-center',
                    width: '15%'
                },
                {
                    data: 'status',
                    class: 'text-center',
                    width: '15%'
                },
                {
                    data: 'action',
                    class: 'text-center',
                    width: '15%',
                    orderable: false
                },
            ]
        });
    });
</script>
<script>
    $(document).on('click', '.deletePromotion', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: "ท่านต้องการลบโปรโมชั่นใช่หรือไม่?",
            icon: "question",
            showDenyButton: true,
            confirmButtonText: "ตกลง",
            denyButtonText: `ยกเลิก`
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{route('promotionDelete')}}",
                    type: "post",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status == true) {
                            Swal.fire(response.message, "", "success");
                            $('#myTable').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire(response.message, "", "error");
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.form-check-input', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var value = $(this).is(':checked');
        $.ajax({
            url: "{{route('changeStatusPromotion')}}",
            type: "post",
            data: {
                id: id,
                value: value
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#myTable').DataTable().ajax.reload(null, false);
            }
        });
    });
</script>
@endsection