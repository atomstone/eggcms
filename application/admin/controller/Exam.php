<?php
namespace app\admin\controller;

class Exam extends Base
{
    private $questionModel;
    private $typeModel;
    private $randompaperModel;
    private $paperModel;
    private $userpaperModel;

    public function __construct(){
        parent::__construct();
        $this->questionModel = model('ExamQuestion');
        $this->typeModel = model('ExamType');
        $this->randompaperModel = model('ExamRandompaper');
        $this->paperModel = model('ExamPaper');
        $this->userpaperModel = model('ExamUserpaper');
    }

    /*
     * 类型列表
     */
    public function typeList()
    {
        $types = $this->typeModel->where("siteid = " . $this->siteid)->order('listorder asc')->select();
        //构造返回
        $this->assign('types', $types);
        return view(__FUNCTION__);
    }

    /*
     * 试题分类校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function typeCheck($data, $opType = false){
        if(empty($data['typename'])){
            return error("请填写分类名称");
        }
        if($opType){
            if(empty($data['typeid'])){
                return error("请传入分类id");
            }
        }
        return success();
    }

    /*
     * 试题分类添加
     */
    public function typeAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->typeCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行添加
            $data['siteid'] = $this->siteid;
            $typeid = $this->typeModel->insertGetId($data);
            if(!$typeid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 试题分类修改
     */
    public function typeEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->typeCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $rs = $this->typeModel->where("typeid = {$data['typeid']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $typeid = input('param.typeid/d');
        if(empty($typeid)){
            return $this->errorPage("请传入试题类型id");
        }
        $type = $this->typeModel->get($typeid);
        if(empty($type)){
            return $this->errorPage("未检索到试题类型");
        }
        $this->assign('type', $type);
        return view(__FUNCTION__);
    }

    /*
     * 类型排序
     */
    public function typeSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的类型');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->typeModel->where("typeid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }


    /*
     * 列表
     */
    public function questionList()
    {
        $typeid = input('param.typeid/d', 0);
        $where = "siteid = " . $this->siteid;
        if(!empty($typeid)){
            $where .= " and typeid = " . $typeid;
        }
        $type = $this->typeModel->where($where)->find();
        if(empty($type)){
            return $this->error('未检索到试题类型');
        }
        $questions = $this->questionModel->where($where)->order('listorder asc')->select();
        //构造返回
        $this->assign('type', $type);
        $this->assign('questions', $questions);
        return view(__FUNCTION__);
    }

    /*
     * 单选题校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function singleCheck($data, $opType = false){
        if($opType){
            if(empty($data['questionid'])){
                return error("请传入题目id");
            }
        }
        if(empty($data['topic'])){
            return error("请填写题目");
        }
        if(empty($data['score'])){
            return error("请填写分数");
        }
        if(!isset($data['option'])){
            return error("请添加选项");
        }
        $options = $data['option'];
        if(count($options) < 2){
            return error("选项数量不能小于2");
        }
        foreach($options as $k => $v){
            if(empty($v)){
                return error("请填写第".$k."个选项名称");
            }
        }
        if (count($options) != count(array_unique($data['option']))) {
            return error("选项名称不可重复");
        }
        if(empty($data['answer'])){
            return error("请选择一个答案");
        }
        $info = array(
            'topic'        => $data['topic'],
            'answer'       => $data['answer'],
            'questiontype' => $data['questiontype'],
            'score'        => $data['score'],
            'option'       => json_encode($options)
        );
        return success(array('info' => $info));
    }

    /*
     * 单选题添加
     */
    public function singleAdd(){
        $typeid = input('param.typeid/d',0);
        $type = $this->typeModel->where("siteid = " . $this->siteid . " and typeid = " . $typeid)->find();
        if(empty($type)){
            return $this->error("未检索到试题类型");
        }
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.');
            $data['questiontype'] = 1;
            //校验参数
            $rs = $this->singleCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $info = $rs['data']['info'];
            $info['siteid'] = $type['siteid'];
            $info['typeid'] = $type['typeid'];
            //执行添加
            $questionid = $this->questionModel->insertGetId($info);
            if(!$questionid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        $this->assign('type', $type);
        return view(__FUNCTION__);
    }

    /*
     * 单选题修改
     */
    public function singleEdit()
    {
        $questionid = input('param.questionid/d');
        if(empty($questionid)){
            return $this->error("请传入试题id");
        }
        $question = $this->questionModel->get($questionid);
        if(empty($question)){
            return $this->error("未检索到试题");
        }
        $type = $this->typeModel->where("siteid = " . $this->siteid . " and typeid = " . $question['typeid'])->find();
        if(empty($type)){
            return $this->error("未检索到试题类型");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.');
            $data['questiontype'] = 1;
            //校验参数
            $rs = $this->singleCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $info = $rs['data']['info'];
            //执行修改
            $rs = $this->questionModel->where("questionid = {$questionid}")->update($info);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $this->assign('type', $type);
        $this->assign('question', $question);
        return view(__FUNCTION__);
    }


    /*
     * 多选题校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function multipleCheck($data, $opType = false){
        if($opType){
            if(empty($data['questionid'])){
                return error("请传入题目id");
            }
        }
        if(empty($data['topic'])){
            return error("请填写题目");
        }
        if(empty($data['score'])){
            return error("请填写分数");
        }
        if(!isset($data['option'])){
            return error("请添加选项");
        }
        $options = $data['option'];
        if(count($options) < 2){
            return error("选项数量不能小于2");
        }
        foreach($options as $k => $v){
            if(empty($v)){
                return error("请填写第".$k."个选项名称");
            }
        }
        if (count($options) != count(array_unique($data['option']))) {
            return error("选项名称不可重复");
        }
        if(empty($data['answer'])){
            return error("请至少选择一个答案");
        }
        $info = array(
            'topic'        => $data['topic'],
            'answer'       => implode(',', $data['answer']),
            'score'        => $data['score'],
            'option'       => json_encode($options)
        );
        return success(array('info' => $info));
    }

    /*
     * 多选题添加
     */
    public function multipleAdd(){
        $typeid = input('param.typeid/d',0);
        $type = $this->typeModel->where("siteid = " . $this->siteid . " and typeid = " . $typeid)->find();
        if(empty($type)){
            return $this->error("未检索到试题类型");
        }
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.');

            //校验参数
            $rs = $this->multipleCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $info = $rs['data']['info'];
            $info['siteid'] = $type['siteid'];
            $info['typeid'] = $type['typeid'];
            $info['questiontype'] = 2;
            //执行添加
            $questionid = $this->questionModel->insertGetId($info);
            if(!$questionid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        $this->assign('type', $type);
        return view(__FUNCTION__);
    }

    /*
     * 多选题修改
     */
    public function multipleEdit()
    {
        $questionid = input('param.questionid/d');
        if(empty($questionid)){
            return $this->error("请传入试题id");
        }
        $question = $this->questionModel->get($questionid);
        if(empty($question)){
            return $this->error("未检索到试题");
        }
        $type = $this->typeModel->where("siteid = " . $this->siteid . " and typeid = " . $question['typeid'])->find();
        if(empty($type)){
            return $this->error("未检索到试题类型");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.');
            //校验参数
            $rs = $this->multipleCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $info = $rs['data']['info'];
            //执行修改
            $rs = $this->questionModel->where("questionid = {$questionid}")->update($info);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $this->assign('type', $type);
        $this->assign('question', $question);
        return view(__FUNCTION__);
    }


    /*
     * 判断题校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function tfngCheck($data, $opType = false){
        if($opType){
            if(empty($data['questionid'])){
                return error("请传入题目id");
            }
        }
        if(empty($data['topic'])){
            return error("请填写题目");
        }
        if(empty($data['score'])){
            return error("请填写分数");
        }
        if(empty($data['option'])){
            return error("请添加选项");
        }
        $options = $data['option'];
        if(count($options) != 2){
            return error("选项数量必须是2个");
        }
        foreach($options as $k => $v){
            if(empty($v)){
                return error("请填写第".$k."个选项名称");
            }
        }
        if (count($options) != count(array_unique($data['option']))) {
            return error("选项名称不可重复");
        }
        if(empty($data['answer'])){
            return error("请选择一个答案");
        }
        $info = array(
            'topic'        => $data['topic'],
            'answer'       => $data['answer'],
            'score'        => $data['score'],
            'option'       => json_encode($options)
        );
        return success(array('info' => $info));
    }

    /*
     * 判断题添加
     */
    public function tfngAdd(){
        $typeid = input('param.typeid/d',0);
        $type = $this->typeModel->where("siteid = " . $this->siteid . " and typeid = " . $typeid)->find();
        if(empty($type)){
            return $this->error("未检索到试题类型");
        }
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.');
            //校验参数
            $rs = $this->tfngCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $info = $rs['data']['info'];
            $info['siteid']       = $type['siteid'];
            $info['typeid']       = $type['typeid'];
            $info['questiontype'] = 3;
            //执行添加
            $questionid = $this->questionModel->insertGetId($info);
            if(!$questionid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        $this->assign('type', $type);
        return view(__FUNCTION__);
    }

    /*
     * 判断题修改
     */
    public function tfngEdit()
    {
        $questionid = input('param.questionid/d');
        if(empty($questionid)){
            return $this->error("请传入试题id");
        }
        $question = $this->questionModel->get($questionid);
        if(empty($question)){
            return $this->error("未检索到试题");
        }
        $type = $this->typeModel->where("siteid = " . $this->siteid . " and typeid = " . $question['typeid'])->find();
        if(empty($type)){
            return $this->error("未检索到试题类型");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.');
            //校验参数
            $rs = $this->tfngCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $info = $rs['data']['info'];
            //执行修改
            $rs = $this->questionModel->where("questionid = {$questionid}")->update($info);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $this->assign('type', $type);
        $this->assign('question', $question);
        return view(__FUNCTION__);
    }


    /*
     * 试题排序
     */
    public function questionSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的试题');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->questionModel->where("questionid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }

    /*
     * 随机卷列表
     */
    public function randompaperList()
    {
        $randompapers = $this->randompaperModel->where("siteid = " . $this->siteid)->order('listorder asc')->select();
        foreach($randompapers as & $v){
            $v['type'] = $this->typeModel->get($v['typeid']);
        }
        //构造返回
        $this->assign('randompapers', $randompapers);
        return view(__FUNCTION__);
    }

    /*
     * 随机卷排序
     */
    public function randompaperSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的测试卷');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->randompaperModel->where("randompaperid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }


    /*
     * 随机试卷生成
     */
    public function randompaperCreate(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            if(empty($data['randompapername'])){
                return $this->error("请填写试卷名称");
            }
            if(empty($data['typeid'])){
                return $this->error("请选择题库类型");
            }
            if(empty($data['minute'])){
                return $this->error("请填写考试时长");
            }
            if(empty($data['totaltopic'])){
                return $this->error("请填写生成的试题数量");
            }
            $topticCout = $this->questionModel->where("typeid = " . $data['typeid'])->count();
            if($topticCout < $data['totaltopic']){
                return $this->error("试题数量不够，此类型下共有试题".$topticCout."个");
            }
            //查询试题
            $topics = $this->questionModel->where("typeid = " . $data['typeid'])->order('rand()')->limit($data['totaltopic'])->select();
            $data['totalscore'] = 0;
            $data['topics'] = array();
            foreach($topics as $k => $v){
                $data['totalscore'] += $v['score'];
                $data['topics'][$k+1] = array(
                    'id'        => $v['questionid'],
                    'type'      => $v['questiontype'],
                    'topic'     => $v['topic'],
                    'listorder' => $v['listorder'],
                    'option'    => json_decode($v['option'],true),
                    'answer'    => $v['answer'],
                    'score'     => $v['score']
                );
            }
            $data['topics'] = json_encode($data['topics']);
            //执行添加
            $data['siteid'] = $this->siteid;
            $randompaperid = $this->randompaperModel->insertGetId($data);
            if(!$randompaperid){
                return $this->error("生成失败");
            }
            return $this->success('生成成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 试卷列表
     */
    public function paperList()
    {
        $papers = $this->paperModel->where("siteid = " . $this->siteid)->order('listorder asc')->select();
        foreach($papers as & $v){
            $v['type'] = $this->typeModel->get($v['typeid']);
        }
        //构造返回
        $this->assign('papers', $papers);
        return view(__FUNCTION__);
    }

    /*
     * 试卷排序
     */
    public function paperSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的试卷');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->paperModel->where("paperid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }


    /*
     * 试卷校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function paperCheck($data, $opType = false){
        if(empty($data['papername'])){
            return error("请填写试卷名称");
        }
        if(empty($data['typeid'])){
            return error("请选择试卷类型");
        }
        if(empty($data['questionids'])){
            return error("请选择试题");
        }
        if(empty($data['minute'])){
            return error("请填写考试时长");
        }
        //查询试题
        $ids = implode(',', $data['questionids']);
        $topics = $this->questionModel->where("siteid = ".$this->siteid." and typeid = " . $data['typeid'] . " and questionid in(".$ids.")")->order('listorder asc')->select();
        $data['totalscore'] = 0;
        $data['topics'] = array();
        foreach($topics as $k => $v){
            $data['totalscore'] += $v['score'];
            $data['topics'][$k+1] = array(
                'id'        => $v['questionid'],
                'type'      => $v['questiontype'],
                'topic'     => $v['topic'],
                'listorder' => $v['listorder'],
                'option'    => json_decode($v['option'],true),
                'answer'    => $v['answer'],
                'score'     => $v['score']
            );
        }
        $info = array(
            'siteid'     => $this->siteid,
            'papername'  => $data['papername'],
            'typeid'     => $data['typeid'],
            'minute'     => $data['minute'],
            'listorder'  => $data['listorder'],
            'totalscore' => $data['totalscore'],
            'totaltopic' => count($data['topics']),
            'topics'     => json_encode($data['topics'])
        );
        return success(array('info' => $info));
    }

    /*
     * 试卷添加
     */
    public function paperAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->paperCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $info = $rs['data']['info'];
            //执行添加
            $paperid = $this->paperModel->insertGetId($info);
            if(!$paperid){
                return $this->error("生成失败");
            }
            return $this->success('生成成功');
        }
        return view(__FUNCTION__);
    }


    /*
     * 试卷修改
     */
    public function paperEdit()
    {
        $paperid = input('param.paperid/d');
        if(empty($paperid)){
            return $this->error("请传入试卷id");
        }
        $paper = $this->paperModel->where("siteid = " . $this->siteid . " and paperid = " . $paperid)->find();
        if(empty($paper)){
            return $this->error("未检索到试卷");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->paperCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $info = $rs['data']['info'];
            //执行修改
            $rs = $this->paperModel->where("paperid = {$paperid}")->update($info);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $this->assign('paper', $paper);
        return view(__FUNCTION__);
    }



    /*
     * 获取试题
     */
    public function getQuestion(){
        $typeid = input('post.typeid/d');
        $ids = input('post.ids/s');
        $questions = $this->questionModel->where("siteid = " . $this->siteid . " and typeid = " . $typeid)->order("listorder asc")->select();
        if(empty($questions)){
            return "此类型下没有试题";
        }
        if($ids){
            $ids = explode(',', $ids);
        }else{
            $ids = array();
        }
        $this->assign('questions', $questions);
        $this->assign('ids', $ids);
        return view(__FUNCTION__);
    }


    /*
     * 用户考卷列表
     */
    public function userPaperList()
    {
        //获取参数
        $limitRows = input('param.pagesize/d',10);
        $search = input('param.search/a');
        //构造条件
        $where = array();
        $where[] = "siteid = " . $this->siteid;
        if(!empty($search)){
            foreach($search as $k => & $v){
                $v = trim($v);
                if($v != ''){
                    if(in_array($k, array('papername','typename'))){
                        $where[] = "`$k` like '%{$v}%'";
                    }else{
                        $where[] = "$k = '{$v}'";
                    }
                }
            }
        }
        $where = implode(' AND ', $where);
        //构造数据
        $count = $this->userpaperModel->where($where)->count();
        $page = new \com\Ajaxpage($count, $limitRows, "TableList.changePage");
        $userpapers = $this->userpaperModel->where($where)->order("userpaperid desc")->limit($page->firstRow, $page->listRows)->select();
        //构造返回
        $this->assign("page", $page);
        $this->assign('search', $search);
        $this->assign('userpapers', $userpapers);
        return view(__FUNCTION__);
    }
}
