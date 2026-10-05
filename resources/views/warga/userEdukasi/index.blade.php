<h2>Edukasi Perawatan Fasilitas Publik</h2>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

@if ($kontenList->isEmpty())
    <p>Belum ada konten edukasi.</p>
@else
    <table border="1" cellpadding="8">
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        @foreach ($kontenList as $i => $konten)
            @php
                $progress = $konten->progress->first();
                $status = $progress->status ?? 'belum_dibaca';
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $konten->judul }}</td>
                <td>{{ $konten->kategori }}</td>
                <td>
                    @if ($status === 'selesai')
                        Selesai ✅
                    @elseif ($status === 'sedang')
                        Sedang Dibaca
                    @else
                        Belum Dibaca
                    @endif
                </td>
                <td>
                    <a href="{{ route('warga.user-edukasi.show', $konten->id) }}">
                        {{ $status === 'selesai' ? 'Baca Lagi' : 'Baca Sekarang' }}
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
@endif