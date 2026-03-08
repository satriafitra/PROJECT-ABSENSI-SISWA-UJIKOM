<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Selamat Datang</h2>
        <p class="text-slate-500 text-xs mt-1">Silakan masuk ke akun Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Email Siswa</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                    <i class="fas fa-envelope text-sm"></i>
                </div>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                    class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 placeholder-slate-400 outline-none focus:ring-4 focus:ring-orange-100 focus:border-orange-500 focus:bg-white transition-all duration-300" 
                    placeholder="nama@sekolah.com" />
            </div>
        </div>

        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                    <i class="fas fa-lock text-sm"></i>
                </div>
                <input id="password" type="password" name="password" required 
                    class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 placeholder-slate-400 outline-none focus:ring-4 focus:ring-orange-100 focus:border-orange-500 focus:bg-white transition-all duration-300" 
                    placeholder="••••••••" />
            </div>
        </div>

        <div class="flex items-center justify-between px-1">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-200 text-orange-500 focus:ring-orange-500">
                <span class="ms-2 text-[11px] text-slate-500">Ingat saya</span>
            </label>
            <a class="text-[11px] font-bold text-orange-500 hover:text-orange-600 transition-colors" href="{{ route('password.request') }}">
                Lupa sandi?
            </a>
        </div>

        <button type="submit" class="w-full py-4 bg-gradient-to-r from-orange-600 to-orange-400 hover:shadow-orange-200 text-white font-bold rounded-2xl shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest text-xs">
            Masuk Sekarang
            <i class="fas fa-chevron-right text-[10px]"></i>
        </button>

        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-xs text-slate-400">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-orange-600 font-bold hover:underline">Daftar sekarang</a>
            </p>
        </div>
    </form>
</x-guest-layout>