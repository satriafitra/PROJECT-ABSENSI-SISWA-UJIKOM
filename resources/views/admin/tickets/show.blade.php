@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfd; }
    .chat-bubble {
        position: relative;
        padding: 1rem 1.25rem;
        border-radius: 1.5rem;
        max-width: 80%;
    }
    .chat-bubble.student {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 0.5rem;
    }
    .chat-bubble.admin {
        background-color: #fff7ed;
        border: 1px solid #ffedd5;
        border-bottom-right-radius: 0.5rem;
    }
</style>

<div class="p-8 min-h-screen">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tickets.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-400 hover:text-orange-500 hover:border-orange-200 transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Detail Tiket #TK-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-sm text-slate-500">Dilaporkan pada {{ $ticket->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
        
        <div class="flex gap-3">
            @if($ticket->status != 'Closed')
            <form action="{{ route('admin.tickets.status', $ticket->id) }}" method="POST" onsubmit="return confirm('Tutup tiket ini?')">
                @csrf
                <input type="hidden" name="status" value="Closed">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-800 text-white text-sm font-bold shadow-lg shadow-slate-800/30 hover:bg-slate-900 transition flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Tandai Selesai
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 text-emerald-600">
        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Percakapan (Kiri) --}}
        <div class="lg:col-span-2 flex flex-col h-[700px]">
            <div class="flex-1 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden flex flex-col">
                {{-- Header Chat --}}
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">{{ $ticket->subject }}</h2>
                        <span class="text-xs font-semibold text-slate-500">Keluhan Awal</span>
                    </div>
                    @if($ticket->status == 'Open')
                        <span class="px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-600 text-xs font-bold">Open</span>
                    @elseif($ticket->status == 'In-Progress')
                        <span class="px-3 py-1 rounded-full border border-amber-200 bg-amber-50 text-amber-600 text-xs font-bold">In-Progress</span>
                    @else
                        <span class="px-3 py-1 rounded-full border border-slate-200 bg-slate-100 text-slate-500 text-xs font-bold">Closed</span>
                    @endif
                </div>

                {{-- Chat Area --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    {{-- Pesan Awal Siswa --}}
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-500 font-bold">
                            {{ substr($ticket->reporter->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-baseline gap-2 mb-1">
                                <span class="text-sm font-bold text-slate-700">{{ $ticket->reporter->name ?? 'Siswa' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $ticket->created_at->format('H:i') }}</span>
                            </div>
                            <div class="chat-bubble student text-sm text-slate-700 leading-relaxed shadow-sm">
                                {{ $ticket->description }}
                            </div>
                        </div>
                    </div>

                    {{-- Balasan --}}
                    @foreach($ticket->responses as $response)
                        @if($response->sender_type == 'student')
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-500 font-bold">
                                {{ substr($ticket->reporter->name ?? 'S', 0, 1) }}
                            </div>
                            <div>
                                <div class="flex items-baseline gap-2 mb-1">
                                    <span class="text-sm font-bold text-slate-700">{{ $ticket->reporter->name ?? 'Siswa' }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $response->created_at->format('d M, H:i') }}</span>
                                </div>
                                <div class="chat-bubble student text-sm text-slate-700 leading-relaxed shadow-sm">
                                    {{ $response->message }}
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="flex gap-4 flex-row-reverse">
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex-shrink-0 flex items-center justify-center text-orange-600 font-bold">
                                AD
                            </div>
                            <div class="flex flex-col items-end">
                                <div class="flex items-baseline gap-2 mb-1 flex-row-reverse">
                                    <span class="text-sm font-bold text-slate-700">Admin</span>
                                    <span class="text-[10px] text-slate-400">{{ $response->created_at->format('d M, H:i') }}</span>
                                </div>
                                <div class="chat-bubble admin text-sm text-slate-800 leading-relaxed shadow-sm">
                                    {{ $response->message }}
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- Form Balas --}}
                @if($ticket->status != 'Closed')
                <div class="p-4 border-t border-slate-100 bg-white">
                    <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="relative">
                            <textarea name="message" rows="3" required placeholder="Ketik balasan Anda di sini..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition resize-none">{{ $suggestedReply }}</textarea>
                            <button type="submit" class="absolute bottom-3 right-3 p-2 bg-orange-500 text-white rounded-xl shadow-md shadow-orange-500/30 hover:bg-orange-600 transition">
                                <i data-lucide="send" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @if($suggestedReply)
                        <p class="text-[10px] text-amber-600 mt-2 flex items-center gap-1">
                            <i data-lucide="lightbulb" class="w-3 h-3"></i> Teks di atas adalah saran balasan otomatis berdasarkan keluhan siswa.
                        </p>
                        @endif
                    </form>
                </div>
                @else
                <div class="p-6 border-t border-slate-100 bg-slate-50 text-center">
                    <p class="text-sm font-medium text-slate-500 flex items-center justify-center gap-2">
                        <i data-lucide="lock" class="w-4 h-4"></i> Percakapan telah ditutup.
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- Info Sidebar (Kanan) --}}
        <div class="space-y-6">
            {{-- Info Siswa --}}
            <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/40 border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Informasi Pelapor</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-xl font-black text-slate-400">
                        {{ substr($ticket->reporter->name ?? 'S', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">{{ $ticket->reporter->name ?? 'Siswa Tidak Ditemukan' }}</p>
                        <p class="text-xs text-slate-500">NIS: {{ $ticket->reporter->nis ?? '-' }}</p>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">Total Poin AkvaScan</span>
                    <span class="font-black text-orange-500 text-lg">{{ $ticket->reporter->points ?? 0 }} <span class="text-xs font-medium text-slate-400">Pts</span></span>
                </div>
            </div>

            {{-- Detail Tiket --}}
            <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/40 border border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Detail Tiket</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] uppercase text-slate-400 font-bold">Tingkat Prioritas</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">
                            @if($ticket->priority == 'High')
                                <span class="text-red-500"><i data-lucide="alert-circle" class="w-4 h-4 inline mr-1"></i> Tinggi (High)</span>
                            @elseif($ticket->priority == 'Mid')
                                <span class="text-orange-500"><i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1"></i> Sedang (Mid)</span>
                            @else
                                <span class="text-slate-600"><i data-lucide="info" class="w-4 h-4 inline mr-1"></i> Rendah (Low)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase text-slate-400 font-bold">Waktu Laporan</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $ticket->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Rating Kepuasan --}}
            @if($ticket->rating)
            <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-[2rem] p-6 shadow-xl shadow-orange-200 text-white">
                <h3 class="text-xs font-bold uppercase tracking-widest text-orange-100 mb-4 flex items-center gap-2">
                    <i data-lucide="star" class="w-4 h-4"></i> Rating Kepuasan
                </h3>
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" class="w-6 h-6 {{ $i <= $ticket->rating->score ? 'fill-white text-white' : 'text-orange-300 opacity-50' }}"></i>
                    @endfor
                </div>
                @if($ticket->rating->feedback)
                    <p class="text-sm text-orange-50 italic">"{{ $ticket->rating->feedback }}"</p>
                @endif
                <div class="mt-4 pt-4 border-t border-orange-300/30">
                    <p class="text-xs text-orange-100 flex items-center gap-1">
                        <i data-lucide="gift" class="w-3 h-3"></i> +5 Poin telah diberikan ke siswa.
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        // Auto scroll to bottom of chat
        const chatArea = document.querySelector('.overflow-y-auto');
        if(chatArea) {
            chatArea.scrollTop = chatArea.scrollHeight;
        }
    });
</script>
@endsection
