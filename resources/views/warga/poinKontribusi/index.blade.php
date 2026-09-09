<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Poin Kontribusi</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .total-poin {
            font-size: 36px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .poin-masuk {
            color: green;
            font-weight: bold;
        }

        .poin-keluar {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Poin Kontribusi</h1>

    {{-- Total poin --}}
    <div class="card">

        <h3>Total Poin Saya</h3>

        <div class="total-poin">
            {{ number_format($totalPoin, 0, ',', '.') }}
            Poin
        </div>

    </div>


    {{-- Riwayat --}}
    <div class="card">

        <h2>Riwayat Poin</h2>

        @if ($riwayatPoin->count() > 0)

            <table>

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Aktivitas</th>
                        <th>Keterangan</th>
                        <th>Poin</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($riwayatPoin as $poin)

                        <tr>

                            <td>
                                {{ $poin->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                {{ $poin->jenis_aktivitas }}
                            </td>

                            <td>
                                {{ $poin->keterangan ?? '-' }}
                            </td>

                            <td>

                                @if ($poin->poin >= 0)

                                    <span class="poin-masuk">
                                        +{{ number_format($poin->poin, 0, ',', '.') }}
                                    </span>

                                @else

                                    <span class="poin-keluar">
                                        {{ number_format($poin->poin, 0, ',', '.') }}
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <p>
                Belum ada riwayat poin kontribusi.
            </p>

        @endif

    </div>

</div>

</body>
</html>