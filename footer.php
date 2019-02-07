<?php if(is_tax('poi_types') || is_singular('poi')): ?>
  <section id="map-link">
    <div class="container">
      <a href="<?php echo esc_url(home_url('king-george-map')); ?>" class="btn-main btn-alt btn-rounded"><?php echo esc_html__('Explore on interactive map >', 'kinggeorge'); ?></a>
    </div>
  </section>
<?php endif; ?>

<?php 
  if(get_field('feature_random_points_of_interest', 'option') == 1){
    $featured_pois_query = new WP_Query(array(
      'post_type' => 'poi',
      'posts_per_page' => 4,
      'post_status' => 'publish',
      'orderby' => 'rand',
      'order' => 'ASC',
      'field' => 'ids'
    ));

    $featured_pois = $featured_pois_query->posts;
  }
  else{
    $featured_pois = get_field('featured_points_of_interest', 'option');
  }
  if($featured_pois): ?>
    <section id="also-like">
      <div class="container">
        <header class="section-header">
          <?php
            $featured_pois_section_title = get_option('options_featured_pois_section_title');
            $featured_pois_section_subtitle = get_option('options_featured_pois_section_subtitle');
          ?>
          <?php if($featured_pois_section_title): ?>
            <h2><?php esc_html_e($featured_pois_section_title); ?></h2>
          <?php endif; if($featured_pois_section_subtitle): ?>
            <p><?php esc_html_e($featured_pois_section_subtitle); ?></p>
          <?php endif; ?>
        </header>

        <div class="row">
          <?php foreach($featured_pois as $poi): ?>
            <div class="col-sm-3">
              <?php
                //$poi_image = get_field('default_poi_image', 'option');
                if(has_post_thumbnail($poi)){
                  $poi_image_url = get_the_post_thumbnail_url($poi, 'thumbnail');
                }
                else{
                  $poi_image_id = get_option('options_default_poi_image');
                  $poi_image = wp_get_attachment_image_src($poi_image_id, 'thumbnail');
                  $poi_image_url = $poi_image[0];
                }
              ?>
              <a href="<?php echo esc_url(get_permalink($poi)); ?>" class="quick-link" style="background-image:url(<?php echo esc_url($poi_image_url); ?>);">
                <div class="caption">
                  <h3><?php echo esc_html(get_the_title($poi)); ?></h3>
                </div>
                <div class="quick-link-overlay"></div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
<?php endif; ?>

<?php if(is_front_page()): ?>
  <?php 
    $alliances = get_post_meta($page_id, 'alliances', true);
    if(have_rows('alliances')): ?>
      <section id="alliances">
        <div class="container">
          <header class="section-header">
            <?php
              $alliances_section_title = get_field('alliances_section_title');
              $alliances_section_subtitle = get_field('alliances_section_subtitle');
            ?>
            <?php if($alliances_section_title): ?>
              <h2><?php esc_html_e($alliances_section_title); ?></h2>
            <?php endif; if($alliances_section_subtitle): ?>
              <p><?php esc_html_e($alliances_section_subtitle); ?></p>
            <?php endif; ?>
          </header>

          <ul class="list-unstyled list-inline">
            <?php
              $page_id = get_the_ID();
              
              for($a = 0; $a < $alliances; $a++):
                $alliance_link = get_post_meta($page_id, 'alliances_' . $a . '_alliance_link', true);
                $alliance_image_id = get_post_meta($page_id, 'alliances_' . $a . '_alliance_image', true);
                $alliance_image = wp_get_attachment_image_src($alliance_image_id, 'full');
                $alliance_image_alt = get_post_meta($alliance_image_id, '_wp_attachment_image_alt', true); ?>

                <li>
                  <a href="<?php echo esc_url($alliance_link); ?>" aria-label="<?php echo esc_attr($alliance_image_alt); ?>">
                    <img src="<?php echo esc_url($alliance_image[0]); ?>" class="img-responsive center-block" alt="<?php echo esc_attr($alliance_image_alt); ?>" />
                  </a>
                </li>
            <?php endfor; ?>
          </ul>
        </div>
      </section>
  <?php endif; ?>
