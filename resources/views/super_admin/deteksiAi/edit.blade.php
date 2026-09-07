<form action="{{ route('deteksi_ai.update', $deteksiAI->id) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    Laporan :
    <select name="laporan_id">
        <option value="">-- Pilih Laporan --</option>

        @foreach ($laporans as $laporan)
            <option value="{{ $laporan->id }}"
                {{ old('laporan_id', $deteksiAI->laporan_id) == $laporan->id ? 'selected' : '' }}>
                {{ $laporan->judul ?? $laporan->deskripsi ?? ('Laporan #' . $laporan->id) }}
            </option>
        @endforeach
    </select>
    @if ($errors->has('laporan_id'))
        <span>{{ $errors->first('laporan_id') }}</span>
    @endif

    <br>

    Jenis Objek :
    <input type="text" name="jenis_objek" value="{{ old('jenis_objek', $deteksiAI->jenis_objek) }}">
    @if ($errors->has('jenis_objek'))
        <span>{{ $errors->first('jenis_objek') }}</span>
    @endif

    <br>

    Confidence (0 - 1) :
    <input type="number" step="0.01" min="0" max="1" name="confidence" value="{{ old('confidence', $deteksiAI->confidence) }}">
    @if ($errors->has('confidence'))
        <span>{{ $errors->first('confidence') }}</span>
    @endif

    <br>

    Tingkat Kerusakan :
    <select name="tingkat_kerusakan">
        <option value="">-- Pilih Tingkat Kerusakan --</option>

        <option value="ringan"
            {{ old('tingkat_kerusakan', $deteksiAI->tingkat_kerusakan) == 'ringan' ? 'selected' : '' }}>
            Ringan
        </option>

        <option value="sedang"
            {{ old('tingkat_kerusakan', $deteksiAI->tingkat_kerusakan) == 'sedang' ? 'selected' : '' }}>
            Sedang
        </option>

        <option value="berat"
            {{ old('tingkat_kerusakan', $deteksiAI->tingkat_kerusakan) == 'berat' ? 'selected' : '' }}>
            Berat
        </option>
    </select>
    @if ($errors->has('tingkat_kerusakan'))
        <span>{{ $errors->first('tingkat_kerusakan') }}</span>
    @endif

    <br>

    Estimasi Prioritas :
    <select name="estimasi_prioritas">
        <option value="">-- Pilih Prioritas --</option>

        <option value="kritis"
            {{ old('estimasi_prioritas', $deteksiAI->estimasi_prioritas) == 'kritis' ? 'selected' : '' }}>
            Kritis
        </option>

        <option value="sedang"
            {{ old('estimasi_prioritas', $deteksiAI->estimasi_prioritas) == 'sedang' ? 'selected' : '' }}>
            Sedang
        </option>

        <option value="rendah"
            {{ old('estimasi_prioritas', $deteksiAI->estimasi_prioritas) == 'rendah' ? 'selected' : '' }}>
            Rendah
        </option>
    </select>
    @if ($errors->has('estimasi_prioritas'))
        <span>{{ $errors->first('estimasi_prioritas') }}</span>
    @endif

    <br>

    Hasil Validasi :
    <select name="hasil_validasi">
        <option value="">-- Pilih Hasil Validasi --</option>

        <option value="valid"
            {{ old('hasil_validasi', $deteksiAI->hasil_validasi) == 'valid' ? 'selected' : '' }}>
            Valid
        </option>

        <option value="tidak_valid"
            {{ old('hasil_validasi', $deteksiAI->hasil_validasi) == 'tidak_valid' ? 'selected' : '' }}>
            Tidak Valid
        </option>
    </select>
    @if ($errors->has('hasil_validasi'))
        <span>{{ $errors->first('hasil_validasi') }}</span>
    @endif

    <br>

    Response LLM :
    <textarea name="response_llm">{{ old('response_llm', $deteksiAI->response_llm) }}</textarea>
    @if ($errors->has('response_llm'))
        <span>{{ $errors->first('response_llm') }}</span>
    @endif

    <br>

    <button type="submit">Save</button>
    <a href="{{ route('deteksi_ai.index') }}">Back</a>
</form>
