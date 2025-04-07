<?php

?>

<a href="#modal<?php echo get_the_ID() ?>" class="c-acsm__link <?php echo $class ?>" data-modal-type="<?php echo $modal_type ?>" data-modal-opener="modal<?php echo get_the_ID() ?>" ><?php echo $label ?></a>
<div id="modal<?php echo get_the_ID() ?>"  data-modal="modal<?php echo get_the_ID() ?>" style="display:none;" class="c-acsm">
<div class="c-acsm__content"><?php the_content();  ?></div>
</div>

