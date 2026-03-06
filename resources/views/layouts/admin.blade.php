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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        /* Custom Luxury Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            border: 2px solid #f1f5f9;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #f97316;
        }

        /* Animation for Page Content */
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Glassmorphism Effect */
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 antialiased">

    <div class="flex min-h-screen overflow-hidden">
        
        <aside class="hidden lg:block">
            @include('layouts.sidebar')
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <nav class="sticky top-0 z-40 glass-nav border-b border-slate-100 px-8 py-4 flex items-center justify-between">
                
                <div class="flex items-center gap-4">
                    <button class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    
                    <div class="hidden md:block">
                        <h2 class="text-sm font-bold text-slate-400 uppercase tracking-[2px] leading-tight">Sistem Informasi</h2>
                        <p class="text-xs text-orange-500 font-semibold italic">Smk Negeri AkvaScan</p>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <button class="relative p-2 text-slate-400 hover:text-orange-500 transition-all hover:scale-110">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-orange-500 border-2 border-white rounded-full"></span>
                    </button>

                    <div class="flex items-center gap-3 pl-6 border-l border-slate-100">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-800 leading-none capitalize">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-[10px] text-orange-600 font-extrabold uppercase mt-1 tracking-wider bg-orange-50 px-2 py-0.5 rounded-md inline-block">
                                {{ auth()->user()->role }}
                            </p>
                        </div>
                        
                        <div class="relative group cursor-pointer">
                            <div class="w-10 h-10 bg-gradient-to-tr from-orange-500 to-amber-400 rounded-2xl flex items-center justify-center text-white font-bold shadow-lg shadow-orange-200 group-hover:rotate-12 transition-all duration-300">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full shadow-sm"></div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-8">
                <div class="max-w-[1600px] mx-auto fade-in-up">
                    @yield('content')
                </div>
            </main>

            <footer class="bg-white/50 px-8 py-4 border-t border-slate-100">
                <p class="text-center text-[10px] text-slate-400 font-bold tracking-[3px] uppercase">
                    &copy; 2026 <span class="text-orange-500">AkvaScan</span> Digital Solutions • Premium Management
                </p>
            </footer>

        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // SweetAlert2 Configuration & Logic
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Logic for Delete Confirmation
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');
                    
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data yang dihapus tidak dapat dipulihkan kembali!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f97316', // Orange 500
                        cancelButtonColor: '#94a3b8', // Slate 400
                        confirmButtonText: 'Ya, Hapus Sekarang',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        background: '#ffffff',
                        customClass: {
                            popup: 'rounded-[2rem] p-6 shadow-2xl',
                            confirmButton: 'rounded-xl px-6 py-3 font-bold uppercase text-xs tracking-widest',
                            cancelButton: 'rounded-xl px-6 py-3 font-bold uppercase text-xs tracking-widest'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // 2. Logic for Success Messages
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 3500,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    background: '#ffffff',
                    iconColor: '#f59e0b', // Amber
                    customClass: {
                        popup: 'rounded-[2rem] p-8 shadow-2xl border-b-4 border-orange-500',
                        title: 'text-2xl font-bold text-slate-800'
                    }
                });
            @endif

            // 3. Logic for Error Messages (Optional but helpful)
            @if(session('error'))
                Swal.fire({
                    title: 'Oops!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#f97316',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2rem]'
                    }
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>