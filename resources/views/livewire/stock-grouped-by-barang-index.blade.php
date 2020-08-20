<div>
    <h1 class="feature-title">
        Stock per Barang
    </h1>

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
                            <td> {{ $barang->nama }} </td>
                            <td> {{ $barang->satuan }} </td>
                            <td class="text-right"> {{ \Facades\App\Support\Formatter::number($barang->stock) }} </td>
                            <td class="text-center">
                                <a
                                        class="btn btn-primary btn-sm"
                                        href="{{ route("barang.stock-by-barang.index", $barang) }}">
                                    Stock
                                </a>
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
                {{ __("messages.no_data") }}
            </div>
        @endif
    </div>
</div>