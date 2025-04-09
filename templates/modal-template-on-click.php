<?php if ($link_type === 'button') : ?>
  <div class="is-link-type-btn c-acsm-btn <?php echo esc_attr($class); ?>">
    <a href="#modal<?php echo esc_attr(get_the_ID()); ?>" class="c-acsm-btn__link" data-modal-type="<?php echo esc_attr($modal_type); ?>" data-modal-opener="modal<?php echo esc_attr(get_the_ID()); ?>">
      <span class="c-acsm-btn__label"><?php echo esc_html($label); ?></span>
    </a>
  </div>

<?php elseif ($link_type === 'text') : ?>
  <a href="#modal<?php echo esc_attr(get_the_ID()); ?>" class="is-link-type-text <?php echo esc_attr($class); ?>" data-modal-type="<?php echo esc_attr($modal_type); ?>" data-modal-opener="modal<?php echo esc_attr(get_the_ID()); ?>">
      <?php echo esc_html($label); ?>
  </a>

<?php elseif ($link_type === 'image' && $link_image) : ?>
  <a href="#modal<?php echo esc_attr(get_the_ID()); ?>" class="is-link-type-image <?php echo esc_attr($class); ?>" data-modal-type="<?php echo esc_attr($modal_type); ?>" data-modal-opener="modal<?php echo esc_attr(get_the_ID()); ?>">
    <div class="c-acsm-img-wrapper <?php echo ($modal_type === 'video') ? 'has-overlay-button' : 'has-label-below'; ?>">
        <?php echo wp_get_attachment_image($link_image, 'full'); ?>

        <?php if (!empty($label)) : ?>
            <?php if ($modal_type === 'video') : ?>
            <span class="c-acsm-img__button is-overlay"><?php echo esc_html($label); ?></span>
            <?php else : ?>
            <div class="c-acsm-img__label"><?php echo esc_html($label); ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
  </a>
<?php endif; ?>


<div id="modal<?php echo esc_attr(get_the_ID()); ?>" data-modal="modal<?php echo esc_attr(get_the_ID()); ?>" style="display:none;" class="c-acsm__modal is-on-click">
  <div class="c-acsm__content">
      <?php the_content(); ?>
  </div>
</div>
