<?php

namespace App\Http\Controllers;

use App\Client;
use App\Estimation;
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
                $request->avatar->storeAs('', $avatarName, 'custom_public');

                $validated['avatar'] = $avatarName;
            }

            $client = Client::create($validated);

            return response()->json(['message' => 'Client created successfully', 'client' => $client], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create client'], 500);
        }
    }
    public function destroy(Client $client)
    {
        if ($client->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $client->delete();

        return response()->json(['success' => true, 'message' => 'Estimation deleted successfully']);
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:clients,email,' . $client->id,
        ]);

        if ($client->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $client->update($request->all());

        return response()->json($client);
    }
    // Другие методы (show, update, destroy) следует аналогично обернуть в блок try-catch
}
