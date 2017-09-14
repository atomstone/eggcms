<?php
//配置文件

return [
    //本项目的配置
    "web_name" => "嘉运达",
    "web_res_root"   => "/static/admin/assets/",
    //数据模板位置
    "path_datatemplate" => "../application/admin/datatemplate/",

    'session' => array(
        'prefix' => 'admin',
        'type' => '',
        'auto_start' => true,
    ),

    "category" => array(
        "type" => array(
            0 => "栏目",
            1 => "单网页",
            2 => "外部链接"
        ),
    ),

    "field" => array(

        "type" => array('text'=>'单行文本',
                        'textarea'=>'多行文本',
                        'editor'=>'编辑器',
                        'box'=>'选项',
                        'image'=>'图片',
                        'images'=>'多图片',
                        'number'=>'数字',
                        'video'=>'视频',
                        'date'=>'日期',
                        'datetime'=>'日期和时间',
                        'downonefile'=>'单文件上传',
                        'downfiles'=>'多文件上传',
                        'dataitem'=>'多数据组',
                    ),
        //不允许删除的字段，这些字段讲不会在字段添加处显示
        //"not_allow_fields" => array('catid','typeid','title','keyword','posid','template','username'),
        //允许添加但必须唯一的字段
        //"unique_fields" => array('pages','readpoint','author','copyfrom','islink'),
        //必须在列表页显示的字段
        "nustshow_islist_fields" => array('title','updatetime'),
        //禁止被禁用的字段列表
        "forbid_disabled_fields" => array('catid','title','updatetime'),
        //禁止被删除的字段列表
        //"forbid_delete" => array('catid','typeid','title','thumb','keywords','updatetime','inputtime','posids','url','listorder','status','template','username'),


    ),


    "position" => array(
        "enable" => array(
            1 => '启用',
            -1 => '禁用',
        ),
    ),

    "team" => array(
        "articleAudit" => array(
            0 => '正常',
            1 => '待支队审核',
            2 => '支队审核未通过',
            3 => '支队审核通过',
            4 => '待总队审核',
            5 => '总队审核未通过',
            6 => '总队审核通过',
        ),
    ),

    "admin" => array(
        "enable" => array(
            1 => '启用',
            -1 => '禁用',
        ),
    ),

    "menu" => array(
        "target" => array(
            '_self'  => '本窗口',
            '_blank' => '新窗口',
        ),
        "linkstype" => array(
            1 => '无连接',
            2 => '超链接',
            3 => '栏目链接',
        ),

    ),

    "dept" => array(
        "type" => array(
            1  => '支队',
            2  => '中队',
            3  => '其他',
        ),
    ),

    "exam" => array(
        "questionType" => array(
            1 => '单选',
            2 => '多选',
            3 => '判断',
        ),
    ),

    "special" => array(
        "elite" => array(
            1 => '推荐',
            0 => '不推荐',
        ),
        "disabled" => array(
            0 => '启用',
            1 => '禁用',
        ),
    ),

    "cookie" => array(
        'prefix' => 'admin_',
        'expire' => 3600*24
    ),

    "ad" => array(
        "space_type" => array(
            'code'        => '代码广告',
            'imagechange' => '图片轮换',
        ),
        "space_enable" => array(
            1  => '启用',
            -1 => '禁用',
        ),
        "ad_enable" => array(
            1  => '启用',
            -1 => '禁用',
        ),
        "myfoucs_pattern" => array(
            'mF_fscreen_tb'  => 'mF_fscreen_tb',
            'mF_YSlider'     => 'mF_YSlider',
            'mF_luluJQ'      => 'mF_luluJQ',
            'mF_51xflash'    => 'mF_51xflash',
            'mF_expo2010'    => 'mF_expo2010',
            'mF_games_tb'    => 'mF_games_tb',
            'mF_ladyQ'       => 'mF_ladyQ',
            'mF_liquid'      => 'mF_liquid',
            'mF_liuzg'       => 'mF_liuzg',
            'mF_pithy_tb'    => 'mF_pithy_tb',
            'mF_qiyi'        => 'mF_qiyi',
            'mF_quwan'       => 'mF_quwan',
            'mF_rapoo'       => 'mF_rapoo',
            'mF_sohusports'  => 'mF_sohusports',
            'mF_taobao2010'  => 'mF_taobao2010',
            'mF_taobaomall'  => 'mF_taobaomall',
            'mF_tbhuabao'    => 'mF_tbhuabao',
            'mF_pconline'    => 'mF_pconline',
            'mF_peijianmall' => 'mF_peijianmall',
            'mF_classicHC'   => 'mF_classicHC',
            'mF_classicHB'   => 'mF_classicHB',
            'mF_slide3D'     => 'mF_slide3D',
            'mF_kiki'        => 'mF_kiki',
            'mF_fancy'       => 'mF_fancy',
            'mF_dleung'      => 'mF_dleung',
            'mF_kdui'        => 'mF_kdui',
            'mF_shutters'    => 'mF_shutters',
        ),
        "myfoucs_trigger" => array(
            'click'     => '鼠标点击',
            'mouseover' => '鼠标悬停'
        ),
        "myfoucs_wrap" => array(
            'true'  => '是',
            'false' => '否'
        ),

    ),

];