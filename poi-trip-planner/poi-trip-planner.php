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
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    add_action('init', array($this, 'poi_load_textdomain'));

    require_once POI_PLUGIN_DIR . '/classes/class-poi_post_type.php';
    $poi_post_type = new poi_post_type();

    require_once POI_PLUGIN_DIR . '/classes/class-poi_tag_search.php';
    $poi_tag_search = new poi_tag_search();

    add_shortcode('poi_map', array($this, 'poi_map'));
    add_shortcode('poi_full_list', array($this, 'poi_full_list'));
    add_shortcode('poi_mytrip', array($this, 'poi_mytrip'));


    add_filter('template_include', array($this, 'set_poi_types_template'));
    add_filter('single_template', array($this, 'get_poi_template'));
  }

  function poi_load_textdomain(){
    load_plugin_textdomain('poi_trip_planner', false, basename(dirname(__FILE__)) . '/languages');
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

}

new poi_trip_planner;