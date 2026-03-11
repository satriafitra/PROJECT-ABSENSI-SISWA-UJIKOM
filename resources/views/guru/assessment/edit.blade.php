@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Edit Nilai: {{ $student->nama }}</h2>
        
        <form action="{{ route('guru.assessment.update', $assessment->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                @foreach($assessment->details as $detail)
                <div class="border-b pb-4">
                    <label class="block font-semibold text-gray-800">{{ $detail->category->name }}</label>
                    <div class="flex items-center gap-4 mt-2">
                        <input type="range" name="scores[{{ $detail->id }}]" min="1" max="5" step="1" 
                               value="{{ $detail->score }}"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-orange-600">
                        <span class="font-bold text-orange-600">{{ $detail->score }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                <label class="block font-bold text-gray-700">Update Catatan</label>
                <textarea name="general_notes" class="w-full border rounded p-2 mt-2" rows="3">{{ $assessment->general_notes }}</textarea>
            </div>

            <div class="mt-8">
                <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded">Update Data</button>
                <a href="{{ route('guru.assessment.index') }}" class="ml-2 text-gray-600 underline">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection