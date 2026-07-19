<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #5520B8;
            padding-bottom: 10px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #5520B8;
            margin: 0;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $title }}</h1>
        <p class="subtitle">Program Studi {{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }} ({{ $settings_data['prodi_jenjang'] ?? 'Sarjana' }})</p>
    </div>

    <p>Dokumen ini merupakan hasil ekspor untuk <strong>{{ $title }}</strong> dari sistem akreditasi {{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }}.</p>
    
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Kriteria</th>
                <th style="width: 15%;">Kode</th>
                <th style="width: 25%;">Nama Elemen/Kriteria</th>
                <th style="width: 25%;">Kondisi Saat Ini</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 15%;">Capaian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            <tr>
                <td style="text-align: center;">{{ $item['kriteria'] }}</td>
                <td>{{ $item['kode'] }}</td>
                <td>{{ $item['nama'] }}</td>
                <td>{{ Str::limit($item['kondisi_saat_ini'], 100) }}</td>
                <td style="text-align: center;">{{ $item['status'] }}</td>
                <td style="text-align: left; font-size: 12px;">
                    Narasi: {{ $item['narasi_persen'] }}%<br>
                    Bukti: {{ $item['bukti_persen'] }}%
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Belum ada data narasi/bukti yang diisi untuk kriteria ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
