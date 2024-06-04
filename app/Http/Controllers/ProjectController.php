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

        // Генерируем уникальное имя для изображения
        $previewName = uniqid('preview_', true) . '.' . $request->file('preview')->extension();

        // Сохраняем изображение на сервере с указанием нужного диска и уникального имени
        $request->file('preview')->storeAs('', $previewName, 'custom_public');

        // Сохраняем только имя изображения в базе данных
        $project->preview = $previewName;

        $project->save();

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

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['success' => true, 'message' => 'Project deleted successfully']);
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
