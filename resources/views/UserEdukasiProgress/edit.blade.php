<form action="{{ route('user-edukasi-progress.update', $userEdukasiProgress->id) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')

    User :
    <select name="user_id">
        <option value="">-- Pilih User --</option>
        @foreach ($users as $item)
        <option value="{{ $item->id }}" {{ old('user_id', $userEdukasiProgress->user_id) == $item->id ? 'selected' : '' }}>
            {{ $item->name }}
        </option>
        @endforeach
    </select>
    @if ($errors->has('user_id'))
    <span>{{ $errors->first('user_id') }}</span>
    @endif

    <br>

    Konten :
    <select name="konten_id">
        <option value="">-- Pilih Konten --</option>
        @foreach ($konten as $item)
        <option value="{{ $item->id }}" {{ old('konten_id', $userEdukasiProgress->konten_id) == $item->id ? 'selected' : '' }}>
            {{ $item->judul }}
        </option>
        @endforeach
    </select>
    @if ($errors->has('konten_id'))
    <span>{{ $errors->first('konten_id') }}</span>
    @endif

    <br>

    Status :
    <select name="status">
        <option value="belum_dibaca" {{ old('status', $userEdukasiProgress->status) == 'belum_dibaca' ? 'selected' : '' }}>Belum Dibaca</option>
        <option value="sedang" {{ old('status', $userEdukasiProgress->status) == 'sedang' ? 'selected' : '' }}>Sedang</option>
        <option value="selesai" {{ old('status', $userEdukasiProgress->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
    </select>
    @if ($errors->has('status'))
    <span>{{ $errors->first('status') }}</span>
    @endif

    <br>

    Progress (%) :
    <input type="number" name="progress" min="0" max="100" value="{{ old('progress', $userEdukasiProgress->progress) }}">
    @if ($errors->has('progress'))
    <span>{{ $errors->first('progress') }}</span>
    @endif

    <br>

    Selesai Pada :
    <input type="date" name="selesai_pada" value="{{ old('selesai_pada', $userEdukasiProgress->selesai_pada) }}">
    @if ($errors->has('selesai_pada'))
    <span>{{ $errors->first('selesai_pada') }}</span>
    @endif

    <br>

    <button type="submit">Update</button>
    <a href="{{ route('user-edukasi-progress.index') }}">Back</a>
</form>