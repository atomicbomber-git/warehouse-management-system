<div>
    <h1>
        Stock per Barang

        /

        {{ $barang->nama }}

        /

        Stock
    </h1>

    <div>
        @if($stocks->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead>
                    <tr>
                        <th> #</th>

                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($stocks as $stock)
                        <tr>
                            <td> {{ $stocks->firstItem() + $loop->index }} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $stocks->links() }}
            </div>

        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __("messages.no_data") }}
            </div>
        @endif
    </div>


</div>
