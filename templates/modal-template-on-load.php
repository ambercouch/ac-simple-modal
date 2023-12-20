<?php
$post_slug = get_post_field('post_name', get_the_ID());
?>

<div id="modal<?php the_ID() ?>" data-modal-onload data-modal="modal-<?php echo $post_slug ?>" style="display:none;" class="c-acsm">
<div class="c-acsm__content">
    <?php the_content();  ?>
</div>
</div>



