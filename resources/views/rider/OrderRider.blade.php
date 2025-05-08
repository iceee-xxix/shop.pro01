@extends('admin.layout')
@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
<style>
    svg {
        width: 100%;
    }
</style>
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="myTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-left">ชื่อไรเดอร์</th>
                                    <th class="text-center">เบอร์ติดต่อ</th>
                                    <th class="text-left">รายละเอียด</th>
                                    <th class="text-center">ดูสถานที่จัดส่ง</th>
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
                url: "{{route('OrderRiderlistData')}}",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            },

            columns: [{
                    data: 'name',
                    class: 'text-left',
                    width: '20%'
                },
                {
                    data: 'tel',
                    class: 'text-center',
                    width: '20%',
                    orderable: false
                },
                {
                    data: 'detail',
                    class: 'text-left',
                    width: '20%',
                    orderable: false
                },
                {
                    data: 'location',
                    class: 'text-center',
                    width: '20%',
                    orderable: false
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
<script>
    $(document).on('click', '.deleteTable', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: "ท่านต้องการลบโต้ะใช่หรือไม่?",
            icon: "question",
            showDenyButton: true,
            confirmButtonText: "ตกลง",
            denyButtonText: `ยกเลิก`
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{route('riderDelete')}}",
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
    $(document).on('click', '.modalQr', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "post",
            url: "{{route('QRshow')}}",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modal-qr').modal('show')
                $('#body-html').html(response);
            }
        });
    });

    $(document).on('click', '.modalShow', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "post",
            url: "{{ route('listOrderDetailRider') }}",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modal-detail').modal('show');
                $('#body-html').html(response);
            }
        });
    });
    $(document).on('click', '.modalPay', function(e) {
        var total = $(this).data('total');
        var id = $(this).data('id');
        Swal.showLoading();
        $.ajax({
            type: "post",
            url: "{{ route('generateQr') }}",
            data: {
                total: total
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.close();
                $('#modal-pay').modal('show');
                $('#totalPay').html(total + ' บาท');
                $('#qr_code').html(response);
                $('#order_id').val(id);
            }
        });
    });
    $('#modal-pay').on('hidden.bs.modal', function() {
        $('#order_id').val('');
    })
    $(document).on('click', '#confirm_pay', function(e) {
        e.preventDefault();
        var id = $('#order_id').val();
        $.ajax({
            url: "{{route('Riderconfirm_pay')}}",
            type: "post",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modal-pay').modal('hide')
                if (response.status == true) {
                    Swal.fire(response.message, "", "success");
                    $('#myTable').DataTable().ajax.reload(null, false);
                } else {
                    Swal.fire(response.message, "", "error");
                }
            }
        });
    });
</script>
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดออเดอร์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="body-html">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modal-pay">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ชำระเงิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body d-flex justify-content-center">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h5>ยอดชำระ</h5>
                                <h1 class="text-success" id="totalPay"></h1>
                            </div>
                            <div class="col-12 d-flex justify-content-center mb-3" id="qr_code">
                            </div>
                        </div>
                        <input type="hidden" id="order_id">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirm_pay">ยืนยันชำระเงิน</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
@endsection