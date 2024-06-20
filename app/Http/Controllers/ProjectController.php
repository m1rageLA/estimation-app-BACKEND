<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Estimation;

class ProjectController extends Controller
{
    public function index()
    {
        // Retrieve projects belonging to the authenticated user
        $projects = Auth::user()->projects;
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'client' => 'required|string',
            'description' => 'required|string',
            'preview' => 'required|image',
        ]);

        $project = new Project();
        $project->name = $validatedData['name'];
        $project->client = $validatedData['client'];
        $project->description = $validatedData['description'];

        // Generate unique name for the image
        $previewName = uniqid('preview_', true) . '.' . $request->file('preview')->getClientOriginalExtension();

        // Store the image with the generated unique name on the server
        $request->file('preview')->storeAs('previews', $previewName, 'public');

        // Store only the image name in the database
        $project->preview = $previewName;

        // Associate the project with the authenticated user
        $project->user_id = Auth::id();

        $project->save();

        return response()->json($project, 201);
    }

    public function show(Project $project)
    {
        // Ensure the project belongs to the authenticated user
        if ($project->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($project);
    }

    public function update(Request $request, Project $project)
    {
        // Validate the request
        $request->validate([
            'name' => 'string|max:255',
            'client' => 'string',
            'description' => 'string',
            'preview' => 'image',
        ]);

        // Ensure the project belongs to the authenticated user
        if ($project->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Update the project with validated data
        $project->update($request->all());

        // Handle preview image update if provided
        if ($request->hasFile('preview')) {
            $previewName = uniqid('preview_', true) . '.' . $request->file('preview')->getClientOriginalExtension();
            $request->file('preview')->storeAs('previews', $previewName, 'public');
            $project->preview = $previewName;
            $project->save();
        }

        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        // Ensure the project belongs to the authenticated user
        if ($project->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $project->delete();

        return response()->json(['success' => true, 'message' => 'Project deleted successfully']);
    }

    public function sumByProject(Request $request)
    {
        // Validate the request
        $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
        ]);

        // Ensure the project belongs to the authenticated user
        $project = Auth::user()->projects()->findOrFail($request->project_id);

        // Calculate total estimation amount for the project
        $total = Estimation::where('project_id', $project->id)->sum('amount');

        return response()->json(['total' => $total]);
    }
}
