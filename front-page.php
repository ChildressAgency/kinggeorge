<?php get_header(); ?>
  <section id="quick-links">
    <div class="container">
      <header class="section-header">
        <h2><?php the_field('featured_poi_types_title'); ?></h2>
        <p><?php the_field('featured_poi_types_subtitle'); ?></p>
      </header>
      <?php
        $featured_poi_categories = get_field('featured_poi_types'); 
        if($featured_poi_categories): ?>
          <div class="row">
            <?php foreach($featured_poi_categories as $poi_id): ?>
              <div class="col-sm-3">
                <?php 
                  $poi_image = get_field('default_poi_image', 'option'); 
                  if(get_field('poi_type_image', 'poi_types_' . $poi_id)){
                    $category_image = get_field('poi_type_image', 'poi_types_' . $poi_id);
                    $poi_image = $category_image['url'];
                  }

                  $poi_term_link = get_term_link($poi_id, 'poi_types');

                  $poi_term_object = get_term($poi_id, 'poi_types');
                  $poi_term_name = $poi_term_object->name;
                ?>
                <a href="<?php echo $poi_term_link; ?>" class="quick-link" style="background-image:url(<?php echo $poi_image; ?>);">
                  <div class="caption">
                    <h3><?php echo $poi_term_name; ?></h3>
                  </div>
                  <div class="quick-link-overlay"></div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      <?php if(get_field('featured_poi_types_content')): ?>
        <article>
          <?php the_field('featured_poi_types_content'); ?>
        </article>
      <?php endif; ?>
    </div>
  </section>

  <section id="instagram-feed">
        <div class="instagram-arrows">
          <span class="instagram_next"></span>
          <span class="instagram_prev"></span>
        </div>
    <h2><?php the_field('instagram_feed_section_title'); ?></h2>
    <div class="container">
      <h2 class="instagram-acct-name"><?php the_field('instagram_feed_title'); ?></h2>
      <p><?php the_field('instagram_feed_subtitle'); ?></p>
      <?php if(get_field('instagram', 'option')): ?>
        <a href="<?php the_field('instagram', 'option'); ?>" class="instagram-link text-hide" target="_blank"><i class="fab fa-instagram"></i></a>
      <?php endif; ?>
      <div class="instagram-feed">
        <?php //echo do_shortcode('[instagram-feed]'); ?>
        <?php
          $feed = kinggeorge_get_instagram_feed();

          foreach($feed->data as $post){
            echo '<a href="' . esc_url($post->link) . '" target="_blank"><img src="' . esc_url($post->images->low_resolution->url) . '" /></a>';
          }
        ?>
      </div>
    </div>
  </section>

  <?php
    $recent_posts = new WP_Query(array(
      'post_type' => 'post',
      'posts_per_page' => 3,
      'post_status' => 'publish'
    ));

    if($recent_posts->have_posts()): ?>
      <section id="spotlight">
        <header class="spotlight-header">
          <h2 class="sr-only">Spotlight</h2>
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/spotlight.png" alt="Spotlight" />
        </header>
        <div class="container">
          <?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
            <?php get_template_part('partials/spotlight-loop'); ?>
          <?php endwhile; ?>
          <a href="<?php echo home_url('spotlight'); ?>" class="btn-main">View All</a>
        </div>
      </section>
  <?php endif; wp_reset_postdata(); ?>

  <section id="home-pois">
    <header class="poi-header">
      <?php if(get_field('poi_section_title')): ?>
        <h2><?php the_field('poi_section_title'); ?></h2>
      <?php endif; if(get_field('poi_section_subtitle')): ?>
        <p><?php the_field('poi_section_subtitle'); ?></p>
      <?php endif; ?>
    </header>
    <?php echo do_shortcode('[poi_map]'); ?>
  </section>
<?php get_footer(); ?>