<?php
namespace Home\Controller;
use Think\Controller;
class AdminController extends Controller {
    public function index(){
        $adminModel = M('admin');
        $adminNum = $adminModel->count();
        if ($adminNum > 0) {
            $this->display("Manage:about");
        } else {
            $this->display("Manage:config");
        }
    }

    /* 
     * 系统配置
     */
    public function config() {
        $adminModel = M('admin');
        $adminNum = $adminModel->count();
        if ($adminNum > 0) {
            $this->display("Manage:about");
        }

        $username = I('post.username');
        $password = I('post.password');

        if ($username == null || $password == null) {
            $this->ajaxReturn(array("status" => false, "msg" => "用户名或密码无效"));
        }

        $password = md5($password . "_SAVECODE_BY_JONATHAN");

        $adminModel = M('admin');
        $data = array(
            'username' => $username,
            'password' => $password,
        );
        $result = $adminModel->add($data);
        if ($result) {
            $this->ajaxReturn(array("status" => true, "url" => "/admin/login"));
        } else {
            $this->ajaxReturn(array("status" => false, "msg" => "数据库错误, 请联系技术人员"));
        }
    }

    /* 
     * 登录
     */
    public function login() {
        $this->display("Manage:login");
    }

    /* 
     * 登录认证
     */
    public function Authentication() {
        $username = I('post.username');
        $password = I('post.password');

        if ($username == null || $password == null) {
            $this->ajaxReturn(array("status" => false, "msg" => "用户名或密码无效"));
        }

        $password = md5($password . "_SAVECODE_BY_JONATHAN");

        $adminModel = M('admin');
        $data = array(
            'username' => $username,
            'password' => $password,
        );
        $result = $adminModel->where($data)->find();
        if ($result) {
            session("uid", $result['uid']);
            session("username", $username);
            $this->ajaxReturn(array("status" => true, "url" => "/survey/manage"));
        } else {
            $this->ajaxReturn(array("status" => false, "msg" => "用户名或密码错误"));
        }
    }

    /* 
     * 管理员信息
     */
    public function profile() {
        if (!session('uid')) {
            $this->redirect("/admin/login");
        }

        $this->display("Manage:profile");
    }

