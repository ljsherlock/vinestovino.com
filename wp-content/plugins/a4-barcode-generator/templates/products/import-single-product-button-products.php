<?php
use UkrSolution\ProductLabelsPrinting\Helpers\UserSettings;

$excluded = UserSettings::getOption('excludedProdStatuses', '');
$excluded = $excluded ? explode(",", $excluded) : array();
?>

<button type="button"
    class="su-generator-button button barcodes-import-single-product"
    data-variations="<?php echo $product && $product->get_type() === "variable" ? "1" : "0" ?>"
    data-action-type="products"
    data-post-id="<?php echo $post->ID ?>"
    data-post-status="<?php echo $post->post_status ?>"
    data-is-excluded="<?php echo in_array($post->post_status, $excluded) ? 1 : 0; ?>"
    title="Product Label"
    onclick="window.barcodesImportIdsType='simple'; window.barcodesImportIds=[<?php echo $post->ID ?>];">
    <span class="dashicons-before dashicons-tag"></span>
</button>

<?php
?>