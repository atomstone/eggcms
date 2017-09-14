<?php
namespace app\index\controller;
class Exam extends Base
{

    private $questionModel;
    private $typeModel;
    private $randompaperModel;
    private $paperModel;
    private $userpaperModel;

    public function __construct(){
        parent::__construct();
        //判断是否登录
        if(empty($this->memberinfo)){
            cookie('refurl_log', '/'.Request()->pathinfo());
            $this->redirect('Index/reg');
        }
        $this->questionModel = model('ExamQuestion');
        $this->typeModel = model('ExamType');
        $this->randompaperModel = model('ExamRandompaper');
        $this->paperModel = model('ExamPaper');
        $this->userpaperModel = model('ExamUserpaper');
    }

    /*
     * 考试首页
     */
    public function index()
    {
        //$this->assign("cats", $cats);
        $view = 'site_'.$this->siteid.'/exam/index';
        return view($view);
    }

    /*
     * 随机测试分类页
     */
    public function random_type_list()
    {
        $types = $this->typeModel->where("siteid = " . $this->siteid)->order("listorder asc")->select();
        $this->assign("types", $types);
        $view = 'site_'.$this->siteid.'/exam/' . __FUNCTION__;
        return view($view);
    }

    /*
     * 随机测试试卷列表页
     */
    public function random_paper_list()
    {
        //校验类型
        $typeid = input('param.typeid/d');
        if(empty($typeid)){
            return $this->error("请传入试题类型id");
        }
        $type = $this->typeModel->where("siteid = " . $this->siteid . " and typeid = " . $typeid)->find();
        if(empty($type)){
            return $this->error("未检索到试题类型");
        }
        //查找类型下的试卷
        $papers = $this->randompaperModel->where("typeid = " . $typeid)->order("listorder asc")->select();
        if(empty($papers)){
            return $this->error("此类型下未检索到试卷");
        }
        $this->assign("type", $type);
        $this->assign("papers", $papers);
        $view = 'site_'.$this->siteid.'/exam/' . __FUNCTION__;
        return view($view);
    }

    /*
     * 随机测试考试页面
     */
    public function random_exam()
    {
        $randompaperid = input('param.randompaperid/d');
        if(empty($randompaperid)){
            return $this->error("请传入试卷id");
        }
        $paper = $this->randompaperModel->where("siteid = " . $this->siteid . " and randompaperid = " . $randompaperid)->find();
        if(empty($paper)){
            return $this->error("未检索到试卷");
        }
        $this->assign("paper", $paper);
        $view = 'site_'.$this->siteid.'/exam/' . __FUNCTION__;
        return view($view);
    }


    /*
     * 试卷列表页
     */
    public function paper_list()
    {
        $papers = $this->paperModel->where("siteid = " . $this->siteid)->order("listorder asc")->select();
        if(empty($papers)){
            return $this->error("未检索到试卷", 'exam/index');
        }
        foreach($papers as & $v){
            $rs = $this->userpaperModel->where("paperid = " . $v['paperid'] . " and member_id = " . $this->memberinfo['member_id'])->find();
            if(!empty($rs)){
                $v['userpaper'] = $rs;
            }
        }
        $this->assign("papers", $papers);
        $view = 'site_'.$this->siteid.'/exam/' . __FUNCTION__;
        return view($view);
    }

    /*
     * 考试页面
     */
    public function paper_exam()
    {

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.');
            //重新构造试题json附加用户答案
            $paper = json_decode($data['paperinfo'],true);
            $topics = json_decode($paper['topics'],true);
            foreach($topics as $k => & $v){
                if(isset($data['answer'][$k])){
                    $v['myanswer'] = !empty($data['answer'][$k]) ? $data['answer'][$k] : 0;
                }
            }
            $info = array(
                'member_id'  => $this->memberinfo['member_id'],
                'member_truename'  => $this->memberinfo['member_truename'],
                'siteid'     => $paper['siteid'],
                'paperid'    => $paper['paperid'],
                'papername'  => $paper['papername'],
                'typeid'     => $paper['typeid'],
                'typename'   => $paper['typename'],
                'minute'     => $paper['minute'],
                'totalscore' => $paper['totalscore'],
                'totaltopic' => $paper['totaltopic'],
                'topics'     => json_encode($topics),
                'grade'      => $data['grade'],
                'rightnum'   => $data['rightnum'],
                'wrongnum'   => $data['wrongnum'],
                'created'    => time()
            );
            $userpaperid = $this->userpaperModel->insertGetId($info);
            if(!$userpaperid){
                return $this->error("提交试卷失败");
            }
            return $this->success('提交试卷成功','',$userpaperid);
        }

        $paperid = input('param.paperid/d');
        if(empty($paperid)){
            return $this->error("请传入试卷id");
        }
        $paper = $this->paperModel->where("siteid = " . $this->siteid . " and paperid = " . $paperid)->find();
        if(empty($paper)){
            return $this->error("未检索到试卷");
        }
        $rs = $this->userpaperModel->where("paperid = " . $paperid . " and member_id = " . $this->memberinfo['member_id'])->find();
        if(!empty($rs)){
            $this->redirect("exam/paper_myresult",array('userpaperid'=>$rs['userpaperid']));
        }
        $type = $this->typeModel->get($paper['typeid']);
        $paper['typename'] = $type['typename'];
        $this->assign("paper", $paper);
        $view = 'site_'.$this->siteid.'/exam/' . __FUNCTION__;
        return view($view);
    }

    /*
     * 查看我的考试结果
     */
    public function paper_myresult()
    {
        $userpaperid = input('param.userpaperid/d');
        if(empty($userpaperid)){
            return $this->error("请传入考卷id");
        }
        $userpaper = $this->userpaperModel->where("siteid = " . $this->siteid . " and userpaperid = " . $userpaperid . " and member_id = " . $this->memberinfo['member_id'] )->find();
        if(empty($userpaper)){
            return $this->error("未检索到考卷");
        }
        $this->assign("userpaper", $userpaper);
        $view = 'site_'.$this->siteid.'/exam/' . __FUNCTION__;
        return view($view);
    }

}
