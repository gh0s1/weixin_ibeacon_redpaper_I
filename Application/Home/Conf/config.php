<?php
return array(
	//'配置项'=>'配置值'
    //主题静态文件路径
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__.'/Application/'.MODULE_NAME.'/View/' . '/Public/static',),
	    //CSRF
    'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
    //是否开启模板布局 根据个人习惯设置
    'LAYOUT_ON'=>false,
	
	'show_page_trace'=>false,	
	'STATIC' => __ROOT__.'/Application/'.MODULE_NAME.'/View/' . '/Public/static/',	
	
	
	'TOKEN' => 'weixin', //Token令牌
	'ext_path' => __ROOT__.'/Application/'.MODULE_NAME.'/Org/',
	'APPID' => '',  
	'APPKEY' => '',
	'APISECRET' => '', //api秘钥
	'KEY' =>'',
	'mch_id' => '', //商户号
	'auth_mchid' => '' , 
	'auth_appid' => '',
	'cert_path' => __ROOT__.'/cert/', //支付证书路径
	'DB_PREFIX'  =>  'tp_'
	
);
