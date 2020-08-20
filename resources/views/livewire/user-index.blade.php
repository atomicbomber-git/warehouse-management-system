<div>
    <h1 class="feature-title">
        Pengguna
    </h1>

    <x-messages></x-messages>

    <div class="d-flex justify-content-between py-3">
        <div class="form-inline">
            <label for="filter_level" class="mr-2">
                Filter Level:
            </label>

            <select
                    class="form-control"
                    wire:model="filterLevel"
                    id="filter_level"
            >
                @foreach ($filter_level_options as $level => $caption)
                    <option value="{{ $level }}">
                        {{ $caption }}
                    </option>
                @endforeach
            </select>
        </div>

        <a href="{{ route("user.create") }}" class="btn btn-primary">
            Tambah
        </a>
    </div>

    @if($users->isNotEmpty())
        <table class="table table-sm table-striped table-hover">
            <thead class="thead-light">
            <tr>
                <th> # </th>
                <th> Nama Asli </th>
                <th> Nama Pengguna </th>
                <th> Level </th>
                <th class="text-center">
                    @lang("app.actions")
                </th>
            </tr>
            </thead>

            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td> {{ $users->firstItem() + $loop->index }} </td>
                    <td> {{ $user->name }} </td>
                    <td> {{ $user->username }} </td>
                    <td> {{ \App\Constants\UserLevel::LEVELS[$user->level] ?? "" }} </td>
                    <td class="text-center">
                        <a class="btn btn-primary btn-sm" href="{{ route("user.edit", $user) }}">
                            Ubah
                        </a>

                            <button
                                    x-data="{}"
                                    x-on:click="
                                    window.confirmDialog()
                                        .then(response => {
                                            if (response.value) {
                                                window.livewire.emit('user:delete', {{ $user->id }})
                                            }
                                        })"
                                    class="btn btn-outline-danger btn-sm">
                                Hapus
                            </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            {{ __("messages.errors.no_data") }}
        </div>
    @endif
</div>
