<?php get_header(); ?>
  <main id="main">
    <div class="container">
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
                echo '<p>' . esc_html__('Sorry, the page you were looking for could not be found.', 'kinggeorge') . '</p>';
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
                echo '<p>' . esc_html__('Sorry, we could not find what you were looking for.', 'kinggeorge') . '</p>';
              }
            }
            //wp_pagenavi();
          ?>
          <div class="pio-full-list-pagination">
            <?php
              //https://codex.wordpress.org/Function_Reference/paginate_links
              global $wp_query;
              $big = 999999999;
              echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $wp_query->max_num_pages
              ));
            ?>
          </div>
        </div>

        <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
      </div>
    </div>
  </main>
<?php get_footer(); ?>