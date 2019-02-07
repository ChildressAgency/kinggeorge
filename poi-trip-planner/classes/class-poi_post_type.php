<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('poi_post_type')){
  class poi_post_type{
    public function __construct(){
      add_action('init', array($this, 'init'));
      add_action('acf/init', array($this, 'acf_init'));
    }

    public function init(){
      $this->create_post_type();
      $this->register_acf_options_page();
    }

    public function acf_init(){
      $this->register_acf_google_api_key();
      $this->add_poi_acf_field_groups();
    }

    public function create_post_type(){
      $poi_labels = array(
        'name' => esc_html__('Points of Interest', 'poi_trip_planner'),
        'singular_name' => esc_html__('Point of Interest', 'poi_trip_planner'),
        'menu_name' => esc_html__('Points of Interest', 'poi_trip_planner'),
        'add_new_item' => esc_html__('Add Point of Interest', 'poi_trip_planner'),
        'search_items' => esc_html__('Search Points of Interest', 'poi_trip_planner'),
        'edit_item' => esc_html__('Edit Point of Interest', 'poi_trip_planner'),
        'view_item' => esc_html__('View Point of Interest', 'poi_trip_planner'),
        'all_items' => esc_html__('All Points of Interest', 'poi_trip_planner'),
        'new_item' => esc_html__('New Point of Interest', 'poi_trip_planner'),
        'not_found' => esc_html__('No Points of Interest Found', 'poi_trip_planner')
      );
      $poi_args = array(
        'labels' => $poi_labels,
        'capability_type' => 'post',
        'public' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-location-alt',
        'query_var' => 'poi',
        'has_archive' => false,
        'show_in_rest' => true,
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
          'show_in_rest' => true,
          'labels' => array(
            'name' => esc_html__('Point of Interest Types', 'poi_trip_planner'),
            'singular_name' => esc_html__('Point of Interest Type', 'poi_trip_planner'),
            'all_items' => esc_html__('All Points of Interest Types', 'poi_trip_planner'),
            'edit_item' => esc_html__('Edit Point of Interest Type', 'poi_trip_planner'),
            'view_item' => esc_html__('View Point of Interest Type', 'poi_trip_planner'),
            'update_item' => esc_html__('Update Point of Interest Type', 'poi_trip_planner'),
            'add_new_item' => esc_html__('Add New Point of Interest Type', 'poi_trip_planner'),
            'new_item_name' => esc_html__('New Point of Interest Type Name', 'poi_trip_planner'),
            'parent_item' => esc_html__('Parent Point of Interest Type', 'poi_trip_planner'),
            'search_items' => esc_html__('Search Point of Interest Types', 'poi_trip_planner'),
            'popular_items' => esc_html__('Popular Point of Interest Types', 'poi_trip_planner'),
            'add_or_remove_items' => esc_html__('Add or Remove Point of Interest Types', 'poi_trip_planner'),
            'not_found' => esc_html__('No Point of Interest Types Found', 'poi_trip_planner'),
            'back_to_items' => esc_html__('Back to Point of Interest Types', 'poi_trip_planner')
          )
        )
      );
  
      register_taxonomy('poi_tags',
        'poi',
        array(
          'hierarchical' => false,
          'show_admin_column' => true,
          'show_in_rest' => true,
          'labels' => array(
            'name' => esc_html__('Point of Interest Tags', 'poi_trip_planner'),
            'singular_name' => esc_html__('Point of Interest Tag', 'poi_trip_planner'),
            'all_items' => esc_html__('All Point of Interest Tags', 'poi_trip_planner'),
            'edit_items' => esc_html__('Edit Point of Interest Tags', 'poi_trip_planner'),
            'view_item' => esc_html__('View Point of Interest Tag', 'poi_trip_planner'),
            'update_item' => esc_html__('Update Point of Interest Tag', 'poi_trip_planner'),
            'add_new_item' => esc_html__('Add New Point of Interest Tag', 'poi_trip_planner'),
            'new_item_name' => esc_html__('New Point of Interest Tag Name', 'poi_trip_planner'),
            'search_items' => esc_html__('Search Point of Interest Tags', 'poi_trip_planner'),
            'popular_items' => esc_html__('Popular Point of Interest Tags', 'poi_trip_planner'),
            'add_or_remove_items' => esc_html__('Add or Remove Point of Interest Tags', 'poi_trip_planner'),
            'not_found' => esc_html__('No Point of Interest Tags Found', 'poi_trip_planner'),
            'back_to_items' => esc_html__('Back to Point of Interest Tags', 'poi_trip_planner')
          )
        )
      );  
    }

    public function register_acf_options_page(){
      if(function_exists('acf_add_options_page')){
        acf_add_options_page(array(
          'page_title' => esc_html__('POI General Settings', 'poi_trip_planner'),
          'menu_title' => esc_html__('POI General Settings', 'poi_trip_planner'),
          'menu_slug' => 'poi-general-settings',
          'capability' => 'edit_posts',
          'redirect' => false
        ));
      }  
    }

    public function register_acf_google_api_key(){
      $google_api_key = get_option('options_google_api_key');
      acf_update_setting('google_api_key', $google_api_key);
    }

    public function add_poi_acf_field_groups(){
      acf_add_local_field_group(array(
        'key' => 'group_1',
        'title' => esc_html__('POI Settings', 'poi_trip_planner'),
        'fields' => array(
          array(
            'key' => 'field_1',
            'label' => esc_html__('Street Address', 'poi_trip_planner'),
            'name' => 'street_address',
            'type' => 'text'
          ),
          array(
            'key' => 'field_2',
            'label' => esc_html__('City, State, Zip', 'poi_trip_planner'),
            'name' => 'city_state_zip',
            'type' => 'text'
          ),
          array(
            'key' => 'field_3',
            'label' => esc_html__('Phone', 'poi_trip_planner'),
            'name' => 'phone',
            'type' => 'text'
          ),
          array(
            'key' => 'field_4',
            'label' => esc_html__('Email', 'poi_trip_planner'),
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
            'label' => esc_html__('Map Description', 'poi_trip_planner'),
            'name' => 'map_description',
            'type' => 'textarea',
            'rows' => '4',
            'new_lines' => 'wpautop',
            'instructions' => esc_html__('Short description of attraction.', 'poi_trip_planner')
          ),
          array(
            'key' => 'field_7',
            'label' => esc_html__('Location', 'poi_trip_planner'),
            'name' => 'location',
            'type' => 'google_map',
            'center_lat' => '38.267451',
            'center_lng' => '-77.180927'
          ),
          array(
            'key' => 'field_8',
            'label' => esc_html__('Featured Gallery Image', 'poi_trip_planner'),
            'name' => 'featured_gallery_image',
            'type' => 'image',
            'instructions' => esc_html__('This is a larger image that will be displayed above the gallery thumbnails. The image will resize to fit but for best performance it shouldn\'t be wider than 800px.', 'poi_trip_planner'),
            'return_format' => 'id',
            'preview_size' => 'full'
          ),
          array(
            'key' => 'field_9',
            'label' => esc_html__('Gallery', 'poi_trip_planner'),
            'name' => 'gallery',
            'type' => 'gallery',
            'instructions' => esc_html__('These images will automatically adjust to fit but for best performance they should be no more than 800px wide or no more than 800px tall. The thumbnails will automatically be size to 80px wide.', 'poi_trip_planner'),
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
        //'position' => 'acf_after_title'
      ));
  
      acf_add_local_field_group(array(
        'key' => 'group_2',
        'title' => esc_html__('Default POI Settings', 'poi_trip_planner'),
        'fields' => array(
          array(
            'key' => 'field_11',
            'label' => esc_html__('Default POI Image', 'poi_trip_planner'),
            'name' => 'default_poi_image',
            'type' => 'image',
            'return_format' => 'url',
            'preview_size' => 'full'
          ),
          array(
            'key' => 'field_12',
            'label' => esc_html__('Google Map API Key', 'poi_trip_planner'),
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
        'title' => esc_html__('POI Type Settings', 'poi_trip_planner'),
        'fields' => array(
          array(
            'key' => 'field_21',
            'label' => esc_html__('POI Type Page Header', 'poi_trip_planner'),
            'name' => 'poi_type_page_header',
            'type' => 'text'
          ),
          array(
            'key' => 'field_22',
            'label' => esc_html__('POI Type Image', 'poi_trip_planner'),
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
}