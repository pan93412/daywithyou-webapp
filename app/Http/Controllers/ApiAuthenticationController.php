<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

#[Group('身分認證')]
class ApiAuthenticationController extends Controller
{
    /**
     * 登入帳號
     *
     * 傳入電子信箱地址和密碼，如果認證成功則會回傳 Bearer 存取權杖。
     *
     * @unauthenticated
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $userAgent = $request->header('User-Agent');
        $user = auth()->user();
        $token = $user->createToken($userAgent);

        return response()->json([
            /**
             * 用來存入 Authorization 標頭的存取權杖
             */
            'token' => $token->plainTextToken
        ], status: 201);
    }

    /**
     * 登出帳號
     *
     * 撤銷目前執行登出動作的存取權杖。
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    /**
     * 註冊帳號
     *
     * 傳入名稱、電子信箱地址和密碼，如果註冊成功則會回傳 Bearer 存取權杖。
     *
     * @unauthenticated
     */
    public function register(Request $request)
    {
        $input = $request->validate([
            /**
             * 使用者姓名
             */
            'name' => ['required', 'string', 'max:255'],
            /**
             * 電子信箱
             */
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            /**
             * 密碼
             */
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create($input);
        $token = $user->createToken($request->header('User-Agent'));

        return response()->json([
            /**
             * 用來存入 Authorization 標頭的存取權杖
             */
            'token' => $token->plainTextToken
        ], status: 201);
    }
}
