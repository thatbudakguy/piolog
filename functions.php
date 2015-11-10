<?php

/***** A modified theme setup that changes the size of featured images in the loop, on line 15 *****/

if (!function_exists('mh_newsdesk_lite_themes_setup')) {
	function mh_newsdesk_lite_themes_setup() {
		load_theme_textdomain('mh-newsdesk-lite', get_template_directory() . '/languages');
		add_theme_support('title-tag');
		add_theme_support('automatic-feed-links');
		add_theme_support('html5', array('search-form'));
		add_theme_support('custom-background', array('default-color' => 'efefef'));
		add_theme_support('post-thumbnails');
		add_theme_support('custom-header', array('default-image' => '', 'default-text-color' => '1f1e1e', 'width' => 300, 'height' => 100, 'flex-width' => true, 'flex-height' => true));
		add_image_size('content-single', 777, 437, true);
		add_image_size('content-list', 200, 200);
		set_post_thumbnail_size(200, 200, true);
		add_image_size('cp-thumb-small', 120, 67, true);
		register_nav_menus(array('main_nav' => __('Main Navigation', 'mh-newsdesk-lite')));
		add_filter('use_default_gallery_style', '__return_false');
		add_filter('widget_text', 'do_shortcode');
		add_post_type_support('page', 'excerpt');
	}
}
add_action('after_setup_theme', 'mh_newsdesk_lite_themes_setup');

/***** Changing the site font to use Crimson Text *****/

if (!function_exists('mh_newsdesk_lite_scripts')) {
	function mh_newsdesk_lite_scripts() {
		wp_enqueue_style('mh-google-fonts', "https://fonts.googleapis.com/css?family=Crimson+Text:400,400italic,700,700italic", array(), null);
		wp_enqueue_style('mh-font-awesome', get_template_directory_uri() . '/includes/font-awesome.min.css', array(), null);
		wp_enqueue_style('mh-style', get_stylesheet_uri(), false, '1.2.1');
		wp_enqueue_script('mh-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'));
		if (!is_admin()) {
			if (is_singular() && comments_open() && (get_option('thread_comments') == 1))
				wp_enqueue_script('comment-reply');
		}
	}
}
add_action('wp_enqueue_scripts', 'mh_newsdesk_lite_scripts');

/***** Allow users with the editor role to work with media *****/

if (!function_exists('pl_editors_media_setup')) {
	function pl_editors_media_setup() {
	$role = get_role( 'editor' );
	$role->add_cap( 'upload_files' );
	}
}
add_action('admin-init', 'pl_editors_media_setup');

/***** Set the admin colors for all new users to the black/orange piolog theme *****/

if (!function_exists('pl_admin_color_setup')){
    function pl_admin_color_setup($result) {
        return 'admin_color_schemer_1';
    }
}
add_filter('get_user_option_admin_color', 'pl_admin_color_setup');

/***** Remove some menu items as appropriate for different user roles *****/

if (!function_exists('pl_admin_menu_setup')) {
    function pl_admin_menu_setup() {
        
        if( !current_user_can( 'manage_options' )){        
            remove_menu_page( 'edit.php?post_type=nemus_slider' );
            remove_menu_page( 'tools.php' );
            remove_menu_page( 'options-general.php' ); 
        }
        if( !current_user_can( 'edit_others_posts' )){
            remove_menu_page( 'edit.php?post_type=feedback' );
        }
    }
}
add_action('admin_menu', 'pl_admin_menu_setup');

//hide jetpack, because for some reason this requires a different hook...

if (!function_exists('pl_remove_jetpack')){
    function pl_remove_jetpack(){
        if( !current_user_can( 'manage_options' )){
            remove_menu_page( 'jetpack' );
        }
    }
}
add_action('admin_init', 'pl_remove_jetpack');

/***** Reconfigure the dashboard to remove some widgets and add new ones, depending on who is the user *****/

