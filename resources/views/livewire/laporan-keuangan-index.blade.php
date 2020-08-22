<div>
    <h1 class="feature-title">
        Laporan Keuangan
    </h1>

    <div>
        @if($transaksis->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead>

                    <tr>
                        <th> # </th>
                        <th> Keterangan </th>
                        <th class="text-right"> Jumlah </th>
                        <th> Tanggal </th>
                    </tr>

                    </thead>

                    <tbody>
                    @foreach ($transaksis as $transaksi)
                        <tr>
                            <td> {{ $transaksis->firstItem() + $loop->index }} </td>
                            <td>
                                @if($transaksi->entitas_terkait instanceof \App\TransaksiStock)
                                    @if($transaksi->jumlah < 0)
                                        Pembelian Stock
                                    @else
                                        Pengembalian Stock
                                    @endif

                                    {{ $transaksi->entitas_terkait->stock->barang->nama }}

                                    ({{ $transaksi->entitas_terkait->jumlah }} {{ $transaksi->entitas_terkait->stock->barang->satuan }})

                                @elseif($transaksi->entitas_terkait instanceof \App\ItemPenjualan)
                                    Penjualan Barang

                                    {{ $transaksi->entitas_terkait->barang->nama }}
                                    ({{ $transaksi->entitas_terkait->jumlah }} {{ $transaksi->entitas_terkait->barang->satuan }})
                                @endif
                            </td>
                            <td class="text-right">
                                {{ \Facades\App\Support\Formatter::currency($transaksi->jumlah) }}
                            </td>
                            <td>
                                {{ $transaksi->created_at }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $transaksis->links() }}
            </div>

        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __("messages.no_data") }}
            </div>
        @endif
    </div>
</div>
