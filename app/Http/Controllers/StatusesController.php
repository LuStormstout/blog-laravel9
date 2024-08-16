<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StatusesController extends Controller
{
    /**
     * StatusesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. 验证数据
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        // 2. 创建微博
        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);

        // 3. 返回提示信息，并且重定向到对应的页面
        session()->flash('success', '发布成功！');
        return redirect()->back();
    }
}