<?php endif; ?>

  <footer id="footer">
    <div class="footer-main">
      <div class="container-fluid">
        <div class="col-sm-2">
          <img src="<?php echo get_stylesheet_directory_uri() . '/images/logo.png'; ?>" class="img-responsive center-block" alt="<?php echo esc_attr__('Logo', 'kinggeorge'); ?>" />
          <img src="<?php echo esc_url(get_field('king_george_seal', 'option')); ?>" class="img-responsive center-block" alt="<?php echo esc_attr__('King George Seal', 'kinggeorge'); ?>" style="margin-top:20px; max-width:135px;" />
          <img src="<?php echo esc_url(get_field('virginia_is_for_lovers_logo', 'option')); ?>" class="img-responsive center-block" style="margin-top:20px;" alt="<?php echo esc_attr__('Virginia Is For Lovers Logo', 'kinggeorge'); ?>" />
        </div>
        <div class="col-sm-6">
          <nav class="footer-nav">
            <div class="row">
              <div class="col-sm-4">
                <?php
                  $footer_nav_1 = kinggeorge_get_menu_by_location('footer-nav-1');
                  $footer_nav_1_title = $footer_nav_1 ? esc_html($footer_nav_1->name) : '';
                  $footer_nav_1_args = array(
                    'theme_location' => 'footer-nav-1',
                    'menu' => esc_html__('Footer Navigation 1', 'kinggeorge'),
                    'container' => '',
                    'container_id' => '',
                    'menu_class' => 'list-unstyled',
                    'menu_id' => '',
                    'echo' => true,
                    'fallback_cb' => false,
                    'items_wrap' => '<h3>' . esc_html($footer_nav_1_title) . '</h3><ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' => 1
                  );
                  wp_nav_menu($footer_nav_1_args);
                ?>
              </div>
              <div class="col-sm-4">
                <?php
                  $footer_nav_2 = kinggeorge_get_menu_by_location('footer-nav-2');
                  $footer_nav_2_title = $footer_nav_2 ? esc_html($footer_nav_2->name) : '';
                  $footer_nav_2_args = array(
                    'theme_location' => 'footer-nav-2',
                    'menu' => esc_html__('Footer Navigation 2', 'kinggeorge'),
                    'container' => '',
                    'container_id' => '',
                    'menu_class' => 'list-unstyled',
                    'menu_id' => '',
                    'echo' => true,
                    'fallback_cb' => false,
                    'items_wrap' => '<h3>' . esc_html($footer_nav_2_title) . '</h3><ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' => 1
                  );
                  wp_nav_menu($footer_nav_2_args);
                ?>
              </div>
              <div class="col-sm-4">
                <?php 
                  $footer_nav_3 = kinggeorge_get_menu_by_location('footer-nav-3');
                  $footer_nav_3_title = $footer_nav_3 ? esc_html($footer_nav_3->name) : '';
                  $footer_nav_3_args = array(
                    'theme_location' => 'footer-nav-3',
                    'menu' => esc_html__('Footer Navigation 3', 'kinggeorge'),
                    'container' => '',
                    'container_id' => '',
                    'menu_class' => 'list-unstyled',
                    'menu_id' => '',
                    'echo' => true,
                    'fallback_cb' => false,
                    'items_wrap' => '<h3>' . esc_html($footer_nav_3_title) . '</h3><ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' => 1
                  );
                  wp_nav_menu($footer_nav_3_args);
                ?>
              </div>
            </div>
          </nav>
        </div>
        <div class="col-sm-4">
          <div class="social">
            <?php
              $instagram = get_option('options_instagram');
              $twitter = get_option('options_twitter');
              $facebook = get_option('options_facebook');
            ?>
            <?php if($instagram): ?>
                <a href="<?php echo esc_url($instagram); ?>" class="instagram text-hide" target="_blank">Instagram<i class="fab fa-instagram"></i></a>
            <?php endif; if($twitter): ?>
              <a href="<?php echo esc_url($twitter); ?>" class="twitter text-hide" target="_blank">Twitter<i class="fab fa-twitter"></i></a>
            <?php endif; if($facebook): ?>
              <a href="<?php echo esc_url($facebook); ?>" class="facebook text-hide" target="_blank">Facebook<i class="fab fa-facebook"></i></a>
            <?php endif; ?>
          </div>
          <div itemscope itemtype="//schema.org/Organization" class="contact-info">
            <p itemprop="name"><?php esc_html_e(get_option('options_footer_contact_title')); ?></p>
            <address itemprop="address" itemscope itemtype="//schema.org/PostalAddress">
              <span itemprop="streetAddress"><?php esc_html_e(get_option('options_street_address')); ?></span><br /><span itemprop="addressLocality"><?php esc_html_e(get_option('options_city')); ?></span>, <span itemprop="addressRegion"><?php esc_html_e(get_option('options_state')); ?></span> <span itemprop="postalCode"><?php esc_html_e(get_option('options_zip')); ?></span>
            </address>
            <p itemprop="telephone"><?php esc_html_e(get_option('options_phone')); ?></p>
            <?php $county_link = get_option('options_county_link'); ?>
            <p><a href="<?php echo esc_url($county_link['url']); ?>" target="<?php echo esc_attr($county_link['target']); ?>"><?php esc_html_e($county_link['title']); ?></a></p>
          </div>
        </div>
      </div>
    </div>
    <div class="copyright">
      <div class="container">
        <p>&copy; <?php echo date('Y'); ?> <?php esc_html_e(get_option('options_footer_contact_title')); ?> &nbsp;|&nbsp; <a href="<?php echo esc_url(home_url('privacy-policy')); ?>"><?php echo esc_html__('Privacy Policy', 'kinggeorge'); ?></a> &nbsp;|&nbsp; <a href="<?php echo esc_url(home_url('list-your-business')); ?>"><?php echo esc_html('List your business', 'kinggeorge'); ?></a><br />
          <?php 
            $site_links = get_option('options_government_sites');
            if($site_links): ?>
              <?php echo esc_html__('Looking for our government websites?', 'kinggeorge'); ?>
              <?php 
                for($s = 0; $s < $site_links; $s++):
                  $site_link = get_option('options_government_sites_' . $s . '_site_link'); ?>

                  <a href="<?php echo esc_url($site_link['url']); ?>" target="<?php echo esc_attr($site_link['target']); ?>"><?php echo esc_html($site_link['title']); ?></a>
                  <?php if($s < count($site_links)){ echo ' &nbsp;|&nbsp; '; } ?>

              <?php endfor; ?>
          <?php endif; ?>
        </p>
        <p class="created-by">Website created by <a href="https://childressagency.com" target="_blank">The Childress Agency</a></p>
      </div>
    </div>
  </footer>
  <?php wp_footer(); ?>
</body>

</html>