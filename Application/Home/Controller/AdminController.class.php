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
        // 判断是否为初次配置
        $adminModel = M('admin');
        $adminNum = $adminModel->count();
        if ($adminNum > 0) {
            $this->display("Manage:about");
        }
        // 获取用户名和密码
        $username = I('post.username');
        $password = I('post.password');

        // 判断不能为空
        if ($username == null || $password == null) {
            $this->ajaxReturn(array("status" => false, "msg" => "用户名或密码无效"));
        }

        // 加盐md5
        $password = md5($password . "_SAVECODE_BY_JONATHAN");

        // 初始化admin模块
        $adminModel = M('admin');
        $data = array(
            'username' => $username,
            'password' => $password,
        );

        // 初始化管理员身份
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
        // 获取用户名和密码
        $username = I('post.username');
        $password = I('post.password');

        // 判断不能为空
        if ($username == null || $password == null) {
            $this->ajaxReturn(array("status" => false, "msg" => "用户名或密码无效"));
        }

        // 加盐md5
        $password = md5($password . "_SAVECODE_BY_JONATHAN");

        $adminModel = M('admin');
        $data = array(
            'username' => $username,
            'password' => $password,
        );

        // 判断用户名密码是否匹配
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
        // 判断是否登录
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录后再操作"));
        }

        // 获取当前用户uid
        $uid = session("uid");

        // 获取用户名密码
        $oldPassword = I('post.oldPassword');
        $newPassword = I('post.newPassword');

        // 加盐md5
        $oldPassword = md5($oldPassword . "_SAVECODE_BY_JONATHAN");
        $newPassword = md5($newPassword . "_SAVECODE_BY_JONATHAN");

        $adminModel = M('admin');
        $data = array(
            'uid' => $uid,
            'password' => $oldPassword,
        );
        // 查询旧密码是否正确
        $result = $adminModel->where($data)->find();

        if ($result) {
            $data['password'] = $newPassword;
            // 保存新密码
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

        // 当前选定的问卷id
        $activeSurvey = session('ActiveSurvey');
        // 判断是否重选问卷id
        if (isset($activeSurvey['sid']) && I('post.clean') == 'false') {
            $this->ajaxReturn(array('status' => false, 'data' => I('post.')));
        }

        // 新问卷的属性
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
        // 初始化survey模块
    	$surveyModel = M('survey');
        // 添加问卷
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
        // 判断用户登录状态
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        // 检查当前选定问卷id
        $activeSurvey = session('ActiveSurvey');
        if (!$activeSurvey) {
            $this->ajaxReturn(array("status" => false, "msg" => "更新问卷数据失败"));
        }

        // 更新数据
        $sid = $activeSurvey['sid'];
    	$data = array(
    		'sid' => $sid,
    		'startdate' => date(),
    		'title' => I('post.title'),
    		'subtitle' => I('post.subtitle'),
    		'ctime' => time());

        // 初始化survey模块
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
        // 判断用户登录状态
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        // 更新数据
    	$data = array(
    		'sid' => I('post.sid'),
            'isdel' => 1);
        
        session("ActiveSurvey", null);

        // 初始化survey模块
    	$surveyModel = M('survey');
        // 保存数据
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
        // 判断用户登录状态
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        // 更新数据
        $data = array(
            'sid' => I('post.sid'),
            'isdel' => 1);
        
        session("ActiveSurvey", null);

        // 初始化survey模块
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
        // 判断用户登录状态
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        // 更新数据
    	$data = array(
    		//'parent_qid' => session('uid'),
    		'sid' => I('post.sid'),
    		'type' => I('post.type'),
    		'title' => I('post.title'),
    		'subtitle' => I('post.subtitle'),
    		'isNecessary' => I('post.isNecessary'));

        // 初始化question模块
    	$questionModel = M('question');

        // 添加问题
    	$qid = $questionModel->add($data);
    	$this->ajaxReturn(array('status' => true, 'data' => $qid));
    }

    /* 
     * 更新问题
     */
    public function UpdateQuestion() {
        // 判断用户登录状态
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        // 获取题目id
        $qid = I('post.qid');

        // 更新数据
    	$data = array(
    		'qid' => $qid,
    		//'parent_qid' => session('uid'),
    		'title' => I('post.title'),
    		'subtitle' => I('post.subtitle'),
    		'isNecessary' => I('post.isnecessary')
            );

        // 初始化question模块
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
            // 必答题和跳题之间不可以存在逻辑矛盾
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
        // 判断用户登录状态
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        // 更新数据
    	$data = array(
    		'qid' => I('post.qid'));

        // 初始化question模块，清除相应问题
    	$questionModel = M('question');
    	$result = $questionModel->where($data)->delete();

        // 清除问题对应的option
        $questionOptionModel = M('question_option');
        $questionOptionModel->where(array('qid' => $data['qid']))->delete();

        // 清除问题对应的跳转条件
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
        // 判断用户登录状态
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        // 更新数据
        $data = array(
            'qid' => I('post.qid'),
            'isRadio' => I('post.isRadio'),
            'isCheckbox' => I('post.isCheckbox'),
            'isText' => I('post.isText'),
            'isMixed' => I('post.isMixed'),
            'content' => I('post.content'),
            'ctime' => date());

        // 初始化question_option模块
        $questionOptionModel = M('question_option');
        $oid = $questionOptionModel->add($data);
        $this->ajaxReturn(array('status' => true, 'data' => $oid));
    }

    /* 
     * 更新问题
     */
    public function UpdateOption() {
        // 判断用户登录状态
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        // 更新数据
        $data = array(
            'oid' => I('post.oid'),
            'qid' => I('post.qid'),
            'isRadio' => I('post.isRadio'),
            'isCheckbox' => I('post.isCheckbox'),
            'isText' => I('post.isText'),
            'isMixed' => I('post.isMixed'),
            'content' => I('post.content'),
            'ctime' => date());

        // 初始化question_option模块
        $questionOptionModel = M('question_option');
        // 保存选项
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
        // 判断用户登录状态
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }

        // 更新数据
        $data = array(
            'oid' => I('post.oid'));

        // 初始化question_option模块
        $questionOptionModel = M('question_option');
        // 删除选项
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
        // 判断用户登录状态
        if (!session('uid')) {
            $this->ajaxReturn(array("status" => false, 'msg' => "请以管理员身份登录"));
        }


        // 获取跳题条件和信息
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

        // 初始化question模块
        $questionModel = M('question');
        $isNoJumped = $questionModel->where("sid = '$sid' AND qid > '$qid' AND qid < '$jump' AND isnecessary = 1")->select();
        if ($isNoJumped) {
            $this->ajaxReturn(array('status' => false, 'msg' => '中间有必答题无法设置'));
        }

        // 初始化condition模块
        $conditionModel = M('condition');
        // 判断option是否存在
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
        // 判断用户登录状态
        $sid = I('post.sid');
        $answerSet = I('post.answerSet');
        $uid = session_id();
        if (!$sid) {
            $this->ajaxReturn(array('status' => false, 'msg' => '此问卷ID出错, 请正确操作'));
        }
        // 初始化survey模块
        $surveyModel = M('survey');
        // 获取问卷信息
        $surveyDetail = $surveyModel->where(array('sid' => $sid))->find();
        if (!$surveyDetail) {
            $this->ajaxReturn(array('status' => false, 'msg' => '此问卷ID不存在, 请正确操作'));
        }
        // 获取问卷的所有题目
        $questionModel = M('question');
        $condition = array('sid' => $sid);
        $questionSet = $questionModel->where($condition)->order('qid asc')->select();

        // 用于存储到数据库的答案
        $dataToDB = array();

        if ($questionSet) {
            // 初始化question_option模块
            $questionOptionModel = M('question_option');
            for ($i = 0; $i < count($questionSet); $i++) {
                // 对于所有问题
                $questionSet[$i]['innerId'] = $i + 1;
                $qid = $questionSet[$i]['qid'];
                $type = $questionSet[$i]['type'];
                // 获取所有选项
                $optionSet = $questionOptionModel->where(array('qid' => $questionSet[$i]['qid']))->select();
                
                $count = 0;
                if ($type == "radio" || $type == "checkbox") {
                    foreach ($answerSet[$qid]['answers'] as $oid => $option) {
                        for ($j = 0; $j < count($optionSet); $j ++) {
                            // 对于每个选项
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
                        //如果单选则得到第一个答案即可
                        if ($type == "radio") {
                            break;
                        }
                    }
                } else if ($type == "text") {
                    // 如果为文本则获取答案文本
                    array_push($dataToDB, array(
                        'uid' => $uid,
                        'qid' => $qid,
                        'content' => $answerSet[$qid]['answers'],
                        'ctime' => time()));
                    $count ++;
                }
                // 不可跳过必选题
                if ($questionSet[$i]['isnecessary'] != 0 && $count < 1) {
                    $this->ajaxReturn(array('status' => false, 'msg' => '请回答完必答题', 'data' => $qid));
                }
            }
        }

        // 将答案写入数据库
        $answerModel = M('answer');
        foreach ($dataToDB as $query) {
            $result = $answerModel->add($query);
            if (!$result) {
                $this->ajaxReturn(array('status' => false, 'msg' => '无法提交回答'));
            }
        }
        // 更新问卷详情
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