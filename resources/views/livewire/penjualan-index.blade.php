<div>
    <h1 class="feature-title">
        Rekap Pengeluaran
    </h1>

    <div class="d-flex justify-content-end py-3">
        <a href="{{ route("penjualan.create") }}"
           class="btn btn-primary"
        >
            Tambah
        </a>
    </div>

    <x-messages></x-messages>

    @if($penjualans->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover">
                <thead class="thead-light">
                <tr>
                    <th> #</th>
                    <th> Waktu Penjualan</th>
                    <th class="text-center"> {{ __("app.actions")  }} </th>
                </tr>
                </thead>

                <tbody>
                @foreach ($penjualans as $penjualan)
                    <tr>
                        <td> {{ $penjualans->firstItem() + $loop->index }} </td>
                        <td> {{ $penjualan->waktu_penjualan }} </td>
                        <td class="text-center">
                            <a href="{{ route("penjualan.show", $penjualan) }}"
                               class="btn btn-primary btn-sm"
                            >
                                Lihat
                            </a>

                            @can(\App\Providers\AuthServiceProvider::DELETE_ANY_PENJUALAN)
                                <button
                                        x-data="{}"
                                        x-on:click="
                                    window.confirmDialog()
                                        .then(response => {
                                            if (response.value) {
                                                window.livewire.emit('penjualan:delete', {{ $penjualan->id }})
                                            }
                                        })"
                                        class="btn btn-outline-danger btn-sm"
                                >
                                    Hapus
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $penjualans->links() }}
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            {{ __("messages.errors.no_data") }}
        </div>
    @endif
</div>
