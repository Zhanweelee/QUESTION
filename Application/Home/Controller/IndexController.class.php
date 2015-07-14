<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $surveyModel = M('survey');
        $surveySet = $surveyModel->order('sid desc')->select();
        $this->assign('surveySet', $surveySet);
        $this->display('Survey:list');
	}

}