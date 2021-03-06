@extends("layouts.app")

@section("content")
    <h1 class="feature-title">
        <a href="{{ route("pemasok.index") }}">
            Pemasok
        </a>

        /

        Ubah
    </h1>

    <x-messages></x-messages>

    <div class="card">
        <div class="card-body">
            <form action="{{ route("pemasok.update", $pemasok) }}"
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
                            value="{{ old("nama", $pemasok->nama) }}"
                    />
                    @error("nama")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_telepon"> No Telepon: </label>
                    <input
                            id="no_telepon"
                            type="text"
                            placeholder="No Telepon"
                            class="form-control @error("no_telepon") is-invalid @enderror"
                            name="no_telepon"
                            value="{{ old("no_telepon", $pemasok->no_telepon) }}"
                    />
                    @error("no_telepon")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alamat"> Alamat: </label>
                    <textarea
                            id="alamat"
                            type="text"
                            placeholder="Alamat"
                            class="form-control @error("alamat") is-invalid @enderror"
                            name="alamat"
                    >{{ old("alamat", $pemasok->alamat) }}</textarea>
                    @error("alamat")
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