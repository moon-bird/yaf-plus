<?php
/**
 * @name Bootstrap
 * @author cmcm-20160113nw\administrator
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{

    public function _initConfig() {

		//把配置保存起来
		$appConfig = Yaf_Application::app()->getConfig();

		//设置字符集
		header('Content-Type: text/html; charset=' . $appConfig->get('application.charset'));

		//设置时区
		date_default_timezone_set($appConfig->get('application.timezone'));

		//版本号
		header("X-Develop-Version:".$appConfig->get('application.version'));

		//环境选择
		$testEnvIps = $appConfig->get('application.server.test');
		$productEnvIps = $appConfig->get('application.server.product');

		$request = Yaf_Application::app()->getDispatcher()->getRequest();

		if ($request->isCli()){

			$uriParams = [];

			$uri = $request->getRequestUri();
			$uriArr = explode('&',$uri);
			foreach($uriArr as $key=>$item){

				if (0 == $key){

					$request->setRequestUri($item);
				} else {

					$itemArr = explode('=', $item);

					if (isset($itemArr[0]) == false || isset($itemArr[1]) == false){

						continue;
					}
					$request->setParam($itemArr[0], $itemArr[1]);
				}
			}

			$env = $request->getParam('env', 'common');
			if (in_array($env, ['common', 'test', 'product'])){

				$curEnv = $env;
			} else {

				$curEnv = 'common';
			}
		} else {

			$curEnv = null;
			$serverIp = $_SERVER['SERVER_ADDR'].'#';
			empty($curEnv) && strpos($productEnvIps,$serverIp) !== false && $curEnv='product';
			empty($curEnv) && strpos($testEnvIps,$serverIp) !== false && $curEnv='test';
			empty($curEnv) && $curEnv = 'common';
		}

		//加载business配置
		$businessConf = new Yaf_Config_Ini(APPLICATION_PATH . "/conf/business.ini", $curEnv);

		//注册配置
		Yaf_Registry::set('config', $businessConf);
	}

	/**
	 * 集成Composer
	 */
	public function _initComposer(){

		file_exists(APPLICATION_PATH . "/vendor/autoload.php") && Yaf_Loader::import(APPLICATION_PATH . "/vendor/autoload.php");
	}

	public function _initPlugin(Yaf_Dispatcher $dispatcher) {
		//注册一个插件
		$objSamplePlugin = new SamplePlugin();
		$dispatcher->registerPlugin($objSamplePlugin);
	}

	public function _initRoute(Yaf_Dispatcher $dispatcher) {
		//在这里注册自己的路由协议,默认使用简单路由
	}
	
	public function _initView(Yaf_Dispatcher $dispatcher){
		//在这里注册自己的view控制器，例如smarty,firekylin
	}
}
