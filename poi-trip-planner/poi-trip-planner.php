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

class kinggeorge_poi{
  public function __construct(){
    add_action('init', array($this, 'create_post_types'));
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

    add_shortcode('poi_map', array($this, 'poi_map'));

    add_action('acf/init', 'acf_init');
  }

  private $google_api_key = get_field('google_api_key', 'option');

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
  }

  function enqueue_scripts(){
    wp_enqueue_script(
      'google_map_api',
      '//maps.googleapis.com/maps/api/js?key=' . $this->google_api_key;
    )
  }

  function poi_map(){
    include('poi-map.php');
  }
}

new kinggeorge_poi;