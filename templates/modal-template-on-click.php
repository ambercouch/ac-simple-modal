<a href="#modal<?php echo esc_attr(get_the_ID()); ?>" class="c-acsm__link <?php echo esc_attr($class); ?>" data-modal-type="<?php echo esc_attr($modal_type); ?>" data-modal-opener="modal<?php echo esc_attr(get_the_ID()); ?>">
    <?php echo esc_html($label); ?>
</a>

<div id="modal<?php echo esc_attr(get_the_ID()); ?>" data-modal="modal<?php echo esc_attr(get_the_ID()); ?>" style="display:none;" class="c-acsm">
  <div class="c-acsm__content c-acsm__content-on-click">
      <?php the_content(); ?>
  </div>
</div>
