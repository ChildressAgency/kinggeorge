<?php get_header(); ?>
<main id="main">
  <div class="container">
    <div class="row">
      <div class="col-sm-8 col-md-9 right-border">
        <article class="spotlight-post">
          <?php if(have_posts()): while(have_posts()): the_post(); ?>
            <h2><?php the_title(); ?></h2>
            <?php the_content(); ?>
            <?php if(get_field('gallery')): ?>
              <div class="spotlight-gallery">
                <?php echo do_shortcode('[poi_gallery]'); ?>
              </div>
            <?php endif; ?>
          <?php endwhile; else: ?>
            <p>Sorry, the page you were looking for could not be found.</p>
          <?php endif; ?>
        </article>
      </div>
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>