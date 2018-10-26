<?php get_header(); ?>
  <main id="main">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-8 col-md-9 right-border">
          <?php
            if(is_single() || is_singular()){
              if(have_posts()){
                while(have_posts()){
                  the_post(); ?>
                  <article class="spotlight-post">
                    <h2><?php the_title(); ?></h2>
                    <?php the_content(); ?>
                  </article><?php
                }
              }
              else{
                echo '<p>Sorry, the page you were looking for could not be found.</p>';
              }
            }
            else{
              if(have_posts()){
                while(have_posts()){
                  the_post();
                  get_template_part('partials/spotlight-loop');
                }
              }
              else{
                echo '<p>Sorry, we could not find what you were looking for.</p>';
              }
            }
            wp_pagenavi();
          ?>
        </div>

        <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
      </div>
    </div>
  </main>
<?php get_footer(); ?>