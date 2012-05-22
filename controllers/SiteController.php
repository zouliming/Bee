<?php
class SiteController extends BController{
    public $layout='main';
    public function actionIndex(){
        $this->render("index");
    }
    public function actionPhotoShow(){
        $this->layout = "";
        $this->render("photoShow");
    }
    public function actionAboutMe(){
        $this->render("aboutMe");
    }
    public function actionZhimakaimen(){
        $this->layout = "";
        $this->render("zhimakaimen");
    }
	public function actionLogin(){
        $db = Bee::dba();
		$va = Bee::va();
        $va->check(array(
            'username'=>array('not_blank'),
            'password'=>array('not_blank')
        ));
        if($va->success){
            $this->redirect("index");
        }else{
            $this->render("zhimakaimen",array(
                'errors'=>'账号或密码错误.'
            ));
        }
	}
}