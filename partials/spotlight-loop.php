<div class="post-summary">
  <div class="row">
    <div class="col-sm-4">
      <?php
        if(has_post_thumbnail()){
          $post_image = get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('class' => 'img-responsive center-block'));
        }
        else{
          $post_image = '<img src="' . esc_url(get_option('options_default_spotlight_image')) . '" class="img-responsive center-block" alt="" />';
        }

        echo $post_image;
      ?>
    </div>
    <div class="col-sm-8">
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <?php the_excerpt(); ?>
    </div>
  </div>
</div>
