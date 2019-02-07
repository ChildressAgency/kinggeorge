<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="">

  <meta http-equiv="cache-control" content="public">
  <meta http-equiv="cache-control" content="private">

  <title><?php echo esc_html(bloginfo('name')); ?></title>

  <?php wp_head(); ?>

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body <?php body_class(); ?>>
  <?php if(is_front_page()): ?>
    <?php 
      $front_page_id = get_the_ID();
      $hero_slides = get_post_meta($front_page_id, 'hero_slider', true);
      if($hero_slides == 1): ?>

        <?php $hero_bg_img_and_css = kinggeorge_get_bg_img_and_css($front_page_id, 'hero_slider_0_slide_image'); ?>

        <div class="hp-hero" style="background-image:url(<?php echo esc_url($hero_bg_img_and_css['image_url']); ?>); <?php echo esc_html($hero_bg_img_and_css['image_css']); ?>">
          <div class="hp-hero-caption">
            <h1><?php echo esc_html(get_post_meta($front_page_id, 'hero_title', true)); ?></h1>
            <h3><?php echo esc_html(get_post_meta($front_page_id, 'hero_subtitle', true)); ?></h3>
          </div>
        </div>

    <?php else: ?>

      <div id="hero-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" role="listbox">
          <?php for($i = 0; $i < $hero_slides; $i++): ?>
            
            <?php $slide_bg_img_and_css = kinggeorge_get_bg_img_and_css($front_page_id, 'hero_slider_' . $i . '_slide_image'); ?>
            <div class="item<?php if($i == 0){ echo ' active'; } ?>" style="background-image:url(<?php echo esc_url($slide_bg_img_and_css['image_url']); ?>); <?php echo esc_html($slide_bg_img_and_css['image_css']); ?>">
              <?php if(get_post_meta($front_page_id, 'hero_slider_' . $i . '_darken_image', true) == 1): ?>
                <div class="hp-hero-overlay"></div>
              <?php endif; ?>
            </div>
          <?php endfor; ?>
        </div>
        <div class="hp-hero-caption">
          <h1><?php echo esc_html(get_post_meta($front_page_id, 'hero_title', true)); ?></h1>
          <h1><?php echo esc_html(get_post_meta($front_page_id, 'hero_subtitle', true)); ?></h1>
        </div>
      </div>

    <?php endif; ?>
  <?php endif; ?>

  <?php 
    $home_page_nav_position = ' data-spy="affix" data-offset-top="395"';
    $other_page_nav_position = ' class="navbar-fixed-top"';
  ?>
  <nav id="header-nav"<?php echo (is_front_page()) ? $home_page_nav_position : $other_page_nav_position; ?>>
    <div class="<?php if(!is_front_page()){ echo 'container-fluid'; } ?>">
      <div class="navbar-header">
        <a href="<?php echo esc_url(home_url()); ?>" class="header-logo"><?php echo esc_html__('Visit King George', 'kinggeorge'); ?></a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only"><?php echo esc_html__('Toggle Navigation', 'kinggeorge'); ?></span>
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
        <a href="#" id="search-icon-menu" class="search-icon hidden-xs" aria-label="Search"><span class="sr-only"><?php echo esc_html__('Search', 'kinggeorge'); ?></span></a>
        <a href="<?php echo esc_url(home_url('my-trip')); ?>" id="my-trip-icon" data-count="0"><?php echo esc_html__('My Trip', 'kinggeorge'); ?></a>
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
      $page_id = get_the_ID(); 

      $hero_image = get_post_meta($page_id, 'hero_image', true);
      if($hero_image){
        $hero_image_and_css = kinggeorge_get_bg_img_and_css($page_id, 'hero_image');
      }
      else{
        $hero_image_and_css = [];
        $hero_image_id = get_option('options_default_hero_image');
        $hero_image = wp_get_attachment_image_src($hero_image_id, 'full');
        $hero_image_and_css['image_url'] = $hero_image[0];
        $hero_image_and_css['image_css'] = get_option('options_default_hero_image_css');
      }

      $hero_caption = '';
      if(is_home() || is_singular('post')){
        $hero_caption = '<img src="' . get_stylesheet_directory_uri() . '/images/spotlight-white.png' . '" class="img-responsive center-block" alt="' . esc_html__('Spotlight', 'kinggeorge') . '" />';
      }
      elseif(is_tax('poi_types') || has_term('', 'poi_types')){
        if(is_archive()){
          /*
          $queried_term = get_the_terms($page_id, 'poi_types');
          //var_dump(get_queried_object());
          $current_term_id = $queried_term[0]->term_id;
          $current_term = get_term($current_term_id, 'poi_types');
        
          //either show the current term or parent if it has one
          if($current_term->parent == 0){
            $term_name = $current_term->name;
          }
          else{
            $term_parent = get_term($current_term->parent, 'poi_types');
            $term_name = $term_parent->name;
          }*/
          $term = get_queried_object();
          $term_name = $term->name;
        }
        else{
          $term_name = get_the_term_list($page_id, 'poi_types', '', ', ');
        }
        $hero_caption = '<h1>' . $term_name . '</h1>';
      }
      elseif(is_post_type_archive('tribe_events') || is_singular('tribe_events')){
        $events_page = get_page_by_path('events');
        $events_page_id = $events_page->ID;
        $events_hero_caption = get_post_meta(598, 'hero_caption', true);
        $hero_caption = '<h1>' . esc_html($events_hero_caption) . '</h1>';
      }
      elseif(get_post_meta($page_id, 'hero_caption', true)){
        $hero_caption = '<h1>' . esc_html(get_post_meta($page_id, 'hero_caption', true)) . '</h1>';
      }
      else{
        $hero_caption = '<h1>' . esc_html(get_the_title()) . '</h1>';
      }
    ?>

    <div class="hero" style="background-image:url(<?php echo esc_url($hero_image_and_css['image_url']); ?>); <?php echo esc_html($hero_image_and_css['image_css']); ?>">
      <div class="container">
        <div class="caption">
          <?php echo $hero_caption; ?>
        </div>
      </div>
      <div class="hero-overlay"></div>
    </div>
  <?php endif; ?>