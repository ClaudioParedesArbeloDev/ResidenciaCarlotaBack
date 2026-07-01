<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Project;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstitutionController extends Controller
{
    public function index(Project $project)
    {
        return response()->json($project->institutions()->with('project')->get());
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone'   => 'nullable|string',
            'email'   => 'nullable|email',
        ]);

        $institution = $project->institutions()->create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'address'   => $request->address,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'is_active' => true,
        ]);

        return response()->json([
            'message'     => 'Institución creada',
            'institution' => $institution,
        ], 201);
    }

    public function show(Project $project, Institution $institution)
    {
        $this->checkBelongsToProject($project, $institution);
        return response()->json($institution->load(['project']));
    }

    public function update(Request $request, Project $project, Institution $institution)
    {
        $this->checkBelongsToProject($project, $institution);

        $request->validate([
            'name'      => 'sometimes|string|max:255',
            'address'   => 'nullable|string',
            'phone'     => 'nullable|string',
            'email'     => 'nullable|email',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->only(['name', 'address', 'phone', 'email', 'is_active']);
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $institution->update($data);

        return response()->json([
            'message'     => 'Institución actualizada',
            'institution' => $institution,
        ]);
    }

    public function destroy(Project $project, Institution $institution)
    {
        $this->checkBelongsToProject($project, $institution);
        $institution->delete();
        return response()->json(['message' => 'Institución eliminada']);
    }

  
    public function employees(Project $project, Institution $institution)
    {
        $this->checkBelongsToProject($project, $institution);

        $employees = $institution->users()
            ->with('specialties')
            ->get()
            ->map(fn($user) => [
                'id'             => $user->id,
                'name'           => $user->name,
                'last_name'      => $user->last_name,
                'email'          => $user->email,
                'role'           => Role::find($user->pivot->role_id),
                'is_active'      => $user->pivot->is_active,
                'specialties'    => $user->specialties,
            ]);

        return response()->json($employees);
    }

    public function addEmployee(Request $request, Project $project, Institution $institution)
    {
        $this->checkBelongsToProject($project, $institution);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $institution->users()->syncWithoutDetaching([
            $request->user_id => ['role_id' => $request->role_id, 'is_active' => true],
        ]);

        return response()->json(['message' => 'Empleado agregado a la institución']);
    }

    public function updateEmployee(Request $request, Project $project, Institution $institution, int $userId)
    {
        $this->checkBelongsToProject($project, $institution);

        $request->validate([
            'role_id'   => 'sometimes|exists:roles,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $institution->users()->updateExistingPivot($userId, $request->only(['role_id', 'is_active']));

        return response()->json(['message' => 'Empleado actualizado']);
    }

    public function removeEmployee(Project $project, Institution $institution, int $userId)
    {
        $this->checkBelongsToProject($project, $institution);
        $institution->users()->detach($userId);
        return response()->json(['message' => 'Empleado removido de la institución']);
    }


    private function checkBelongsToProject(Project $project, Institution $institution): void
    {
        abort_if($institution->project_id !== $project->id, 404, 'Institución no encontrada en este proyecto');
    }
}
