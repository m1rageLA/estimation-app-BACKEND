<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        // Получение клиентов только текущего пользователя
        $clients = Client::where('user_id', auth()->id())->get();

        return response()->json($clients);
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:clients,email',
                'country' => 'required|string|max:255',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $validated = $request->only('name', 'email', 'country');
            $validated['user_id'] = Auth::id();

            if ($request->hasFile('avatar')) {
                $avatarName = time() . '.' . $request->avatar->getClientOriginalExtension();
                $request->avatar->storeAs('avatars', $avatarName, 'public');
                $validated['avatar'] = $avatarName;
            }

            $client = Client::create($validated);

            return response()->json(['message' => 'Client created successfully', 'client' => $client], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create client'], 500);
        }
    }

    // Другие методы (show, update, destroy) следует аналогично обернуть в блок try-catch
}
