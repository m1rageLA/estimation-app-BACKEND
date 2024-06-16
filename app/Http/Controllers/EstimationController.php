<?php

namespace App\Http\Controllers;

use App\Estimation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EstimationController extends Controller
{
    public function index()
    {
        $estimations = Estimation::all();
        return response()->json($estimations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'project_id' => 'required|exists:projects,id', // Убедитесь, что project_id обязательный и существует
            'date' => 'sometimes|required|date'
        ]);

        // Handle date conversion if provided
        if (isset($validated['date'])) {
            $validated['date'] = Carbon::parse($validated['date'])->toDateString();
        } else {
            $validated['date'] = now()->toDateString();
        }

        $estimation = Estimation::create($validated);

        return response()->json($estimation, 201);
    }



    public function show(Estimation $estimation)
    {
        return response()->json($estimation);
    }

    public function update(Request $request, Estimation $estimation)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string|max:255',
                'type' => 'sometimes|required|string|max:255',
                'cost' => 'sometimes|required|numeric|min:0',
                'project_id' => 'sometimes|required|exists:projects,id', // Убедитесь, что project_id требуется и существует
                'date' => 'sometimes|required|date'
            ]);

            // Handle date conversion if provided
            if ($request->has('date')) {
                $validated['date'] = Carbon::parse($validated['date'])->toDateString();
            }

            // Update the specified fields if present in $validated
            $estimation->fill($validated);
            $estimation->save();

            return response()->json(['message' => 'Estimation updated successfully']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }







    public function destroy(Estimation $estimation)
    {
        $estimation->delete();
        return response()->json(null, 204);
    }

    public function sumByProject(Request $request)
    {
        $query = Estimation::query();

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $total = $query->sum('cost');

        return response()->json(['total' => $total]);
    }
}
