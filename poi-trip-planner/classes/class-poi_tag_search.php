<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('poi_tag_search')){
  class poi_tag_search{
    public function __construct(){
      add_filter('posts_join', array($this, 'posts_join'), 10, 2);
      add_filter('posts_where', array($this, 'posts_where'), 10, 2);
      add_filter('posts_groupby', array($this, 'posts_groupby'), 10, 2);
    }

    public function posts_join($join, $query){
      global $wpdb;
      if(is_main_query() && is_search()){
        $join .= " 
          LEFT JOIN (
            {$wpdb->term_relationships}
            INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_taxonomy}.term_taxonomy_id = {$wpdb->term_relationships}.term_taxonomy_id
            INNER JOIN {$wpdb->terms} ON {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id
          )
          ON {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id
        ";
      }

      return $join;
    }

    public function posts_where($where, $query){
      global $wpdb;
      if(is_main_query() && is_search()){
        $taxonomies = $this->posts_where_taxonomies();
        $taxonomies = implode(', ', $taxonomies);

        $where .= " 
          OR (
            {$wpdb->term_taxonomy}.taxonomy IN( {$taxonomies} )
            AND
            {$wpdb->terms}.name LIKE '%" . esc_sql(get_query_var('s')) . "%'
          )";
      }
      return $where;
    }

    public function posts_where_taxonomies(){
      $taxonomies = array(
        'poi_tags'
      );

      $taxonomies = apply_filters('poi_posts_where_taxonomies', $taxonomies);
      foreach($taxonomies as $index => $taxonomy){
        $taxonomies[$index] = sprintf("'%s'", esc_sql($taxonomy));
      }
      return $taxonomies;
    }

    public function posts_groupby($groupby, $query){
      global $wpdb;

      if(is_main_query() && is_search()){
        $groupby = "{$wpdb->posts}.ID";
      }
      return $groupby;
    }
  }
}