<?php
//配置文件

return [
    //资源文件位置
    "res_site_1"   => "/static/site_1",
    "res_web"   => "/static/web",
    "patch_public_js"   => "/static/web/public/js",
    //comment 提交留言密钥
    'secretkey' => "atomstone",
    //字母数字对照
    'letter' => array(
       1  => 'A',
       2  => 'B',
       3  => 'C',
       4  => 'D',
       5  => 'E',
       6  => 'F',
       7  => 'G',
       8  => 'H',
       9  => 'I',
       10 => 'J',
       11 => 'K',
       12 => 'L',
       13 => 'M',
       14 => 'N',
       15 => 'O',
       16 => 'P',
       17 => 'Q',
       18 => 'R',
       19 => 'S',
       20 => 'T',
       21 => 'U',
       22 => 'V',
       23 => 'W',
       24 => 'X',
       25 => 'Y',
       26 => 'Z'
    ),
    //开启咨询的栏目
    'consult_catids' => array(30,31),

    "exam" => array(
        "questionType" => array(
            1 => '单选',
            2 => '多选',
            3 => '判断',
        ),
    ),

];