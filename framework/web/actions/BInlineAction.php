<?php
/**
 * BInlineAction代表一个控制器方法的action.
 *
 * 方法名类似:actionXYZ,XYZ代表的是action的名字
 *
 * @author zouliming <zouliming888@gmail.com>
 * @version $Id: BInlineAction.php
 */
class BInlineAction extends BAction
{
	/**
	 * Runs the action.
	 * The action method defined in the controller is invoked.
	 * This method is required by {@link CAction}.
	 */
	public function run()
	{
		$method='action'.$this->getId();
		$this->getController()->$method();
	}

	/**
	 * 带入请求参数运行action
	 * 这个方法常以这种形式被内部调用 {@link CController::runAction()}.
	 * @param array $params 请求参数 (name=>value)
	 * @return boolean
	 */
	public function runWithParams($params)
	{
		$methodName='action'.$this->getId();
		$controller=$this->getController();
		$method=new ReflectionMethod($controller, $methodName);
		if($method->getNumberOfParameters()>0)
			return $this->runWithParamsInternal($controller, $method, $params);
		else
			return $controller->$methodName();
	}

}
