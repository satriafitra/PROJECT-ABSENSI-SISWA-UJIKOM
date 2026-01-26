<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Absensi Siswa SD')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-4 flex items-center justify-between shadow-md">

        <!-- Left: Logo & Title -->
        <div class="flex items-center gap-3">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6v6l4 2M20 12a8 8 0 11-16 0 8 8 0 0116 0z" />
            </svg>

            <!-- Title -->
            <span class="text-lg font-semibold tracking-wide">
                Absensi SD
            </span>
        </div>

        <!-- Right: User Info -->
        <div class="flex items-center gap-3 bg-orange-700/40 px-4 py-2 rounded-lg">
            <!-- User Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

            <!-- User Name -->
            <div class="text-sm leading-tight">
                <div class="font-medium">{{ auth()->user()->name }}</div>
                <div class="text-xs opacity-80 capitalize">
                    {{ auth()->user()->role }}
                </div>
            </div>
        </div>

    </nav>


    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Content -->
        <main class="p-6 w-full">
            @yield('content')
        </main>
    </div>

</body>

</html>