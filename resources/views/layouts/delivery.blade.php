<?php

use App\Models\Config;
use Illuminate\Support\Facades\Session;

$config = Config::first();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'So Fin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ✅ Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-PapQnVEUR/..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- ✅ Bootstrap 5.3.0 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: <?= $config->color1 ?? '#1479eb' ?>;
            --sub-color: <?= $config->color2 ?? '#84d2f8' ?>;
            --text-color: <?= $textColorHover ?? '#f0f0f0' ?>;
            --bg-card-food: <?= $bgCardFod ?? '#ffffff' ?>;
        }


        @font-face {
            font-family: 'PROMPT';
            src: url('{{ asset("fonts/PROMPT-LIGHT.TTF") }}') format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: 'PROMPT';
            src: url('{{ asset("fonts/PROMPT-SEMIBOLD.TTF") }}') format('truetype');
            font-weight: bold;
        }

        body {
            margin: 0;
            padding-bottom: 30px;
            font-family: 'PROMPT', sans-serif;
            background-image: url('{{ $config->image_bg ? url("storage/".$config->image_bg) : asset("bg-df/bg-dfs.jpg") }}');
            background-size: cover;
            background-position: center;
            /* background-repeat: no-repeat; */
            /* min-height: 30vh; */
            color: #333333;
            transition: background-image 0.3s ease, color 0.3s ease;
        }

        .bottom-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-around;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            z-index: 999;
            transition: transform 0.3s ease;
        }

        .bottom-navbar.hide {
            transform: translateY(100%);
        }

        .bottom-navbar a {
            flex: 1;
            text-align: center;
            text-decoration: none;
            color: var(--primary-color);
            font-size: 0.75rem;
            padding: 0.5rem 0rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .bottom-navbar a:hover {
            color: var(--text-color);
            background: linear-gradient(360deg, var(--primary-color), var(--sub-color));
            transform: scale(1.05);
        }

        .bottom-navbar a.active {
            color: var(--text-color);
        }

        .bottom-navbar a .icon {
            font-size: 1.5rem;
        }

        main {
            padding: 1rem;
            text-align: center;
        }
    </style>
</head>

<body>

    <main>
        @yield('content')
    </main>

    <nav class="bottom-navbar" id="bottomNavbar">
        <?php if (!Session::has('user')) { ?>
            <a href="{{url('delivery/login')}}" class="">
                <div class="icon"><i class="fas fa-home"></i></div>
                <div>เข้าสู่ระบบ</div>
            </a>
        <?php } else { ?>
            <a href="/delivery" class="">
                <div class="icon"><i class="fas fa-home"></i></div> <!-- ✅ ไอคอนจาก Font Awesome -->
                <div>หน้าแรก</div>
            </a>
            <a href="{{route('delivery.order')}}">
                <div class="icon"><i class="fas fa-receipt"></i></div> <!-- ✅ ใช้ icon แสดงคำสั่งซื้อ -->
                <div>คำสั่งซื้อ</div>
            </a>
            <a href="{{route('delivery.listorder')}}">
                <div class="icon"><i class="fas fa-clipboard-list"></i></div> <!-- ✅ ใช้ icon แสดงคำสั่งซื้อ -->
                <div>รายการ</div>
            </a>
            <a href="{{route('delivery.users')}}">
                <div class="icon"><i class="fas fa-user"></i></div> <!-- ✅ ใช้ icon แสดงคำสั่งซื้อ -->
                <div>โปรไฟล์</div>
            </a>
        <?php } ?>
    </nav>
    <!-- ✅ Bootstrap 5.3.0 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
    <!-- ✅ Javascript -->
    <script>
        let lastScrollTop = 0;
        const navbar = document.getElementById('bottomNavbar');

        window.addEventListener("scroll", function() {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll > lastScrollTop) {
                navbar.classList.add("hide");
            } else {
                navbar.classList.remove("hide");
            }
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        }, false);
    </script>
    <script>
        $('#sendEmp').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "{{route('sendEmp')}}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        Swal.fire(response.message, "", "success");
                    } else {
                        Swal.fire(response.message, "", "error");
                    }
                }
            });
        });
    </script>
</body>

</html>