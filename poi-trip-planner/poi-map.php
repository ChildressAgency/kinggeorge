<?php
if(!defined('ABSPATH')){ exit; } ?>

<section id="pois">
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-5 col-md-4">
      <div id="poi-nav" class="panel-group" role="tablist">
        <?php
          $poi_types = get_terms(array(
            'taxonomy' => 'poi_types',
            'hide_empty' => true,
          ));

          if(!empty($poi_types)):
            $p = 0;
            foreach($poi_types as $poi_type):
              $pois = new WP_Query(array(
                'post_type' => 'poi',
                'posts_per_page' => -1,
                'tax_query' => array(
                  array(
                    'taxonomy' => 'poi_types',
                    'field' => 'term_id',
                    'terms' => $poi_type->term_id
                  )
                )
              ));

              if($pois->have_posts()): ?>
                <div class="panel panel-default">
                  <div id="poi-type-heading-<?php echo $p; ?>" class="panel-heading" role="tab">
                    <h4 class="panel-title">
                      <a href="#poi-type-<?php echo $p; ?>" class="collapsed" data-toggle="collapse" data-parent="#poi-nav" role="button"  aria-expanded="false" aria-controls="poi-type-<?php echo $p; ?>"><?php echo $poi_type->name; ?><i class="fas fa-chevron-up"></i></a>
                    </h4>
                  </div>
                  <div id="poi-type-<?php echo $p; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="poi-type-heading-<?php echo $p; ?>" aria-expanded="false">
                    <ul class="list-group">
                      <?php while($pois->have_posts()): $pois->the_post(); ?>
                        <li class="list-group-item">
                          <?php 
                            $poi_lat = '';
                            $poi_lng = '';
                            $location = get_field('location'); 
                            if(!empty($location)){
                              $poi_lat = $location['lat'];
                              $poi_lng = $location['lng'];
                            }
                            $website = get_field('website');
                          ?>
                          <a href="#" data-poi_title="<?php echo get_the_title(); ?>" data-poi_description="<?php echo get_field('map_description'); ?>" data-poi_page="<?php echo get_permalink(); ?>" data-poi_website="<?php echo esc_url($website['url']); ?>" data-poi_lat="<?php echo $poi_lat; ?>" data-poi_lng="<?php echo $poi_lng; ?>" class="marker-link">
                            <?php the_title(); ?>
                          </a>
                        </li>
                      <?php endwhile; ?>
                    </ul>
                  </div>
                </div>
              <?php endif; wp_reset_postdata();
              $p++;
            endforeach;
          endif; ?>
      </div>
    </div>
    <div class="col-sm-7 col-md-8">
      <div id="poi-map" class="poi-map">

      </div>
    </div>
  </div>
</div>
</section>