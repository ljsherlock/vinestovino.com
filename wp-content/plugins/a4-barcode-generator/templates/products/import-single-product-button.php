<?php
use UkrSolution\ProductLabelsPrinting\Helpers\UserSettings;

$excluded = UserSettings::getOption('excludedProdStatuses', '');
$excluded = $excluded ? explode(",", $excluded) : array();
?>

<style>
    .barcodes-import-single-product {
        float: right;
        margin-left: 5px !important;
    }
</style>
<button type="button"
    class="su-generator-button button barcodes-import-single-product"
    data-action-type="products"
    data-post-id="<?php echo $post->ID ?>"
    data-post-status="<?php echo $post->post_status ?>"
    data-is-excluded="<?php echo in_array($post->post_status, $excluded) ? 1 : 0; ?>"
    title="Product Label"
    onclick="window.barcodesImportIdsType='simple'; window.barcodesImportIds=[<?php echo $post->ID ?>];">
    <span class="dashicons-before dashicons-tag"></span>
    <?php echo __("Product Label", "wpbcu-barcode-generator") ?>
</button>
<script>
    try {
        btns = document.querySelectorAll(".barcodes-import-single-product");

        if (btns.length > 1) {
            for (let i = 1; i < btns.length; i++) btns[i].remove();
        }
    } catch (error) {}
</script>

<?php
?>