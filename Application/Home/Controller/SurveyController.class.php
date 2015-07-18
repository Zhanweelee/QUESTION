<?php
namespace Home\Controller;
use Think\Controller;
class SurveyController extends Controller {
    public function index(){
        $this->redirect("/");
    }

    /*
     * 管理调查
     */
    public function manage() {
        // 判断用户登录状态
        if (!session('uid')) {
            $this->redirect("/Admin/login");
        }

        // 加载survey模块
        $surveyModel = M('survey');
        // 获取问卷列表，倒序
        $surveySet = $surveyModel->order('sid desc')->select();
        // 将数据传输到前端页面
        $this->assign('surveySet', $surveySet);
        $this->display('Manage:index');
    }

    /*
     * 调查统计
     */
    public function statics() {
        // 判断用户登录状态
        if (!session('uid')) {
            $this->redirect("/Admin/login");
        }

        // 获取问卷id
        $sid = I('get.s');
        if (!$sid) {
            $this->error("请勿胡乱操作");
        }

        // 用于查询的条件（问卷id）
        $data = array(
            'sid' => $sid
        );

        // 初始化survey模块
        $surveyModel = M('survey');
        // 查询该问卷的信息
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('请勿胡乱操作');
        }

        // 回答人数
        $participate = $surveyDetail['participate'];

        // 记录当前活跃的问卷
        session('ActiveSurvey', array("sid" => $sid));

        // 初始化question模块
        $questionModel = M('question');

        $condition = array('sid' => $surveyDetail['sid']);
        // 查询该问卷下所有的答案
        $questionSet = $questionModel->where($condition)->order('qid asc')->select();


        // 初始化问卷回答模块
        $answerModel = M('answer');

        // 如果该问卷非空
        if ($questionSet) {
            $questionOptionModel = M('question_option');
            for ($i = 0; $i < count($questionSet); $i++) {
                // 获取所有的问题
                $questionSet[$i]['innerId'] = $i + 1;
                $qid = $questionSet[$i]['qid'];
                $type = $questionSet[$i]['type'];
                // 判断该问题是否为选择题
                if ($type == "checkbox" || $type == "radio") {
                    // 获得所有选项
                    $optionSet = $questionOptionModel->where(array('qid' => $qid))->select();
                    $questionSet[$i]['optionSet'] = $optionSet;
                    // 统计回答的人数
                    for ($j = 0; $j < count($optionSet); $j ++) {
                        $oid = $optionSet[$j]['oid'];
                        $selectNum = $answerModel->where(array('qid' => $qid, 'oid' => $oid))->count();
                        $questionSet[$i]['optionSet'][$j]['percent'] = number_format($selectNum/$participate*100, 2);
                    }
                    // 判断该问题是否为填空题
                } else if ($type == "text") {

                    // 获取所有的答案
                    $answerSet = $answerModel->where(array('qid' => $qid))->limit(10)->select();
                    $questionSet[$i]['answerSet'] = $answerSet;
                }
            }
        } else {
            $questionSet = array();
        }

        // 基础样板类型
        array_unshift($questionSet, array('qid' => 'sample', 'sid' => $surveyDetail['sid'], 'type' => 'text'));
        array_unshift($questionSet, array('qid' => 'sample', 'sid' => $surveyDetail['sid'], 'type' => 'checkbox'));
        array_unshift($questionSet, array('qid' => 'sample', 'sid' => $surveyDetail['sid'], 'type' => 'radio'));

