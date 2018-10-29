<?php get_header(); ?>
  <main id="main">
    <div class="container">
      <div class="row">
        <?php if(get_field('gallery')): ?>
          <div class="col-sm-7 col-sm-push-5">
        <?php endif; ?>
          <?php if(have_posts()): while(have_posts()): the_post(); ?>
            <article class="poi">
              <header>
                <h2 class="page-header"><?php the_title(); ?></h2>
                <p class="poi-address"><?php the_field('street_address'); ?> <?php the_field('city_state_zip'); ?></p>
                <p class="poi-phone"><?php the_field('phone'); ?></p>
                <p class="poi-email"><?php the_field('email'); ?></p>
              </header>
              <a href="#" class="btn-main btn-rounded add-to-trip" data-poi_id="<?php echo get_the_ID(); ?>">+ Add to Trip</a>
              <h3 class="article-title">Details:</h3>
              <?php the_content(); ?>
          <?php endwhile; endif; ?>
          </article>
        <?php if(get_field('gallery')): ?>
          </div>
          <div class="col-sm-5 col-sm-pull-7">
            <div class="gallery">
              <?php 
                $images = get_field('gallery');
                echo '<img src="' . $images[0]['url'] . '" class="img-responsive center-block" alt="' . $images[0]['alt'] . '" />';
                foreach($images as $image): ?>
                  <a href="<?php echo $image['url']; ?>" rel="prettyPhoto[poi_gallery]" title="<?php echo $image['caption']; ?>">
                    <img src="<?php echo $image['sizes']['thumbnail']; ?>" class="img-responsive center-block" alt="<?php echo $image['alt']; ?>" />
                  </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>
<?php get_footer(); ?>