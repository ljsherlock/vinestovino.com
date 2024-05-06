<?php
defined( 'ABSPATH' ) || exit;

$etn_speaker_summary            = get_post_meta(get_the_id(), 'etn_speaker_summery', true);
?>
<div class="etn-speaker-summery"> 
    <?php echo wpautop($etn_speaker_summary) ; ?>
</div>
