<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionsController extends Controller
{
    /**
     * 显示登录页面
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('sessions.create');
    }

    /**
     * 登录
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $credential = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        // Laravel 中 Auth 的 attempt 方法可以让我们很方便的完成用户的身份认证操作
        if (Auth::attempt($credential)){
            // 登录成功
            session()->flash('success', '欢迎回来！');
            return redirect()->route('users.show', [Auth::user()]);
        }else{
            // 登录失败
            session()->flash('danger', '抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }
}
