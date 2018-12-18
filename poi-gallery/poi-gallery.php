<?php
/*
  Plugin Name: Point of Interest Gallery
  Description: Displays a gallery using ACF Gallery field.
  Author: The Childress Agency
  Author URI: https://childressagency.com
  Version: 1.0
  Text Domain: poi_gallery
*/

if(!defined('ABSPATH')){ exit; }

add_shortcode('poi_gallery', 'poi_gallery');

function poi_gallery(){
  ob_start();
  $page_id = get_the_ID();

  $image_ids = get_post_meta($page_id, 'gallery', true);
  if($image_ids){
    echo '<div class="gallery">';

    $featured_gallery_image_id = get_post_meta($page_id, 'featured_gallery_image', true);
    if($featured_gallery_image_id){
      $featured_gallery_image = wp_get_attachment_image_src($featured_gallery_image_id, 'full');
      $featured_gallery_image_alt = get_post_meta($page_id, '_wp_attachment_image_alt', true);

      echo '<img src="' . esc_url($featured_gallery_image[0]) . '" class="img-responsive center-block" alt="' . esc_attr($featured_gallery_image_alt) . '" />';
    }

    for($i = 0; $i < count($image_ids); $i++){
      $image = wp_get_attachment_image_src((int)$image_ids[$i], 'full');
      $image_url = $image[0];
      $image_caption = wp_get_attachment_caption($image_ids[$i]);
      $image_alt = get_post_meta($image_ids[$i], '_wp_attachment_image_alt', true);
      $image_thumb = wp_get_attachment_image_src((int)$image_ids[$i], 'thumb');
      $image_thumb_url = $image_thumb[0];

      echo '<a href="' . esc_url($image_url) . '" class="gallery-image" title="' . esc_html($image_caption) . '">';
      echo '<img src="' . esc_url($image_thumb_url) . '" class="img-responsive center-block" alt="' . esc_attr($image_alt) . '" />';
      echo '</a>';
    }

    echo '</div>';
  }
  return ob_get_clean();
}