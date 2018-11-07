<?php
/*
  Plugin Name: Point of Interest Trip Planner
  Description: Plan trips to points of interest
  Author: The Childress Agency
  Author URI: https://childressagency.com
  Version: 1.0
  Text Domain: poi_trip_planner
*/

if(!defined('ABSPATH')){ exit; }

define('POI_PLUGIN_DIR', dirname(__FILE__));
define('POI_PLUGIN_URL', plugin_dir_url(__FILE__));

class poi_trip_planner{
  public function __construct(){
    add_action('init', array($this, 'create_post_types'));
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    add_action('init', array($this, 'register_acf_options_page'));
    add_action('init', array($this, 'poi_load_textdomain'));

    add_shortcode('poi_map', array($this, 'poi_map'));
    add_shortcode('poi_full_list', array($this, 'poi_full_list'));
    add_shortcode('poi_mytrip', array($this, 'poi_mytrip'));

    add_action('acf/init', array($this, 'acf_init'));
    add_action('acf/init', array($this, 'add_poi_acf_field_groups'));

    add_filter('template_include', array($this, 'set_poi_types_template'));
    add_filter('single_template', array($this, 'get_poi_template'));
  }

  //private $google_api_key = get_field('google_api_key', 'option');

  function poi_load_textdomain(){
    load_plugin_textdomain('poi', false, basename(dirname(__FILE__)) . '/languages');
  }

  function create_post_types(){
    $poi_labels = array(
      'name' => __('Points of Interest', 'poi_trip_planner'),
      'singular_name' => __('Point of Interest', 'poi_trip_planner'),
      'menu_name' => __('Points of Interest', 'poi_trip_planner'),
      'add_new_item' => __('Add Point of Interest', 'poi_trip_planner'),
      'search_items' => __('Search Points of Interest', 'poi_trip_planner'),
      'edit_item' => __('Edit Point of Interest', 'poi_trip_planner'),
      'view_item' => __('View Point of Interest', 'poi_trip_planner'),
      'all_items' => __('All Points of Interest', 'poi_trip_planner'),
      'new_item' => __('New Point of Interest', 'poi_trip_planner'),
      'not_found' => __('No Points of Interest Found', 'poi_trip_planner')
    );
    $poi_args = array(
      'labels' => $poi_labels,
      'capability_type' => 'post',
      'public' => true,
      'menu_position' => 5,
      'menu_icon' => 'dashicons-location-alt',
      'query_var' => 'poi',
      'has_archive' => false,
      'supports' => array(
        'title',
        'editor',
        'custom-fields',
        'thumbnail',
        'revisions',
        'excerpt',
        'author'
      )
    );
    register_post_type('poi', $poi_args);

    register_taxonomy('poi_types',
      'poi',
      array(
        'hierarchical' => true,
        'show_admin_column' => true,
        'public' => true,
        'labels' => array(
          'name' => __('Point of Interest Types', 'poi_trip_planner'),
          'singular_name' => __('Point of Interest Type', 'poi_trip_planner'),
          'all_items' => __('All Points of Interest Types', 'poi_trip_planner'),
          'edit_item' => __('Edit Point of Interest Type', 'poi_trip_planner'),
          'view_item' => __('View Point of Interest Type', 'poi_trip_planner'),
          'update_item' => __('Update Point of Interest Type', 'poi_trip_planner'),
          'add_new_item' => __('Add New Point of Interest Type', 'poi_trip_planner'),
          'new_item_name' => __('New Point of Interest Type Name', 'poi_trip_planner'),
          'parent_item' => __('Parent Point of Interest Type', 'poi_trip_planner'),
          'search_items' => __('Search Point of Interest Types', 'poi_trip_planner'),
          'popular_items' => __('Popular Point of Interest Types', 'poi_trip_planner'),
          'add_or_remove_items' => __('Add or Remove Point of Interest Types', 'poi_trip_planner'),
          'not_found' => __('No Point of Interest Types Found', 'poi_trip_planner'),
          'back_to_items' => __('Back to Point of Interest Types', 'poi_trip_planner')
        )
      )
    );
  }

  function acf_init(){
    acf_update_setting('google_api_key', get_field('google_api_key', 'option'));
  }

  function register_acf_options_page(){
    if(function_exists('acf_add_options_page')){
      acf_add_options_page(array(
        'page_title' => __('POI General Settings', 'poi_trip_planner'),
        'menu_title' => __('POI General Settings', 'poi_trip_planner'),
        'menu_slug' => 'poi-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
      ));
    }
  }

