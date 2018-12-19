<?php
/*
  Template Name: Gallery Page Template
  Description: Template to display a gallery on the page
*/

get_header(); ?>
<main id="main">
  <div class="container">
    <h2 class="page-header"><?php esc_html_e(get_field('page_header')); ?></h2>
    <?php echo do_shortcode('[poi_gallery]'); ?>
  </div>
</main>
<?php get_footer(); ?>