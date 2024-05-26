<?php

namespace App\Http\Controllers;

use App\Estimation;
use Illuminate\Http\Request;

class EstimationController extends Controller
{
    public function index()
    {
        $estimations = Estimation::all();
        return response()->json($estimations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:hourly,fixed',
            'amount' => 'required|numeric|min:0',
        ]);

        $estimation = Estimation::create($request->all());

        return response()->json($estimation, 201);
    }

    public function show(Estimation $estimation)
    {
        return response()->json($estimation);
    }

    public function update(Request $request, Estimation $estimation)
    {
        $request->validate([
            'project_id' => 'exists:projects,id',
            'type' => 'in:hourly,fixed',
            'amount' => 'numeric|min:0',
        ]);

        $estimation->update($request->all());

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

        $total = $query->sum('amount');

        return response()->json(['total' => $total]);
    }

}

