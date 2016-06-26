<?php
require_once "autoload.php";

use app\MobileQuery;

$phone = $_POST['phone'];
$ret = MobileQuery::query($phone);
if(is_array($ret) and isset($ret['province'])){
	$ret['phone'] = $phone;
	$ret['code'] = 200;
}else{
	$ret['code'] = 400;
	$ret['msg'] = '手机号码错误';
}
echo json_encode($ret);