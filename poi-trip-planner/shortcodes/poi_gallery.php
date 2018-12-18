<?php
if(!defined('ABSPATH')){ exit; }

$page_id = get_the_ID(); ?>

<div class="gallery">
  <?php
    $featured_gallery_image_id = get_post_meta(get_the_ID(), 'featured_gallery_image', true);
    if($featured_gallery_image_id){
      $featured_gallery_image = wp_get_attachment_image_src($featured_gallery_image_id, 'full');
      $featured_gallery_image_alt = get_post_meta(get_the_ID(), '_wp_attachment_image_alt', true);

      echo '<img src="' . esc_url($featured_gallery_image[0]) . '" class="img-responsive center-block" alt="' . esc_attr($featured_gallery_image_alt) . '" />';
    }
  
    $image_ids = get_post_meta(get_the_ID(), 'gallery_images', true);

    if($image_ids){
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
    }
  ?>
</div>