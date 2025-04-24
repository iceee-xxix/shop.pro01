@extends('layouts.luxury-nav')

@section('title', 'หน้ารายละเอียด')

@section('content')
    <style>


        .title-list-buy {
            font-size: 35px;
            font-weight: bold;
        }

    
    </style>

    <div class="container">
        <div class="d-flex flex-column justify-content-center gap-2">

            <div class="bg-white px-2 py-3 shadow-lg d-flex flex-column aling-items-center justify-content-center"
                style="border-radius: 10px;">
                <div class="title-list-buy">
                    แสกนเพื่อชำระ
                </div>
                <img src="{{asset('qrcode/qr-code.png')}}" alt="/qr-code">
            </div>
            <div class="bg-white p-2 shadow-lg mt-3" style="border-radius:20px;">
                <textarea class="form-control fw-bold text-center bg-white p-2" style="border-radius: 20px;" rows="4"
                    placeholder="ข้อเสนอแนะ/แสดงความคิดเห็นที่มีต่อร้าน">
</textarea>
            </div>
        </div>
    </div>

    

@endsection
