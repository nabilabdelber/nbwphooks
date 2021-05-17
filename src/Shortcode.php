<?php 
namespace WpHooks;


Abstract Class SHORTCODE
{	

    protected $shortcode;
	public $request;
	public $wp;
	public $user;

	
	abstract protected function run();

	public function __construct()
	{ 	
		global $wp;
		$this->wp = $wp;
		$this->request = $_REQUEST;

	}

    public static function boot()
	{ 	
		$class = Self::getClassName();
		$action = new $class;
		return $action->run();
		
	}

	public static function listen($public = TRUE)
	{
		$shortcodeName = Self::getShortcodeName();
		$className = Self::getClassName();
		add_shortcode("{$shortcodeName}", [$className, 'boot']);
		
		if($public){
		//	add_action("wp_ajax_nopriv_{$actionName}", [$className, 'boot']);
		}
	}


	// -----------------------------------------------------
	// UTILITY METHODS
	// -----------------------------------------------------
	public static function getClassName()
	{
		return get_called_class();
	}

	public static function getShortcodeName()
	{
		$class = Self::getClassName();
		$reflection = new \ReflectionClass($class);
		$shortcode = $reflection->newInstanceWithoutConstructor();
		if(!isset($shortcode->shortcode)){
			throw new \Exception("Public property \$action not provied");
		}

		return $shortcode->shortcode;
	}
}
