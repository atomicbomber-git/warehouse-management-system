<div>
    <h1 class="feature-title">
        Stok per Barang
    </h1>

    <x-messages></x-messages>

    <div>
        @if($barangs->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th> # </th>
                        <th> Nama </th>
                        <th> Satuan </th>
                        <th class="text-right"> Stock </th>
                        <th class="text-center"> @lang("app.actions") </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($barangs as $barang)
                        <tr>
                            <td> {{ $barangs->firstItem() + $loop->index }} </td>
                            <td>
                                {{ $barang->nama }}
                                @if($barang->has_alert)
                                    <span class="badge badge-pill badge-danger">
                                        Stok Hampir Habis / Habis
                                    </span>
                                @endif
                            </td>
                            <td> {{ $barang->satuan }} </td>
                            <td class="text-right"> {{ \Facades\App\Support\Formatter::number($barang->stock) }} </td>
                            <td class="text-center">
                                @if(! $inGuestMode)
                                    <a
                                            class="btn btn-primary btn-sm"
                                            href="{{ route("stock-grouped-by-barang.stock-by-barang.index", $barang) }}">
                                        Stok
                                    </a>
                                @else
                                    <a
                                            class="btn btn-primary btn-sm"
                                            href="{{ route("login", ["barang_id" => $barang->id]) }}">
                                        Stok
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $barangs->links() }}
            </div>

        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __("messages.errors.no_data") }}
            </div>
        @endif
    </div>
</div>