  function enqueue_scripts(){
    wp_enqueue_script(
      'google_map_api',
      '//maps.googleapis.com/maps/api/js?key=' . get_field('google_api_key', 'option'),
      array('jquery'),
      null,
      true
    );
    wp_enqueue_script(
      'magnific-js',
      POI_PLUGIN_URL . 'magnific/magnific.min.js',
      array('jquery'),
      null,
      true
    );
    wp_enqueue_style(
      'magnific-css',
      POI_PLUGIN_URL . 'magnific/magnific.css'
    );
    wp_enqueue_script(
      'js-cookie',
      POI_PLUGIN_URL . 'js/js-cookie.js',
      array('jquery'),
      null,
      true
    );
    wp_enqueue_script(
      'poi-scripts-js',
      POI_PLUGIN_URL . 'js/poi-scripts.js',
      array('jquery'),
      null,
      true
    );
    wp_localize_script(
      'poi-scripts-js',
      'mapMarker',
      POI_PLUGIN_URL . 'images/map-marker2.png'
    );
  }

  function poi_map(){
    include(POI_PLUGIN_DIR . '/poi-map.php');
  }

  function poi_full_list(){
    include(POI_PLUGIN_DIR . '/poi-full-list.php');
  }

  function set_poi_types_template($template){
    if(is_tax('poi_types') && !$this->is_cust_template($template)){
      $template = POI_PLUGIN_DIR . '/templates/taxonomy-poi_types.php';
    }

    return $template;
  }

  function is_cust_template($template_path){
    $template = basename($template_path);

    if(preg_match('/^taxonomy-poi_types((-(\S*))?).php/', $template)){
      return true;
    }

    return false;
  }

  function get_poi_template($single_template){
    global $post;

    if($post->post_type == 'poi'){
      $single_template = POI_PLUGIN_DIR . '/templates/poi-template.php';
    }

    return $single_template;
  }

  function poi_mytrip(){
    include(POI_PLUGIN_DIR . '/poi-mytrip.php');
  }

  function add_poi_acf_field_groups(){
    acf_add_local_field_group(array(
      'key' => 'group_1',
      'title' => 'POI Settings',
      'fields' => array(
        array(
          'key' => 'field_1',
          'label' => 'Street Address',
          'name' => 'street_address',
          'type' => 'text'
        ),
        array(
          'key' => 'field_2',
          'label' => 'City, State, Zip',
          'name' => 'city_state_zip',
          'type' => 'text'
        ),
        array(
          'key' => 'field_3',
          'label' => 'Phone',
          'name' => 'phone',
          'type' => 'text'
        ),
        array(
          'key' => 'field_4',
          'label' => 'Email',
          'name' => 'email',
          'type' => 'email'
        ),
        array(
          'key' => 'field_5',
          'label' => 'Website',
          'name' => 'website',
          'type' => 'link'
        ),
        array(
          'key' => 'field_6',
          'label' => 'Map Description',
          'name' => 'map_description',
          'type' => 'textarea',
          'rows' => '4',
          'new_lines' => 'wpautop',
          'instructions' => 'Short description of attraction.'
        ),
        array(
          'key' => 'field_7',
          'label' => 'Location',
          'name' => 'location',
          'type' => 'google_map',
          'center_lat' => '38.267451',
          'center_lng' => '-77.180927'
        ),
        array(
          'key' => 'field_8',
          'label' => 'Gallery',
          'name' => 'gallery',
          'type' => 'gallery'
        )
      ),
      'location' => array(
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'poi'
          )
        )
      ),
      'position' => 'acf_after_title'
    ));

    acf_add_local_field_group(array(
      'key' => 'group_2',
      'title' => 'Default POI Settings',
      'fields' => array(
        array(
          'key' => 'field_11',
          'label' => 'Default POI Image',
          'name' => 'default_poi_image',
          'type' => 'image',
          'return_format' => 'url',
          'preview_size' => 'full'
        ),
        array(
          'key' => 'field_12',
          'label' => 'Google Map API Key',
          'name' => 'google_api_key',
          'type' => 'text',
        )
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'poi-general-settings'
          )
        )
      )
    ));

    acf_add_local_field_group(array(
      'key' => 'group_3',
      'title' => 'POI Type Settings',
      'fields' => array(
        array(
          'key' => 'field_21',
          'label' => 'POI Type Page Header',
          'name' => 'poi_type_page_header',
          'type' => 'text'
        ),
        array(
          'key' => 'field_22',
          'label' => 'POI Type Image',
          'name' => 'poi_type_image',
          'type' => 'image',
          'return_format' => 'array',
          'preview_size' => 'full'
        )
      ),
      'location' => array(
        array(
          array(
            'param' => 'taxonomy',
            'operator' => '==',
            'value' => 'poi_types'
          )
        )
      )
    ));
  }
}

new poi_trip_planner;