<?php
//create like dislike button

function bgc_like_dislike_button($content){
    $like_btn_label = get_option('bgc_like_label','Like');
    $dislike_btn_label = get_option('bgc_dislike_label','Dislike');
    $user_id = get_current_user_id();
    $post_id = get_the_ID();
    $bgc_button_wrapper = '<div class="bgc_butttons_wrapper">';
    $like_btn = '<a href="javascript:;" onclick="bgc_like_btn_ajax('.$post_id.','.$user_id.')" class="bgc-btn bgc-like"><i class="fa fa-thumbs-up" aria-hidden="true"></i>
'.$like_btn_label.'</a>';
    $dislike_btn = '<a href="javascript:;" onclick="bgc_dislike_btn_ajax('.$post_id.','.$user_id.')" class="bgc-btn bgc-dislike"><i class="fa fa-thumbs-down" aria-hidden="true"></i>
'.$dislike_btn_label.'</a>';
    $bgc_button_wrapper_end = '</div>';
    $bgc_response_wrapper = '<div id="bgc_response_wrapper" class="bgc_response_wrapper"><span></span></div>';
    $content .= $bgc_button_wrapper;
    $content .=$like_btn;
    $content .= $dislike_btn;
    $content .=$bgc_button_wrapper_end;
    $content .= $bgc_response_wrapper;
    return $content;
}

add_filter('the_content','bgc_like_dislike_button');