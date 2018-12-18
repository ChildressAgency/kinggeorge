<?php get_header(); ?>
  <section id="mytrip-steps">
    <div class="container">
      <h2 class="page-header"><?php the_field('page_header'); ?></h2>
      <div class="row">
        <div class="col-sm-4">
          <div class="mytrip-step">
            <span class="step-number"><span>1</span></span>
            <p><?php echo wp_kses_post(get_field('step_one_text')); ?></p>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="mytrip-step">
            <span class="step-number"><span>2</span></span>
            <p><?php echo wp_kses_post(get_field('step_two_text')); ?></p>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="mytrip-step">
            <span class="step-number"><span>3</span></span>
            <p><?php echo wp_kses_post(get_field('step_three_text')); ?></p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php if(have_posts()): while(have_posts()): the_post(); ?>
    <?php the_content(); ?>
  <?php endwhile; endif; ?>
<?php get_footer(); ?>