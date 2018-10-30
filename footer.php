<?php if(is_tax('poi_types') || is_singular('poi')): ?>
  <section id="map-link">
    <div class="container">
      <a href="<?php echo home_url('king-george-map'); ?>" class="btn-main btn-alt btn-rounded">Explore our area through our interactive map ></a>
    </div>
  </section>
<?php endif; ?>

<?php 
  $featured_pois = get_field('featured_points_of_interest', 'option');
  if($featured_pois): ?>
    <section id="also-like">
      <div class="container">
        <header class="section-header">
          <?php if(get_field('featured_pois_section_title', 'option')): ?>
            <h2><?php the_field('featured_pois_section_title', 'option'); ?></h2>
          <?php endif; if(get_field('featured_pois_section_subtitle', 'option')): ?>
            <p><?php the_field('featured_pois_section_subtitle', 'option'); ?></p>
          <?php endif; ?>
        </header>

        <div class="row">
          <?php foreach($featured_pois as $poi): ?>
            <div class="col-sm-3">
              <?php
                $poi_image = get_field('default_poi_image', 'option');
                if(has_post_thumbnail($poi)){
                  $poi_image = get_the_post_thumbnail_url($poi, 'thumbnail');
                }
              ?>
              <a href="<?php echo get_permalink($poi); ?>" class="quick-link" style="background-image:url(<?php echo $poi_image; ?>);">
                <div class="caption">
                  <h3><?php echo get_the_title($poi): ?></h3>
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
  <?php if(have_rows('alliances')): ?>
    <section id="alliances">
      <div class="container">
        <header class="section-header">
          <?php if(get_field('alliances_section_title')): ?>
            <h2><?php the_field('alliances_section_title'); ?></h2>
          <?php endif; if(get_field('alliances_section_subtitle')): ?>
            <p><?php the_field('alliances_section_subtitle'); ?></p>
          <?php endif; ?>
        </header>

        <ul class="list-unstyled list-inline">
          <?php while(have_rows('alliances')): the_row(); ?>
            <li>
              <a href="<?php the_sub_field('alliance_link'); ?>">
                <?php $alliance_image = get_sub_field('alliance_image'); ?>
                <img src="<?php echo $alliance_image['url']; ?>" class="img-responsive center-block" alt="<?php echo $alliance_image['alt']; ?>" />
              </a>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </section>
  <?php endif; ?>
<?php endif; ?>

  <footer id="footer">
    <div class="footer-main">
      <div class="container-fluid">
        <div class="col-sm-2">
          <img src="<?php echo get_stylesheet_directory_uri() . '/images/logo.png'; ?>" class="img-responsive center-block" alt="Logo" />
        </div>
        <div class="col-sm-6">
          <nav class="footer-nav">
            <div class="row">
              <div class="col-sm-4">
                <?php
                  $footer_nav_1 = get_term('footer-nav-1', 'nav_menu');
                  $footer_nav_1_args = array(
                    'theme_location' => '_no_such_location',
                    'menu' => 'Footer Navigation 1',
                    'container' => '',
                    'container_id' => '',
                    'menu_class' => 'list-unstyled',
                    'menu_id' => '',
                    'echo' => true,
                    'fallback_cb' => false,
                    'items_wrap' => '<h3>' .esc_html($footer_nav_1->name) . '</h3><ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' => 1
                  );
                  wp_nav_menu($footer_nav_1_args);
                ?>
              </div>
              <div class="col-sm-4">
                <?php
                  $footer_nav_2 = get_term('footer-nav-2', 'nav_menu');
                  $footer_nav_2_args = array(
                    'theme_location' => '_no_such_location',
                    'menu' => 'Footer Navigation 2',
                    'container' => '',
                    'container_id' => '',
                    'menu_class' => 'list-unstyled',
                    'menu_id' => '',
                    'echo' => true,
                    'fallback_cb' => false,
                    'items_wrap' => '<h3>' . esc_html($footer_nav_2->name) . '</h3><ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth' => 1
                  );
                  wp_nav_menu($footer_nav_2_args);
                ?>
              </div>
              <div class="col-sm-4">
                <?php 
                  $footer_nav_3 = get_term('footer-nav-3', 'nav_menu');
                  $footer_nav_3_args = array(
                    'theme_location' => '_no_such_location',
                    'menu' => 'Footer Navigation 3',
                    'container' => '',
                    'container_id' => '',
                    'menu_class' => 'list-unstyled',
                    'menu_id' => '',
                    'echo' => true,
                    'fallback_cb' => false,
                    'items_wrap' => '<h3>' . esc_html($footer_nav_3->name) . '</h3><ul id="%1$s" class="%2$s">%3$s</ul>',
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
            <?php if(get_field('instagram', 'option')): ?>
              <a href="<?php the_field('instagram', 'option'); ?>" class="instagram text-hide" target="_blank">Instagram<i class="fab fa-instagram"></i></a>
            <?php endif; if(get_field('twitter', 'option')): ?>
              <a href="<?php the_field('twitter', 'option'); ?>" class="twitter text-hide" target="_blank">Twitter<i class="fab fa-twitter"></i></a>
            <?php endif; if(get_field('facebook', 'option')): ?>
              <a href="<?php the_field('facebook', 'option'); ?>" class="facebook text-hide" target="_blank">Facebook<i class="fab fa-facebook"></i></a>
            <?php endif; ?>
          </div>
          <div itemscope itemtype="//schema.org/Organization" class="contact-info">
            <p itemprop="name">King George County Department of Economic Development & Tourism</p>
            <address itemprop="address" itemscope itemtype="//schema.org/PostalAddress">
              <span itemprop="streetAddress"><?php the_field('street_address'); ?></span><br /><span itemprop="addressLocality"><?php the_field('city', 'option'); ?></span>, <span itemprop="addressRegion"><?php the_field('state', 'option'); ?></span> <span itemprop="postalCode"><?php the_field('zip', 'option'); ?></span>
            </address>
            <p itemprop="telephone"><?php the_field('phone', 'option'); ?></p>
          </div>
        </div>
      </div>
    </div>
    <div class="copyright">
      <div class="container">
        <p>&copy; <?php echo date('Y'); ?> King George Economic Development and Tourism &nbsp;|&nbsp; <a href="<?php echo home_url('privacy-policy'); ?>">Privacy Policy</a> &nbsp;|&nbsp; <a href="<?php echo home_url('list-your-business'); ?>">List your business</a><br />
          <?php if(have_rows('government_sites', 'option')): ?>
            Looking for our government websites? 
            <?php 
              $site_link = get_field('government_sites');
              for($s = 0; $s < count($site_link; $s++)): ?>
                <a href="<?php echo $site_link['site_link']['url']; ?>" target="<?php echo $site_link['site_link']['target']; ?>"><?php echo $site_link['site_link']['title']; ?></a>
                <?php if($s !== count($site_link) - 1){ echo ' &nbsp;|&nbsp; '; } ?>
            <?php endfor; ?>
          <?php endif; ?>
        </p>
      </div>
    </div>
  </footer>
  <?php wp_footer(); ?>
</body>

</html>