if (!function_exists('pl_dash_setup')) {
	function pl_dash_setup() {

			//remove QuickPress, WPBeginner, WordPress News

			remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
			remove_meta_box( 'wpbeginner', 'dashboard', 'normal' );

			//remove Google Analytics and Jetpack Summary unless user is an administrator

			if( !current_user_can( 'manage_options' ) ) {
				remove_meta_box( 'gadash-widget', 'dashboard', 'normal' );
				remove_meta_box( 'jetpack_summary_widget', 'dashboard', 'normal' );
			}

			//add a Help widget to the right side of the dashboard

			add_meta_box('pl_help', 'Documentation', 'pl_help_widget', 'dashboard', 'side', 'high');

			//add a Pending Posts widget to help out editors, if the user is an editor

			if( current_user_can( 'edit_others_posts' ) ) {
				add_meta_box('pl_pending_posts', 'Article Activity', 'pl_pending_posts_widget', 'dashboard', 'side', 'high');
			}
	}

}
add_action('wp_dashboard_setup', 'pl_dash_setup');

/***** Code for the new custom widgets ****/

//The Documentation widget

function pl_help_widget(){
	$current_user = wp_get_current_user();
    if ( $current_user->user_firstname == true ) {
	   echo '<strong>Hello, ' . $current_user->user_firstname . '!</strong><br>';
    }
    else {
        echo '<strong>Hello!</strong><br>';
    }
	echo '<p>Questions about using WordPress? Try the <a target="_blank" href="https://docs.google.com/document/d/16iRLRF5hc0utXMnrNqamcq3uKxGO-GekZHlFditIIoo/edit?usp=sharing">Piolog Guide</a>! You might also find what you need in the <a target="_blank" href="https://codex.wordpress.org/">WordPress Codex</a>.</p>';
}

//The Article Activity widget

function pl_pending_posts_widget(){
	$current_user = wp_get_current_user();
	$uname = $current_user->user_login;

	//If the user has editorial responsibility for a given category, identify the category id and name

	if (strpos($uname, 'News') !== false){
		$editing_domain = 2;
        $cat_name = 'News';
	}
	elseif (strpos($uname, 'Opinion') !== false){
		$editing_domain = 7;
        $cat_name = 'Opinion';
	}
	elseif (strpos($uname, 'Features') !== false){
		$editing_domain = 6;
        $cat_name = 'Features';
	}
	elseif (strpos($uname, 'Arts') !== false){
		$editing_domain = 3;
        $cat_name = 'Arts';
	}
	elseif (strpos($uname, 'Sports') !== false){
		$editing_domain = 4;
        $cat_name = 'Sports';
	}
	else {
		$editing_domain = 'admin';
	} 
    
    if ($editing_domain == 'admin'){
        
        //Count the total number of pending posts
        
        $args = array('posts_per_page' => -1, 'paged' => 0, 'post_status' => 'pending');
        $posts_array = get_posts($args);
        foreach ($posts_array as $post){
            $total_pending += 1;
        }
        
        //Output the total number of pending posts with a link to view them
        
        $view_link = 'http://www.piolog.com/wp-admin/edit.php?post_status=pending&post_type=post';
        echo 'Currently ' . $total_pending . ' total posts pending review.<br><br>';
	    echo '<a class="button" href=' . $view_link . '>View all</a>';
    }
    else {
        
         //Count the number of pending posts in the given category
        
        $args = array('posts_per_page' => -1, 'paged' => 0, 'category' => $editing_domain, 'post_status' => 'pending');
        $posts_array = get_posts($args);
        foreach ($posts_array as $post){
            $total_pending += 1;
        }
        
        //Output the number of pending posts in the category with a link to view them
        $view_link = 'http://www.piolog.com/wp-admin/edit.php?s&post_status=pending&post_type=post&action=-1&m=0&cat=' . $editing_domain . '&filter_action=Filter&paged=1&mode=excerpt&action2=-1';
        echo 'Currently ' . $total_pending . ' posts pending your review in <strong>' . $cat_name . '</strong>.<br><br>';
	    echo '<a class="button" href=' . $view_link . '>View posts</a>';
    }    

}

?>