        $this->assign('surveyDetail', $surveyDetail);
        $this->assign('activeSid', $surveyDetail['sid']);
        $this->assign('activeQuestionSet', $questionSet);
        $this->display('Manage:statics');
    }


    /*
     * 详细统计
     */
    public function staticsDetail() {
        // 判断用户登录状态
        if (!session('uid')) {
            $this->redirect("/Admin/login");
        }

        // 获取问卷id
        $sid = I('get.s');
        if (!$sid) {
            $this->error("请勿胡乱操作");
        }

        // 查询问卷的条件
        $data = array(
            'sid' => $sid
        );

        // 初始化survey模块
        $surveyModel = M('survey');
        // 查询问卷的信息
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('请勿胡乱操作');
        }

        // 获取参与人数
        $participate = $surveyDetail['participate'];

        // 获取问题id
        $qid = I('get.q');
        if (!$qid) {
            $this->error('问题ID不合法');
        }

        // 初始化question模块
        $questionModel = M('question');

        // 查询该问卷下的所有题目
        $condition = array('sid' => $surveyDetail['sid'], 'qid' => $qid);
        $questionSet = $questionModel->where($condition)->order('qid asc')->select();

        // 初始化answer模块
        $answerModel = M('answer');

        if ($questionSet) {
            $questionOptionModel = M('question_option');
            for ($i = 0; $i < count($questionSet); $i++) {
                // 获取该问题的所有选项
                $questionSet[$i]['innerId'] = $i + 1;
                $qid = $questionSet[$i]['qid'];
                $type = $questionSet[$i]['type'];
                if ($type == "checkbox" || $type == "radio") {
                    $oid = I('get.o');
                    if (!$oid) {
                        $this->error('选项ID不合法');
                    }
                    $optionSet = $questionOptionModel->where(array('qid' => $qid, 'oid' => $oid))->select();
                    $questionSet[$i]['optionSet'] = $optionSet;

                    for ($j = 0; $j < count($optionSet); $j ++) {
                        $oid = $optionSet[$j]['oid'];
                        $optionAnswerSet = $answerModel->where(array('qid' => $qid, 'oid' => $oid))->select();
                        $questionSet[$i]['optionSet'][$j]['num'] = count($optionAnswerSet[0]);
                        $questionSet[$i]['optionSet'][$j]['answerSet'] = $optionAnswerSet[0];
                    }

                    // 如果是文本则获取所有的答案
                } else if ($type == "text") {
                    $answerSet = $answerModel->where(array('qid' => $qid))->select();
                    $questionSet[$i]['answerSet'] = $answerSet;
                }
            }
        } else {
            // 如果该问卷没有设置题目，则答案为空
            $questionSet = array();
        }

        $this->assign('surveyDetail', $surveyDetail);
        $this->assign('activeSid', $surveyDetail['sid']);
        $this->assign('activeQuestionSet', $questionSet);
        $this->display('Manage:statics-detail');
    }

    /* 
     * 调查页面
     */
    public function view() {
        // 判断用户登录状态
        $sid = I('get.s');
        if (!$sid) {
            $this->error("请勿胡乱操作");
        }
        
        // 记录当前选定的问卷id
        $activeSurvey = session('ActiveSurvey');
        session('ActiveSurvey', null);

        // 查询问卷的条件
        $data = array(
            'sid' => $sid,
            'isdel' => 0,
        );

        // 初始化survey模块
        $surveyModel = M('survey');
        // 查询问卷的信息
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('该问卷已经过期');
        }

        // 获取参与人数
        $questionModel = M('question');
        // 获取问题id
        $condition = array('sid' => $sid);
        $questionSet = $questionModel->where($condition)->order('qid asc')->select();

        // 初始化condition模块
        $conditionModel = M('condition');

        if ($questionSet) {
            $questionOptionModel = M('question_option');
            for ($i = 0; $i < count($questionSet); $i++) {
                // 查询该题目下的所有选项
                $questionSet[$i]['innerId'] = $i + 1;
                $type = $questionSet[$i]['type'];
                $optionSet = $questionOptionModel->where(array('qid' => $questionSet[$i]['qid']))->select();

                // 获取该问题的所有选项
                if ($type == "radio") {
                    $conditionSet = $conditionModel->where(array('qid' => $questionSet[$i]['qid']))->select();

                    for ($k = 0; $k < count($optionSet); $k ++) {
                        $oid = $optionSet[$k]['oid'];
                        for ($j = 0; $j < count($conditionSet); $j ++) {
                            if ($conditionSet[$j]['oid'] == $oid) {
                                $optionSet[$k]['jump'] = $conditionSet[$j]['jump'];
                                break;
                            }
                        }
                    }
                }
                $questionSet[$i]['optionSet'] = $optionSet;

            }
        } else {
            $questionSet = array();
        }

        $this->assign('surveyDetail', $surveyDetail);
        $this->assign('activeSid', $sid);
        $this->assign('activeQuestionSet', $questionSet);
        $this->assign('activeModify', !!$activeSurvey);
        $this->display();
    }

    /*
     * 创建调查
     */
    public function create() {
        // 判断用户登录状态
        if (!session('uid')) {
            $this->redirect("/Admin/login");
        }

        $this->display();
    }

    /*
     * 创建调查
     */
    public function modify() {
        // 判断用户登录状态
        if (!session('uid')) {
            $this->redirect("/Admin/login");
        }

        if (null !== session("ActiveSurvey")) {
            $activeSurvey = session("ActiveSurvey");
        }

        $sid = I('get.s', $activeSurvey['sid']);

        if (null == $sid) {
            $this->error('请勿胡乱操作');
        }

        // 查询问卷的条件
        $data = array(
            'sid' => $sid
        );

        // 初始化survey模块
        $surveyModel = M('survey');
        // 查询问卷的信息
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('请勿胡乱操作');
        }
        session('ActiveSurvey', array("sid" => $sid));

        // 初始化question模块
        $questionModel = M('question');
        $condition = array('sid' => $surveyDetail['sid']);
        // 查询该问卷下的所有题目
        $questionSet = $questionModel->where($condition)->order('qid asc')->select();

        if ($questionSet) {
            // 初始化question_option模块
            $questionOptionModel = M('question_option');
            for ($i = 0; $i < count($questionSet); $i++) {
                // 获取每个问题的所有选项
                $questionSet[$i]['innerId'] = $i + 1;
                $type = $questionSet[$i]['type'];

                // 如果是选择题，则获取该问题的所有选项
                if ($type == "radio" || $type == "checkbox") {
                    $optionSet = $questionOptionModel->where(array('qid' => $questionSet[$i]['qid']))->select();
                    $questionSet[$i]['optionSet'] = $optionSet;
                } else if ($type == "text") {

                }
            }
        } else {
            $questionSet = array();
        }

        // 基础样板类型
        array_unshift($questionSet, array('qid' => 'sample', 'sid' => $surveyDetail['sid'], 'type' => 'text'));
        array_unshift($questionSet, array('qid' => 'sample', 'sid' => $surveyDetail['sid'], 'type' => 'checkbox'));
        array_unshift($questionSet, array('qid' => 'sample', 'sid' => $surveyDetail['sid'], 'type' => 'radio'));

        $this->assign('surveyDetail', $surveyDetail);
        $this->assign('activeSid', $surveyDetail['sid']);
        $this->assign('activeQuestionSet', $questionSet);
        $this->display();
    }


    /*
     * 题目跳题条件
     */
    public function condition() {
        // 判断用户登录状态
        if (!session('uid')) {
            $this->redirect("/Admin/login");
        }

        if (null !== session("ActiveSurvey")) {
            $activeSurvey = session("ActiveSurvey");
        }

        $sid = I('get.s');
        $qid = I('get.q');
        if (!$sid || !$qid) {
            $this->error('请勿胡乱操作');
        }

        // 查询问卷的条件
        $data = array(
            'sid' => $sid
        );

        // 初始化survey模块
        $surveyModel = M('survey');
        // 查询问卷的信息
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('请勿胡乱操作');
        }

        // 初始化question模块
        $questionModel = M('question');
        // 查询该问卷下的所有题目
        $questionDetail = $questionModel->where(array('sid' => $sid, 'qid' => $qid))->find();
        if (!$questionDetail) {
            $this->error('问题不存在');
        }

        // 查询所有跳转条件是否符合先后顺序
        $forwardQuestion = $questionModel->where("sid = '$sid' AND qid > '$qid'")->select();
        $temp = array();
        for ($i = 0; $i < count($forwardQuestion); $i ++) {
            if ($forwardQuestion[$i]['isnecessary'] == 1) {
                $temp[$i] = $forwardQuestion[$i];
                break;
            }
            $temp[$i] = $forwardQuestion[$i];
        }
        $forwardQuestion = $temp;

        // 初始化question_option模块
        $questionOptionModel = M('question_option');
        // 获取该问题的所有选项
        $optionSet = $questionOptionModel->where(array('qid' => $qid))->select();

        // 初始化condition模块
        $conditionModel = M('condition');
        $conditionSet = $conditionModel->where(array('qid' => $qid))->select();
        for ($i = 0; $i < count($optionSet); $i ++) {
            // 获取每个选项的跳转题目id
            $oid = $optionSet[$i]['oid'];
            for ($j = 0; $j < count($conditionSet); $j ++) {
                if ($conditionSet[$j]['oid'] == $oid) {
                    $optionSet[$i]['jump'] = $conditionSet[$j]['jump'];
                    break;
                }
            }
        }

        $this->assign('surveyDetail', $surveyDetail);
        $this->assign('questionDetail', $questionDetail);
        $this->assign('optionSet', $optionSet);
        $this->assign('forwardQuestion', $forwardQuestion);
        $this->assign('activeQuestionSet', $questionSet);
        $this->display();
    }

    /*
     * 分享问卷
     */
    public function share() {
        // 判断用户登录状态
        $sid = I('get.s', $activeSurvey['sid']);

        if (null == $sid) {
            $this->redirect("/");
        }

        $data = array(
            'sid' => $sid
        );

        $surveyModel = M('survey');
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('请勿胡乱操作');
        }

        $this->assign("surveyDetail", $surveyDetail);
        $this->display();
    }

    public function login() {
        $this->redirect("/");
    }

    public function logout() {
        // 清空用户登录状态
        session(null);
        $this->redirect("/");
    }
}