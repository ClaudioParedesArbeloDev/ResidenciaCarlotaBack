<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $institution = app('current_institution');

        $employees = $institution->users()
            ->with('specialties')
            ->get()
            ->map(fn($user) => [
                'id'             => $user->id,
                'name'           => $user->name,
                'last_name'      => $user->last_name,
                'dni'            => $user->dni,
                'email'          => $user->email,
                'phone'          => $user->phone,
                'employee_code'  => $user->employee_code,
                'license_number' => $user->license_number,
                'hire_date'      => $user->hire_date,
                'avatar'         => $user->avatar,
                'is_active'      => $user->pivot->is_active,
                'role'           => Role::find($user->pivot->role_id),
                'specialties'    => $user->specialties,
            ]);

        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string',
            'last_name'      => 'nullable|string',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6',
            'role_id'        => 'required|exists:roles,id',
            'dni'            => 'nullable|string|unique:users,dni',
            'phone'          => 'nullable|string',
            'address'        => 'nullable|string',
            'employee_code'  => 'nullable|string|unique:users,employee_code',
            'license_number' => 'nullable|string',
            'birth_date'     => 'nullable|date',
            'hire_date'      => 'nullable|date',
            'specialties'    => 'nullable|array',
            'specialties.*'  => 'exists:specialties,id',
        ]);

        $user = User::create([
            'name'           => $request->name,
            'last_name'      => $request->last_name,
            'dni'            => $request->dni,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'employee_code'  => $request->employee_code,
            'license_number' => $request->license_number,
            'birth_date'     => $request->birth_date,
            'hire_date'      => $request->hire_date,
            'password'       => Hash::make($request->password),
            'is_active'      => true,
        ]);

        app('current_institution')->users()->attach($user->id, [
            'role_id'   => $request->role_id,
            'is_active' => true,
        ]);

        if ($request->filled('specialties')) {
            $user->specialties()->sync($request->specialties);
        }

        return response()->json([
            'message' => 'Empleado creado',
            'user'    => $user->load('specialties'),
        ], 201);
    }

    public function show(User $employee)
    {
        $institution = app('current_institution');

        abort_unless(
            $employee->belongsToInstitution($institution->id),
            404,
            'Empleado no encontrado en esta institución'
        );

        return response()->json($employee->load('specialties'));
    }

    public function update(Request $request, User $employee)
    {
        $institution = app('current_institution');

        abort_unless(
            $employee->belongsToInstitution($institution->id),
            404,
            'Empleado no encontrado en esta institución'
        );

        $employee->update($request->except(['role_id', 'specialties', 'password']));

        if ($request->filled('password')) {
            $employee->update(['password' => Hash::make($request->password)]);
        }

        if ($request->has('role_id')) {
            $institution->users()->updateExistingPivot($employee->id, [
                'role_id' => $request->role_id,
            ]);
        }

        if ($request->has('specialties')) {
            $employee->specialties()->sync($request->specialties);
        }

        return response()->json([
            'message' => 'Empleado actualizado',
            'user'    => $employee->load('specialties'),
        ]);
    }

    public function destroy(User $employee)
    {
        $institution = app('current_institution');

        abort_unless(
            $employee->belongsToInstitution($institution->id),
            404,
            'Empleado no encontrado en esta institución'
        );

        
        $institution->users()->detach($employee->id);

        return response()->json(['message' => 'Empleado removido de la institución']);
    }
}