@extends('template.layout')

@section('title', 'Penugasan')

@section('content')
<div>

    <h2 class="text-[36px] font-bold text-gray-800 mb-2">Daftar Penugasan</h2>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-[#4CAF50]/10 text-[#4CAF50] text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-[#E53935]/10 text-[#E53935] text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tab Filter --}}
    <div class="flex gap-6 border-b border-[#D9D9D9] mb-6">
        @php
            $filter = request('filter', 'semua');
            $tabs = [
                'semua' => 'Semua',
                'aktif' => 'Aktif',
                'prioritas' => 'Prioritas',
                'selesai' => 'Selesai',
            ];
        @endphp

        @foreach($tabs as $key => $label)
            <a href="{{ $key == 'semua' ? url()->current() : url()->current().'?filter='.$key }}"
               class="pb-3 text-sm font-medium {{ $filter == $key ? 'text-[#0C343D] border-b-2 border-[#0C343D]' : 'text-[#4A4A4A] hover:text-[#0C343D]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- List Penugasan --}}
    <div class="flex flex-col gap-3">
        @forelse($penugasan as $item)
            @php
                $prioritas = $item->laporan->tingkat_prioritas;
                $badge = match(true) {
                    $item->status == 'Selesai' => ['bg' => '#4CAF50', 'text' => '#FFFFFF', 'label' => 'Selesai'],
                    $prioritas == 'Kritis' => ['bg' => '#E53935', 'text' => '#FFFFFF', 'label' => 'Kritis'],
                    $prioritas == 'Sedang' => ['bg' => '#FF9800', 'text' => '#FFFFFF', 'label' => 'Sedang'],
                    default => ['bg' => '#FFC107', 'text' => '#4A4A4A', 'label' => 'Rendah'],
                };
            @endphp
            <div class="bg-white border border-[#D9D9D9] rounded-xl px-4 py-3 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#4A4A4A]">{{ $item->laporan->judul }}</p>
                    <p class="text-xs text-[#4A4A4A] mt-1">
                        {{ $item->status }} &bull; {{ \Carbon\Carbon::parse($item->tanggal_penugasan)->format('d M Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-medium px-3 py-1 rounded-md"
                          style="background-color: {{ $badge['bg'] }}; color: {{ $badge['text'] }};">
                        {{ $badge['label'] }}
                    </span>
                    <a href="{{ route('petugas.penugasan.show', $item->id) }}"
                       class="text-sm text-[#45818E] hover:underline">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <p class="text-center text-[#4A4A4A] py-6">Belum ada penugasan.</p>
        @endforelse
    </div>

</div>
@endsection
