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
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #444;
            margin-top: 30px;
            margin-bottom: 10px;
            background-color: #f0f0f0;
            padding: 8px 10px;
            border-left: 4px solid #5520B8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-size: 12px;
            color: #555;
            text-transform: uppercase;
        }
        td {
            font-size: 13px;
        }
        .text-center { text-align: center; }
        .text-muted { color: #6c757d; font-size: 11px; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $title }}</h1>
        <p class="subtitle">Program Studi {{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }} ({{ $settings_data['prodi_jenjang'] ?? 'Sarjana' }})</p>
    </div>

    <p>Dokumen ini merupakan hasil ekspor untuk <strong>{{ $title }}</strong> dari sistem akreditasi {{ $settings_data['prodi_nama'] ?? 'S1 Kesehatan Lingkungan' }}.</p>
    
    <div class="section-title">Dokumen Universitas (UNIV)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">NO</th>
                <th style="width: 10%;">KODE</th>
                <th style="width: 32%;">NAMA DOKUMEN</th>
                <th style="width: 9%;">JENIS</th>
                <th style="width: 8%;" class="text-center">STATUS</th>
                <th style="width: 16%;">LINK / LOKASI FILE</th>
                <th style="width: 8%;">PIC</th>
                <th style="width: 13%;">DEADLINE</th>
            </tr>
        </thead>
        <tbody>
            @forelse($univDocs as $index => $doc)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $doc->kode }}</td>
                <td>
                    <div class="fw-bold">{{ $doc->nama_dokumen }}</div>
                    @if($doc->deskripsi)
                    <div class="text-muted" style="margin-top: 4px;">{{ $doc->deskripsi }}</div>
                    @endif
                </td>
                <td>{{ $doc->jenis }}</td>
                <td class="text-center">{{ $doc->status }}</td>
                <td>{{ $doc->link ?: '-' }}</td>
                <td>{{ $doc->pic ?: '-' }}</td>
                <td style="white-space: nowrap;">{{ $doc->deadline ? \Carbon\Carbon::parse($doc->deadline)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada dokumen Universitas</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title" style="page-break-before: always;">Dokumen Fakultas (FIKES)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">NO</th>
                <th style="width: 10%;">KODE</th>
                <th style="width: 32%;">NAMA DOKUMEN</th>
                <th style="width: 9%;">JENIS</th>
                <th style="width: 8%;" class="text-center">STATUS</th>
                <th style="width: 16%;">LINK / LOKASI FILE</th>
                <th style="width: 8%;">PIC</th>
                <th style="width: 13%;">DEADLINE</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fikesDocs as $index => $doc)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $doc->kode }}</td>
                <td>
                    <div class="fw-bold">{{ $doc->nama_dokumen }}</div>
                    @if($doc->deskripsi)
                    <div class="text-muted" style="margin-top: 4px;">{{ $doc->deskripsi }}</div>
                    @endif
                </td>
                <td>{{ $doc->jenis }}</td>
                <td class="text-center">{{ $doc->status }}</td>
                <td>{{ $doc->link ?: '-' }}</td>
                <td>{{ $doc->pic ?: '-' }}</td>
                <td style="white-space: nowrap;">{{ $doc->deadline ? \Carbon\Carbon::parse($doc->deadline)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada dokumen Fakultas</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
