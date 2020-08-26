@extends("layouts.app")

@section("content")
    <h1 class="feature-title">
        <a href="{{ route("stock-grouped-by-barang.index") }}">
            Stok per Barang
        </a>

        /

        <a href="{{ route("stock-grouped-by-barang.stock-by-barang.index", $barang) }}">
            {{ $barang->nama }}
        </a>

        / Tambah
    </h1>

    <x-messages></x-messages>

    <div class="card">
        <div class="card-body">
            <form action="{{ route("stock-grouped-by-barang.stock-by-barang.store", $barang) }}"
                  method="POST"
            >
                @csrf
                @method("POST")

                <div class="form-group">
                    <label for="pemasok_id"> Pemasok: </label>
                    <select
                            id="pemasok_id"
                            type="text"
                            class="form-control @error("pemasok_id") is-invalid @enderror"
                            name="pemasok_id"
                    >
                        @if($old_pemasok ?? null)
                            <option selected="selected" value="{{ $old_pemasok->id }}">
                                {{ $old_pemasok->nama  }}
                            </option>
                        @endif
                    </select>
                    @error("pemasok_id")
                    <span class="invalid-feedback">
                    {{ $message }}
                </span>
                    @enderror

                    @push("scripts")
                        <script type="application/javascript">
                            $("#pemasok_id").select2({
                                ajax: {
                                    url: "{{ route("pemasok-search.index") }}",
                                },
                                theme: "bootstrap4"
                            })
                        </script>
                    @endpush
                </div>

                <div class="form-group">
                    <label for="tanggal_masuk"> Tanggal Masuk: </label>
                    <input
                            id="tanggal_masuk"
                            type="date"
                            placeholder="Tanggal Masuk"
                            class="form-control @error("tanggal_masuk") is-invalid @enderror"
                            name="tanggal_masuk"
                            value="{{ old("tanggal_masuk") }}"
                    />
                    @error("tanggal_masuk")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_kadaluarsa"> Tanggal Kadaluarsa: </label>
                    <input
                            id="tanggal_kadaluarsa"
                            type="date"
                            placeholder="Tanggal Kadaluarsa"
                            class="form-control @error("tanggal_kadaluarsa") is-invalid @enderror"
                            name="tanggal_kadaluarsa"
                            value="{{ old("tanggal_kadaluarsa") }}"
                    />
                    @error("tanggal_kadaluarsa")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah"> Jumlah: </label>
                    <input
                            id="jumlah"
                            type="number"
                            placeholder="Jumlah"
                            class="form-control @error("jumlah") is-invalid @enderror"
                            name="jumlah"
                            value="{{ old("jumlah") }}"
                    />
                    @error("jumlah")
                    <span class="invalid-feedback">
                    {{ $message }}
                </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="harga_satuan"> Harga Satuan: </label>
                    <input
                            id="harga_satuan"
                            type="number"
                            placeholder="Harga Satuan"
                            class="form-control @error("harga_satuan") is-invalid @enderror"
                            name="harga_satuan"
                            value="{{ old("harga_satuan") }}"
                    />
                    @error("harga_satuan")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bisa_dikembalikan"> Bisa Dikembalikan: </label>
                    <select
                            id="bisa_dikembalikan"
                            type="text"
                            class="form-control @error("bisa_dikembalikan") is-invalid @enderror"
                            name="bisa_dikembalikan"
                    >
                        <option {{ old("bisa_dikembalikan") == 0 ? "selected" : ""  }} value="0"> Ya </option>
                        <option {{ old("bisa_dikembalikan") == 1 ? "selected" : ""  }} value="1"> Tidak </option>
                    </select>
                    @error("bisa_dikembalikan")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection