<?php

namespace App\Http\Livewire;

use App\Barang;
use App\Constants\AlasanTransaksi;
use App\Constants\MessageState;
use App\Exceptions\QuantityExceedsCurrentStockException;
use App\ItemPenjualan;
use App\Penjualan;
use App\Repositories\Inventory;
use App\Support\SessionHelper;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Throwable;

class PenjualanCreate extends Component
{
    public $waktu_penjualan;
    public $items;
    public $selectedBarangId;

    protected $listeners = [
        "item:add" => "addItem",
        "item:remove" => "removeItem",
    ];

    public function mount()
    {
        $this->waktu_penjualan = now()->format("Y-m-d\TH:i");
        $this->items = [];
    }

    public function submit(Inventory $inventory)
    {
        $data = $this->getValidatedData();

        DB::beginTransaction();

        /** @var Penjualan $penjualan */
        $penjualan = Penjualan::query()->create([
            "user_id" => auth()->id(),
            "waktu_penjualan" => $this->waktu_penjualan,
        ]);

        foreach ($data["items"] as $barangId => $item) {
            /** @var ItemPenjualan $itemPenjualan */
            $itemPenjualan = $penjualan->items()->create([
                "barang_id" => $barangId,
                "jumlah" => $item["jumlah"],
                "harga_satuan" => $item["harga_jual"],
            ]);

            $itemPenjualanSubtotal = ItemPenjualan::query()
                ->where("id", $itemPenjualan->id)
                ->selectRaw("jumlah * harga_satuan AS subtotal")
                ->value("subtotal");

            $itemPenjualan->transaksi_keuangan()->create([
                "tanggal_transaksi" => today(),
                "jumlah" => $itemPenjualanSubtotal,
                "alasan" => AlasanTransaksi::PENJUALAN,
            ]);

            /** @var Barang $barang */
            $barang = Barang::query()->findOrFail($barangId);

            $inventory->removeStockByBarang(
                $barang,
                $item["jumlah"],
                [
                    "entitas_terkait" => $itemPenjualan,
                    "alasan" => AlasanTransaksi::PENJUALAN,
                ],
            );
        }

        DB::commit();

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        $this->redirectRoute("penjualan.index");
    }

    public function getValidatedData(): array
    {
        return $this->validate([
            "waktu_penjualan" => ["required", "date_format:Y-m-d\TH:i"],
            "items" => ["required", "array"],
            "items.*.barang.id" => [
                "required",
                Rule::exists(Barang::class, "id")
                    ->where("disembunyikan", 0)
            ],
            "items.*.harga_jual" => ["required", "numeric", "gte:0"],
            "items.*.jumlah" => [
                "required",
                "numeric",
                function ($attribute, $value, $fail) {
                    try {
                        $barangId = explode(".", $attribute)[1] ?? null;
                        $barang = Barang::query()
                            ->withStock()
                            ->findOrFail($barangId);

                        throw_if($value > $barang->stock, new QuantityExceedsCurrentStockException());
                    } catch (QuantityExceedsCurrentStockException $exception) {
                        $fail($exception->getMessage());
                    } catch (Throwable $throwable) {
                        $fail("Terjadi masalah di server.");
                    }
                },
            ]
        ]);
    }

    public function removeItem($barangId)
    {
        $this->items = array_filter($this->items, function ($key) use ($barangId) {
            return $key !== $barangId;
        }, ARRAY_FILTER_USE_KEY);
    }

    public function addItem($barangId)
    {
        try {
            $barang = Barang::query()
                ->withStock()
                ->findOrFail($barangId);

            if (isset($this->items[$barangId])) {
                throw new Exception("Can't add items that's already added.");
            }

            $this->items[$barangId] = [
                "barang" => $barang->toArray(),
                "harga_jual" => $barang->harga_jual,
                "jumlah" => 1,
            ];
        } catch (Throwable $throwable) {
            SessionHelper::flashMessage(
                __("messages.create.failure"),
                MessageState::STATE_DANGER,
            );
        }
    }

    public function render()
    {
        $this->items = array_map(function ($item) {
            return array_merge($item, [
                "subtotal" => (
                    ($item["harga_jual"] ?: 0) *
                    ($item["jumlah"] ?: 0)
                )
            ]);
        }, $this->items);

        return view('livewire.penjualan-create', [
            "total_harga" => array_reduce($this->items, function ($current, $next) {
                return $current +
                    (
                        ($next["jumlah"] ?: 0) *
                        ($next["harga_jual"] ?: 0)
                    );
            }, 0)
        ]);
    }
}
