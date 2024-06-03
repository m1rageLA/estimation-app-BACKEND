<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use App\Estimation;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'country' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('', $avatarName, 'custom_public');
            $validated['avatar'] = '' . $avatarName;
        }

        $client = Client::create($validated);

        return response()->json(['message' => 'Client created successfully.'], 201);
    }


    public function show(Client $client)
    {
        return response()->json($client);
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:clients,email,' . $client->id,
        ]);

        $client->update($request->all());

        return response()->json($client);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['success' => true, 'message' => 'Client deleted successfully']);
    }

    public function sumByProject(Request $request)
    {
        $query = Estimation::query();

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $total = $query->sum('amount');

        return response()->json(['total' => $total]);
    }


}
