<?php get_header(); ?>
  <main id="main">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-8 col-md-9 right-border">
          <?php if(have_posts()): while(have_posts()): the_post(); ?>
            <?php get_template_part('partials/spotlight-loop'); ?>
          <?php endwhile; endif; wp_pagenavi(); ?>
        </div>

        <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
      </div>
    </div>
  </main>
<?php get_footer(); ?>