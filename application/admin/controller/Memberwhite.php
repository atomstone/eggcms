<?php
namespace app\admin\controller;

class Memberwhite extends Base
{

    private $whiteModel;

    public function __construct(){
        parent::__construct();
        $this->whiteModel = model('MemberWhite');
    }

    /*
     * 白名单列表
     */
    public function whiteList()
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
                    if(in_array($k, array('white_truename','white_mobile'))){
                        $where[] = "`$k` like '%{$v}%'";
                    }else{
                        $where[] = "$k = '{$v}'";
                    }
                }
            }
        }
        $where = implode(' AND ', $where);
        //构造数据
        $count = $this->whiteModel->where($where)->count();
        $page = new \com\Ajaxpage($count, $limitRows, "TableList.changePage");
        $whites = $this->whiteModel->where($where)->order("white_id desc")->limit($page->firstRow, $page->listRows)->select();
        //构造返回
        $this->assign("page", $page);
        $this->assign('whites', $whites);
        $this->assign('search', $search);
        return view(__FUNCTION__);
    }

    /*
     * 白名单校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function whiteCheck($data, $opType = false){
        if(empty($data['white_truename'])){
            return error("请填写姓名");
        }
        if(empty($data['white_mobile'])){
            return error("请填写手机号");
        }
        if(!preg_match("/^[0-9]+$/",$data['white_mobile'])){
            return error("手机号只能包含数字");
        }
        if(strlen($data['white_mobile']) != 11){
            return error("手机号长度错误 ");
        }
        if($opType){
            if(empty($data['white_id'])){
                return error("请传入白名单id");
            }
            $rs = $this->whiteModel->where("white_mobile = '".$data['white_mobile']."' and white_id <> " . $data['white_id'])->find();
            if(!empty($rs)){
                return error("此手机号已存在");
            }
        }else{
            $rs = $this->whiteModel->where("white_mobile = '".$data['white_mobile']."'")->find();
            if(!empty($rs)){
                return error("此手机号已存在");
            }
        }
        return success();
    }

    /*
     * 白名单添加
     */
    public function whiteAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->whiteCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data['white_addtime'] = time();
            $data['white_lasttime'] = time();
            //执行添加
            $white_id = $this->whiteModel->insertGetId($data);
            if(!$white_id){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 白名单修改
     */
    public function whiteEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->whiteCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data['white_lasttime'] = time();
            //执行修改
            $rs = $this->whiteModel->where("white_id = {$data['white_id']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $white_id = input('param.white_id/d');
        if(empty($white_id)){
            return $this->errorPage("请传入白名单id");
        }
        $white = $this->whiteModel->get($white_id);
        if(empty($white)){
            return $this->errorPage("未检索到白名单数据");
        }
        $this->assign('white', $white);
        return view(__FUNCTION__);
    }


    /*
     * 白名单删除
     */
    public function whiteDel()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $white_id = input('post.white_id/d');
            //执行删除
            $rs = $this->whiteModel->where("white_id = {$white_id}")->delete();
            if(!$rs){
                return $this->error("删除失败");
            }
            return $this->success('删除成功');
        }
    }


    /*
    * 导入白名单
    */
    public function whiteImport(){
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
                $data['white_truename'] = input('post.white_truename/s');
                $data['white_mobile'] = input('post.white_mobile/s');
                //校验参数
                $rs = $this->whiteCheck($data);
                if(!$rs['status']){
                    return $this->error($rs['msg']);
                }
                $data['white_addtime'] = time();
                $data['white_lasttime'] = time();
                //执行添加
                $white_id = $this->whiteModel->insertGetId($data);
                if(!$white_id){
                    return $this->error("添加失败");
                }
                return $this->success('添加成功');
            }
        }
        return view(__FUNCTION__);
    }

}
