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

/**
 * Class Name: wp_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class wp_bootstrap_navwalker extends Walker_Nav_Menu {

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
		} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
		} else {

			$class_names = $value = '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

			if ( $args->has_children )
				$class_names .= ' dropdown';

			if ( in_array( 'current-menu-item', $classes ) )
				$class_names .= ' active';

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names .'>';

			$atts = array();
			$atts['title']  = ! empty( $item->title )	? $item->title	: '';
			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

			// If item has_children add atts to a.
			if ( $args->has_children && $depth === 0 ) {
				$atts['href']   		= '#';
                                $atts['href'] = ! empty( $item->url ) ? $item->url : '';
        
        //$atts['data-toggle']	= 'dropdown';
				$atts['class']			= 'dropdown-toggle';
				$atts['aria-haspopup']	= 'true';
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

			/*
			 * Glyphicons
			 * ===========
			 * Since the the menu item is NOT a Divider or Header we check the see
			 * if there is a value in the attr_title property. If the attr_title
			 * property is NOT null we apply it as the class name for the glyphicon.
			 */

			 $item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			if ( ! empty( $item->attr_title ) ){
				$item_output .= '&nbsp;<span class="' . esc_attr( $item->attr_title ) . '"></span>';
			}

			$item_output .= ( $args->has_children && 0 === $depth ) ? ' </a>' : '</a>';
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element )
            return;

        $id_field = $this->db_fields['id'];

        // Display this element.
        if ( is_object( $args[0] ) )
           $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {

			extract( $args );

			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';

			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';

			if ( $container )
				$fb_output .= '</' . $container . '>';

			echo $fb_output;
		}
	}
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

function kinggeorge_header_fallback_menu(){ ?>
  <ul class="nav navbar-nav navbar-right">
    <li <?php if(is_page('who-we-are')){ echo 'class="active"'; } ?>><a href="<?php echo home_url('who-we-are'); ?>">Who We Are</a></li>
    <li <?php if(has_term('activities', 'poi_types')){ echo 'class="active"'; } ?>><a href="<?php echo get_term_link('activities', 'poi_types'); ?>">Activities</a></li>
    <li <?php if(has_term('lodging-food', 'pois')){ echo 'class="active"'; } ?>><a href="<?php echo get_term_link('lodging-food', 'poi_types'); ?>">Lodging & Food</a></li>
    <li <?php if(is_home() || is_singular('post')){ echo 'class="active"'; } ?>><a href="<?php echo home_url('spotlight'); ?>">Spotlight</a></li>
    <li><a href="#">Events</a></li>
    <li <?php if(is_page('explore-our-area')){ echo 'class="active"'; } ?>><a href="<?php echo home_url('explore-our-area'); ?>">Map</a></li>
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

function fetch_data($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 20);

  $result = curl_exec($ch);
  curl_close($ch);

  return json_decode($result);
}