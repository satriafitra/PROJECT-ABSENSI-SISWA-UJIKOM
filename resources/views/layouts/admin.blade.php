<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AkvaScan - Management System')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar for Luxury Feel */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #f97316;
        }

        .main-content {
            background: radial-gradient(circle at top right, #ffffff 0%, #ffffff 100%);
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 antialiased">

    <div class="flex min-h-screen overflow-hidden">
        
        <aside class="hidden lg:block">
            @include('layouts.sidebar')
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <nav class="sticky top-0 z-40 bg-white/70 backdrop-blur-xl border-b border-slate-100 px-8 py-4 flex items-center justify-between">
                
                <div class="flex items-center gap-4">
                    <button class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    
                    <div class="hidden md:block">
                        <h2 class="text-sm font-bold text-slate-400 uppercase tracking-[2px]">Sistem Informasi</h2>
                        <p class="text-xs text-orange-500 font-medium italic">SD Negeri AkvaScan</p>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <button class="relative p-2 text-slate-400 hover:text-orange-500 transition-colors">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-orange-500 border-2 border-white rounded-full"></span>
                    </button>

                    <div class="flex items-center gap-3 pl-6 border-l border-slate-100">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-800 leading-none capitalize">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-[10px] text-orange-500 font-bold uppercase mt-1 tracking-wider bg-orange-50 px-2 py-0.5 rounded-md inline-block">
                                {{ auth()->user()->role }}
                            </p>
                        </div>
                        
                        <div class="relative group cursor-pointer">
                            <div class="w-10 h-10 bg-gradient-to-tr from-orange-500 to-amber-400 rounded-2xl flex items-center justify-center text-white font-bold shadow-lg shadow-orange-100 group-hover:rotate-6 transition-transform">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full shadow-sm"></div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="main-content flex-1 overflow-y-auto overflow-x-hidden p-8">
                <div class="max-w-[1600px] mx-auto animate-in fade-in slide-in-from-bottom-4 duration-700">
                    @yield('content')
                </div>
            </main>

            <footer class="bg-white/50 px-8 py-4 border-t border-slate-100">
                <p class="text-center text-[10px] text-slate-400 font-medium tracking-widest uppercase">
                    &copy; 2026 AkvaScan Digital Solutions • All Rights Reserved
                </p>
            </footer>

        </div>
    </div>

    <script>
        // Initialize Icons
        lucide.createIcons();
    </script>
</body>

</html>