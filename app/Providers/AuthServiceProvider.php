<?php
namespace App\Providers;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use App\Models\UserRole;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();

        Gate::define('view-packages', function ($user) {
            $user_id = $user->id;
            $userAccess = UserRole::where('user_id',$user_id)->value('role_id');
            return $userAccess === 2;
        });
        Gate::define('edit-settings', function ($user) {
            $userAccess = UserRole::where('user_id',$user_id)->value('role_id');
            return $user->isAdmin;
        });
    
        Gate::define('update-post', function ($user, $post) {
            return $user->id === $post->user_id;
        });
    }
}