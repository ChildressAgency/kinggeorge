<?php
/*
  Template Name: Gallery Page Template
  Description: Template to display a gallery on the page
*/

get_header(); ?>
<main id="main">
  <div class="container">
    <h2 class="page-header"><?php esc_html_e(get_field('page_header')); ?></h2>
    <div class="gallery">
      <?php
        $featured_gallery_image_id = get_post_meta(get_the_ID(), 'featured_gallery_image', true);
        $featured_gallery_image = wp_get_attachment_image_src($featured_gallery_image_id, 'full');
        $featured_gallery_image_alt = get_post_meta(get_the_ID(), '_wp_attachment_image_alt', true);
      ?> 
        <img src="<?php echo esc_url($featured_gallery_image[0]); ?>" class="img-responsive center-block" alt="<?php echo esc_attr($featured_gallery_image_alt); ?>" />
      <?php 
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
  </div>
</main>
<?php get_footer(); ?>