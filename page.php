<?php get_header(); ?>
<main id="main">
  <div class="container">
    <?php
      if(is_post_type_archive()){
        $events_page = get_page_by_path('events');
        $events_page_id = $events_page->ID;
        $page_header = get_field('page_header', $events_page_id);
      }
      else{
        $page_header = get_field('page_header', get_the_ID());
      }
      if($page_header): ?>
        <h2 class="page-header"><?php esc_html_e($page_header); ?></h2>
    <?php endif; ?>
    <?php if(have_posts()): while(have_posts()): the_post(); ?>
      <?php the_content(); ?>
    <?php endwhile; endif; ?>
  </div>
</main>
<?php get_footer(); ?>