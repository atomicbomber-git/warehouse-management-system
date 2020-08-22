<div>
    <h1 class="feature-title">
        <a href="{{ route("penjualan.index") }}">
            Penjualan
        </a>

        /

        Tambah
    </h1>

    <x-messages></x-messages>

    <h1>
        {{ $selectedBarangId }}
    </h1>

    <div class="card">
        <div class="card-body">
            <form wire:submit.prevent="submit">
                <div class="form-group">
                    <label for="tanggal_penjualan"> Tanggal Penjualan: </label>
                    <input
                            wire:model="tanggal_penjualan"
                            id="tanggal_penjualan"
                            type="date"
                            placeholder="Tanggal Penjualan"
                            class="form-control @error("tanggal_penjualan") is-invalid @enderror"
                            name="tanggal_penjualan"
                            value="{{ old("tanggal_penjualan") }}"
                    />
                    @error("tanggal_penjualan")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <p class="h5"> Daftar Item: </p>
                @if(count($items) !== 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead class="thead-light">
                            <tr>
                                <th> # </th>
                                <th> Barang </th>
                                <th class="text-right"> Stock </th>
                                <th class="text-right"> Jumlah </th>
                                <th class="text-right"> Harga Jual Satuan </th>
                                <th class="text-right"> Subtotal </th>
                                <th class="text-center"> {{ __("app.actions")  }} </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($items as $barangId => $item)
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td> {{ $item["barang"]["nama"] }} </td>
                                    <td class="text-right"> {{ $item["barang"]["stock"] }} </td>
                                    <td class="text-right">
                                        <label for="jumlah">
                                            <input
                                                    id="jumlah"
                                                    wire:key="items.{{ $barangId }}.jumlah"
                                                    wire:model="items.{{ $barangId }}.jumlah"
                                                    type="number"
                                                    placeholder="Jumlah"
                                                    class="form-control form-control-sm text-right @error("items.{$barangId}.jumlah") is-invalid @enderror"
                                                    name="jumlah"
                                            />
                                            @error("items.{$barangId}.jumlah")
                                                <span class="invalid-feedback">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </label>
                                    </td>
                                    <td class="text-right">
                                        <label for="harga_jual">
                                            <input
                                                    id="harga_jual"
                                                    wire:key="items.{{ $barangId }}.harga_jual"
                                                    wire:model="items.{{ $barangId }}.harga_jual"
                                                    type="number"
                                                    placeholder="Harga Jual"
                                                    class="form-control form-control-sm text-right @error("items.{$barangId}.harga_jual") is-invalid @enderror"
                                                    name="harga_jual"
                                                    value="{{ old("harga_jual") }}"
                                            />
                                            @error("items.{$barangId}.harga_jual")
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </label>
                                    </td>
                                    <td class="text-right">
                                        {{ \Facades\App\Support\Formatter::currency($item["subtotal"]) }}
                                    </td>
                                    <td class="text-center">
                                        <button
                                                type="button"
                                                x-data="{}"
                                                x-on:click="
                                                    window.confirmDialog()
                                                        .then(response => {
                                                            if (response.value) {
                                                                window.livewire.emit('item:remove', {{ $barangId }})
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

                            <tfoot>
                            <tr>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td class="text-right"> Total Harga </td>
                                <td class="text-right">
                                    {{ \Facades\App\Support\Formatter::currency($total_harga) }}
                                </td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-exclamation-circle"></i>
                        Belum terdapat item yang ditambahkan.
                    </div>
                @endif

                <div class="d-flex justify-content-end form-inline py-3">
                    <label for="barang_id" class="mr-3">
                        Tambah Item:
                    </label>

                    <select
                            id="barang_id"
                            type="text"
                            style="width: 300px"
                            class="form-control @error("barang_id") is-invalid @enderror"
                            name="barang_id"
                    >
                    </select>
                    @error("barang_id")
                    <span class="invalid-feedback">
                    {{ $message }}
                </span>
                    @enderror

                    @push("scripts")
                        <script type="application/javascript">
                            function setupSelect2() {
                                $("#barang_id").select2({
                                    ajax: {
                                        url: "{{ route("barang-search.index") }}",
                                        delay: 400,
                                    },
                                    theme: "bootstrap4"
                                }).change(function () {
                                    let value = $(this).val()

                                    if (value !== null) {
                                        window.livewire.emit("item:add", value)
                                        $(this).val(null).trigger("change")
                                    }
                                })
                            }

                            document.addEventListener("livewire:load", function(event) {
                                setupSelect2()

                                window.livewire.hook('afterDomUpdate', () => {
                                    setupSelect2()
                                });
                            });
                        </script>
                    @endpush
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
