<?php get_header(); ?>
<main id="main">
  <div class="container">
    <?php $poi_type = get_queried_object(); ?>
    <h2 class="page-header"><?php esc_html(get_field('poi_type_page_header', $poi_type)); ?></h2>
    <div class="row">
      <?php
        $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
        $pois = new WP_Query(array(
          'post_type' => 'poi',
          'posts_per_page' => 12,
          'post_status' => 'publish',
          'paged' => $paged,
          'orderby' => 'post_title',
          'order' => 'ASC',
          'tax_query' => array(
            array(
              'taxonomy' => 'poi_types',
              'field' => 'term_id',
              'terms' => $poi_type->term_id
            )
          )
        ));
        if($pois->have_posts()): while($pois->have_posts()): $pois->the_post(); ?>
        <div class="col-sm-4 col-md-3">
          <?php 
            if(has_post_thumbnail()){
              $poi_image = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            }
            else{
              if(get_field('poi_type_image', 'poi_types_' . $poi_type->term_id)){
                $category_image = get_field('poi_type_image', 'poi_types_' . $poi_type->term_id);
                $poi_image = $category_image['url'];
              }
              else{
                $poi_image = get_field('default_poi_image', 'option');
              }
            }
          ?>
          <a href="<?php the_permalink(); ?>" class="quick-link" style="background-image:url(<?php echo esc_url($poi_image); ?>);">
            <div class="caption">
              <h3><?php the_title(); ?></h3>
            </div>
            <div class="quick-link-overlay"></div>
          </a>
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
  </div>
</main>
<?php get_footer(); ?>