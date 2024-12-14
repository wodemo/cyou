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

function cl_search_hashtags($keyword = "", $offset = false, $limit = 30) {
	global $db;

    $data    = array();
    $db      = $db->where('posts', '0', '>');
    $db      = $db->orderBy('posts','DESC');
    $db      = $db->orderBy('time','DESC');
    $keyword = ltrim($keyword,'#');
    $db      = (not_empty($keyword)) ? $db->where('tag', "%{$keyword}%", 'LIKE') : $db;

    if (is_posnum($offset)) {
        $tags = $db->get(T_HTAGS, array($offset, $limit));
    }
    else{
        $tags = $db->get(T_HTAGS, $limit);
    }
    

    if (cl_queryset($tags)) {
    	foreach ($tags as $tag_data) {
            $tag_data['tag']     = cl_rn_strip($tag_data['tag']);
    		$tag_data['hashtag'] = cl_strf("#%s", $tag_data['tag']);
    		$tag_data['url']     = cl_link(cl_strf("explore/posts?q=%s", cl_remove_emoji($tag_data['tag'])));
    		$tag_data['total']   = cl_number($tag_data['posts']);
    		$data[]              = $tag_data;
    	}
    }
    
    return $data;
}

function cl_search_people($keyword = "", $offset = false, $limit = 30) {
    global $db, $cl, $me;

    $data          = array();
    $user_id       = ((not_empty($cl['is_logged'])) ? $me['id'] : false);
    $sql           = cl_sqltepmlate('apps/explore/sql/fetch_people',array(
        't_users'  => T_USERS,
        't_blocks' => T_BLOCKS,
        'user_id'  => $user_id,
        'limit'    => $limit,
        'offset'   => $offset,
        'keyword'  => $keyword
    ));

    $query_result = $db->rawQuery($sql);

    if (cl_queryset($query_result)) {
        foreach ($query_result as $row) {
            $row['about']            = cl_rn_strip($row['about']);
            $row['about']            = stripslashes($row['about']);
            $row['name']             = cl_strf("%s %s",$row['fname'],$row['lname']);      
            $row['avatar']           = cl_get_media($row['avatar']);
            $row['url']              = cl_link($row['username']);
            $row['is_user']          = false;
            $row['is_following']     = false;
            $row['follow_requested'] = false;
            $row['country_a2c']      = fetch_or_get($cl['country_codes'][$row['country_id']], 'us');
            $row['country_name']     = cl_translate($cl['countries'][$row['country_id']], 'Unknown');

            if (not_empty($user_id)) {
            	$row['is_user']      = ($user_id == $row['id']);
            	$row['is_following'] = cl_is_following($user_id, $row['id']);

                if (empty($row['is_following'])) {
                    $row['follow_requested'] = cl_follow_requested($user_id, $row['id']);
                }
            }

            $row['about'] = cl_linkify_urls($row['about']);
            $data[]       = $row;
        }
    }

    return $data;
}

function cl_search_posts($keyword = "", $offset = false, $limit = 10) {
	global $db,$cl,$me;

	$user_id        = ((not_empty($cl['is_logged'])) ? $me['id'] : false);
	$data           = array();
    $htag           = ((not_empty($keyword)) ? cl_get_htag_id($keyword) : false);
	$sql            = cl_sqltepmlate("apps/explore/sql/fetch_posts",array(
		"t_pubs"    => T_PUBS,
        "t_blocks"  => T_BLOCKS,
        "t_conns"   => T_CONNECTIONS,
        't_reports' => T_PUB_REPORTS,
		"keyword"   => $keyword,
        "user_id"   => $user_id,
        "htag"      => $htag,
		"offset"    => $offset,
		"limit"     => $limit
 	));

	$query_res = $db->rawQuery($sql);
    $counter   = 0;

	if (cl_queryset($query_res)) {
		foreach ($query_res as $row) {
			$data[] = cl_post_data($row);

            if ($cl['config']['advertising_system'] == 'on') {
                if (cl_is_feed_nad_allowed()) {
                    if (not_empty($offset)) {
                        if ($counter == 5) {
                            $counter = 0;
                            $ad      = cl_get_timeline_ads();

                            if (not_empty($ad)) {
                                $data[] = $ad;
                            }
                        }
                        else {
                            $counter += 1;
                        }
                    }
                }
            }
		}

        if ($cl['config']['advertising_system'] == 'on') {
            if (cl_is_feed_nad_allowed()) {
                if (empty($offset)) {
                    $ad = cl_get_timeline_ads();

                    if (not_empty($ad)) {
                        $data[] = $ad;
                    }
                }
            }
        }
	}

	return $data;
}

function cl_get_admin_pinned_post() {
    global $db;

    $db = $db->where("admin_pinned", "Y");
    $qr = $db->getOne(T_PUBS);

    if (cl_queryset($qr)) {
        return cl_post_data($qr);
    }

    else{
        return false;
    }
}