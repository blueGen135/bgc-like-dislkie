<?php
function createTable(){
    global $wpdb;
    $query = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix ."bgc_post_data (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    like_count INT,
    dislike_count INT 
        
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
    $wpdb->query($query);
}
register_activation_hook(__FILE__, 'createTable');