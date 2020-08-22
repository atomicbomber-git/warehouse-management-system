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
                        <th> Barang </th>
                        <th> Jumlah </th>
                        <th> Harga Beli </th>
                        <th> Subtotal </th>
                    </tr>

                    </thead>

                    <tbody>
                    @foreach ($transaksis as $transaksi)
                        <tr>
                            <td> {{ $transaksis->firstItem() + $loop->index }} </td>
                            <td> {{ $transaksi->stock->barang->nama  }} </td>
                            <td> {{ $transaksi->stock->barang->nama  }} </td>
                            <td> {{ $transaksi->stock->barang->nama  }} </td>
                            <td> {{ $transaksi->stock->barang->nama  }} </td>
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
