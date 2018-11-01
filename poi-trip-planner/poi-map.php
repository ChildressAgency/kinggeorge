<?php
if(!defined('ABSPATH')){ exit; } ?>

<section id="pois">
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-5 col-md-4">
      <nav id="poi-nav">
        <ul class="list-unstyled">
          <?php
            $poi_types = get_terms(array(
              'taxonomy' => 'poi_types',
              'hide_empty' => true,
            ));

            if(!empty($poi_types)):
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
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $poi_type->name; ?><i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                      <?php while($pois->have_posts()): $pois->the_post(); ?>
                        <li>
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
                          <a href="<?php the_permalink(); ?>" data-poi_title="<?php echo get_the_title(); ?>" data-poi_description="<?php echo get_field('map_description'); ?>" data-poi_website="<?php echo esc_url($website['url']); ?>" data-poi_lat="<?php echo $poi_lat; ?>" data-poi_lng="<?php echo $poi_lng; ?>">
                            <?php the_title(); ?>
                          </a>
                        </li>
                      <?php endwhile; ?>
                    </ul>
                  </li>
              <?php endif; wp_reset_postdata();
            endforeach;
          endif; ?>
        </ul>
      </nav>
    </div>
    <div class="col-sm-7 col-md-8">
      <div id="poi-map" class="poi-map">

      </div>
    </div>
  </div>
</div>
</section>