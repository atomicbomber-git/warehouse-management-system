@extends("layouts.app")

@section("content")
    <h1 class="feature-title">
        <a href="{{ route("laporan-keuangan.index") }}">
            Laporan Keuangan
        </a>

        /

        Saldo Awal
    </h1>

    <x-messages></x-messages>

    <div class="card">
        <div class="card-body">
            <form action="{{ route("saldo-awal.update", $saldo_awal) }}"
                  method="POST"
            >
                @csrf
                @method("PUT")

                <div class="form-group">
                    <label for="jumlah"> Jumlah: </label>
                    <input
                            id="jumlah"
                            type="number"
                            placeholder="Jumlah"
                            class="form-control @error("jumlah") is-invalid @enderror"
                            name="jumlah"
                            value="{{ old("jumlah", $saldo_awal->jumlah) }}"
                    />
                    @error("jumlah")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary">
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection