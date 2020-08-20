<?php

namespace App\Providers;

use App\Constants\UserLevel;
use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    const MANAGE_ANY_USER = "manage-any-user";
    const DELETE_USER = "delete-user";

    const MANAGE_ANY_BARANG = "manage-any-barang";

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define(self::MANAGE_ANY_USER, function (User $user) {
            return $user->level === UserLevel::MANAGER;
        });

        Gate::define(self::DELETE_USER, function (User $user, User $targetUser) {
            return (
                $user->level === UserLevel::MANAGER
                && $user->id !== $targetUser->id
            ) ?
                Response::allow() :
                Response::deny("Anda tidak dapat menghapus akun Anda sendiri.")
                ;
        });

        Gate::define(self::MANAGE_ANY_BARANG, function (User $user) {
            return $user->level === UserLevel::MANAGER;
        });
    }
}
