<?php namespace GeneaLabs\Bones\Macros;

class BonesMacrosServiceProvider extends \Illuminate\Html\HtmlServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
//	protected $defer = false;
//
//    public function boot()
//    {
//    	$this->package('genealabs/bones-macros');
//    }
//
//	/**
//	 * Register the service provider.
//	 *
//	 * @return void
//	 */
//	public function register()
//	{
//	    //$this->unregisterHtmlServiceProvider();
//		$this->registerHtmlBuilder();
//		$this->registerFormBuilder();
//	}
//
//	protected function unregisterHtmlServiceProvider()
//	{
//
//	}

	/**
	 * Register the HTML builder instance.
	 *
	 * @return void
	 */
	protected function registerHtmlBuilder()
	{
		$this->app->bindShared('html', function($app)
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
		$this->app->bindShared('form', function($app)
		{
			return new BonesMacrosFormBuilder($app['html'], $app['url'], $app['session.store']->getToken());
		});
	}

//	/**
//	 * Get the services provided by the provider.
//	 *
//	 * @return array
//	 */
//	public function provides()
//	{
//		return array('html', 'form');
//	}

}
