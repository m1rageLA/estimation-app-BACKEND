<?php

namespace App\Http\Controllers;

use App\Estimation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EstimationController extends Controller
{
    public function index()
    {
        $estimations = Estimation::where('user_id', auth()->id())->get();

        return response()->json($estimations);

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'project_id' => 'required|exists:projects,id', // Ensure project_id is required and exists
            'date' => 'sometimes|required|date'
        ]);

        // Handle date conversion if provided
        if (isset($validated['date'])) {
            $validated['date'] = Carbon::parse($validated['date'])->toDateString();
        } else {
            $validated['date'] = now()->toDateString();
        }

        // Add user_id of the authenticated user to ensure association
        $validated['user_id'] = Auth::id();

        $estimation = Estimation::create($validated);

        return response()->json(['message' => 'Estimation created successfully', 'estimation' => $estimation], 201);
    }

    public function show(Estimation $estimation)
    {
        // Ensure the estimation belongs to the authenticated user
        if ($estimation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($estimation);
    }

    public function update(Request $request, Estimation $estimation)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'cost' => 'sometimes|required|numeric|min:0',
            'project_id' => 'sometimes|required|exists:projects,id', // Ensure project_id is required and exists
            'date' => 'sometimes|required|date'
        ]);

        // Ensure the estimation belongs to the authenticated user
        if ($estimation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Handle date conversion if provided
        if ($request->has('date')) {
            $validated['date'] = Carbon::parse($validated['date'])->toDateString();
        }

        // Update the estimation with validated data
        $estimation->fill($validated);
        $estimation->save();

        return response()->json(['message' => 'Estimation updated successfully', 'estimation' => $estimation]);
    }

    public function destroy(Estimation $estimation)
    {
        // Ensure the estimation belongs to the authenticated user
        if ($estimation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $estimation->delete();

        return response()->json(['success' => true, 'message' => 'Estimation deleted successfully']);
    }

    public function sumByProject(Request $request)
    {
        // Validate the request
        $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
        ]);

        // Ensure the project belongs to the authenticated user
        $project = Auth::user()->projects()->findOrFail($request->project_id);

        // Calculate total estimation cost for the project
        $total = Estimation::where('project_id', $project->id)->sum('cost');

        return response()->json(['total' => $total]);
    }
}
