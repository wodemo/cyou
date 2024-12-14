<?php 
# @*************************************************************************@
# @ Software author: Mansur Terla (Mansur_TL)                               @
# @ UI/UX Designer & Web developer ;)                                       @
# @                                                                         @
# @*************************************************************************@
# @ Instagram: https://www.instagram.com/mansur_tl                          @
# @ VK: https://vk.com/mansur_tl_uiux                                       @
# @ Envato: http://codecanyon.net/user/mansur_tl                            @
# @ Behance: https://www.behance.net/mansur_tl                              @
# @ Telegram: https://t.me/mansurtl_contact                                 @
# @*************************************************************************@
# @ E-mail: mansurtl.contact@gmail.com                                      @
# @ Website: https://www.mansurtl.com                                       @
# @*************************************************************************@
# @ ColibriSM - The Ultimate Social Network PHP Script                      @
# @ Copyright (c)  ColibriSM. All rights reserved                           @
# @*************************************************************************@

require_once("core/web_req_init.php");

$app_name = (isset($_GET["app"])) ? $_GET["app"] : "home";
$app_stat = fetch_or_get($applications[$app_name], false);
$spa_load = fetch_or_get($_GET['spa_load'], '0');
$spa_data = array();
$site_url = parse_url($site_url);

$cl["curr_url"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (is_array($site_url)) {
	if ($site_url['host'] != fetch_or_get($_SERVER['HTTP_HOST'], 'none')) {
		cl_redirect("/");
	}
}

if ($spa_load != '1') {
	require_once("core/components/mw/http_request_mw.php");
}

if ($app_stat == true) {
	include_once(cl_strf("apps/native/http/%s/content.php",$app_name));

	if (empty($cl["http_res"])) {
		include_once("apps/native/http/err404/content.php");
	}
} 

else {
	include_once("apps/native/http/err404/content.php");
}


$json_data = array(
	"page_title" => $cl["page_title"],
	"pn"         => $cl["pn"],
	"page_xdata" => fetch_or_get($cl["page_xdata"], array()),
	"page_tab"   => fetch_or_get($cl["page_tab"], "none")
);

if ($spa_load == '1') {

	header('Content-Type: application/json');

	$spa_data['status'] = 200;
	$spa_data['html']   = $cl["http_res"];
	$spa_data['json_data'] = $json_data;

	echo json_encode($spa_data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	mysqli_close($mysqli);
	unset($cl);
	exit();
}

else {

	$cl['json_data'] = $json_data;
	$http_res = cl_template("main/content");

	echo $http_res;
	mysqli_close($mysqli);
	unset($cl);
}
