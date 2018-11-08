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
                <?php $poi_website = get_field('website'); ?>
                <p class="poi-website">
                  <a href="<?php echo $poi_website['url']; ?>" target="_blank"><?php echo $poi_website['title']; ?></a>
                </p>
              </header>
              <div class="add-remove-trip">
                <a href="#" class="btn-main btn-rounded add-to-trip" data-poi_id="<?php echo get_the_ID(); ?>">+ Add to Trip</a>
              </div>
              <?php if($post->post_content != ''): ?>
                <h3 class="article-title">Details:</h3>
                <?php the_content(); ?>
              <?php endif; ?>
          <?php endwhile; endif; ?>
          </article>
        <?php if(get_field('gallery')): ?>
          </div>
          <div class="col-sm-5 col-sm-pull-7">
            <div class="gallery">
              <?php 
                $images = get_field('gallery');
                if(has_post_thumbnail()){
                  $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                }
                else{
                  $featured_image = $images[0]['url'];
                }
                echo '<img src="' . $featured_image . '" class="img-responsive center-block" alt="' . get_the_title() . '" />';
                foreach($images as $image): ?>
                  <a href="<?php echo $image['url']; ?>" class="gallery-image" title="<?php echo $image['caption']; ?>">
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