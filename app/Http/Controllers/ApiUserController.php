<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

#[Group('使用者資訊')]
class ApiUserController extends Controller
{
    /**
     * 取得目前使用者的資訊
     */
    public function index()
    {
        $user = auth()->user();

        if (! $user) {
            throw new AuthenticationException;
        }

        return UserResource::make($user);
    }

    /**
     * 更新使用者的個人資訊
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
    }

    /**
     * 更新密碼
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            /**
             * 目前使用者的密碼
             */
            'current_password' => ['required', 'current_password'],
            /**
             * 新的密碼
             */
            'password' => ['required', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * 刪除帳號
     */
    public function destroy(Request $request)
    {
        $request->validate([
            /**
             * 目前使用者的密碼
             */
            'password' => ['required', 'current_password'],
        ]);

        $request->user()->delete();

        return response()->json([
            'message' => 'Account deleted successfully.',
        ]);
    }
}
