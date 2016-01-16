<?php namespace GeneaLabs\Bones\Macros;

use Collective\Html\HtmlServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class BonesMacrosServiceProvider extends HtmlServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHtmlBuilder();
        $this->registerFormBuilder();
        AliasLoader::getInstance()->alias('Form', BonesMacrosFormFacade::class);
        AliasLoader::getInstance()->alias('HTML', BonesMacrosHtmlFacade::class);
    }

    /**
     * Register the HTML builder instance.
     *
     * @return void
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function($app)
        {
            return new BonesMacrosHtmlBuilder($app['url']);
        });
    }

    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function($app)
        {
            return new BonesMacrosFormBuilder($app['html'], $app['url'], $app['session.store']->getToken());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['genealabs-bones-macros'];
    }
}
