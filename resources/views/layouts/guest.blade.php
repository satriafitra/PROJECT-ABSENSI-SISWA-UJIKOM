<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AkvaScan - Portal Absensi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #ffffff;
            font-family: 'Poppins', sans-serif; /* Menggunakan Poppins */
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            letter-spacing: -0.01em;
        }

        /* Container Utama */
        .main-container-layer {
            position: relative;
            width: 100%;
            max-width: 1100px;
            height: 85vh;
            border-radius: 3.5rem;
            overflow: hidden;
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
            box-shadow: 0 50px 100px -20px rgba(249, 115, 22, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Pattern Tekstur Halus */
        .main-container-layer::before {
            content: "";
            position: absolute;
            width: 150%;
            height: 150%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2v-4h4v-2H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 1;
        }

        .content-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-around;
            padding: 0 4rem;
        }

        /* Card Login Putih */
        .white-solid-card {
            background-color: #ffffff;
            border-radius: 3rem;
            padding: 3.5rem; /* Sedikit lebih luas */
            width: 100%;
            max-width: 420px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
            animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Animasi Floating Icon */
        .floating-icon {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .branding-text h1 {
            font-weight: 900; /* Extra Bold untuk Poppins */
            text-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .branding-text p {
            font-weight: 500;
            letter-spacing: 0.5em;
        }
    </style>
</head>

<body class="antialiased text-slate-800">
    <div class="w-full flex items-center justify-center p-6">

        <div class="main-container-layer">
            
            <div class="content-wrapper flex flex-col md:flex-row">

                <div class="hidden md:flex flex-col items-center text-center text-white branding-text">
                    <div class="floating-icon mb-8">
                        <div class="w-36 h-36 bg-white rounded-[2.8rem] flex items-center justify-center shadow-2xl border-[6px] border-white/20">
                            <i class="fas fa-user-graduate text-7xl text-orange-500"></i>
                        </div>
                    </div>
                    
                    <h1 class="text-6xl tracking-tighter italic uppercase">
                        Akva<span class="text-orange-100/80">Scan</span>
                    </h1>
                    
                    <p class="mt-4 text-[11px] uppercase opacity-90">
                        Smart School Attendance
                    </p>
                </div>

                <div class="white-solid-card">
                    {{ $slot }}
                </div>
            </div>

            <div class="absolute bottom-8 z-20 text-white/60 text-[9px] tracking-[0.6em] uppercase font-bold">
                &copy; 2026 AkvaScan Digital System
            </div>
        </div>
    </div>
</body>

</html>