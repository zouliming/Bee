<?php
interface IApplicationComponent
{
	/**
	 * Initializes the application component.
	 * This method is invoked after the application completes configuration.
	 */
	public function init();
	/**
	 * @return boolean whether the {@link init()} method has been invoked.
	 */
	public function getIsInitialized();
}
/**
 * IAction是所有控制器的Action必须implement的接口.
 */
interface IAction
{
	/**
	 * @return string id of the action
	 */
	public function getId();
	/**
	 * @return BController the controller instance
	 */
	public function getController();
}