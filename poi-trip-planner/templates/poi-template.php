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
                <?php 
                  $street_address = get_field('street_address');
                  $city_state_zip = get_field('city_state_zip');

                  if($street_address || $city_state_zip){
                    echo '<p class="poi-address">';
                      echo $street_address ? esc_html($street_address) : '';
                      if($street_address && $city_state_zip){ echo ' '; }
                      echo esc_html($city_state_zip);
                    echo '</p>';
                  }
                
                  $phone = get_field('phone');
                  echo $phone ? '<p class="poi-phone">' . esc_html($phone) . '</p>' : '';

                  $email = get_field('email');
                  echo $email ? '<p class="poi-email">' . sanitize_email($email) . '</p>' : '';

                  $website = get_field('website');
                  if(!empty($website)){
                    $website_title = $website['title'];
                    if($website_title == ''){
                      $website_title = $website['url'];
                    }

                    echo '<p class="poi-website"><a href="' . esc_url($website['url']) . '" target="_blank">' . esc_html($website_title) . '</a></p>';
                  }
                ?>
              </header>
              <div class="add-remove-trip">
                <a href="#" class="btn-main btn-rounded add-to-trip" data-poi_id="<?php echo get_the_ID(); ?>"><?php echo esc_html__('+ Add to Trip', 'poi_trip_planner'); ?></a>
              </div>
              <?php if($post->post_content != ''): ?>
                <h3 class="article-title"><?php echo esc_html__('Details:', 'poi_trip_planner'); ?></h3>
                <?php the_content(); ?>
              <?php endif; ?>
          <?php endwhile; endif; ?>
          </article>
        <?php if(get_field('gallery')): ?>
          </div>
          <div class="col-sm-5 col-sm-pull-7">
            <?php poi_gallery(); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>
<?php get_footer(); ?>