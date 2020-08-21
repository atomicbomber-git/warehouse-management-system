<?php

namespace App\Http\Controllers;

use App\Constants\MessageState;
use App\Constants\UserLevel;
use App\Providers\AuthServiceProvider;
use App\Support\SessionHelper;
use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_USER);

        return $this->responseFactory->view("user.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_USER);

        return $this->responseFactory->view("user.create", [
            "level_options" => UserLevel::LEVELS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_USER);

        $data = $request->validate([
            "name" => ["required", "string"],
            "username" => ["required", "alpha_dash", Rule::unique(User::class)],
            "level" => ["required", Rule::in(array_keys(UserLevel::LEVELS))],
            "password" => ["required", "string", "confirmed"],
        ]);

        $data["password"] = Hash::make($data["password"]);

        User::query()->create(
            $data
        );

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("user.index");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_USER);

        return $this->responseFactory->view("user.edit", [
            "user" => $user,
            "level_options" => UserLevel::LEVELS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $this->authorize(AuthServiceProvider::MANAGE_ANY_USER);

        $data = $request->validate([
            "name" => ["required", "string"],
            "username" => ["required", "alpha_dash", Rule::unique(User::class)->ignoreModel($user)],
            "level" => ["required", Rule::in(array_keys(UserLevel::LEVELS))],
            "password" => ["nullable", "string", "confirmed"],
        ]);

        if (isset($data["password"])) {
            $data["password"] = Hash::make($data["password"]);
        }
        else {
            unset($data["password"]);
        }

        $user->update($data);

        SessionHelper::flashMessage(
            __("messages.update.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("user.edit", $user);
    }
}
