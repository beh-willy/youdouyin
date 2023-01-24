<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        //'sign' => array('name' => 'sign', 'require' => true),
    ),
		'REDIS_HOST' => "127.0.0.1",
		'REDIS_AUTH' => "123321",
		'REDIS_PORT' => "6379",
		
	'uptype'=>3,//上传方式：1表示 七牛，2表示 本地,3表示 腾讯云
		/**
     * 七牛相关配置（改为了网站后台配置）
     */
    'Qiniu' =>  array(
        //统一的key
        'accessKey' => '',
        'secretKey' => '',
        //自定义配置的空间
        'space_bucket' => '',
        'space_host' => '',
    ),
		
		 /**
     * 本地上传
     */
    'UCloudEngine' => 'local',

    /**
     * 本地存储相关配置（UCloudEngine为local时的配置）
     */
    'UCloud' => array(
        //对应的文件路径
        'host' => 'http://mmlba.space/api/upload'
    ),
	/**
     * 腾讯云相关配置（改为了网站后台配置，此处废弃了）
     */
    'TxCloud' =>  array(
		'config'=>array(
			'app_id' => '',
			'secret_id' => '',
			'secret_key' => '',
			'region' => '',   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
			'timeout' => 6000
		),
		'bucketname'=>'',
		'folder'=>'/test1',
		    
    ),
	
		
		/**
     * 云上传引擎,支持local,oss,upyun
     */
    //'UCloudEngine' => 'oss',

    /**
     * 云上传对应引擎相关配置
     * 如果UCloudEngine不为local,则需要按以下配置
     */
   /*  'UCloud' => array(
        //上传的API地址,不带http://,以下api为阿里云OSS杭州节点
        'api' => 'oss-cn-hangzhou.aliyuncs.com',

        //统一的key
        'accessKey' => '',
        'secretKey' => '',

        //自定义配置的空间
        'bucket' => '',
        'host' => 'http://image.xxx.com', //必带http:// 末尾不带/

        'timeout' => 90
    ), */
		

		
);
