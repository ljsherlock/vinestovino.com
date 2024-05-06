<?php
?>

<div id="barcodes-wrapper-tooltip"
    style="float: initial;">
    <div id="barcodes-tooltip"><?php echo __("Select items and press this button to create barcodes.", "wpbcu-barcode-generator"); ?></div>
    <button type="button"
        name="barcodes_import_orders"
        id="barcodes-import-orders-items"
        class="button barcodes-import-orders-all-items"
        data-action-type="items"
        data-order-id="<?php echo $orderId; ?>"
        onclick="barcodesImportOrdersAllItems();">
        <span class="dashicons-before dashicons-tag"></span>
        <?php echo __("Product labels", "wpbcu-barcode-generator") ?>
    </button>
</div>

<script>
    const barcodesImportOrdersAllItems = () => {
        jQuery(".barcode-order-item input[type='checkbox']").each((i, e) => {
            jQuery(e).prop('checked', true);
        })
    }
</script>

<?php
?>