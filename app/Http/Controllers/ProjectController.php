<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Estimation;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
        ]);

        $project = Project::create($request->all());

        return response()->json($project, 201);
    }

    public function show(Project $project)
    {
        return response()->json($project);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'string|max:255',
            'client_id' => 'exists:clients,id',
        ]);

        $project->update($request->all());

        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
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
