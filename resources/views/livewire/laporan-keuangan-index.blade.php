<div>
    <h1 class="feature-title">
        Laporan Keuangan
    </h1>

    <div class="py-3 form-inline">
        <div class="form-group mr-2">
            <label for="filterType" class="mr-2">
                Tipe Filter:
            </label>

            <select
                    id="filterType"
                    wire:model="filterType"
                    class="form-control"
            >
                @foreach ($filterTypes as $value => $filterName)
                    <option value="{{ $value }}">
                        {{ $filterName }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="filterValue" class="mr-2">
                Nilai Filter:
            </label>

            <input
                    wire:model="filterValue"
                    id="filterValue"
                    class="form-control"
                    type="{{ $filterInputType }}"
            >
        </div>

    </div>


    <div>
        @if($transaksis->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead class="thead-light">

                    <tr>
                        <th> # </th>
                        <th> Keterangan </th>
                        <th class="text-right"> Debit </th>
                        <th class="text-right"> Kredit </th>
                        <th class="text-right"> Saldo </th>
                        <th> Tanggal </th>
                    </tr>

                    </thead>

                    <tbody>
                    @foreach ($transaksis as $transaksi)
                        <tr>
                            <td> {{ $transaksis->firstItem() + $loop->index }} </td>
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
                            <td class="text-right">
                                @if($transaksi->jumlah < 0)
                                    {{ \Facades\App\Support\Formatter::currency($transaksi->jumlah) }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if($transaksi->jumlah >= 0)
                                    {{ \Facades\App\Support\Formatter::currency($transaksi->jumlah) }}
                                @endif
                            </td>
                            <td class="text-right">
                                {{ \Facades\App\Support\Formatter::currency($transaksi->saldo) }}
                            </td>
                            <td>
                                {{ \Facades\App\Support\Formatter::date($transaksi->tanggal_transaksi) }}
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
                {{ __("messages.errors.no_data") }}
            </div>
        @endif
    </div>
</div>
