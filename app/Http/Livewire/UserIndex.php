<?php

namespace App\Http\Livewire;

use App\Constants\MessageState;
use App\Constants\UserLevel;
use App\Providers\AuthServiceProvider;
use App\Support\SessionHelper;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $filterLevel;

    protected $listeners = [
        "user:delete" => "deleteUser",
    ];

    const ALL_LEVELS = "all-levels";

    public function mount(Request $request)
    {
        $this->filterLevel = $request->query("filter_level", self::ALL_LEVELS);
    }

    public function deleteUser($userId)
    {
        try {
            $user = User::query()->findOrFail($userId);

            $this->authorize(AuthServiceProvider::DELETE_USER, $user);

            $user->delete();

            SessionHelper::flashMessage(
                __("messages.delete.success"),
                MessageState::STATE_SUCCESS,
        );
        }
        catch (AuthorizationException $authorizationException) {
            SessionHelper::flashMessage(
                $authorizationException->getMessage(),
                MessageState::STATE_DANGER,
            );
        }
        catch (\Throwable $throwable) {
            SessionHelper::flashMessage(
                __("messages.delete.failure"),
                MessageState::STATE_DANGER,
            );
        }
    }

    public function render()
    {
        return view('livewire.user-index', [
            "filter_level_options" => array_merge(UserLevel::LEVELS, [
                self::ALL_LEVELS => "Semua",
            ]),

            "users" => User::query()
                ->orderBy("name")
                ->when($this->filterLevel !== self::ALL_LEVELS, function (Builder $builder) {
                    $builder->where("level", "=", $this->filterLevel);
                })
                ->paginate()
        ]);
    }
}
