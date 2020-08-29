@extends("layouts.app")

@section("content")
    <h1 class="feature-title">
        <a href="{{ route("penjualan.index") }}">
            Rekap Pengeluaran
        </a>

        /

        Lihat
    </h1>

    <div class="card">
        <div class="card-body">
            <dl>
                <dt> Tanggal Pengeluaran </dt>
                <dd> {{ $penjualan->tanggal_penjualan }} </dd>

                <dt> Daftar Item </dt>
                <dd>
                    @if($penjualan->items->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th> # </th>
                                    <th> Barang </th>
                                    <th class="text-right"> Jumlah </th>
                                    <th class="text-right"> Harga </th>
                                    <th class="text-right"> Subtotal </th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($penjualan->items as $item)
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td> {{ $item->barang->nama }} </td>
                                        <td class="text-right"> {{ \Facades\App\Support\Formatter::currency($item->jumlah) }} </td>
                                        <td class="text-right"> {{ \Facades\App\Support\Formatter::currency($item->harga_satuan) }} </td>
                                        <td class="text-right"> {{ \Facades\App\Support\Formatter::currency($item->subtotal) }} </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>  </td>
                                        <td>  </td>
                                        <td>  </td>
                                        <td class="text-right font-weight-bold"> Total: </td>
                                        <td class="text-right"> {{ \Facades\App\Support\Formatter::currency($total_harga_penjualan) }} </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Tidak terdapat item pengeluaran sama sekali
                        </div>
                    @endif
                </dd>
            </dl>
        </div>
    </div>
@endsection