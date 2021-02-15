function bgc_like_btn_ajax(postID, userId){
    var post_id = postID;
    var user_id = userId;

    jQuery.ajax({
            url: bgc_ajax_url.ajax_url,
            type: 'post',
            dataType: 'JSON',
            data: {
                action: 'bgc_like_btn_ajax_action',
                pid: post_id,
                uid: user_id,
            },
        success: function (response){

           jQuery('#lcount').html(response.lcount);
           jQuery('#dcount').html(response.dcount);
           jQuery('#bgc_response_wrapper span').html(response.msg);
        }
    });
}

function bgc_dislike_btn_ajax(postID, userID){
    var post_id = postID;
    var user_id = userID;
    jQuery.ajax({
        url: bgc_ajax_url.ajax_url,
        type: 'post',
        dataType: 'JSON',
        data: {
            action: 'bgc_dislike_btn_ajax_action',
            pid: post_id,
            uid: user_id,
        },
        success: function (response){

            jQuery('#lcount').html(response.lcount);
            jQuery('#dcount').html(response.dcount);
            jQuery('#bgc_response_wrapper span').html(response.msg);
        }
   });
}