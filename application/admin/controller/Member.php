<?php
namespace app\admin\controller;

class Member extends Base
{

    private $memberModel;
    private $deptModel;

    public function __construct(){
        parent::__construct();
        $this->memberModel = model('Member');
        $this->deptModel = model('dept');
    }

    /*
     * 用户列表
     */
    public function memberList()
    {
        //获取参数
        $limitRows = input('param.pagesize/d',10);
        $search = input('param.search/a');
        //构造条件
        $where = array();
        $where[] = "1 = 1";
        if(!empty($search)){
            foreach($search as $k => & $v){
                $v = trim($v);
                if($v != ''){
                    if(in_array($k, array('member_truename','member_mobile'))){
                        $where[] = "`$k` like '%{$v}%'";
                    }elseif(in_array($k, array('deptid'))){
                        if(!empty($v)){
                            $where[] = "$k = {$v}";
                        }
                    }else{
                        $where[] = "$k = '{$v}'";
                    }
                }
            }
        }
        $where = implode(' AND ', $where);
        //构造数据
        $count = $this->memberModel->where($where)->count();
        $page = new \com\Ajaxpage($count, $limitRows, "TableList.changePage");
        $members = $this->memberModel->where($where)->order("member_id desc")->limit($page->firstRow, $page->listRows)->select();
        foreach($members as & $v){
            if(!empty($v['deptid'])){
                $v['dept'] = $this->deptModel->find($v['deptid']);
            }
        }
        //构造返回
        $this->assign("page", $page);
        $this->assign('members', $members);
        $this->assign('search', $search);
        return view(__FUNCTION__);
    }

    /*
     * 用户校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function memberCheck($data, $opType = false){
        if(empty($data['deptid'])){
            return error("请选择归属组织机构");
        }
        $dept = $this->deptModel->get($data['deptid']);
        if(empty($dept)){
            return $this->errorPage("未检索到组织机构");
        }
        if(empty($data['member_truename'])){
            return error("请填写姓名");
        }
        if(empty($data['member_mobile'])){
            return error("请填写手机号");
        }
        if(!preg_match("/^[0-9]+$/",$data['member_mobile'])){
            return error("手机号只能包含数字");
        }
        if(strlen($data['member_mobile']) != 11){
            return error("手机号长度错误 ");
        }
        if($opType){
            if(empty($data['member_id'])){
                return error("请传入用户id");
            }
            $rs = $this->memberModel->where("member_mobile = '".$data['member_mobile']."' and member_id <> " . $data['member_id'])->find();
            if(!empty($rs)){
                return error("此手机号已存在");
            }
        }else{
            $rs = $this->memberModel->where("member_mobile = '".$data['member_mobile']."'")->find();
            if(!empty($rs)){
                return error("此手机号已存在");
            }
        }
        return success();
    }

    /*
     * 用户添加
     */
    public function memberAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->memberCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data['member_addtime'] = time();
            $data['member_lasttime'] = time();
            //执行添加
            $member_id = $this->memberModel->insertGetId($data);
            if(!$member_id){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 用户修改
     */
    public function memberEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->memberCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data['member_lasttime'] = time();
            //执行修改
            $rs = $this->memberModel->where("member_id = {$data['member_id']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $member_id = input('param.member_id/d');
        if(empty($member_id)){
            return $this->errorPage("请传入用户id");
        }
        $member = $this->memberModel->get($member_id);
        if(empty($member)){
            return $this->errorPage("未检索到用户数据");
        }
        $this->assign('member', $member);
        return view(__FUNCTION__);
    }


    /*
     * 用户删除
     */
    public function memberDel()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $member_id = input('post.member_id/d');
            //执行删除
            $rs = $this->memberModel->where("member_id = {$member_id}")->delete();
            if(!$rs){
                return $this->error("删除失败");
            }
            return $this->success('删除成功');
        }
    }


    /*
    * 导入用户
    */
    public function memberImport(){
        if (request()->isPost() && request()->isAjax()){
            $type = input('post.type/s');
            if($type == 'loadexcel'){
                $excelpath = input('post.excelpath/s');
                Vendor('PHPExcel.PHPExcel');
                Vendor("PHPExcel.Reader.Excel2007");
                $objReader = new \PHPExcel_Reader_Excel2007();
                $objReader->setReadDataOnly ( true );
                $PHPReader = $objReader->load(ROOT_PATH."public".$excelpath);
                $sheet = $PHPReader->getSheet ( 0 ); // 获取第0个工作表的内容
                $numRows = $sheet->getHighestRow (); // 取得总行数
                $startRow = 1; //起始行
                $highestColumn = $sheet->getHighestColumn(); // 最高列数  A B C ..E
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);  //总列数
                $excelVals = array ();
                for ($row = $startRow; $row <= $numRows; $row++) {
                    $rowVals = array();
                    for ($col = 0; $col < $highestColumnIndex; $col++) {
                        $rowVals[] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                    }
                    $excelVals[] = $rowVals;
                }
                if (empty($excelVals)) {
                    return $this->error("数据为空");
                }else{
                    return $this->success('','',$excelVals);
                }
            }elseif($type == 'add'){
                //获取参数
                $data = array();
                $data['member_truename'] = input('post.member_truename/s');
                $data['member_mobile']   = input('post.member_mobile/s');
                $data['deptid']          = input('param.deptid/d',0);
                //校验参数
                $rs = $this->memberCheck($data);
                if(!$rs['status']){
                    return $this->error($rs['msg']);
                }
                $data['member_addtime'] = time();
                $data['member_lasttime'] = time();
                //执行添加
                $member_id = $this->memberModel->insertGetId($data);
                if(!$member_id){
                    return $this->error("导入失败");
                }
                return $this->success('导入成功');
            }
        }
        return view(__FUNCTION__);
    }

}
