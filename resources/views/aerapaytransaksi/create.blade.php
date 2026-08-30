<form action="{{ route('aera_pay_transaksi.store') }}" method="POST">
    {{ csrf_field() }}

    User :
    <select name="user_id">
        <option value="">-- Pilih User --</option>

        @foreach ($users as $user)
            <option value="{{ $user->id }}"
                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
    @if ($errors->has('user_id'))
        <span>{{ $errors->first('user_id') }}</span>
    @endif

    <br>

    Laporan :
    <select name="laporan_id">
        <option value="">-- Pilih Laporan --</option>

        @foreach ($laporans as $laporan)
            <option value="{{ $laporan->id }}"
                {{ old('laporan_id') == $laporan->id ? 'selected' : '' }}>
                {{ $laporan->judul ?? $laporan->deskripsi ?? ('Laporan #' . $laporan->id) }}
            </option>
        @endforeach
    </select>
    @if ($errors->has('laporan_id'))
        <span>{{ $errors->first('laporan_id') }}</span>
    @endif

    <br>

    Jenis Transaksi :
    <select name="jenis_transaksi">
        <option value="">-- Pilih Jenis Transaksi --</option>

        <option value="reward" {{ old('jenis_transaksi') == 'reward' ? 'selected' : '' }}>
            Reward
        </option>

        <option value="redeem" {{ old('jenis_transaksi') == 'redeem' ? 'selected' : '' }}>
            Redeem
        </option>

        <option value="admin_adjust" {{ old('jenis_transaksi') == 'admin_adjust' ? 'selected' : '' }}>
            Admin Adjust
        </option>
    </select>
    @if ($errors->has('jenis_transaksi'))
        <span>{{ $errors->first('jenis_transaksi') }}</span>
    @endif

    <br>

    Nominal :
    <input type="number" name="nominal" value="{{ old('nominal') }}">
    @if ($errors->has('nominal'))
        <span>{{ $errors->first('nominal') }}</span>
    @endif

    <br>

    Saldo Sebelum :
    <input type="number" name="saldo_sebelum" value="{{ old('saldo_sebelum') }}">
    @if ($errors->has('saldo_sebelum'))
        <span>{{ $errors->first('saldo_sebelum') }}</span>
    @endif

    <br>

    Saldo Sesudah :
    <input type="number" name="saldo_sesudah" value="{{ old('saldo_sesudah') }}">
    @if ($errors->has('saldo_sesudah'))
        <span>{{ $errors->first('saldo_sesudah') }}</span>
    @endif

    <br>

    Status :
    <select name="status">
        <option value="">-- Pilih Status --</option>

        <option value="berhasil" {{ old('status') == 'berhasil' ? 'selected' : '' }}>
            Berhasil
        </option>

        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
            Pending
        </option>

        <option value="gagal" {{ old('status') == 'gagal' ? 'selected' : '' }}>
            Gagal
        </option>
    </select>
    @if ($errors->has('status'))
        <span>{{ $errors->first('status') }}</span>
    @endif

    <br>

    <button type="submit">Save</button>
    <a href="{{ route('aera_pay_transaksi.index') }}">Back</a>
</form>
