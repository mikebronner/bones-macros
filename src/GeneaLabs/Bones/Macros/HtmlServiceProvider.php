<?php namespace GeneaLabs\Bones\Macros;

use Illuminate\Support\ServiceProvider;

class HtmlServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    public function boot()
    {
    	//$this->package('genealabs/bones-macros');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
	    //$this->unregisterHtmlServiceProvider();
		$this->registerHtmlBuilder();
		$this->registerFormBuilder();
	}

	protected function unregisterHtmlServiceProvider()
	{

	}

	/**
	 * Register the HTML builder instance.
	 *
	 * @return void
	 */
	protected function registerHtmlBuilder()
	{
		$app->bindShared('html', function($app)
		{
			return new GeneaLabs\Bones\Macros\HtmlBuilder($app['url']);
		});
	}

	/**
	 * Register the form builder instance.
	 *
	 * @return void
	 */
	protected function registerFormBuilder()
	{
		$this->app->bindShared('form', function() use ($this->app)
		{
			$form = new GeneaLabs\Bones\Macros\FormBuilder($this->app['html'], $this->app['url'], $this->app['session.store']->getToken());

			return $form->setSessionStore($this->app['session.store']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('html', 'form');
	}

}
