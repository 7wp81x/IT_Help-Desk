<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
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

    public function showRegistrationForm()
    {
        return view('welcome')->with('showRegisterModal', true);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator, 'register')
                ->with('showRegisterModal', true);
        }

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }

    /**
     * Handle actions after registration including sending the verification code.
     */
    protected function registered(Request $request, $user)
    {
        if (!$user->verification_code || !$user->verification_code_expires_at || $user->verification_code_expires_at->isPast()) {
            $user->sendEmailVerificationNotification();
        }

        // Log the user out (since we want them to manually login)
        $this->guard()->logout();

        // Redirect to login with success message about email verification
        return redirect()->route('login')->with('success', 'Account created successfully! Please check your email for the verification code before logging in.');
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
            'phone' => ['required', 'string', 'max:20', 'philippine_phone', Rule::unique('users', 'phone')],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ];

        return Validator::make($data, $rules);
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
            'role' => 'user',
            'status' => 'active',
        ];
        
        // Create user in MySQL
        $user = User::create($userData);
        
        // Save to Supabase (after user is created)
        $supabase = new SupabaseSync();
        $supabase->saveUser($user);
        
        return $user;
    }
}
