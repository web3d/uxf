<?php

require dirname(__FILE__) . '/../core/base/CAutoloader.php';

CAutoloader::register();

$url_manager = new CUrlManager();
$url_manager->urlSuffix = '.html';
$url_manager->showScriptName = FALSE;
$url_manager->setUrlFormat(CUrlManager::PATH_FORMAT);

$url_manager->init();

$rules = array(
    'articles' => 'article/list',
    'article/<id:\d+>/*' => 'article/read',
);

$url_manager->addRules($rules);

$_aurl = $url_manager->createUrl('article/read', array('id' => 11));
echo $_aurl;

$_aurl = $url_manager->createUrl('article/list');
echo $_aurl;

$_aurl = $url_manager->createUrl('user/login', array('ref' => 'http://www.chaoma.me'));
echo $_aurl;

$req_route = $url_manager->parseUrl();
var_dump($req_route);
$url_manager->parsePathInfo($req_route);
//var_dump($_GET);
var_dump($_REQUEST);



//根据用户请求调度控制器请参看 yii CWebApplication::runController方法