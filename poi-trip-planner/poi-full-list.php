<div class="attractions-list" role="list">
  <?php
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
    $pois = new WP_Query(array(
      'post_type' => 'poi',
      'posts_per_page' => 14,
      'post_status' => 'publish',
      'paged' => $paged
    ));

    if($pois->have_posts()): while($pois->have_posts()): $pois->the_post(); ?>
      <div class="attractions-list-item" role="list-item">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p><?php the_field('street_address'); ?><br /><?php the_field('city_state_zip'); ?></p>
        <p><?php the_field('phone_number'); ?></p>
        <?php 
          $website = get_field('website');
          $website_title = $website['title'];
          if($website_title !== ''){
            $website_title = $website['url'];
          }
        ?>
        <p><a href="<?php echo $website['url']; ?>" target="_blank"><?php echo $website_title; ?></a></p>
        <p><?php the_field('short_description'); ?></p>
      </div>
  <?php endwhile; endif; ?>
</div>
<div class="pio-full-list-pagination">
  <?php
    //https://codex.wordpress.org/Function_Reference/paginate_links
    $big = 999999999;
    echo paginate_links(array(
      'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
      'format' => '?paged=%#%',
      'current' => max(1, get_query_var('paged')),
      'total' => $pois->max_num_pages
    ));
  ?>
</div>