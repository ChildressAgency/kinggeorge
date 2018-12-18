<?php get_header(); ?>
<main id="main">
  <h2 class="page-header"><?php esc_html_e(get_field('page_header')); ?></h2>
  <?php if(have_posts()): while(have_posts()): the_post(); ?>
    <?php the_content(); ?>
  <?php endwhile; endif; ?> 
</main>
<?php get_footer(); ?>