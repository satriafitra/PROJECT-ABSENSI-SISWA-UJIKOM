<aside class="w-72 bg-white min-h-screen shadow-[4px_0_24px_rgba(0,0,0,0.02)] border-r border-slate-100 flex flex-col transition-all duration-300">

    <div class="p-8 flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-200">
            <i data-lucide="graduation-cap" class="text-white w-6 h-6"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800 leading-none">Akva<span class="text-orange-500">Scan</span></h2>
            <p class="text-[10px] text-slate-400 uppercase tracking-[2px] font-semibold mt-1">Management System</p>
        </div>
    </div>

    {{-- STYLE PREMIUM --}}
    <style>
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            color: #64748b;
            /* Slate 500 */
            position: relative;
            overflow: hidden;
        }

        /* Hover Effect */
        .sidebar-item:hover {
            color: #f97316;
            /* Orange 500 */
            background: #fff7ed;
            /* Orange 50 */
            padding-left: 24px;
        }

        /* Active State */
        .sidebar-item.active {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            box-shadow: 0 10px 20px -5px rgba(249, 115, 22, 0.4);
        }

        .sidebar-item.active i {
            color: white !important;
        }

        /* Sub-header text */
        .menu-label {
            padding: 0 20px;
            margin-top: 24px;
            margin-bottom: 8px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 800;
            color: #cbd5e1;
        }
    </style>

    <div class="flex-1 px-4 overflow-y-auto custom-scrollbar">
        <ul class="space-y-1.5">

            {{-- ADMIN MENU --}}
            @if(auth()->user()->role === 'admin')
            <div class="menu-label">Main Administrator</div>

            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.tahunajar') }}"
                    class="sidebar-item {{ request()->routeIs('admin.tahunajar') ? 'active' : '' }}">
                    <i data-lucide="calendar-days" class="w-5 h-5"></i>
                    Tahun Ajar
                </a>
            </li>

            <li>
                <a href="{{ route('admin.manual') }}"
                    class="sidebar-item {{ request()->routeIs('admin.manual') ? 'active' : '' }}">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                    Laporan Izin atau Sakit
                </a>
            </li>

            <li>
                <a href="{{ route('admin.total_penilaian') }}"
                    class="sidebar-item {{ request()->routeIs('admin.total_penilaian') ? 'active' : '' }}">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <span>Total Penilaian Siswa</span>
                </a>
            </li>

            <div class="menu-label">Master Data</div>

            <li>
                <a href="{{ route('admin.guru.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                    <i data-lucide="users-2" class="w-5 h-5"></i>
                    Data Guru
                </a>
            </li>

            <li>
                <a href="{{ route('admin.siswa.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    Data Siswa
                </a>
            </li>

            <li>
                <a href="{{ route('admin.rekapabsensi') }}"
                    class="sidebar-item {{ request()->routeIs('admin.rekapabsensi') ? 'active' : '' }}">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                    Rekap Absensi
                </a>
            </li>

            <li>
                <a href="{{ route('admin.lokasi.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.lokasi.index') ? 'active' : '' }}">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                    Lokasi Absensi
                </a>
            </li>

            <li>
                <a href="{{ route('admin.jadwal.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    Jadwal Guru
                </a>
            </li>

            <li>
                <a href="{{ route('admin.assessment-category.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.assessment-category.*') || request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                    <span>Kategori Penilaian</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.gamifikasi.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.gamifikasi.*') ? 'active' : '' }}">
                    <i data-lucide="zap" class="w-5 h-5"></i> <span>Gamifikasi</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.siswa-shop.index') }}"
                    class="sidebar-item {{ request()->routeIs('admin.siswa-shop.*') ? 'active' : '' }}">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i> <span>Siswa Point</span>
                </a>
            </li>

            @endif

            {{-- GURU MENU --}}
            @if(auth()->user()->role === 'guru')
            <div class="menu-label">Teacher Panel</div>

            <li>
                <a href="{{ route('guru.dashboard') }}"
                    class="sidebar-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                    <i data-lucide="home" class="w-5 h-5"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('guru.scan.qr') }}"
                    class="sidebar-item {{ request()->routeIs('guru.scan.qr') ? 'active' : '' }}">
                    <i data-lucide="qr-code" class="w-5 h-5"></i>
                    Scan QR Absensi
                </a>
            </li>

            <li>
                <a href="{{ route('guru.rekap.absensi', ['date' => now()->toDateString()]) }}" class="sidebar-item">
                    <i data-lucide="calendar-check" class="w-5 h-5"></i>
                    Absensi Hari Ini
                </a>
            </li>

            <li>
                <a href="{{ route('guru.assessment.index') }}" class="sidebar-item {{ request()->routeIs('guru.assessment.*') ? 'active' : '' }}">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                    Penilaian Siswa
                </a>
            </li>

            @endif

        </ul>
    </div>

    {{-- LOGOUT FOOTER --}}
    <div class="p-4 mt-auto">
        <div class="bg-slate-50 rounded-2xl p-2 border border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl
                           text-red-500 font-bold hover:bg-white hover:shadow-sm transition-all duration-300">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </div>

</aside>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>