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
            'project_id' => 'required|exists:projects,id',
            'date' => 'sometimes|required|date'
        ]);

        // Если дата предоставлена, сконвертируйте ее в формат YYYY-MM-DD
        if (isset($validated['date'])) {
            $validated['date'] = Carbon::parse($validated['date'])->toDateString();
        } else {
            // Если дата не предоставлена, установите значение по умолчанию на текущую дату
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
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'cost' => 'sometimes|required|numeric|min:0',
            'project_id' => 'sometimes|exists:projects,id',
            'date' => 'sometimes|required|date'
        ]);

        $estimation->update($validated);

        return response()->json($estimation);
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
