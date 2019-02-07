<?php
if(!defined('ABSPATH')){ exit; }

function poi_mytrip(){
  ob_start();
  $poi_ids = [];

  if(isset($_GET['poi_ids'])){
    $poi_ids_array = explode(',', $_GET['poi_ids']);

    $poi_ids = array_map(
      function($value){ return (int)$value; },
      $poi_ids_array
    );
  }
  else if(isset($_COOKIE['poi_ids'])){
    $poi_ids_cookie = $_COOKIE['poi_ids'];
    $poi_ids_array = explode(',', $poi_ids_cookie);

    $poi_ids = array_map(
      function($value){ return (int)$value; },
      $poi_ids_array
    );
  }
  ?>
  <section id="mytrip-share-link">
    <div class="container">
      <h3><?php echo esc_html__('Share Your Trip:', 'poi_trip_planner'); ?></h3>
      <?php if(!empty($poi_ids) && $poi_ids[0] != 0): ?>
        <input type="text" class="share-link" value="<?php echo esc_url(add_query_arg(array('poi_ids' => implode(',', $poi_ids)), home_url('my-trip'))); ?>" />
      <?php else: ?>
        <input type="text" class="share-link" value="<?php echo esc_html__('You haven\'t added any destinations.', 'poi_trip_planner'); ?>" />
      <?php endif; ?>
    </div>
  </section>

  <?php if(!empty($poi_ids) && $poi_ids[0] != 0): ?>
    <section id="mytrip-listings">
      <div class="container">
        <a href="#mytrip-map" class="btn-main btn-alt" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="mytrip-map"><?php echo esc_html__('Open Map', 'poi_trip_planner'); ?></a>
        <div id="mytrip-map" class="collapse">
          <div class="poi-map">

          </div>
        </div>
        <h3><?php echo esc_html__('Listings', 'poi_trip_planner'); ?></h3>
        <div class="row">
          <?php
            $p = 0;
            foreach($poi_ids as $poi_id):
              $poi = new WP_Query(array(
                'post_type' => 'poi',
                'p' => $poi_id
              ));
              
              if($poi->have_posts()): while($poi->have_posts()): $poi->the_post(); ?>
                <?php if($p % 3 == 0){ echo '<div class="clearfix"></div>'; } ?>
                <div class="col-sm-4">
                  <?php $location = get_field('location'); ?>
                  <div class="mytrip-listing">
                    <?php 
                      if(has_post_thumbnail()){
                        $poi_image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                      }
                      else{
                        $poi_image_id = get_option('options_default_poi_image');
                        $poi_image = wp_get_attachment_image_src($poi_image_id, 'full');
                        $poi_image_url = $poi_image[0];
                      }
                    ?>
                    <img src="<?php echo esc_url($poi_image_url); ?>" class="img-responsive center-block" alt="" />
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <p><?php esc_html_e(get_field('street_address')); ?>, <?php esc_html_e(get_field('city_state_zip')); ?></p>
                    <div class="add-remove-trip">
                      <a href="#" class="trip-link remove-trip remove-from-trip" data-poi_id="<?php echo get_the_ID(); ?>"><?php echo esc_html__('Remove From Trip', 'poi_trip_planner'); ?></a>
                    </div>
                    <a href="#mytrip-map" class="trip-link view-map marker-link" role="button" aria-expanded="false" aria-controls="mytrip-map" data-poi_title="<?php esc_html_e(get_the_title()); ?>" data-poi_description="<?php echo wp_kses_post(get_field('map_description')); ?>" data-poi_website="<?php echo esc_url(get_permalink()); ?>" data-poi_lat="<?php echo $location['lat']; ?>" data-poi_lng="<?php echo $location['lng']; ?>"><?php echo esc_html__('View on Map', 'poi_trip_planner'); ?></a>
                  </div>
                </div>
            <?php endwhile; endif; ?>
          <?php $p++; endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif;
  return ob_get_clean();
}