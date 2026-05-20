<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index()
    {
        $agents = User::where('role', 'agent')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => ['nullable', 'string', 'max:20', 'philippine_phone'],
        ]);

        $departmentId = $request->input('department_id');
        if (empty($departmentId) && $request->filled('department')) {
            $department = Department::where('name', $request->input('department'))->first();
            $departmentId = $department ? $department->id : null;
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'agent',
            'status' => 'active',
            'department_id' => $departmentId,
        ]);

        return redirect()->route('admin.agents.index')->with('success', 'Agent created successfully!');
    }

    public function edit(int $id)
    {
        $agent = User::findOrFail($id);
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, int $id)
    {
        $agent = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => ['nullable', 'string', 'max:20', 'philippine_phone'],
        ]);

        $agent->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $agent->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.agents.index')->with('success', 'Agent updated successfully!');
    }

    public function destroy(int $id)
    {
        $agent = User::findOrFail($id);
        $agent->delete();
        
        return redirect()->back()->with('success', 'Agent deleted successfully!');
    }
}