<?php
return array(
	//'配置项'=>'配置值'
    //主题静态文件路径
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__.'/Application/'.MODULE_NAME.'/View/' . '/Public/static',),
	'show_page_trace'=>false,	
	'STATIC' => __ROOT__.'/Application/'.MODULE_NAME.'/View/' . '/Public/static/',	
	'APPID' => '',  
	'APPKEY' => '',
	'APISECRET' => '', //api秘钥
	'KEY' => '',
	'mch_id' => '', //商户号
	'auth_mchid' => '' , 
	'auth_appid' => '',
	'cert_path' => __ROOT__.'/cert/', //支付证书路径
	'DB_PREFIX' =>  'tp_' 
	
);
