<div>
    <h1 class="feature-title">
        Pemasok
    </h1>

    <x-messages></x-messages>

    <div>
        @if($pemasoks->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th> #</th>
                        <th> Nama </th>
                        <th> Alamat </th>
                        <th> No. Telepon </th>
                        <th class="text-center"> @lang("app.actions") </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($pemasoks as $pemasok)
                        <tr>
                            <td> {{ $pemasoks->firstItem() + $loop->index }} </td>
                            <td> {{ $pemasok->nama }} </td>
                            <td> {{ $pemasok->alamat }} </td>
                            <td> {{ $pemasok->no_telepon }} </td>
                            <td class="text-center">
                                <button
                                        x-data="{}"
                                        x-on:click="
                                        window.confirmDialog()
                                            .then(response => {
                                                if (response.value) {
                                                    window.livewire.emit('pemasok:delete', {{ $pemasok->id }})
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
                {{ $pemasoks->links() }}
            </div>

        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __("messages.no_data") }}
            </div>
        @endif
    </div>
</div>
