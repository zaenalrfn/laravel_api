<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Import model dan policy
use App\Models\Post;
use App\Policies\PostPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Daftarkan model dengan policy
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // Register policies
        $this->registerPolicies();

        // Contoh tambahan: Mendefinisikan Gate secara manual (opsional)
        Gate::define('update', function ($user, $post) {
            return $user->id === $post->user_id;
        });

        Gate::define('delete', function ($user, $post) {
            return $user->id === $post->user_id && $post->status !== 'archived';
        });
    }
}
