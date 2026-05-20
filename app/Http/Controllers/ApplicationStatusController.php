<?php

namespace App\Http\Controllers;

use App\Models\AgentApplication;
use Illuminate\Http\Request;

class ApplicationStatusController extends Controller
{
    /**
     * Show the application status check form
     */
    public function showForm()
    {
        return view('applications.status-check');
    }

    /**
     * Check application status by email
     */
    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $application = AgentApplication::where('email', $request->email)->first();

        if (!$application) {
            return back()->with('error', 'No application found with this email address.');
        }

        return view('applications.status-result', compact('application'));
    }
}
