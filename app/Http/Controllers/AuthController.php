<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // Метод регистрации нового пользователя
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'image_url' => 'nullable|string',
            'bio' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'image_url' => $request->image_url,
            'bio' => $request->bio,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $token = $user->createToken('Personal Access Token')->accessToken;

        return response()->json(['token' => $token], 201)->header('Authorization', 'Bearer ' . $token);
    }

    // Получение информации о пользователе по его ID
    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            // Дополнительная проверка доступа, если это необходимо
            // Например, проверка, что пользователь имеет право на просмотр этого профиля

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    // Метод аутентификации пользователя
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json([
                'id' => $user->id,
                'token' => $token,
            ], 200)->header('Authorization', 'Bearer ' . $token);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    // Метод обновления профиля пользователя
    public function update(Request $request)
    {
        $user = Auth::user(); // Получаем текущего аутентифицированного пользователя

        // Определение правил валидации для полей
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6',
            'image_url' => 'nullable|string',
            'bio' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Обновляем данные пользователя, только если они были переданы
        if ($request->filled('first_name')) {
            $user->first_name = $request->input('first_name');
        }

        if ($request->filled('last_name')) {
            $user->last_name = $request->input('last_name');
        }

        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        if ($request->filled('image_url')) {
            $user->image_url = $request->input('image_url');
        }

        if ($request->filled('bio')) {
            $user->bio = $request->input('bio');
        }

        if ($request->filled('phone')) {
            $user->phone = $request->input('phone');
        }

        if ($request->filled('address')) {
            $user->address = $request->input('address');
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    // Метод выхода пользователя из системы
    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $user->token()->revoke();
        }

        return response()->json(['message' => 'Successfully logged out']);
    }
}
