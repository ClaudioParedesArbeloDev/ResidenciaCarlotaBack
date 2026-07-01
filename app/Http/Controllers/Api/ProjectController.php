<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        return response()->json(Project::with('institutions')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_active'   => true,
        ]);

        return response()->json(['message' => 'Proyecto creado', 'project' => $project], 201);
    }

    public function show(Project $project)
    {
        return response()->json($project->load('institutions'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'sometimes|boolean',
        ]);

        $data = $request->only(['name', 'description', 'is_active']);
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $project->update($data);

        return response()->json(['message' => 'Proyecto actualizado', 'project' => $project]);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Proyecto eliminado']);
    }
}
