<?php

add_action('wp_footer', 'show_template');
function show_template() {
	global $template;
	print_r($template);
}

add_action('wp_enqueue_scripts', 'jquery_cdn');
function jquery_cdn(){
  if(!is_admin()){
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, null, true);
    wp_enqueue_script('jquery');
  }
}

add_action('wp_enqueue_scripts', 'kinggeorge_scripts', 100);
function kinggeorge_scripts(){
  wp_register_script(
    'bootstrap-script', 
    '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', 
    array('jquery'), 
    '', 
    true
  );

  wp_register_script(
    'slick-js',
    '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
    array('jquery'),
    '',
    true
  );

  wp_register_script(
    'kinggeorge-scripts', 
    get_template_directory_uri() . '/js/kinggeorge-scripts.js',
    array('jquery'), 
    '', 
    true
  ); 
  
  wp_enqueue_script('bootstrap-script');
  if(is_front_page()){
    wp_enqueue_script('slick-js');
  }
  wp_enqueue_script('kinggeorge-scripts'); 
}

add_action('wp_enqueue_scripts', 'kinggeorge_styles');
function kinggeorge_styles(){
  wp_register_style('bootstrap-css', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
  wp_register_style('google-fonts', '//fonts.googleapis.com/css?family=Crimson+Text:400,700|Lato:300,400,700,900');
  wp_register_style('fontawesome', '//use.fontawesome.com/releases/v5.1.0/css/all.css');
  wp_register_style('slick-css', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
  wp_register_style('kinggeorge', get_template_directory_uri() . '/style.css');
  
  wp_enqueue_style('bootstrap-css');
  wp_enqueue_style('google-fonts');
  wp_enqueue_style('fontawesome');
  wp_enqueue_style('slick-css');
  wp_enqueue_style('kinggeorge');
}

//add meta to enqueued styles
add_filter('style_loader_tag', 'kinggeorge_add_css_meta', 10, 2);
function kinggeorge_add_css_meta($link, $handle){
  if($handle == 'fontawesome'){
    $link = str_replace('/>', ' integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous " />', $link);
  }
  return $link;
}

add_action('after_setup_theme', 'kinggeorge_setup');
function kinggeorge_setup(){
  add_theme_support('post-thumbnails');

  register_nav_menus(array(
    'header-nav' => 'Header Navigation',
    'footer-nav-1' => 'Footer Navigation 1',
    'footer-nav-2' => 'Footer Navigation 2',
    'footer-nav-3' => 'Footer Navigation 3',
    'history-nav' => 'History Pages Navigation'
  ));

  load_theme_textdomain('kinggeorge', get_template_directory() . '/languages');
}

function kinggeorge_get_menu_by_location($location){
  if(empty($location)){ return false; }

  $locations = get_nav_menu_locations();
  if(!isset($locations[$location])){ return false; }

  $menu_obj = get_term($locations[$location], 'nav_menu');
  return $menu_obj;
}

if(function_exists('acf_add_options_page')){
  acf_add_options_page(array(
    'page_title' => __('General Settings', 'kinggeorge'),
    'menu_title' => __('General Settings', 'kinggeorge'),
    'menu_slug' => 'general-settings',
    'capability' => 'edit_posts',
    'redirect' => false
  ));
}

require_once dirname(__FILE__) . '/includes/class-wp_bootstrap_navwalker.php';

function kinggeorge_header_fallback_menu(){ ?>
  <ul class="nav navbar-nav navbar-right">
    <li <?php if(is_page('who-we-are')){ echo 'class="active"'; } ?>><a href="<?php echo esc_url(home_url('who-we-are')); ?>">Who We Are</a></li>
    <li <?php if(has_term('activities', 'poi_types')){ echo 'class="active"'; } ?>><a href="<?php echo esc_url(get_term_link('activities', 'poi_types')); ?>">Activities</a></li>
    <li <?php if(has_term('lodging-food', 'pois')){ echo 'class="active"'; } ?>><a href="<?php echo esc_url(get_term_link('lodging-food', 'poi_types')); ?>">Lodging & Food</a></li>
    <li <?php if(is_home() || is_singular('post')){ echo 'class="active"'; } ?>><a href="<?php echo esc_url(home_url('spotlight')); ?>">Spotlight</a></li>
    <li <?php if(is_page('events')){ echo 'class="active"'; } ?>><a href="<?php echo esc_url(home_url('events')); ?>">Events</a></li>
    <li <?php if(is_page('explore-our-area')){ echo 'class="active"'; } ?>><a href="<?php echo esc_url(home_url('explore-our-area')); ?>">Map</a></li>
  </ul>
<?php }

add_action('widgets_init', 'kinggeorge_widgets_init');
function kinggeorge_widgets_init(){
  register_sidebar(array(
    'name' => __('Spotlight Sidebar', 'kinggeorge'),
    'id' => 'spotlight-sidebar',
    'description' => __('Sidebar for the Spotlight pages.', 'kinggeorge'),
    'before_widget' => '<section class="sidebar-section">',
    'after_widget' => '</section>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
}

add_action('init', 'kinggeorge_change_post_object_labels');
function kinggeorge_change_post_object_labels(){
  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->name = 'Spotlight Articles';
  $labels->singular_name = 'Spotlight Article';
  $labels->add_new = 'Add New Spotlight Article';
  $labels->add_new_item = 'Add New Spotlight Article';
  $labels->edit_item = 'Edit Spotlight Article';
  $labels->new_item = 'New Spotlight Article';
  $labels->view_item = 'View Spotlight Article';
  $labels->search_items = 'Search Spotlight Articles';
  $labels->not_found = 'No Spotlight Articles found';
  $labels->not_found_in_trash = 'No Spotlight Articles found in Trash';
  $labels->all_items = 'All Spotlight Articles';
  $labels->menu_name = 'Spotlight';
  $labels->name_admin_bar = 'Spotlight';
}

add_action('admin_menu', 'kinggeorge_change_post_labels');
function kinggeorge_change_post_labels(){
  global $menu;
  global $submenu;
  $menu[5][0] = 'Spotlight';
  $submenu['edit.php'][5][0] = 'Spotlight';
  $submenu['edit.php'][10][0] = 'New Spotlight Article';
  $submenu['edit.php'][16][0] = 'Spotlight Tags';
}

function kinggeorge_get_instagram_feed(){
  $cache_time = 24;
  $access_token = get_option('options_instagram_access_token');
  $feed = '';

  if(get_transient('kinggeorge_instagram_feed') === false){
    $url = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $access_token;
    $result = wp_remote_get($url);

    if($result){
      $feed = json_decode($result['body']);
    }
  }
  else{
    $feed = get_transient('kinggeorge_instagram_feed');
  }

  return $feed;
}

function kinggeorge_get_bg_img_and_css($page_id, $img_field){
	$bg_img_and_css = [];

	$img_id = get_post_meta($page_id, $img_field, true);
	$img = wp_get_attachment_image_src($img_id, 'full');
	$bg_img_and_css['image_url'] = $img[0];

	$bg_img_and_css['image_css'] = get_post_meta($page_id, $img_field . '_css', true);

	return $bg_img_and_css;
}