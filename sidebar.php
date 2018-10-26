<div id="sidebar">
  <section class="sidebar-section">
    <div class="sidebar-search">
      <?php get_search_form(); ?>
    </div>
  </section>

  <section class="sidebar-section">
    <h3>Recent Spotlights</h3>
    <ul>
      <?php wp_get_archives(array('type' => 'postbypost', 'limit' => 4)); ?>
    </ul>
  </section>

  <section class="sidebar-section">
    <h3>Archives</h3>
    <ul>
      <?php wp_get_archives('type' => 'monthly'); ?>
    </ul>
  </section>

  <section class="sidebar-section">
    <h3>Categories</h3>
    <ul>
      <?php wp_list_categories(array('style' => 'list', 'title_li' => '')); ?>
    </ul>
  </section>

  <?php 
    if(is_active_sidebar('spotlight-sidebar')){
      dynamic_sidebar('spotlight-sidebar');
    }
  ?>
</div>
