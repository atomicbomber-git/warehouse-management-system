@extends('layouts.app')

@section('content')
    <h1 class="feature-title">
        Masuk
    </h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route("login") }}"
                  method="POST"
            >
                @csrf
                @method("POST")

                <div class="form-group">
                    <label for="username"> Nama Pengguna: </label>
                    <input
                            id="username"
                            type="text"
                            placeholder="Nama Pengguna"
                            class="form-control @error("username") is-invalid @enderror"
                            name="username"
                            value="{{ old("username") }}"
                    />
                    @error("username")
                    <span class="invalid-feedback">
                    {{ $message }}
                </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password"> Kata Sandi: </label>
                    <input
                            id="password"
                            type="password"
                            placeholder="Kata Sandi"
                            class="form-control @error("password") is-invalid @enderror"
                            name="password"
                            value="{{ old("password") }}"
                    />
                    @error("password")
                    <span class="invalid-feedback">
                    {{ $message }}
                </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary">
                        Masuk
                        <i class="fas fa-sign-in-alt"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-3">
        @if(request()->query("barang_id") === null)
            <livewire:stock-grouped-by-barang-index
                    in_guest_mode="true"
            />
        @else
            @livewire("stock-by-barang-index", [
                "barangId" =>  request()->query("barang_id"),
                "inGuestMode" => true,
            ])
        @endif
    </div>
@endsection
