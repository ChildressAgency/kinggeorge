<?php get_header(); ?>
<main id="main">
  <div class="container">
    <?php $poi_type = get_queried_object(); ?>
    <h2 class="page-header"><?php the_field('poi_type_page_header', $poi_type); ?></h2>
    <div class="row">
      <?php if(have_posts()): while(have_posts()): the_post(); ?>
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
          <a href="<?php the_permalink(); ?>" class="quick-link" style="background-image:url(<?php echo $poi_image; ?>);">
            <div class="caption">
              <h3><?php the_title(); ?></h3>
            </div>
            <div class="quick-link-overlay"></div>
          </a>
        </div>
      <?php endwhile; endif; ?>
    </div>
  </div>
</main>
<?php get_footer(); ?>