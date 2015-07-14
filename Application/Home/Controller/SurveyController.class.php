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
        if (!session('uid')) {
            $this->redirect("/Admin/login");
        }

        $surveyModel = M('survey');
        $surveySet = $surveyModel->order('sid desc')->select();
        $this->assign('surveySet', $surveySet);
        $this->display('Manage:index');
    }

    /*
     * 调查统计
     */
    public function statics() {
        if (!session('uid')) {
            $this->redirect("/Admin/login");
        }

        $sid = I('get.s');
        if (!$sid) {
            $this->error("请勿胡乱操作");
        }

        $data = array(
            'sid' => $sid
        );

        $surveyModel = M('survey');
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('请勿胡乱操作');
        }

        $participate = $surveyDetail['participate'];

        session('ActiveSurvey', array("sid" => $sid));

        $questionModel = M('question');
        $condition = array('sid' => $surveyDetail['sid']);
        $questionSet = $questionModel->where($condition)->order('qid asc')->select();

        $answerModel = M('answer');

        if ($questionSet) {
            $questionOptionModel = M('question_option');
            for ($i = 0; $i < count($questionSet); $i++) {
                $questionSet[$i]['innerId'] = $i + 1;
                $qid = $questionSet[$i]['qid'];
                $type = $questionSet[$i]['type'];
                if ($type == "checkbox" || $type == "radio") {
                    $optionSet = $questionOptionModel->where(array('qid' => $qid))->select();
                    $questionSet[$i]['optionSet'] = $optionSet;

                    for ($j = 0; $j < count($optionSet); $j ++) {
                        $oid = $optionSet[$j]['oid'];
                        $selectNum = $answerModel->where(array('qid' => $qid, 'oid' => $oid))->count();
                        $questionSet[$i]['optionSet'][$j]['percent'] = number_format($selectNum/$participate*100, 2);
                    }
                } else if ($type == "text") {
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
        if (!session('uid')) {
            $this->redirect("/Admin/login");
        }

        $sid = I('get.s');
        if (!$sid) {
            $this->error("请勿胡乱操作");
        }

        $data = array(
            'sid' => $sid
        );

        $surveyModel = M('survey');
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('请勿胡乱操作');
        }

        $participate = $surveyDetail['participate'];

        $qid = I('get.q');
        if (!$qid) {
            $this->error('问题ID不合法');
        }

        $questionModel = M('question');
        $condition = array('sid' => $surveyDetail['sid'], 'qid' => $qid);
        $questionSet = $questionModel->where($condition)->order('qid asc')->select();

        $answerModel = M('answer');

        if ($questionSet) {
            $questionOptionModel = M('question_option');
            for ($i = 0; $i < count($questionSet); $i++) {
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
                } else if ($type == "text") {
                    $answerSet = $answerModel->where(array('qid' => $qid))->select();
                    $questionSet[$i]['answerSet'] = $answerSet;
                }
            }
        } else {
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
        $sid = I('get.s');
        if (!$sid) {
            $this->error("请勿胡乱操作");
        }
        
        $activeSurvey = session('ActiveSurvey');
        session('ActiveSurvey', null);
        $data = array(
            'sid' => $sid,
            'isdel' => 0,
        );

        $surveyModel = M('survey');
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('该问卷已经过期');
        }

        $questionModel = M('question');
        $condition = array('sid' => $sid);
        $questionSet = $questionModel->where($condition)->order('qid asc')->select();

        $conditionModel = M('condition');

        if ($questionSet) {
            $questionOptionModel = M('question_option');
            for ($i = 0; $i < count($questionSet); $i++) {
                $questionSet[$i]['innerId'] = $i + 1;
                $type = $questionSet[$i]['type'];
                $optionSet = $questionOptionModel->where(array('qid' => $questionSet[$i]['qid']))->select();

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
        if (!session('uid')) {
            $this->redirect("/Admin/login");
        }

        $this->display();
    }

    /*
     * 创建调查
     */
    public function modify() {
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

        $data = array(
            'sid' => $sid
        );

        $surveyModel = M('survey');
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('请勿胡乱操作');
        }
        session('ActiveSurvey', array("sid" => $sid));

        $questionModel = M('question');
        $condition = array('sid' => $surveyDetail['sid']);
        $questionSet = $questionModel->where($condition)->order('qid asc')->select();

        if ($questionSet) {
            $questionOptionModel = M('question_option');
            for ($i = 0; $i < count($questionSet); $i++) {
                $questionSet[$i]['innerId'] = $i + 1;
                $type = $questionSet[$i]['type'];
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

        $data = array(
            'sid' => $sid
        );

        $surveyModel = M('survey');
        $surveyDetail = $surveyModel->where($data)->find();
        if (!$surveyDetail) {
            $this->error('请勿胡乱操作');
        }

        $questionModel = M('question');
        $questionDetail = $questionModel->where(array('sid' => $sid, 'qid' => $qid))->find();
        if (!$questionDetail) {
            $this->error('问题不存在');
        }
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

        $questionOptionModel = M('question_option');
        $optionSet = $questionOptionModel->where(array('qid' => $qid))->select();

        $conditionModel = M('condition');
        $conditionSet = $conditionModel->where(array('qid' => $qid))->select();
        for ($i = 0; $i < count($optionSet); $i ++) {
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
        session(null);
        $this->redirect("/");
    }
}