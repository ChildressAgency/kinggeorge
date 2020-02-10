<?php get_header(); ?>
  <section id="quick-links">
    <div class="container">
      <header class="section-header">
        <h2><?php esc_html_e(get_field('featured_poi_types_title')); ?></h2>
        <p><?php esc_html_e(get_field('featured_poi_types_subtitle')); ?></p>
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
                  
                  if($poi_term_object):
                    $poi_term_name = $poi_term_object->name;
                ?>
                <a href="<?php echo $poi_term_link; ?>" class="quick-link" style="background-image:url(<?php echo esc_url($poi_image); ?>);">
                  <div class="caption">
                    <h3><?php esc_html_e($poi_term_name); ?></h3>
                  </div>
                  <div class="quick-link-overlay"></div>
                </a>
                  <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      <?php if(get_field('featured_poi_types_content')): ?>
        <article>
          <?php echo wp_kses_post(get_field('featured_poi_types_content')); ?>
        </article>
      <?php endif; ?>
    </div>
  </section>

  <section id="instagram-feed">
        <div class="instagram-arrows">
          <span class="instagram_next"></span>
          <span class="instagram_prev"></span>
        </div>
    <h2><?php esc_html_e(get_field('instagram_feed_section_title')); ?></h2>
    <div class="container">
      <h2 class="instagram-acct-name"><?php esc_html_e(get_field('instagram_feed_title')); ?></h2>
      <p><?php esc_html_e(get_field('instagram_feed_subtitle')); ?></p>
      <?php 
        $instagram = get_option('options_instagram');
        if($instagram): ?>
        <a href="<?php echo esc_url($instagram); ?>" class="instagram-link text-hide" target="_blank"><span class="sr-only"><?php echo esc_html__('King George Instagram Page', 'kinggeorge'); ?></span><i class="fab fa-instagram"></i></a>
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
          <h2 class="sr-only"><?php echo esc_html__('Spotlight', 'kinggeorge'); ?></h2>
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/spotlight.png" alt="Spotlight" />
        </header>
        <div class="container">
          <?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
            <?php get_template_part('partials/spotlight-loop'); ?>
          <?php endwhile; ?>
          <a href="<?php echo esc_url(home_url('spotlight')); ?>" class="btn-main"><?php echo esc_html__('View All', 'kinggeorge'); ?></a>
        </div>
      </section>
  <?php endif; wp_reset_postdata(); ?>

  <section id="home-pois">
    <header class="poi-header">
      <?php 
        $poi_section_title = get_post_meta(get_the_ID(), 'poi_section_title', true);
        $poi_section_subtitle = get_post_meta(get_the_ID(), 'poi_section_subtitle', true);
        if($poi_section_title): ?>
        <h2><?php esc_html_e($poi_section_title); ?></h2>
      <?php endif; if($poi_section_subtitle): ?>
        <p><?php esc_html_e($poi_section_subtitle); ?></p>
      <?php endif; ?>
    </header>
    <?php echo do_shortcode('[poi_map]'); ?>
  </section>
<?php get_footer(); ?>