    /* 
     * 登录认证
     */
    public function updatePassword() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录后再操作"));
        }

        $uid = session("uid");

        $oldPassword = I('post.oldPassword');
        $newPassword = I('post.newPassword');

        $oldPassword = md5($oldPassword . "_SAVECODE_BY_JONATHAN");
        $newPassword = md5($newPassword . "_SAVECODE_BY_JONATHAN");

        $adminModel = M('admin');
        $data = array(
            'uid' => $uid,
            'password' => $oldPassword,
        );
        $result = $adminModel->where($data)->find();

        if ($result) {
            $data['password'] = $newPassword;
            $isSaved = $adminModel->save($data);
            if (false !== $isSaved) {
                session('uid', null);
                $this->ajaxReturn(array("status" => true, "msg" => "新密码保存成功"));
            } else {
                $this->ajaxReturn(array("status" => false, "msg" => "密码保存失败"));
            }
        } else {
            $this->ajaxReturn(array("status" => false, "msg" => "原密码不正确"));
        }
    }

    /* 
     * 创建调查
     */
    public function CreateSurvey() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        $activeSurvey = session('ActiveSurvey');
        if (isset($activeSurvey['sid']) && I('post.clean') == 'false') {
            $this->ajaxReturn(array('status' => false, 'data' => I('post.')));
        }

    	$data = array(
    		//'owner_uid' => session('uid'),
    		'isEncrypt' => I('post.isEncrypt'),
    		'expires' => I('post.expires'),
    		'startdate' => date(),
    		'title' => I('post.title'),
    		'subtitle' => I('post.subtitle'),
    		'ctime' => time());
    	if ($data['isEncrypt'] == 1) {
    		$data['security_code'] = I('post.subtitle', null, '123456');
    	}

    	$surveyModel = M('survey');
    	$sid = $surveyModel->add($data);
        session("ActiveSurvey", array(
            'sid' => $sid,
        ));

    	$this->ajaxReturn(array('status' => true, 'data' => $sid));
    }

    /* 
     * 更新调查
     */
    public function UpdateSurveyTitle() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        $activeSurvey = session('ActiveSurvey');
        if (!$activeSurvey) {
            $this->ajaxReturn(array("status" => false, "msg" => "更新问卷数据失败"));
        }

        $sid = $activeSurvey['sid'];
    	$data = array(
    		'sid' => $sid,
    		'startdate' => date(),
    		'title' => I('post.title'),
    		'subtitle' => I('post.subtitle'),
    		'ctime' => time());

    	$surveyModel = M('survey');
    	$result = $surveyModel->save($data);
    	if (false !== $result) {
    		$this->ajaxReturn(array('status' => true));
    	} else {
    		$this->ajaxReturn(array('status' => false));
    	}
    }

    /* 
     * 结束调查
     */
    public function FinishSurvey() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

    	$data = array(
    		'sid' => I('post.sid'),
            'isdel' => 1);
        
        session("ActiveSurvey", null);

    	$surveyModel = M('survey');
    	$result = $surveyModel->save($data);

    	if (false !== $result) {
    		$this->ajaxReturn(array('status' => true, 'data' => ''));
    	} else {
    		$this->ajaxReturn(array('status' => false, 'data' => ''));
    	}
    }

    /* 
     * 删除调查
     */
    public function DeleteSurvey() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        $data = array(
            'sid' => I('post.sid'),
            'isdel' => 1);
        
        session("ActiveSurvey", null);

        $surveyModel = M('survey');
        $result = $surveyModel->where($data)->delete();

        if (false !== $result) {
            $this->ajaxReturn(array('status' => true, 'data' => ''));
        } else {
            $this->ajaxReturn(array('status' => false, 'data' => ''));
        }
    }

    /* 
     * 创建问题
     */
    public function CreateQuestion() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

    	$data = array(
    		//'parent_qid' => session('uid'),
    		'sid' => I('post.sid'),
    		'type' => I('post.type'),
    		'title' => I('post.title'),
    		'subtitle' => I('post.subtitle'),
    		'isNecessary' => I('post.isNecessary'));

    	$questionModel = M('question');
    	$qid = $questionModel->add($data);
    	$this->ajaxReturn(array('status' => true, 'data' => $qid));
    }

    /* 
     * 更新问题
     */
    public function UpdateQuestion() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        $qid = I('post.qid');
    	$data = array(
    		'qid' => $qid,
    		//'parent_qid' => session('uid'),
    		'title' => I('post.title'),
    		'subtitle' => I('post.subtitle'),
    		'isNecessary' => I('post.isnecessary')
            );

        $questionModel = M('question');
        $questionDetail = $questionModel->where(array('qid' => $qid))->find();
        if (!$questionDetail) {
            $this->ajaxReturn(array('status' => false, 'msg' => '问题ID不存在'));
        }

        // 检查判断条件是否可以设置为必答
        if ($data['isNecessary'] == "1") {
            $sid = $questionDetail['sid'];
            $conditionModel = M('condition');
            $isJumped = $conditionModel->where("qid < '$qid' AND jump > '$qid'")->select();
            for ($i = 0; $i < count($isJumped); $i ++) {
                $toJumpQuestion = $questionModel->where(array('qid' => $qid))->find();
                if ($toJumpQuestion['sid'] == $sid) {
                    $this->ajaxReturn(array('status' => false, 'msg' => '存在跳题，此题不能设置为必答题'));
                }
            }
        }

    	$result = $questionModel->save($data);
    	if (false !== $result) {
    		$this->ajaxReturn(array('status' => true, 'msg' => '更新题目成功'));
    	} else {
    		$this->ajaxReturn(array('status' => false, 'msg' => '更新题目失败'));
    	}
    }

    /* 
     * 删除问题
     */
    public function DeleteQuestion() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

    	$data = array(
    		'qid' => I('post.qid'));

    	$questionModel = M('question');
    	$result = $questionModel->where($data)->delete();

        $questionOptionModel = M('question_option');
        $questionOptionModel->where(array('qid' => $data['qid']))->delete();

        $conditionModel = M('condition');
        $conditionModel->where(array('qid' => $data['qid']))->delete();

    	if (false !== $result) {
    		$this->ajaxReturn(array('status' => true, 'msg' => '删除失败'));
    	} else {
    		$this->ajaxReturn(array('status' => false, 'msg' => '删除成功'));
    	}
    }


    /* 
     * 创建选项
     */
    public function CreateOption() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        $data = array(
            'qid' => I('post.qid'),
            'isRadio' => I('post.isRadio'),
            'isCheckbox' => I('post.isCheckbox'),
            'isText' => I('post.isText'),
            'isMixed' => I('post.isMixed'),
            'content' => I('post.content'),
            'ctime' => date());

        $questionOptionModel = M('question_option');
        $oid = $questionOptionModel->add($data);
        $this->ajaxReturn(array('status' => true, 'data' => $oid));
    }

    /* 
     * 更新问题
     */
    public function UpdateOption() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        $data = array(
            'oid' => I('post.oid'),
            'qid' => I('post.qid'),
            'isRadio' => I('post.isRadio'),
            'isCheckbox' => I('post.isCheckbox'),
            'isText' => I('post.isText'),
            'isMixed' => I('post.isMixed'),
            'content' => I('post.content'),
            'ctime' => date());

        $questionOptionModel = M('question_option');
        $result = $questionOptionModel->save($data);
        if (false !== $result) {
            $this->ajaxReturn(array('status' => true, 'data' => ''));
        } else {
            $this->ajaxReturn(array('status' => false, 'data' => ''));
        }
    }

    /* 
     * 删除问题
     */
    public function DeleteOption() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        $data = array(
            'oid' => I('post.oid'));

        $questionOptionModel = M('question_option');
        $result = $questionOptionModel->where($data)->delete();
        if (false !== $result) {
            $this->ajaxReturn(array('status' => true, 'data' => ''));
        } else {
            $this->ajaxReturn(array('status' => false, 'data' => ''));
        }
    }


    /* 
     * 更新跳题条件
     */
    public function UpdateCondition() {
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }


        $sid = I('post.sid');
        $oid = I('post.oid');
        $jump = I('post.jump');
        if (!$oid) {
            $this->ajaxReturn(array("status" => false, 'msg' => "非法操作"));
        }

        if ($jump) {
            $questionOptionModel = M('question_option');
            $questionDetail = $questionOptionModel->where(array('oid' => $oid))->find();

            if (!$questionDetail) {
                $this->ajaxReturn(array("status" => false, 'msg' => "非法操作"));
            }
        }
        $data = array(
            'oid' => $oid,
            'sid' => $sid,
            'qid' => $questionDetail['qid'],
            'jump' => $jump);

        $qid = $questionDetail['qid'];
        $questionModel = M('question');
        $isNoJumped = $questionModel->where("sid = '$sid' AND qid > '$qid' AND qid < '$jump' AND isnecessary = 1")->select();
        if ($isNoJumped) {
            $this->ajaxReturn(array('status' => false, 'msg' => '中间有必答题无法设置'));
        }

        $conditionModel = M('condition');
        $isExist = $conditionModel->where(array('oid' => $oid))->find();

        if ($isExist) {
            $result = $conditionModel->save($data);
            if (false !== $result) {
                $this->ajaxReturn(array('status' => true, 'msg' => '更新成功'));
            } else {
                $this->ajaxReturn(array('status' => false, 'msg' => '更新失败'));
            }
        } else {
            $conditionModel->add($data);
            $this->ajaxReturn(array('status' => true, 'msg' => '添加条件成功'));
        }
    }

    /*
     *
     */
    public function SubmitAnswerSet() {
        
        $sid = I('post.sid');
        $answerSet = I('post.answerSet');
        $uid = session_id();
        if (!$sid) {
            $this->ajaxReturn(array('status' => false, 'msg' => '此问卷ID出错, 请正确操作'));
        }

        $surveyModel = M('survey');
        $surveyDetail = $surveyModel->where(array('sid' => $sid))->find();
        if (!$surveyDetail) {
            $this->ajaxReturn(array('status' => false, 'msg' => '此问卷ID不存在, 请正确操作'));
        }

        $questionModel = M('question');
        $condition = array('sid' => $sid);
        $questionSet = $questionModel->where($condition)->order('qid asc')->select();

        $dataToDB = array();

        if ($questionSet) {
            $questionOptionModel = M('question_option');
            for ($i = 0; $i < count($questionSet); $i++) {
                $questionSet[$i]['innerId'] = $i + 1;
                $qid = $questionSet[$i]['qid'];
                $type = $questionSet[$i]['type'];

                $optionSet = $questionOptionModel->where(array('qid' => $questionSet[$i]['qid']))->select();
                
                $count = 0;
                if ($type == "radio" || $type == "checkbox") {
                    foreach ($answerSet[$qid]['answers'] as $oid => $option) {
                        for ($j = 0; $j < count($optionSet); $j ++) {

                            if ($oid == $optionSet[$j]['oid']) {
                                array_push($dataToDB, array(
                                    'uid' => $uid,
                                    'qid' => $qid,
                                    'oid' => $oid,
                                    'content' => $option['content'],
                                    'ctime' => time()
                                ));
                                $count ++;
                                break;
                            }
                        }
                        if ($type == "radio") {
                            break;
                        }
                    }
                } else if ($type == "text") {
                    array_push($dataToDB, array(
                        'uid' => $uid,
                        'qid' => $qid,
                        'content' => $answerSet[$qid]['answers'],
                        'ctime' => time()));
                    $count ++;
                }

                if ($questionSet[$i]['isnecessary'] != 0 && $count < 1) {
                    $this->ajaxReturn(array('status' => false, 'msg' => '请回答完必答题', 'data' => $qid));
                }
            }
        }

        $answerModel = M('answer');
        foreach ($dataToDB as $query) {
            $result = $answerModel->add($query);
            if (!$result) {
                $this->ajaxReturn(array('status' => false, 'msg' => '无法提交回答'));
            }
        }

        $newSurveyDetail = array(
            'sid' => $sid,
            'participate' => $surveyDetail['participate'] + 1,
        );
        $result = $surveyModel->save($newSurveyDetail);
        if ($result) {
            $this->ajaxReturn(array('status' => true));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '更新问卷数据失败'));
        }
    }

    public function SaveSurvey() {
        session("ActiveSurvey", null);
    }

}