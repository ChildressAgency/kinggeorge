<?php
/*
  Template Name: Gallery Page Template
  Description: Template to display a gallery on the page
*/

get_header(); ?>
<main id="main">
  <div class="container">
    <h2 class="page-header"><?php the_field('page_header'); ?></h2>
    <div class="gallery">
      <?php $featured_gallery_image = get_field('featured_gallery_image'); ?>
      <img src="<?php echo $featured_gallery_image['url']; ?>" class="img-responsive center-block" alt="<?php echo $featured_gallery_image['alt']; ?>" />
      <?php 
        $gallery_images = get_field('gallery_images');
        foreach($gallery_images as $image): ?>
          <a href="<?php echo $image['url']; ?>" class="gallery-image" title="<?php echo $image['caption']; ?>">
            <img src="<?php echo $image['sizes']['thumbnail']; ?>" class="img-responsive center-block" alt="<?php echo $image['alt']; ?>" />
          </a>
      <?php endforeach; ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>