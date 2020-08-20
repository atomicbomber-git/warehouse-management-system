@extends("layouts.app")

@section("content")
    <h1 class="feature-title">
        <a href="{{ route("barang.index") }}">
            Barang
        </a>

        /

        Ubah
    </h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route("barang.update", $barang) }}"
                  method="POST"
            >
                @csrf
                @method("PUT")

                <div class="form-group">
                    <label for="nama"> Nama: </label>
                    <input
                            id="nama"
                            type="text"
                            placeholder="Nama"
                            class="form-control @error("nama") is-invalid @enderror"
                            name="nama"
                            value="{{ old("nama", $barang->nama) }}"
                    />
                    @error("nama")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="satuan"> Satuan: </label>
                    <input
                            id="satuan"
                            type="text"
                            placeholder="Satuan"
                            class="form-control @error("satuan") is-invalid @enderror"
                            name="satuan"
                            value="{{ old("satuan", $barang->satuan) }}"
                    />
                    @error("satuan")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="harga_jual"> Harga Jual: </label>
                    <input
                            id="harga_jual"
                            type="number"
                            placeholder="Harga Jual"
                            class="form-control @error("harga_jual") is-invalid @enderror"
                            name="harga_jual"
                            value="{{ old("harga_jual", $barang->harga_jual) }}"
                    />
                    @error("harga_jual")
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