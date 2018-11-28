<?php get_header(); ?>
<main id="main">
  <div class="container">
    <div class="row">
      <div class="col-sm-8 col-md-9 right-border">
        <article class="spotlight-post">
          <?php if(have_posts()): while(have_posts()): the_post(); ?>
            <h2><?php the_title(); ?></h2>
            <?php the_content(); ?>
            <?php if(get_field('gallery_images')): ?>
              <div class="spotlight-gallery">
                <div class="gallery">
                  <?php 
                    $featured_gallery_image = get_field('featured_gallery_image');
                    if($featured_gallery_image): ?>
                      <img src="<?php echo $featured_gallery_image['url']; ?>" class="img-responsive center-block" alt="<?php echo $featured_gallery_image['alt']; ?>" />
                  <?php endif; ?>

                  <?php
                    $gallery_images = get_field('gallery_images');
                    foreach($gallery_images as $image): ?>
                      <a href="<?php echo $image['url']; ?>" class="gallery-image" title="<?php echo $image['caption']; ?>">
                        <img src="<?php echo $image['sizes']['thumbnail']; ?>" class="img-responsive center-block" alt="<?php echo $image['alt']; ?>" />
                      </a>
                  <?php endforeach; ?>
                </div>
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