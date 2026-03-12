@extends('layouts.admin')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* Custom Range Slider Styling agar konsisten */
    input[type=range]::-webkit-slider-thumb {
        -webkit-appearance: none;
        height: 26px;
        width: 26px;
        border-radius: 50%;
        background: #f97316;
        cursor: pointer;
        border: 4px solid white;
        box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.3);
    }
    .icon-box svg { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
</style>

<div class="p-4 md:p-8 bg-[#f8fafc] min-h-screen">
    <div class="max-w-3xl mx-auto">
        {{-- Navigation & Title --}}
        <div class="mb-8">
            <a href="{{ route('guru.assessment.index') }}" class="inline-flex items-center text-slate-400 hover:text-orange-600 transition-all font-bold group text-sm uppercase tracking-widest mb-6">
                <i data-lucide="arrow-left" class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>
            <div class="flex items-center gap-4">
                <div class="p-4 bg-orange-500 rounded-3xl shadow-lg shadow-orange-200 text-white">
                    <i data-lucide="edit-3" class="w-8 h-8"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Edit Penilaian</h2>
                    <p class="text-slate-500 font-medium">Memperbarui data: <span class="text-orange-600 font-bold">{{ $student->name }}</span></p>
                </div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-[3.5rem] shadow-2xl shadow-slate-200/60 p-10 lg:p-14 border border-white relative overflow-hidden">
            {{-- Decorative Icon --}}
            <i data-lucide="file-text" class="absolute -right-10 -bottom-10 w-64 h-64 text-slate-50 opacity-[0.03] -rotate-12 pointer-events-none"></i>

            <form action="{{ route('guru.assessment.update', $assessment->id) }}" method="POST" class="relative z-10">
                @csrf
                @method('PUT')
                
                {{-- Periode Input --}}
                <div class="mb-12">
                    <label class="flex items-center text-[11px] font-black text-slate-400 uppercase tracking-[2px] mb-4 ml-1">
                        <i data-lucide="calendar" class="w-4 h-4 mr-2 text-orange-500"></i> Periode Penilaian
                    </label>
                    <input type="text" name="period" value="{{ $assessment->period }}" 
                           class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-8 py-5 focus:border-orange-500 focus:bg-white outline-none font-bold text-slate-700 transition-all shadow-sm"
                           required>
                </div>

                {{-- Parameter Penilaian --}}
                <div class="space-y-10">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-[2px]">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 inline mr-1 text-orange-500"></i> Indikator Karakter
                        </label>
                        <span class="text-[10px] font-bold text-slate-300 uppercase italic">Geser untuk mengubah nilai</span>
                    </div>
                    
                    @foreach($assessment->details as $detail)
                    <div class="space-y-6">
                        <div class="flex justify-between items-end">
                            <div class="max-w-[65%]">
                                <label class="font-black text-slate-700 text-lg tracking-tight block">{{ $detail->category->name }}</label>
                                <p class="text-xs text-slate-400 mt-1">{{ $detail->category->description ?? 'Aspek penilaian karakter' }}</p>
                            </div>
                            
                            <div class="flex flex-col items-end">
                                {{-- Icon Emote Box --}}
                                <div id="icon-box-{{ $detail->id }}" class="mb-2 p-2 bg-slate-50 rounded-xl border border-slate-100 icon-box">
                                    <i data-lucide="smile" class="w-6 h-6" id="icon-{{ $detail->id }}"></i>
                                </div>
                                {{-- Score Display --}}
                                <div id="val-display-{{ $detail->id }}" 
                                     class="text-white text-xl font-black min-w-[3.5rem] h-12 flex items-center justify-center rounded-2xl shadow-lg transition-all duration-300">
                                    {{ $detail->score }}
                                </div>
                            </div>
                        </div>

                        <input type="range" name="scores[{{ $detail->id }}]" min="1" max="100" step="1" 
                               value="{{ $detail->score }}"
                               oninput="updateEditScore(this, '{{ $detail->id }}')"
                               class="w-full h-2.5 bg-slate-100 rounded-full appearance-none cursor-pointer accent-orange-500 transition-all">
                    </div>
                    @endforeach
                </div>

                {{-- Catatan Guru --}}
                <div class="mt-14">
                    <label class="flex items-center text-[11px] font-black text-slate-400 uppercase tracking-[2px] mb-4 ml-1">
                        <i data-lucide="message-square-quote" class="w-4 h-4 mr-2 text-orange-500"></i> Catatan Perkembangan
                    </label>
                    <textarea name="general_notes" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] px-8 py-6 focus:border-orange-500 focus:bg-white outline-none text-slate-600 font-medium leading-relaxed transition-all" 
                              rows="4" placeholder="Berikan catatan tambahan jika diperlukan...">{{ $assessment->general_notes }}</textarea>
                </div>

                {{-- Submit Buttons --}}
                <div class="mt-12 flex flex-col md:flex-row gap-5">
                    <button type="submit" class="flex-[2] py-6 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-black rounded-3xl shadow-xl shadow-orange-200 hover:shadow-orange-300 hover:-translate-y-1 transition-all duration-300 flex justify-center items-center uppercase tracking-widest text-sm group">
                        <i data-lucide="save" class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform"></i> Perbarui Data Penilaian
                    </button>
                    <a href="{{ route('guru.assessment.index') }}" class="flex-1 py-6 bg-slate-100 text-slate-400 font-black rounded-3xl hover:bg-slate-200 transition-all text-center uppercase tracking-widest text-sm flex items-center justify-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateEditScore(el, id) {
        const val = parseInt(el.value);
        const display = document.getElementById('val-display-' + id);
        const iconElement = document.getElementById('icon-' + id);
        
        display.innerText = val;
        
        let iconName = "smile";
        let color = "#f97316";
        
        // Logika Emote Dinamis
        if (val <= 20) { iconName = "frown"; color = "#ef4444"; }
        else if (val <= 45) { iconName = "meh"; color = "#f59e0b"; }
        else if (val <= 70) { iconName = "smile"; color = "#f97316"; }
        else if (val <= 85) { iconName = "laugh"; color = "#10b981"; }
        else { iconName = "award"; color = "#6366f1"; }
        
        // Update Warna dan Ikon
        display.style.backgroundColor = color;
        iconElement.setAttribute('data-lucide', iconName);
        iconElement.style.color = color;
        
        // Refresh Lucide Icons untuk mengganti SVG
        lucide.createIcons();
    }

    // Jalankan fungsi saat halaman pertama kali dimuat agar warna & ikon sesuai data awal
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        document.querySelectorAll('input[type=range]').forEach(slider => {
            const id = slider.name.match(/\[(.*?)\]/)[1];
            updateEditScore(slider, id);
        });
    });
</script>
@endsection