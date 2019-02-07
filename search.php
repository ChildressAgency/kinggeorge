<?php get_header(); ?>
<main id="main">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-8 col-md-9 right-border">
        <h2 class="search-results-header"><?php echo esc_html__('Showing results for', 'kinggeorge'); ?> "<?php echo get_search_query(); ?>"</h2>
        <?php
          if(have_posts()){
            while(have_posts()){
              the_post();
              get_template_part('partials/spotlight-loop');
            }
          }
          else{
            echo '<p>' . esc_html__('Sorry, we could not find what you were looking for.', 'kinggeorge') . '</p>';
          }
        ?>
      </div>
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>