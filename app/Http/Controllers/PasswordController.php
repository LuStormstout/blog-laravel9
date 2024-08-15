<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PasswordController extends Controller
{
    /**
     * 显示密码重设页面, 填写 Email 的表单
     *
     * @return Factory|View|Application
     */
    public function showLinkRequestForm(): Factory|View|Application
    {
        return view('auth.passwords.email');
    }

    /**
     * 发送密码重设邮件, 处理表单提交，成功的话就发送邮件，附带 Token 的链接
     *
     * @param Request $request
     * @return mixed
     */
    public function sendResetLinkEmail(Request $request): mixed
    {
        // 1. 验证邮箱
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        // 2. 获取对应的用户
        $user = User::where('email', $email)->first();

        // 3. 如果不存在
        if (is_null($user)) {
            session()->flash('danger', '邮箱未注册');
            return redirect()->back()->withInput();
        }

        // 4. 生成 token 在视图中拼接链接 emails.reset_link
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        // 5. 存入数据库, 使用 updateOrInsert 方法来保持 Email 唯一
        //    updateOrInsert 方法会自动判断，如果存在就更新，不存在就插入
        DB::table('password_resets')->updateOrInsert(['email' => $email], [
            'email' => $email,
            'token' => $token,
            'created_at' => now()
        ]);

        // 6. 将 token 链接发送给用户
        //    使用 Mail::send 方法来发送邮件
        Mail::send('emails.reset_link', compact('token'), function ($message) use ($email) {
            $message->to($email)->subject('忘记密码');
        });

        session()->flash('success', '重置邮件发送成功，请查收');
        return redirect()->back();
    }
}
