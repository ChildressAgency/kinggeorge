<form action="<?php echo home_url(); ?>" method="get">
  <label for="search" class="sr-only"><?php echo esc_html__('Search', 'kinggeorge'); ?></label>
  <div class="input-group">
    <input type="text" id="search" class="form-control" name="s" placeholder="<?php echo esc_html__('Search...', 'kinggeorge'); ?>" />
    <span class="input-group-btn">
      <button type="submit" class="search-icon" aria-label="Search"><span class="sr-only"><?php echo esc_html__('Search', 'kinggeorge'); ?></span></button>
    </span>
  </div>
</form>
