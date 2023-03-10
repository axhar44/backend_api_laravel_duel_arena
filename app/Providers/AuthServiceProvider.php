<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Passport\Passport;  

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */


 
            public function boot()
            {
                $this->registerPolicies();
            }



    // public function boot()
    // {
    //     $this->registerPolicies();

    //     Passport::routes();
    //     VerifyEmail::toMailUsing(function ($notifiable, $url) {
    //         $spaUrl = "http://localhost:8080?email_verify_url=".$url;

    //         return (new MailMessage)
    //             ->subject('Verify Email Address')
    //             ->line('Click the button below to verify your email address.')
    //             ->action('Verify Email Address', $spaUrl);
    //     });

    // }
}
