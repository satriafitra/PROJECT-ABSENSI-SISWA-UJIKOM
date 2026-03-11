@extends('layouts.admin')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
            <div>
                <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight">Evaluasi Siswa</h2>
                <p class="text-slate-500 mt-1 font-medium italic">Manajemen Penilaian Karakter <span class="text-blue-500">AkvaScan</span></p>
            </div>
            <a href="{{ route('guru.assessment.create') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold rounded-2xl shadow-xl shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-1 transition-all duration-300 group">
                <i data-lucide="plus" class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform"></i>
                Tambah Penilaian Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-2 border-green-100 text-green-600 p-4 rounded-2xl mb-6 flex items-center shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Siswa</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Status</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Periode</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($students as $student)
                        <tr class="hover:bg-blue-50/30 transition-all group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black text-lg mr-4 shadow-lg shadow-blue-100 group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-700 leading-tight">{{ $student->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1 italic">Student ID: #{{ $student->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if($student->is_evaluated)
                                    <span class="px-4 py-2 bg-green-50 text-green-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-green-100">
                                        Sudah Dinilai
                                    </span>
                                @else
                                    <span class="px-4 py-2 bg-slate-50 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-100">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i data-lucide="calendar" class="w-4 h-4 text-blue-400"></i>
                                    <span class="text-sm font-bold tracking-tight">Semester Genap 2026</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('guru.assessment.show', $student->id) }}" 
                                       class="h-11 w-11 flex items-center justify-center text-blue-500 bg-blue-50 hover:bg-blue-500 hover:text-white rounded-2xl transition-all shadow-sm">
                                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-slate-400">Belum ada data siswa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection