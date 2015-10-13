<?php

/**
 * Defines the Yii framework installation path.
 */
defined('YII_ROUTER_PATH') or define('YII_ROUTER_PATH',dirname(dirname(__FILE__)));

class CAutoloader {
    
    /**
	 * @var boolean whether to rely on PHP include path to autoload class files. Defaults to true.
	 * You may set this to be false if your hosting environment doesn't allow changing the PHP
	 * include path, or if you want to append additional autoloaders to the default Yii autoloader.
	 * @since 1.1.8
	 */
	public static $enableIncludePath=true;
    
    private static $_includePaths;
    
    /**
	 * Registers a new class autoloader.
	 * The new autoloader will be placed before {@link autoload} and after
	 * any other existing autoloaders.
	 * @param callback $callback a valid PHP callback (function name or array($className,$methodName)).
	 * @param boolean $append whether to append the new autoloader after the default Yii autoloader.
	 * Be careful using this option as it will disable {@link enableIncludePath autoloading via include path}
	 * when set to true. After this the Yii autoloader can not rely on loading classes via simple include anymore
	 * and you have to {@link import} all classes explicitly.
	 */
	public static function register()
	{
        spl_autoload_register(array('CAutoloader','autoload'));
	}

	/**
	 * @var array class map for core Yii classes.
	 * NOTE, DO NOT MODIFY THIS ARRAY MANUALLY. IF YOU CHANGE OR ADD SOME CORE CLASSES,
	 * PLEASE RUN 'build autoload' COMMAND TO UPDATE THIS ARRAY.
	 */
	private static $_coreClasses=array(
		'CException' => '/base/CException.php',
		'CHttpException' => '/web/http/CHttpException.php',
		'CHttpRequest' => '/web/http/CHttpRequest.php',
		'CBaseUrlRule' => '/web/router/CBaseUrlRule.php',
		'CUrlRule' => '/web/router/CUrlRule.php',
		'CUrlManager' => '/web/router/CUrlManager.php',
	);
    
    /**
	 * Class autoload loader.
	 * This method is provided to be invoked within an __autoload() magic method.
	 * @param string $className class name
	 * @param bool $classMapOnly whether to load classes via classmap only
	 * @return boolean whether the class has been loaded successfully
	 * @throws CException When class name does not match class file in debug mode.
	 */
	public static function autoload($className,$classMapOnly=false)
	{
		if(isset(self::$_coreClasses[$className]))
			include(YII_ROUTER_PATH.self::$_coreClasses[$className]);
		elseif($classMapOnly)
			return false;
		else
		{
			// include class file relying on include_path
			if(strpos($className,'\\')===false)  // class without namespace
			{
				if(self::$enableIncludePath===false)
				{
					foreach(self::$_includePaths as $path)
					{
						$classFile=$path.DIRECTORY_SEPARATOR.$className.'.php';
						if(is_file($classFile))
						{
							include($classFile);
							if(basename(realpath($classFile))!==$className.'.php')
								throw new CException('Class name "{class}" does not match class file "{file}".');
							break;
						}
					}
				}
				else
					include($className.'.php');
			}
			
			return class_exists($className,false) || interface_exists($className,false);
		}
		return true;
	}
}