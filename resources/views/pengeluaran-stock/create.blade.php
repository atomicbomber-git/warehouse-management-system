@extends("layouts.app")

@section("content")
    <h1 class="feature-title">
        <a href="{{ route("stock-grouped-by-barang.stock-by-barang.index", $stock->barang_id) }}">
            {{ $stock->barang->nama }}
        </a>

        /

        Pengeluaran
    </h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route("stock-by-barang.pengeluaran.store", $stock) }}"
                  method="POST"
            >
                @csrf
                @method("POST")

                <div class="form-group">
                    <label for="jumlah_awal"> Jumlah Awal: </label>
                    <input
                            value="{{ $stock->jumlah }}"
                            id="jumlah_awal"
                            type="text"
                            class="form-control"
                    />
                </div>

                <div class="form-group">
                    <label for="alasan"> Alasan: </label>
                    <select
                            id="alasan"
                            type="text"
                            class="form-control @error("alasan") is-invalid @enderror"
                            name="alasan">
                        @foreach ($reason_types as $key => $caption)
                            <option value="{{ $key }}" {{ old("alasan") === $key ? "selected" : "" }}>
                                {{ $caption }}
                            </option>
                        @endforeach
                    </select>
                    @error("alasan")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_dikeluarkan"> Jumlah Pengembalian: </label>
                    <input
                            id="jumlah_dikeluarkan"
                            type="text"
                            placeholder="Jumlah Dikeluarkan"
                            class="form-control @error("jumlah_dikeluarkan") is-invalid @enderror"
                            name="jumlah_dikeluarkan"
                            value="{{ old("jumlah_dikeluarkan") }}"
                    />
                    @error("jumlah_dikeluarkan")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary">
                        Keluarkan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection