<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CModel
 *
 * @author zouliming
 */
abstract class BModel {
    private static $_models=array();			// class name => model
    public static function model($className=__CLASS__)
	{
		if(isset(self::$_models[$className])){
			return self::$_models[$className];
        }else{
			$model=self::$_models[$className]=new $className(null);
			return $model;
		}
	}
}

?>
