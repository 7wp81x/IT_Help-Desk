<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::id();
        
        // Get FAQ data
        $faqs = [
            [
                'category' => 'Getting Started',
                'items' => [
                    [
                        'question' => 'How do I create a new ticket?',
                        'answer' => 'Navigate to "<a href="'.route('user.tickets.create').'" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">New Ticket</a>" in the sidebar or visit the "<a href="'.route('user.tickets.index').'" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">tickets</a>" section. Fill in the required information and submit your ticket.'
                    ],
                    [
                        'question' => 'How do I track my ticket status?',
                        'answer' => 'Go to "<a href="'.route('user.tickets.index').'" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">My Tickets</a>" to see all your tickets with their current status. Click on any ticket to view detailed information.'
                    ],
                ]
            ],
            [
                'category' => 'Account & Profile',
                'items' => [
                    [
                        'question' => 'How do I update my profile information?',
                        'answer' => 'Click on your profile icon in the bottom left, then select "<a href="'.route('user.profile').'" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">Edit Profile</a>" to update your information.'
                    ],
                    [
                        'question' => 'How do I change my password?',
                        'answer' => 'Go to your profile, select "<a href="'.route('user.profile.password').'" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">Change Password</a>", and follow the instructions to set a new password.'
                    ],
                ]
            ],
            [
                'category' => 'Tickets & Support',
                'items' => [
                    [
                        'question' => 'What should I include in my ticket description?',
                        'answer' => 'Provide a clear description of your issue, steps to reproduce it, and any error messages you\'re seeing.'
                    ],
                    [
                        'question' => 'Can I add attachments to my ticket?',
                        'answer' => 'Yes, you can attach files when creating a ticket. Supported formats include images, documents, and logs.'
                    ],
                    [
                        'question' => 'How long does it take to get a response?',
                        'answer' => 'Our support team aims to respond within 24 hours. Priority issues may be addressed sooner.'
                    ],
                ]
            ],
            [
                'category' => 'Ratings & Feedback',
                'items' => [
                    [
                        'question' => 'How do I rate an agent?',
                        'answer' => 'After your ticket is resolved, you\'ll have the option to rate the agent who helped you. You can edit your rating within 7 days.'
                    ],
                ]
            ],
        ];

        $openTickets = Ticket::where('user_id', $userId)
            ->where('status', 'open')
            ->count();

        return view('user.support.index', compact('faqs', 'openTickets'));
    }
}
