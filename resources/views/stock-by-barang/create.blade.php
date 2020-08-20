@extends("layouts.app")

@section("content")
    <h1 class="feature-title">
        <a href="{{ route("stock-grouped-by-barang.index") }}">
            Stock per Barang
        </a>

        /

        <a href="{{ route("stock-grouped-by-barang.stock-by-barang.index", $barang) }}">
            {{ $barang->nama }}
        </a>

        / Tambah
    </h1>

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
                    <label for="barang_id"> Barang: </label>
                    <select
                            id="barang_id"
                            type="text"
                            class="form-control @error("barang_id") is-invalid @enderror"
                            name="barang_id"
                    >
                        @if($old_barang ?? null)
                            <option selected="selected" value="{{ $old_barang->id }}">
                                {{ $old_barang->nama  }}
                            </option>
                        @endif
                    </select>
                    @error("barang_id")
                    <span class="invalid-feedback">
                    {{ $message }}
                </span>
                    @enderror

                    @push("scripts")
                        <script type="application/javascript">
                            $("#barang_id").select2({
                                ajax: {
                                    url: "{{ route("barang-search.index") }}",
                                },
                                theme: "bootstrap4"
                            })
                        </script>
                    @endpush
                </div>

                <div class="form-group">
                    <label for="jumlah"> Jumlah: </label>
                    <input
                            id="jumlah"
                            type="text"
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



            </form>


        </div>
    </div>
@endsection