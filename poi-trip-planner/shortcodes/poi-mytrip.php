<?php
if(!defined('ABSPATH')){ exit; }

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
    <h3>Share Your Trip:</h3>
    <?php if(!empty($poi_ids) && $poi_ids[0] != 0): ?>
      <input type="text" class="share-link" value="<?php echo esc_url(add_query_arg(array('poi_ids' => implode(',', $poi_ids)), home_url('poi_ids'))); ?>" />
    <?php else: ?>
      <input type="text" class="share-link" value="You haven't added any destinations." />
    <?php endif; ?>
  </div>
</section>

<?php if(!empty($poi_ids) && $poi_ids[0] != 0): ?>
  <section id="mytrip-listings">
    <div class="container">
      <a href="#mytrip-map" class="btn-main btn-alt" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="mytrip-map">Open Map</a>
      <div id="mytrip-map" class="collapse">
        <div class="poi-map">

        </div>
      </div>
      <h3>Listings</h3>
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
                      $poi_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    }
                    else{
                      $poi_image = get_field('default_poi_image', 'option');
                    }
                  ?>
                  <img src="<?php echo $poi_image; ?>" class="img-responsive center-block" alt="" />
                  <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                  <p><?php the_field('street_address'); ?>, <?php the_field('city_state_zip'); ?></p>
                  <div class="add-remove-trip">
                    <a href="#" class="trip-link remove-trip remove-from-trip" data-poi_id="<?php echo get_the_ID(); ?>">Remove From Trip</a>
                  </div>
                  <a href="#mytrip-map" class="trip-link view-map marker-link" role="button" aria-expanded="false" aria-controls="mytrip-map" data-poi_title="<?php echo get_the_title(); ?>" data-poi_title="<?php echo get_the_title(); ?>" data-poi_description="<?php echo get_field('map_description'); ?>" data-poi_website="<?php echo esc_url(get_permalink()); ?>" data-poi_lat="<?php echo $location['lat']; ?>" data-poi_lng="<?php echo $location['lng']; ?>">View on Map</a>
                </div>
              </div>
          <?php endwhile; endif; ?>
        <?php $p++; endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>