<?php
/**
 * Plugin Name:       BGC Like Dislike
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Wordpress post like or dislike syste,
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Raaj
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bgc-like-dislike
 * Domain Path:       /languages
 */

 /*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

if(!defined('BGC_PLUGIN_PATH')){
    define('BGC_PLUGIN_PATH', plugin_dir_url( __FILE__ ));
}

if(!function_exists('bgc_plugin_scripts')){
    function bgc_plugin_scripts(){
        wp_enqueue_style( 'bgc-css', BGC_PLUGIN_PATH. 'assets/css/bgc-main-css.css');
        wp_enqueue_style( 'bgc-font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_script('bgc-js', BGC_PLUGIN_PATH. 'assets/js/main.js','jQuery','1.0.0', true);
        wp_enqueue_script('bgc-ajax-js', BGC_PLUGIN_PATH. 'assets/js/ajax.js','jQuery','1.0.0', true);
        wp_localize_script('bgc-ajax-js','bgc_ajax_url',array('ajax_url' => admin_url('admin-ajax.php')));
    }
    add_action( 'wp_enqueue_scripts', 'bgc_plugin_scripts' );
}
require plugin_dir_path(__FILE__).'inc/inc.settings.php';
require plugin_dir_path(__FILE__).'inc/inc.db.php';
require plugin_dir_path(__FILE__).'inc/inc.btns.php';

//Ajax requests
function bgc_like_btn_ajax_action(){
    global $wpdb;
    $table_name = $wpdb->prefix ."bgc_post_data";
    $userID = $_POST['uid'];
    $postID = $_POST['pid'];
    $setArray = array();
    if(isset($userID) && isset($postID)){
        $check_like = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $userID AND post_id = $postID AND like_count = 1");
        $dislike_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE  post_id = $postID AND dislike_count = 1");
        if($check_like > 0){
            echo "Sorry! You already liked this post";
        }else{
            $wpdb->insert($table_name,
                array('post_id' =>$_POST['pid'],'user_id' => $_POST['uid'], 'like_count' => 1),array('%d', '%d','%d')
                );
            if($wpdb->insert_id){
                $wpdb->update($table_name,
                    array('dislike_count' => 0), array('user_id' =>$userID, 'post_id' =>$postID)
                );
                if($check_like >= 0){
                    $lc = $check_like + 1;
                }
                if($dislike_count >0){
                    $dc = $dislike_count - 1;
                }else{
                    $dc = $dislike_count;
                }
                 $setArray = array('msg' => 'Thanks for liking this post','lcount' => $lc,'dcount' => $dc);
            }
            echo json_encode($setArray);
        }
    }
    wp_die();
}
add_action('wp_ajax_bgc_like_btn_ajax_action','bgc_like_btn_ajax_action');
add_action('wp_ajax_nopriv_bgc_like_btn_ajax_action','bgc_like_btn_ajax_action');

function bgc_show_like_count($content){
    global $wpdb;
    $postID = get_the_ID();
    $table_name = $wpdb->prefix ."bgc_post_data";
    $like_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE  post_id = $postID AND like_count = 1");
    $dislike_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE  post_id = $postID AND dislike_count = 1");

    $like_count_wrapper_start = '<div class="show_like_count"><span class="like_span"> <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
<span id="lcount">'.$like_count.'</span></span><span class="dislike_span"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
<span id="dcount">'.$dislike_count.'</span></span>';
    $content .= $like_count_wrapper_start;
    return $content;
}
add_filter('the_content','bgc_show_like_count');


function  bgc_dislike_btn_ajax_action(){
   global $wpdb;
   $table_name = $wpdb->prefix ."bgc_post_data";
   $userID = $_POST['uid'];
   $postID = $_POST['pid'];
    $setArray = array();
   if(isset($userID) && isset($postID)){
       $check_like = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $userID AND post_id = $postID AND like_count = 1");
       $check_dislike = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $userID  AND post_id = $postID AND dislike_count = 1");
       if($check_dislike > 0){
           echo 'You have already dislike this post'; }
       else{
           $wpdb->insert($table_name,array('post_id' => $postID, 'user_id'=>$userID,'like_count' => 0, 'dislike_count' =>1));
           $wpdb->update($table_name,
               array('like_count' => 0), array('user_id' =>$userID, 'post_id' =>$postID)
            );
           if($wpdb->insert_id){
               if($check_like > 0){
                   $lc = $check_like - 1;
               }else{
                   $lc=0;
               }
               if($check_dislike >0){
                   $dc = $check_dislike + 1;
               }else{
                   $dc = 1;
               }
               $setArray = array('msg' => 'I am sad','lcount' => $lc,'dcount' => $dc);

           }
           echo json_encode($setArray);
       }
   }
    wp_die();
}
add_action('wp_ajax_bgc_dislike_btn_ajax_action','bgc_dislike_btn_ajax_action');
add_action('wp_ajax_nopriv_bgc_dislike_btn_ajax_action','bgc_dislike_btn_ajax_action');

