<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../routes/web.php'          => base_path('routes/web.php'),
            __DIR__ . '/../../routes/api.php'          => base_path('routes/api.php'),

            __DIR__ . '/../../app/Http/Controllers/Auth/LoginController.php' => app_path('Http/Controllers/Auth/LoginController.php'),

            __DIR__ . '/../../app/Http/Middleware/HandleInertiaRequests.php' => app_path('Http/Middleware/HandleInertiaRequests.php'),
            __DIR__ . '/../../app/Http/Kernel.php'                           => app_path('Http/Kernel.php'),

            __DIR__ . '/../../resources/js/app.js.php' => resource_path('js/app.js'),
            __DIR__ . '/../../resources/sass/app.scss' => resource_path('sass/app.scss'),

            __DIR__ . '/../../resources/views/auth/nav/dashboard.blade.php' => resource_path('views/auth/nav/dashboard.blade.php'),
            __DIR__ . '/../../resources/views/layouts/auth.blade.php'       => resource_path('views/layouts/auth.blade.php'),
            __DIR__ . '/../../resources/views/layouts/dashboard.blade.php'  => resource_path('views/layouts/dashboard.blade.php'),
            __DIR__ . '/../../resources/views/layouts/public.blade.php'     => resource_path('views/layouts/public.blade.php'),
            __DIR__ . '/../../resources/views/error.blade.php'              => resource_path('views/error.blade.php'),

            __DIR__ . '/../../resources/js/components/Dashboard/Nav.vue'    => resource_path('js/components/Dashboard/Nav.vue'),
            __DIR__ . '/../../resources/js/Layouts/CardLayoutFluid.vue'     => resource_path('js/Layouts/CardLayoutFluid.vue'),
            __DIR__ . '/../../resources/js/Pages/Dashboard/Index.vue'       => resource_path('js/Pages/Dashboard/Index.vue'),
            __DIR__ . '/../../resources/js/Pages/Public/Index.vue'          => resource_path('js/Pages/Public/Index.vue'),
        ]);

        try
        {
            unlink(resource_path('views/home.blade.php'));
            unlink(resource_path('views/welcome.blade.php'));
            unlink(resource_path('views/layouts/app.blade.php'));
            unlink(resource_path('views/auth/nav/public.blade.php'));
            unlink(resource_path('js/components/ExampleComponent.vue'));
        }
        catch (\Exception | \Error $e)
        {

        }
    }
}
