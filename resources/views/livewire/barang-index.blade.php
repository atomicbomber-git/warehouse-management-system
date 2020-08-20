<div>
    <h1 class="feature-title">
        Barang
    </h1>

    <x-messages></x-messages>

    <div class="d-flex justify-content-between py-3">
        <div></div>
        <a href="{{ route("barang.create") }}" class="btn btn-primary">
            Tambah
        </a>
    </div>

    <div>
        @if($barangs->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th> # </th>
                        <th> Nama </th>
                        <th> Satuan </th>
                        <th class="text-right"> Harga Jual </th>
                        <th class="text-center"> @lang("app.actions") </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($barangs as $barang)
                        <tr>
                            <td> {{ $barangs->firstItem() + $loop->index }} </td>
                            <td> {{ $barang->nama }} </td>
                            <td> {{ $barang->satuan }} </td>
                            <td class="text-right"> {{ Facades\App\Support\Formatter::currency($barang->harga_jual) }} </td>
                            <td class="text-center">
                                <a href="{{ route("barang.edit", $barang)}}" class="btn btn-primary btn-sm">
                                    Ubah
                                </a>

                                <button
                                        x-data="{}"
                                        x-on:click="
                                        window.confirmDialog()
                                            .then(response => {
                                                if (response.value) {
                                                    window.livewire.emit('barang:delete', {{ $barang->id }})
                                                }
                                            })"
                                        class="btn btn-outline-danger btn-sm"
                                >
                                    Hapus
                                </button>
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
