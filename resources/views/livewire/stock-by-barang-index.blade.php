<div>
    <h1 class="feature-title">
        <a href="{{ route("stock-grouped-by-barang.index") }}">
            Stock per Barang
        </a>

        /

        {{ $barang->nama }}
    </h1>

    <div class="d-flex justify-content-end py-3">
        <a href="{{ route("stock-grouped-by-barang.stock-by-barang.create", $barang) }}" class="btn btn-primary">
            Tambah
        </a>
    </div>

    <x-messages></x-messages>

    <div>
        @if($stocks->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th> # </th>
                        <th> Tanggal Masuk </th>
                        <th> Tanggal Kadaluarsa </th>
                        <th style="width: 200px"> Pemasok </th>
                        <th class="text-right"> Jumlah </th>
                        <th class="text-right"> Harga Satuan </th>
                        <th> @lang("app.actions") </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($stocks as $stock)
                        <tr>
                            <td> {{ $stocks->firstItem() + $loop->index }} </td>
                            <td> {{ $stock->tanggal_masuk }} </td>
                            <td>
                                {{ $stock->tanggal_kadaluarsa }}
                                @if($stock->has_alert)
                                    <span class="badge badge-pill badge-danger">
                                        Hampir Kadaluarsa
                                    </span>
                                @endif
                            </td>
                            <td> {{ $stock->pemasok->nama }}</td>
                            <td class="text-right"> {{ \Facades\App\Support\Formatter::number($stock->jumlah) }} </td>
                            <td class="text-right"> {{ \Facades\App\Support\Formatter::currency($stock->harga_satuan) }} </td>
                            <td class="text-center">
                                <a href="{{ route("stock-by-barang.pengeluaran.create", $stock) }}" class="btn btn-primary btn-sm">
                                    Pengeluaran
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $stocks->links() }}
            </div>

        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __("messages.errors.no_data") }}
            </div>
        @endif
    </div>
</div>
