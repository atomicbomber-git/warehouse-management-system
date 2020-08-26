<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    >
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge"
    >


    <link rel="stylesheet"
          href="{{ asset("css/paper.css") }}"
    >

    <style>
        table {
            width: 100%;
            border: thin solid black;
            border-collapse: collapse;
        }

        table td, table th {
            border: thin solid black;
            padding: 0.2rem;
        }
    </style>

    <style>@page { size: A4 }</style>

    <title> REKAP KEUANGAN </title>
</head>
<body class="A4">

@foreach ($laporan_pages as $laporan_page)
    <section class="sheet padding-10mm">
        @if($loop->first)
            <h1 style="text-align: center"> REKAP KEUANGAN </h1>

            <h4 style="text-align: center; text-transform: uppercase">
                @if($filterType == \App\Repositories\LaporanKeuangan::FILTER_TYPE_DAY)
                    {{ \Facades\App\Support\Formatter::dayAndDate($filterValue) }}
                @elseif($filterType == \App\Repositories\LaporanKeuangan::FILTER_TYPE_MONTH)
                    {{ \Facades\App\Support\Formatter::monthAndYear($filterValue) }}
                @elseif($filterType == \App\Repositories\LaporanKeuangan::FILTER_TYPE_YEAR)
                    Tahun {{ $filterValue }}
                @endif
            </h4>
        @endif

        <table>
            <thead>
                <tr>
                    <th> # </th>
                    <th> Keterangan </th>
                    <th style="text-align: right"> Debit </th>
                    <th style="text-align: right"> Kredit </th>
                    <th style="text-align: right"> Saldo </th>
                    <th> Tanggal </th>
                </tr>
            </thead>

            <tbody>
            @foreach ($laporan_page as $transaksi)
                <tr>
                    <td> {{ ($loop->parent->index * $laporan_page->count()) + $loop->iteration }} </td>
                    <td>
                        {{ $transaksi->alasan }}

                        @if($transaksi->entitas_terkait instanceof \App\TransaksiStock)
                            {{ $transaksi->entitas_terkait->stock->barang->nama }}
                            ({{ $transaksi->entitas_terkait->jumlah }} {{ $transaksi->entitas_terkait->stock->barang->satuan }})
                        @elseif($transaksi->entitas_terkait instanceof \App\ItemPenjualan)
                            {{ $transaksi->entitas_terkait->barang->nama }}
                            ({{ $transaksi->entitas_terkait->jumlah }} {{ $transaksi->entitas_terkait->barang->satuan }})
                        @endif
                    </td>
                    <td style="text-align: right">
                        @if($transaksi->jumlah < 0)
                            {{ \Facades\App\Support\Formatter::currency($transaksi->jumlah) }}
                        @endif
                    </td>
                    <td style="text-align: right">
                        @if($transaksi->jumlah >= 0)
                            {{ \Facades\App\Support\Formatter::currency($transaksi->jumlah) }}
                        @endif
                    </td>
                    <td style="text-align: right">
                        {{ \Facades\App\Support\Formatter::currency($transaksi->saldo) }}
                    </td>
                    <td>
                        {{ \Facades\App\Support\Formatter::date($transaksi->tanggal_transaksi) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
@endforeach


</body>
</html>