<?
/*
  Template Name: History Page
  Description: Template for the history pages with nav menu on left.
*/

get_header(); ?>
  <main id="main">
    <div class="container">
      <div class="row">
        <div class="col-sm-3 col-lg-2 right-border">
          <nav id="history-nav">
            <?php
              $history_nav_args = array(
                'theme_location' => '_no_such_location',
                'menu' => 'History Pages Navigation',
                'container' => '',
                'container_id' => '',
                'menu_class' => 'list-unstyled',
                'menu_id' => '',
                'echo' => true,
                'fallback_cb' => false,
                'items_wrap' => '<ul id="%1$s" class=%2$s">%3$s</ul>',
                'depth' => 1
              );
              wp_nav_menu($history_nav_args);
            ?>
          </nav>
        </div>
        <div class="col-sm-9 col-lg-10">
          <article class="spotlight-post">
            <?php if(have_posts()): while(have_posts()): the_post(); ?>
              <h2><?php the_title(); ?></h2>
              <?php the_content(); ?>
            <?php endwhile; endif; ?>
          </article>
        </div>
      </div>
    </div>
  </main>
<?php get_footer(); ?>