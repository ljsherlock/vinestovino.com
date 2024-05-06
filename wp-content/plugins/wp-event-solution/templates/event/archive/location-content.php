<?php
use \Etn\Utils\Helper as Helper;

defined( 'ABSPATH' ) || die();
$etn_event_location = get_post_meta( get_the_ID(), 'etn_event_location', true );
$existing_location  = Helper::cate_with_link(get_the_ID(), 'etn_location');
$etn_event_location_type = get_post_meta(get_the_ID(), 'etn_event_location_type', true);

?>

<?php  if (($etn_event_location_type === 'existing_location') && (isset($etn_event_location) && $etn_event_location != '')) { ?>
    <div class="etn-event-location">
        <i class="etn-icon etn-location"></i>
        <?php
            echo esc_html($etn_event_location); 
        ?>
    </div>
<?php } ?>
<?php if (($etn_event_location_type === 'new_location') && (isset($existing_location) && $existing_location != '')) { ?> 
    <div class="etn-event-location">
        <i class="etn-icon etn-location"></i>
        <?php
            echo Helper::kses($existing_location); 
        ?>
    </div>
<?php } ?>