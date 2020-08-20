@extends("layouts.app")

@section("content")
    <livewire:stock-by-barang-index
        barang_id="{{ $barang_id }}"
    />
@endsection