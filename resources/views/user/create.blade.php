@extends("layouts.app")

@section("content")
    <h1 class="feature-title">
        <a href="{{ route("user.index") }}"> Pengguna </a>
        /
        Tambah
    </h1>

    <x-messages></x-messages>

    <div class="card">
        <div class="card-body">
            <form action="{{ route("user.store") }}"
                  method="POST"
            >
                @csrf
                @method("POST")

                <div class="form-group">
                    <label for="name"> Nama: </label>
                    <input
                            id="name"
                            type="text"
                            placeholder="Nama"
                            class="form-control @error("name") is-invalid @enderror"
                            name="name"
                            value="{{ old("name") }}"
                    />
                    @error("name")
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>

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
                    <label for="level"> Level: </label>
                    <select
                            id="level"
                            type="text"
                            class="form-control @error("level") is-invalid @enderror"
                            name="level"
                    >
                        @foreach ($level_options as $level => $caption)
                            <option value="{{ $level }}" @if($level === old("level")) selected @endif>
                                {{ $caption }}
                            </option>
                        @endforeach

                    </select>
                    @error("level")
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

                <div class="form-group">
                    <label for="password_confirmation"> Ulangi Kata Sandi: </label>
                    <input
                            id="password_confirmation"
                            type="password"
                            placeholder="Ulangi Kata Sandi"
                            class="form-control @error("password_confirmation") is-invalid @enderror"
                            name="password_confirmation"
                            value="{{ old("password_confirmation") }}"
                    />
                    @error("password_confirmation")
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