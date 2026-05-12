<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Services\SupabaseSync;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,agent'],
        ];
        
        // For agents, employee_id is optional because the admin will auto-generate it on approval.
        if (isset($data['role']) && $data['role'] === 'agent') {
            $rules['employee_id'] = ['nullable', 'string', 'unique:users', 'max:50', 'regex:/^AGT\-[A-Z0-9]{4}$/'];
        }

        $messages = [
            'employee_id.regex' => 'Employee ID must match the format AGT-XXXX when provided.',
        ];

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        // Prepare user data
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'] ?? 'user',
            'status' => 'active',
        ];
        
        // For agents, set role to 'pending_agent' and save employee_id
        if (isset($data['role']) && $data['role'] === 'agent') {
            $userData['role'] = 'pending_agent';
            $userData['employee_id'] = $data['employee_id'];
        }
        
        // Create user in MySQL
        $user = User::create($userData);
        
        // Save to Supabase (after user is created)
        $supabase = new SupabaseSync();
        $supabase->saveUser($user);
        
        return $user;
    }

    /**
     * OVERRIDE: Handle post-registration redirect with success message
     */
    protected function registered(Request $request, $user)
    {
        // Log the user out (since we want them to manually login)
        $this->guard()->logout();

        // Redirect to login with success message about email verification
        return redirect()->route('login')->with('success', 'Account created successfully! Please check your email for verification instructions before logging in.');
    }
}