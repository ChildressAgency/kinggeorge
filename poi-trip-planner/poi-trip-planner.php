<?php
/*
  Plugin Name: King George Point of Interest Trip Planner
  Description: Plan trips to points of interest in King George
  Author: The Childress Agency
  Author URI: https://childressagency.com
  Version: 1.0
  Text Domain: poi_trip_planner
*/

if(!defined('ABSPATH')){ exit; }

define(POI_PLUGIN_DIR, plugin_dir_url(__FILE__));

class kinggeorge_poi{
  public function __construct(){
    add_action('init', array($this, 'create_post_types'));
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    add_action('init', array($this, 'register_acf_options_page'));
    add_action('init', array($this, 'kinggeorge_load_textdomain'));

    add_shortcode('kinggeorge_poi_map', array($this, 'poi_map'));
    add_shortcode('kinggeorge_poi_full_list', array($this, 'poi_full_list'));
    add_shortcode('kinggeorge_poi_mytrip', array($this, 'poi_mytrip'));

    add_action('acf/init', 'acf_init');

    add_filter('template_include', array($this, 'set_poi_types_template'));
    add_filter('single_template', array($this, 'get_poi_template'));
  }

  private $google_api_key = get_field('google_api_key', 'option');

  function kinggeorge_load_textdomain(){
    load_plugin_textdomain('poi', false, basename(dirname(__FILE__)) . '/languages');
  }

  function create_post_types(){
    $poi_labels => array(
      'name' => __('Points of Interest', 'poi_trip_planner'),
      'singular_name' => __('Point of Interest', 'poi_trip_planner'),
      'menu_name' => __('Points of Interest', 'poi_trip_planner'),
      'add_new_item' => __('Add Point of Interest', 'poi_trip_planner'),
      'search_items' => __('Search Points of Interest', 'poi_trip_planner'),
      'edit_item' => __('Edit Point of Interest', 'poi_trip_planner'),
      'view_item' => __('View Point of Interest', 'poi_trip_planner'),
      'all_items' => __('All Points of Interest', 'poi_trip_planner'),
      'new_item' => __('New Point of Interest', 'poi_trip_planner'),
      'not_found' => __('No Points of Interest Found' 'poi_trip_planner')
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
          'name' => 'Point of Interest Types',
          'singular_name' => 'Point of Interest Type'
        )
      )
    );
  }

  function acf_init(){
    acf_update_setting('google_api_key', $this->google_api_key);
    add_poi_acf_field_groups();
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
      '//maps.googleapis.com/maps/api/js?key=' . $this->google_api_key;
    );
    wp_enqueue_script(
      'prettyPhoto-js',
      POI_PLUGIN_DIR . '/prettyPhoto/js/jquery.prettyPhoto.js',
      array('jquery'),
      null,
      true
    );
    wp_enqueue_style(
      'prettyPhoto-css',
      POI_PLUGIN_DIR . '/prettyPhoto/css/prettyPhoto.css'
    );
    wp_enqueue_script(
      'js-cookie',
      POI_PLUGIN_DIR . '/js/js-cookie.js',
      array('jquery'),
      null,
      true
    );
    wp_enqueue_script(
      'poi-scripts.js',
      POI_PLUGIN_DIR . '/js/poi-scripts.js',
      array('jquery'),
      null,
      true
    );
  }

  function poi_map(){
    include(POI_PLUGIN_DIR . 'poi-map.php');
  }

  function poi_full_list(){
    include(POI_PLUGIN_DIR . 'poi-full-list.php');
  }

  function set_poi_types_template($template){
    if(is_tax('poi_types') && !is_cust_template($template)){
      $template = POI_PLUGIN_DIR . 'templates/taxonomy-poi_types.php';
    }
  }

  function is_cust_template($template_path){
    $template = basename($template_path);

    if(preg_match('/^taxonomy-poi_types((-(\S*))?).php/', $template)){
      return true;
    }

    return false;
  }

  function get_pois_template($single_template){
    global $post;

    if($post->post_type == 'poi'){
      $single_template = POI_PLUGIN_DIR . 'templates/poi_template.php';
    }

    return $single_template;
  }

  function poi_mytrip(){
    include(POI_PLUGIN_DIR . 'poi-mytrip.php');
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
          'center' => array(
            'center_lat' => '38.264493',
            'center_lng' => '-77.2198848'
          )
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
      'title' => 'Default POI Image Setting',
      'fields' => array(
        array(
          'key' => 'field_11',
          'label' => 'Default POI Image',
          'name' => 'default_poi_image',
          'type' => 'image',
          'return_format' => 'url'
        )
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'poi-general-options'
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
        )
      ),
      'location' => array(
        array(
          array(
            'param' => 'taxonomy_term',
            'operator' => '==',
            'value' => 'poi_type'
          )
        )
      )
    ));
  }
}

new kinggeorge_poi;