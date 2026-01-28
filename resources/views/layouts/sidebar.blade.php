<aside class="w-64 bg-white min-h-screen shadow-lg border-r">

    {{-- STYLE LANGSUNG DI BLADE --}}
    <style>
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 14px;
            font-weight: 500;
            transition: all .35s ease;
            color: #374151;
        }

        .sidebar-item:hover {
            background: linear-gradient(to right, #ffedd5, #fed7aa);
            transform: translateX(6px);
            color: #1e0c03;
        }

        .sidebar-item.active {
            background: linear-gradient(to right, #fb923c, #f97316);
            color: white;
            transform: translateX(6px);
            box-shadow: 0 10px 25px rgba(249, 115, 22, .35);
        }

        .sidebar-icon {
            width: 20px;
            height: 20px;
        }
    </style>

    <ul class="p-4 space-y-2">

        {{-- ADMIN MENU --}}
        @if(auth()->user()->role === 'admin')

        <li>
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2 7-7 7 7M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3" />
                </svg>
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('admin.tahunajar') }}"
                class="sidebar-item {{ request()->routeIs('admin.tahunajar') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Tahun Ajar
            </a>
        </li>

        <li>
            <a href="{{ route('admin.rombel') }}"
                class="sidebar-item {{ request()->routeIs('admin.rombel') ? 'active' : '' }}">
                Rombel
            </a>
        </li>

        <li>
            <a href="{{ route('admin.guru.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                Guru
            </a>
        </li>


        <li>
            <a href="{{ route('admin.siswa') }}"
                class="sidebar-item {{ request()->routeIs('admin.siswa') ? 'active' : '' }}">
                Siswa
            </a>
        </li>

        <li>
            <a href="{{ route('admin.rekapabsensi') }}"
                class="sidebar-item {{ request()->routeIs('admin.rekapabsensi') ? 'active' : '' }}">
                Rekap Absensi
            </a>
        </li>

        @endif

        {{-- GURU MENU --}}
        @if(auth()->user()->role === 'guru')

        <li>
            <a href="{{ route('guru.dashboard') }}"
                class="sidebar-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                <svg class="sidebar-icon" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2 7-7 7 7" />
                </svg>
                Dashboard
            </a>
        </li>

        <li>
            <a href="#"
                class="sidebar-item">
                Scan QR
            </a>
        </li>

        <li>
            <a href="#"
                class="sidebar-item">
                Absensi Hari Ini
            </a>
        </li>

        @endif

        {{-- LOGOUT --}}
        <li class="pt-4 mt-4 border-t">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl
                           text-red-500 hover:bg-red-50 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </li>

    </ul>
</aside>