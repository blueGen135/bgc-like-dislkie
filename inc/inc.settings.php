<?php
function bgc_setting_page_html(){
    if(!is_admin()){
        return;
    }
    ?>
    <div class="wrap">
        <h1><?=esc_html(get_admin_page_title())?></h1>
        <form action="options.php" method="post">
            <?php
                settings_fields('bgc-settings');
                do_settings_sections('bgc-settings');
                submit_button('Save Changes');
            ?>
        </form>
    </div>
    <?php
}
function bgc_register_menu_page(){
    add_menu_page( 'BGC Post Like Dislike', 'BGC Settings', 'manage_options', 'bgc-settings', 'bgc_setting_page_html', 'dashicons-thumbs-up', 30);
}
add_action('admin_menu','bgc_register_menu_page');

function bgc_settings_init() {
    register_setting('bgc-settings','bgc_like_label');
    register_setting('bgc-settings','bgc_dislike_label');
    add_settings_section('bgc-label-settings','BGC Button Label','bgc_plugin_setting_label_cb','bgc-settings');
    add_settings_field('bgc-like-label-field','Like Button Label','bgc_like_label_field_cb','bgc-settings','bgc-label-settings');
    add_settings_field('bgc-dislike-label-field','Dislike Button Label','bgc_dislike_label_field_cb','bgc-settings','bgc-label-settings');
}

add_action('admin_init', 'bgc_settings_init');

function bgc_plugin_setting_label_cb(){
    echo '<p>Define Button label</p>';
}
function bgc_like_label_field_cb(){
   $settings = get_option('bgc_like_label');?>
    <input type="text" value="<?=isset($settings) ? esc_attr($settings) : ''?>" name="bgc_like_label">
<?php
}
function bgc_dislike_label_field_cb(){
    $settings = get_option('bgc_dislike_label');?>
    <input type="text" value="<?=isset($settings) ? esc_attr($settings) : ''?>" name="bgc_dislike_label">
    <?php
}