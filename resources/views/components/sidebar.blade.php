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

    @can(\App\Providers\AuthServiceProvider::MANAGE_ANY_PEMASOK)
        <a class="text-decoration-none d-block {{ Route::is("pemasok.*") ? "text-primary" : "text-dark"  }}"
           href="{{ route("pemasok.index") }}">
            Pemasok
        </a>
    @endcan

    @can(\App\Providers\AuthServiceProvider::MANAGE_ANY_STOCK)
        <a class="text-decoration-none d-block {{ Route::is("stock-grouped-by-barang.*", "stock-by-barang.*") ? "text-primary" : "text-dark"  }}"
           href="{{ route("stock-grouped-by-barang.index") }}">
            Stok per Barang
        </a>
    @endcan

    @can(\App\Providers\AuthServiceProvider::MANAGE_ANY_PENJUALAN)
        <a class="text-decoration-none d-block {{ Route::is("penjualan.*") ? "text-primary" : "text-dark"  }}"
           href="{{ route("penjualan.index") }}">
            Rekap Pengeluaran
        </a>
    @endcan

    @can(\App\Providers\AuthServiceProvider::VIEW_LAPORAN_KEUANGAN)
        <a class="text-decoration-none d-block {{ Route::is("laporan-keuangan.*", "saldo-awal.*", "print-laporan-keuangan.*") ? "text-primary" : "text-dark"  }}"
           href="{{ route("laporan-keuangan.index") }}">
            Rekap Keuangan
        </a>
    @endcan
</div>