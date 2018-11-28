<?php get_header(); ?>
  <main id="main">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-9 right-border">
          <?php if(have_posts()): while(have_posts()): the_post(); ?>
            <?php get_template_part('partials/spotlight-loop'); ?>
          <?php endwhile; endif; ?>
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