<?php
/**
 * 定义程序的起始时间
 */
defined('BEE_BEGIN_TIME') or define('BEE_BEGIN_TIME',microtime(true));
/**
 * 这个常量定义程序是否是调试模式,默认是false;
 */
defined('BEE_DEBUG') or define('BEE_DEBUG',false);
/**
 * 这个常量代表在用Bee::trace()时会调用多少栈信息
 * 默认为0,代表没有后台跟踪信息;
 * 如果数字比0大,则至少会调用数字所代表的栈信息,记住,只有用户的栈程序信息会被调用
 */
defined('BEE_TRACE_LEVEL') or define('BEE_TRACE_LEVEL',0);
/**
 * 定义Bee框架的安装路径
 */
defined('BEE_PATH') or define('BEE_PATH',dirname(__FILE__));

class BeeBase{
    /**
     *
     * @var array 这个配置用来为Bee的自动加载机制提供服务
     */
    public static $classMap = array();
    
    private static $_aliases=array('system'=>BEE_PATH); // alias => path
    private static $_imports=array();					// 别名 => 类名或者目录
    private static $_includePaths;						// 加载路径列表
    private static $_app;
    /**
	 * @var boolean 是否根据PHP的加载路径去自动加载类文件. 默认为true.
	 * 如果你的主机环境不允许改变php加载路径,你可以把这里设置为false.
	 * 或者如果你想为Bee的自动加载器附加额外的自动加载器.
	 */
	public static $enableIncludePath=true;
    public static function createWebApplication($config=null)
	{
		return self::createApplication('BWebApplication',$config);
	}
    public static function createApplication($class,$config=null)
	{
		return new $class($config);
	}
    public static function app()
	{
		return self::$_app;
	}
    public static function setApplication($app)
	{
		if(self::$_app===null || $app===null)
			self::$_app=$app;
		else
			throw new BException(Bee::t('Bee应用程序只能创建一次.'));
	}
    public static function autoload($className)
	{
		// 用include,这样出错的PHP页面就可以看见了
		if(isset(self::$_coreClasses[$className])){
			include(BEE_PATH.self::$_coreClasses[$className]);
        }else if(isset(self::$classMap[$className])){
			include(self::$classMap[$className]);
        }else{
			// 根据include_path来加载类文件
			if(strpos($className,'\\')===false){  //没有命名空间的类文件
				if(self::$enableIncludePath===false){
					foreach(self::$_includePaths as $path){
						$classFile=$path.DIRECTORY_SEPARATOR.$className.'.php';
						if(is_file($classFile)){
							include($classFile);
							break;
						}
					}
				}else{
					include($className.'.php');
                }
			}else{  //PHP 5.3有命名空间的类文件
				$namespace=str_replace('\\','.',ltrim($className,'\\'));
				if(($path=self::getPathOfAlias($namespace))!==false){
					include($path.'.php');
                }else{
					return false;
                }
			}
			return class_exists($className,false) || interface_exists($className,false);
		}
		return true;
	}
    /**
	 * 把一个别名转换成对应的文件路径
     * 请注意，此方法不确定能支持生成的文件的路径
	 * 它只会检查根别名是否有效.
	 * @param string $alias 别名 (例如 system.web.CController)
	 * @return mixed 别名对应的文件路径,如果别名是无效的,则返回false
	 */
	public static function getPathOfAlias($alias)
	{
		if(isset(self::$_aliases[$alias])){
			return self::$_aliases[$alias];
        }elseif(($pos=strpos($alias,'.'))!==false){
			$rootAlias=substr($alias,0,$pos);
			if(isset(self::$_aliases[$rootAlias])){
				return self::$_aliases[$alias]=rtrim(self::$_aliases[$rootAlias].DIRECTORY_SEPARATOR.str_replace('.',DIRECTORY_SEPARATOR,substr($alias,$pos+1)),'*'.DIRECTORY_SEPARATOR);
            }else if(self::$_app instanceof CWebApplication){
				if(self::$_app->findModule($rootAlias)!==null)
					return self::getPathOfAlias($alias);
			}
		}
		return false;
	}
    /**
     * 新建组件
     * @param array $config
     * @return type 
     */
    public static function createComponent($config)
	{
		if(is_string($config)){
			$type=$config;
			$config=array();
		}else if(isset($config['class'])){
			$type=$config['class'];
			unset($config['class']);
		}else{
			throw new BException(Yii::t('配置对象必须是一个包含 "class" 元素的数组.'));
        }
		if(!class_exists($type,false))
			$type=Bee::import($type,true);
		if(($n=func_num_args())>1){
			$args=func_get_args();
			if($n===2){
				$object=new $type($args[1]);
            }else if($n===3){
				$object=new $type($args[1],$args[2]);
            }else if($n===4){
				$object=new $type($args[1],$args[2],$args[3]);
            }else{
				unset($args[0]);
				$class=new ReflectionClass($type);
				// Note: ReflectionClass::newInstanceArgs() is available for PHP 5.1.3+
				// $object=$class->newInstanceArgs($args);
				$object=call_user_func_array(array($class,'newInstance'),$args);
			}
		}else{
			$object=new $type;
        }
		foreach($config as $key=>$value)
			$object->$key=$value;
		return $object;
	}
    /**
	 * 导入一个类或者一个目录
	 *
	 * 导入一个类就像 including 相应的 class file.
	 * 主要的区别是: importing a class 更加轻量级,因为它只会在类第一次被引用的时候才includes the class file
	 *
	 * Importing 一个目录相当于添加一个目录到PHP的Include路径
	 * 如果多个目录被导入, 那些目录将按照文件的搜索优先级进行先后导入(i.e., they are added to the front of the PHP include path).
	 *
	 * 路径别名是用来导入一个类或者目录用的.比如,
	 * <ul>
	 *   <li><code>application.components.GoogleMap</code>: import the <code>GoogleMap</code> class.</li>
	 *   <li><code>application.components.*</code>: import the <code>components</code> directory.</li>
	 * </ul>
	 *
	 * 同样的路径别名能被多次导入,但只有第一次是有效的.
	 * 导入一个目录,不会导入它下面的子目录.
	 *
	 * Starting from version 1.1.5, this method can also be used to import a class in namespace format
	 * (available for PHP 5.3 or above only). It is similar to importing a class in path alias format,
	 * except that the dot separator is replaced by the backslash separator. For example, importing
	 * <code>application\components\GoogleMap</code> is similar to importing <code>application.components.GoogleMap</code>.
	 * The difference is that the former class is using qualified name, while the latter unqualified.
	 *
	 * Note, importing a class in namespace format requires that the namespace is corresponding to
	 * a valid path alias if we replace the backslash characters with dot characters.
	 * For example, the namespace <code>application\components</code> must correspond to a valid
	 * path alias <code>application.components</code>.
	 *
	 * @param string $alias 要被导入的路径别名
	 * @param boolean $forceInclude 是否立即加载这个类文件. 如果是false, 这个类文件只会被用到的时候才加载.
	 * 这个参数只有在路径别名指定到一个类时才会被用到
	 * @return string 路径别名指到的类文件名称
	 * @throws BException 如果别名非法
	 */
	public static function import($alias,$forceInclude=false)
	{
		if(isset(self::$_imports[$alias]))  // 以前导入过
			return self::$_imports[$alias];

		if(class_exists($alias,false) || interface_exists($alias,false))
			return self::$_imports[$alias]=$alias;

		if(($pos=strrpos($alias,'\\'))!==false){ // a class name in PHP 5.3 namespace format
			$namespace=str_replace('\\','.',ltrim(substr($alias,0,$pos),'\\'));
			if(($path=self::getPathOfAlias($namespace))!==false){
				$classFile=$path.DIRECTORY_SEPARATOR.substr($alias,$pos+1).'.php';
				if($forceInclude){
					if(is_file($classFile)){
						require($classFile);
                    }else{
						throw new BException(Bee::t('别名 "{alias}" 是无效的.请确认它指向的是一个有效的PHP文件.',array('{alias}'=>$alias)));
                    }
					self::$_imports[$alias]=$alias;
				}else{
					self::$classMap[$alias]=$classFile;
                }
				return $alias;
			}else{
				throw new BException(Bee::t('别名 "{alias}" 是无效的. 请确认它指向的是一个有效的目录.',
					array('{alias}'=>$namespace)));
            }
		}

		if(($pos=strrpos($alias,'.'))===false){  // a simple class name
			if($forceInclude && self::autoload($alias)){
				self::$_imports[$alias]=$alias;
            }
			return $alias;
		}

		$className=(string)substr($alias,$pos+1);
		$isClass=$className!=='*';

		if($isClass && (class_exists($className,false) || interface_exists($className,false))){
			return self::$_imports[$alias]=$className;
        }

		if(($path=self::getPathOfAlias($alias))!==false){
			if($isClass){
				if($forceInclude){
					if(is_file($path.'.php')){
						require($path.'.php');
                    }else{
						throw new BException(Bee::t('Alias "{alias}" is invalid. Make sure it points to an existing PHP file.',array('{alias}'=>$alias)));
                    }
					self::$_imports[$alias]=$className;
				}else{
					self::$classMap[$className]=$path.'.php';
                }
				return $className;
			}else{  // a directory
				if(self::$_includePaths===null){
					self::$_includePaths=array_unique(explode(PATH_SEPARATOR,get_include_path()));
					if(($pos=array_search('.',self::$_includePaths,true))!==false)
						unset(self::$_includePaths[$pos]);
				}

				array_unshift(self::$_includePaths,$path);

				if(self::$enableIncludePath && set_include_path('.'.PATH_SEPARATOR.implode(PATH_SEPARATOR,self::$_includePaths))===false){
					self::$enableIncludePath=false;
                }

				return self::$_imports[$alias]=$path;
			}
		}else{
			throw new BException(Bee::t('Alias "{alias}" is invalid. Make sure it points to an existing directory or file.',
				array('{alias}'=>$alias)));
        }
	}
    /**
     * 转换一段文字
     * @param String $message 文字模板,可以包含{XXX}这样的变量
     * @param array or String $params 模板里变量对应的值
     * @return String 转换后的文字 
     */
    public static function t($message,$params=array())
	{
		if($params===array())
			return $message;
		if(!is_array($params))
			$params=array($params);
		if(isset($params[0])) // number choice
		{
			if(!isset($params['{n}']))
				$params['{n}']=$params[0];
			unset($params[0]);
		}
		return $params!==array() ? strtr($message,$params) : $message;
	}
    private static $_coreClasses=array(
		'BApplication' => '/base/BApplication.php',
        'BException' => '/base/BException.php',
        'BHttpException' => '/base/BHttpException.php',
        'BWebApplication' => '/web/BWebApplication.php',
		'BUrlManager' => '/web/BUrlManager.php',
        'BHttpRequest' => '/web/BHttpRequest.php',
        'BController' => '/web/BController.php',
        'BBaseController' => '/web/BBaseController.php',
        'BAction' => '/web/actions/BAction.php',
        'BInlineAction' => '/web/actions/BInlineAction.php',
        'BValidator' => '/web/BValidator.php',
	);
}
spl_autoload_register(array('BeeBase','autoload'));
require(BEE_PATH.'/base/interfaces.php');