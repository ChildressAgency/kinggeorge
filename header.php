<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="">

  <meta http-equiv="cache-control" content="public">
  <meta http-equiv="cache-control" content="private">

  <title><?php echo bloginfo('name'); ?></title>

  <?php wp_head(); ?>

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body <?php body_class(); ?>>
  <?php if(is_front_page()): ?>
    <div class="hp-hero" style="background-image:url(<?php the_field('hero_image'); ?>); <?php the_field('hero_image_css'); ?>">
      <div class="hp-hero-caption">
        <h1><?php the_field('hero_title'); ?></h1>
        <h3><?php the_field('hero_subtitle'); ?></h3>
      </div>
    </div>
  <?php endif; ?>

  <nav id="header-nav"<?php if(is_front_page()){ echo ' data-spy="affix" data-offset-top="395"'; } ?>>
    <div class="container_fluid">
      <div class="navbar-header">
        <a href="<?php echo home_url(); ?>" class="header-logo">King George</a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle Navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <div class="search-bar-wrapper">
          <div id="search-bar">
            <?php get_search_form(); ?>
          </div>
        </div>
        <a href="#" id="search-icon-menu" class="search-icon hidden-xs"></a>
        <a href="<?php echo home_url('my-trip'); ?>" id="my-trip-icon" data-count="0">My Trip</a>
        <?php
          $header_nav_args = array(
            'theme_location' => 'header-nav',
            'menu' => '',
            'container' => '',
            'container_id' => '',
            'menu_class' => 'nav navbar-nav navbar-right',
            'menu_id' => '',
            'echo' => true,
            'fallback_cb' => 'kinggeorge_header_fallback_menu',
            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'depth' => 2,
            'walker' => new wp_bootstrap_navwalker()
          );
          wp_nav_menu($header_nav_args);
        ?>
      </div>
    </div>
  </nav>

  <?php if(!is_front_page()): ?>
    <?php 
      //$hero_image = get_stylesheet_directory_uri() . '/images/pond.jpg';
      //$hero_image_css = 'background-position:center center;';
      $hero_image = get_field('default_hero_image', 'option');
      $hero_image_css = get_field('default_hero_image_css', 'option');

      if(get_field('hero_image')){
        $hero_image = get_field('hero_image');
        if(get_field('hero_image_css')){
          $hero_image_css = get_field('hero_image_css');
        }
        else{
          $hero_image_css = '';
        }
      }

      $hero_caption = '';
      if(is_home() || is_singular('post')){
        $hero_caption = '<img src="' . get_stylesheet_directory_uri() . '/images/spotlight-white.png' . '" class="img-responsive center-block" alt="Spotlight" />';
      }
      elseif(is_tax('poi_types') || has_term('', 'poi_types')){
        $queried_term = get_the_terms(get_the_ID(), 'poi_types');
        //var_dump($queried_term);
        $current_term_id = $queried_term[0]->term_id;
        $current_term = get_term($current_term_id, 'poi_types');
        
        //either show the current term or parent if it has one
        if($current_term->parent == 0){
          $term_name = $current_term->name;
        }
        else{
          $term_parent = get_term($current_term->parent, 'poi_types');
          $term_name = $term_parent->name;
        }
        $hero_caption = '<h1>' . $term_name . '</h1>';
      }
      elseif(is_post_type_archive('tribe_events') || is_singular('tribe_events')){
        $events_page = get_page_by_path('events');
        $events_page_id = $events_page->ID;
        $hero_caption = '<h1>' . get_field('hero_caption', 598) . '</h1>';
      }
      elseif(get_field('hero_caption')){
        $hero_caption = '<h1>' . get_field('hero_caption') . '</h1>';
      }
      else{
        $hero_caption = '<h1>' . get_the_title() . '</h1>';
      }
    ?>

    <div class="hero" style="background-image:url(<?php echo $hero_image; ?>); <?php echo $hero_image_css; ?>">
      <div class="container">
        <div class="caption">
          <?php echo $hero_caption; ?>
        </div>
      </div>
      <div class="hero-overlay"></div>
    </div>
  <?php endif; ?>