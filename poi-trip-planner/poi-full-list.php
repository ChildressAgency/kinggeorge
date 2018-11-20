<div class="attractions-list" role="list">
  <?php
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
    $pois = new WP_Query(array(
      'post_type' => 'poi',
      'posts_per_page' => 14,
      'post_status' => 'publish',
      'paged' => $paged,
      'orderby' => 'post_title',
      'order' => 'ASC'
    ));

    if($pois->have_posts()): while($pois->have_posts()): $pois->the_post(); ?>
      <div class="attractions-list-item" role="list-item">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php 
          $street_address = get_field('street_address');
          $city_state_zip = get_field('city_state_zip');

          if($street_address || $city_state_zip){
            echo '<p>';
              echo $street_address ? $street_address : '';
              if($street_address && $city_state_zip){ echo '<br />'; }
              echo $city_state_zip ? $city_state_zip : '';
            echo '</p>';
          }

          $phone = get_field('phone');
          echo $phone ? '<p>' . $phone . '</p>' : '';

          $website = get_field('website');
          if(!empty($website)){
            $website_title = $website['title'];
            if($website_title == ''){
              $website_title = $website['url'];
            }
            echo '<p><a href="' . $website['url'] . '" target="_blank">' . $website_title . '</a></p>';
          }

          $map_description = get_field('map_description');
          echo $map_description ? '<p>' . $map_description . '</p>' : '';
        ?>
      </div>
  <?php endwhile; endif; ?>
</div>
<div class="poi-pagination">
  <?php
    //https://codex.wordpress.org/Function_Reference/paginate_links
    $big = 999999999;
    echo paginate_links(array(
      'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
      'format' => '?paged=%#%',
      'current' => max(1, get_query_var('paged')),
      'total' => $pois->max_num_pages,
      'prev_text' => '<',
      'next_text' => '>'
    ));
  ?>
</div>