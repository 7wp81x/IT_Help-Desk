<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use App\Models\Ticket;
use App\Policies\TicketPolicy;
use App\View\Composers\SidebarComposer;
use App\Notifications\Channels\SmsChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register sidebar composer to pass ticket counts and notifications to all sidebars
        View::composer([
            'layouts.admin.sidebar',
            'layouts.agent.sidebar',
            'layouts.user.sidebar',
        ], SidebarComposer::class);

        Gate::policy(Ticket::class, TicketPolicy::class);

        // Register SMS notification channel
        Notification::extend('sms', function ($app) {
            return new SmsChannel();
        });

        // Register Philippine phone validation rule
        Validator::extend('philippine_phone', function ($attribute, $value, $parameters, $validator) {
            if (empty($value)) {
                return true;
            }

            return preg_match('/^(?:\+639|639|09)\d{9}$/', trim($value));
        });

        Validator::replacer('philippine_phone', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The '.$attribute.' must be a valid Philippine mobile number in 09XXXXXXXXX, 639XXXXXXXXX, or +639XXXXXXXXX format.');
        });
    }
}