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

require_once(cl_full_path("core/apps/profile/app_ctrl.php"));

if (empty($_GET["uname"])) {
	require_once cl_full_path("apps/native/http/err404/content.php");
}
else{
	$uname           = fetch_or_get($_GET["uname"], false);
	$uname           = cl_text_secure($uname);
	$cl['prof_user'] = cl_get_user_by_name($uname);
	$cl['page_tab']  = fetch_or_get($_GET["tab"], "followers");

	if (empty($cl['prof_user'])) {
		require_once cl_full_path("apps/native/http/err404/content.php");
	}

	else {

		$cl['can_view']   = cl_can_view_profile($cl['prof_user']['id']);
		$cl["page_title"] = $cl['prof_user']['name'];
		$cl["page_desc"]  = $cl['prof_user']['about'];
		$cl["page_kw"]    = $cl["config"]["keywords"];
		$cl["pn"]         = "connections";
		$cl["sbr"]        = true;
		$cl["sbl"]        = true;
		$cl["users_list"] = array();

		if (not_empty($cl["is_logged"])) {
			$cl['prof_user']['owner']           = ($cl['prof_user']['id'] == $me['id']);
			$cl['prof_user']['follow_requests'] = cl_get_follow_requests_total();
		}

		if (not_empty($cl['can_view'])) {
			if ($cl['page_tab'] == 'followers') {
				$cl["users_list"] = cl_get_followers($cl['prof_user']['id'], 30, false);
			}

			else if ($cl['page_tab'] == 'follow_requests') {
				if (not_empty($cl['prof_user']['owner'])) {
					$cl["users_list"] = cl_get_follow_requests(30, false);
				}

				else{
					cl_redirect("404");
				}
			}

			else {
				$cl["users_list"] = cl_get_followings($cl['prof_user']['id'], 30, false);
			}
		}
		else {
			$cl['prof_user']['followers'] = 0;
			$cl['prof_user']['following'] = 0;
		}

		$cl["http_res"] = cl_template("connections/content");
	}
}