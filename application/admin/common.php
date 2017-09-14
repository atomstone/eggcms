<?php


function error($msg = '', $data = ''){
    $rs = array(
        'status' => false,
        'msg'    => $msg,
        'data'   => $data
    );
    return $rs;
}

function success($data = '',$msg = ''){
    $rs = array(
        'status' => true,
        'msg'    => $msg,
        'data'   => $data
    );
    return $rs;
}

/**
 * 创建目录
 * @param string $path 目录
 * @param string $dir  定义记录目录结构的临时变量
 */
function xmkdir($dir, $mode = 0777){
    if(!is_dir($dir)) {
        xmkdir(dirname($dir));
        @mkdir($dir, $mode);
        @touch($dir.'/index.html'); @chmod($dir.'/index.html', 0777);
    }
    return true;
}

/**
 * @ $fun: 获取客户端IP
 * @ $Author: sunlei  孙磊 13260022690
 * @ $Date: 2015-09-30
 **/
function get_real_ip(){
    $ip=false;
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!preg_match ("/^(10|172.16|192.168)./i", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/**
 * 简单对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 */
function encode($string = '', $skey = 'yourkey') {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}
/**
 * 简单对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 */
function decode($string = '', $skey = 'yourkey') {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}

