<div class="font-weight-bold">
    <span class="text-uppercase">
        MENU
    </span>
    <hr class="mt-0">

    @can(\App\Providers\AuthServiceProvider::MANAGE_ANY_USER)
        <a class="text-decoration-none d-block {{ Route::is("user.*") ? "text-primary" : "text-dark"  }}"
           href="{{ route("user.index") }}">
            Pengguna
        </a>
    @endcan

    @can(\App\Providers\AuthServiceProvider::MANAGE_ANY_BARANG)
        <a class="text-decoration-none d-block {{ Route::is("barang.*") ? "text-primary" : "text-dark"  }}"
           href="{{ route("barang.index") }}">
            Barang
        </a>
    @endcan
</div>