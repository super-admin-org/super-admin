<?php

namespace SuperAdmin\Admin;

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use SuperAdmin\Admin\Auth\TwoFactor\TwoFactor;
use SuperAdmin\Admin\Layout\Content;
use SuperAdmin\Admin\Template\TemplateManager;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        Console\AdminCommand::class,
        Console\MakeCommand::class,
        Console\ControllerCommand::class,
        Console\MenuCommand::class,
        Console\InstallCommand::class,
        Console\PublishCommand::class,
        Console\UninstallCommand::class,
        Console\ImportCommand::class,
        Console\CreateUserCommand::class,
        Console\ResetPasswordCommand::class,
        Console\ExtendCommand::class,
        Console\ExportSeedCommand::class,
        Console\MinifyCommand::class,
        Console\FormCommand::class,
        Console\PermissionCommand::class,
        Console\ActionCommand::class,
        Console\GenerateMenuCommand::class,
        Console\ConfigCommand::class,
        Console\DevLinksCommand::class,
        Console\TemplateCommand::class,
        Console\LintViewsCommand::class,
        Console\TwoFactorCommand::class,
        Console\UpgradeCommand::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.auth' => Middleware\Authenticate::class,
        'admin.throttle' => Middleware\Throttle::class,
        'admin.pjax' => Middleware\Pjax::class,
        'admin.log' => Middleware\LogOperation::class,
        'admin.permission' => Middleware\Permission::class,
        'admin.bootstrap' => Middleware\Bootstrap::class,
        'admin.session' => Middleware\Session::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'admin' => [
            'admin.auth',
            'admin.throttle',
            'admin.pjax',
            'admin.log',
            'admin.bootstrap',
            'admin.permission',
            //            'admin.session',
        ],
    ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTemplateViews();

        $this->ensureHttps();

        if (file_exists($routes = admin_path('routes.php'))) {
            $this->loadRoutesFrom($routes);
        }

        $this->registerPublishing();
        $this->registerMigrations();
        $this->compatibleBlade();
        $this->bladeDirectives();
        $this->flushPageAssetsAfterRequests();
    }

    /**
     * Forget the page's queued scripts once its response is built, so a
     * long-running server (Octane, a test server) starts the next request clean.
     */
    protected function flushPageAssetsAfterRequests(): void
    {
        $this->app['events']->listen(RequestHandled::class, function (): void {
            Admin::flushPageAssets();
        });
    }

    /**
     * Point the `admin::` view namespace at the configured template.
     *
     * The template chosen in the admin panel is applied later, per request,
     * by Admin::bootstrap(), so booting never touches the database.
     */
    protected function loadTemplateViews(): void
    {
        $templates = $this->app->make(TemplateManager::class);

        $templates->apply($templates->configured());
    }

    /**
     * Get the names of the available templates.
     *
     * @return array<int, string>
     */
    public static function availableTemplates(): array
    {
        return app(TemplateManager::class)->names();
    }

    /**
     * Force to set https scheme if https enabled.
     *
     * @return void
     */
    protected function ensureHttps()
    {
        if (config('admin.https') || config('admin.secure')) {
            url()->forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'super-admin-config');
            $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang')], 'super-admin-lang');
            $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'super-admin-migrations');
            // Also tagged "laravel-assets": Laravel apps republish that tag after every `composer update`.
            $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/super-admin')], ['super-admin-assets', 'laravel-assets']);
            $this->publishes([__DIR__.'/../resources/assets/test' => public_path('vendor/super-admin-test')], 'super-admin-test');
        }
    }

    /**
     * Run the package migrations with `php artisan migrate`, without publishing them first.
     *
     * Copies published by older versions keep working: a migration with the same file
     * name in database/migrations is used instead, and none runs twice.
     */
    protected function registerMigrations(): void
    {
        if (config('admin.database.load_migrations', true)) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Remove the default feature of double encoding enable in laravel 5.6 or later.
     *
     * @return void
     */
    protected function compatibleBlade()
    {
        $reflectionClass = new \ReflectionClass('\Illuminate\View\Compilers\BladeCompiler');

        if ($reflectionClass->hasMethod('withoutDoubleEncoding')) {
            Blade::withoutDoubleEncoding();
        }
    }

    /**
     * Extends laravel router.
     */
    protected function macroRouter()
    {
        Router::macro('content', function ($uri, $content, $options = []) {
            return $this->match(['GET', 'HEAD'], $uri, function (Content $layout) use ($content, $options) {
                return $layout
                    ->title(Arr::get($options, 'title', ' '))
                    ->description(Arr::get($options, 'desc', ' '))
                    ->body($content);
            });
        });

        Router::macro('component', function ($uri, $component, $data = [], $options = []) {
            return $this->match(['GET', 'HEAD'], $uri, function (Content $layout) use ($component, $data, $options) {
                return $layout
                    ->title(Arr::get($options, 'title', ' '))
                    ->description(Arr::get($options, 'desc', ' '))
                    ->component($component, $data);
            });
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Admin::class);
        $this->app->alias(Admin::class, 'admin');

        $this->app->singleton(TemplateManager::class, function ($app) {
            $templates = new TemplateManager($app);
            $templates->discover(__DIR__.'/../resources/views');

            return $templates;
        });

        $this->app->singleton(TwoFactor::class);
        $this->app->singleton(Upgrade::class);

        $this->loadAdminAuthConfig();

        $this->registerRouteMiddleware();

        $this->commands($this->commands);

        $this->macroRouter();
    }

    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function loadAdminAuthConfig()
    {
        config(Arr::dot(config('admin.auth', []), 'auth.'));
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }

    /**
     * Register the blade box directive.
     *
     * @return void
     */
    public function bladeDirectives()
    {
        Blade::directive('box', function ($title) {
            return "<?php \$box = new \SuperAdmin\Admin\Widgets\Box({$title}, '";
        });

        Blade::directive('endbox', function ($expression) {
            return "'); echo \$box->render(); ?>";
        });
    }
}
