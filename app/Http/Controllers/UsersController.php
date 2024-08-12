<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    /**
     * 显示用户列表
     *
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        return view('users.create');
    }

    /**
     * 显示用户个人信息
     *
     * @param User $user
     * @return Factory|View|Application
     */
    public function show(User $user): Factory|View|Application
    {
        return view('users.show', compact('user'));
    }

    /**
     * 创建用户
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        session()->flash('success', '注册成功！');
        return redirect()->route('users.show', [$user]);
    }

}
