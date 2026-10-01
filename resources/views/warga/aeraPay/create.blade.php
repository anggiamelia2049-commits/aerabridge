<h2>Tukar Poin ke Saldo AERA Pay</h2>

@if ($errors->any())
    <div>
        <strong>Terjadi kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<p>Poin yang kamu miliki saat ini: <strong>{{ $totalPoin }}</strong> Poin</p>
<p><small>Rasio konversi: 1.000 poin = Rp500 saldo simulasi.</small></p>

<form action="{{ route('warga.aeraPay.store') }}" method="POST">
    {{ csrf_field() }}

    Jumlah Poin yang Ditukar :
    <input
        type="number"
        name="jumlah_poin"
        value="{{ old('jumlah_poin') }}"
        min="1000"
        step="1000"
        max="{{ $totalPoin }}"
        required
        {{ $totalPoin < 1000 ? 'disabled' : '' }}
    >
    <br>
    <small>Wajib kelipatan 1.000 (contoh: 1000, 2000, 3000, dst).</small>

    @if ($totalPoin < 1000)
        <p>Poin kamu belum mencukupi untuk ditukar (minimal 1.000 poin).</p>
    @endif

    <br><br>

    <button type="submit" {{ $totalPoin < 1000 ? 'disabled' : '' }}>Tukar Sekarang</button>
    <a href="{{ route('warga.aeraPay.index') }}">Batal</a>
</form>