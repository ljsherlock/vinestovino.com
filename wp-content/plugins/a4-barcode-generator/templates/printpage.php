<?php
?>
<!DOCTYPE html>
<html lang="en"
    translate="no">

<head>
    <title>Print page</title>
    <?php echo implode(USWBG_print_lStylePath('public/dist/css/app_demo_3.4.6-4a7b21a1.css')); ?>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Oxygen-Sans, Ubuntu, Cantarell, \"Helvetica Neue\", sans-serif;
            font-size: 13px;
            line-height: 1.4em;
        }

        #wpwrap {
            height: auto;
            min-height: 100%;
            width: 100%;
            position: relative;
            -webkit-font-smoothing: subpixel-antialiased;
        }

        #wpcontent {
            height: 100%;
            padding-left: 20px;
        }
    </style>
    <meta name="google"
        content="notranslate">
    <meta http-equiv="expires"
        content="Sun, 01 Jan 2014 00:00:00 GMT" />
    <meta http-equiv="pragma"
        content="no-cache" />
</head>

<body class='body-defender'>
    <div id='wpwrap'>
        <div id='wpcontent'>
            <div id='wpbody'>
                <div id='wpbody-content'></div>
            </div>
        </div>
    </div>
    <script>
        window.outline = <?php echo (string) (isset($_GET['grid']) && 'true' == sanitize_key($_GET['grid'])) ? 'true' : 'false'; ?>;
        window.profileId = <?php echo (int) isset($_GET['profile']) ? (int)sanitize_key($_GET['profile']) : 0; ?>;
        window.paperId = <?php echo (int) isset($_GET['paper']) ? (int)sanitize_key($_GET['paper']) : 0; ?>;
        window.sheetId = <?php echo (int) isset($_GET['sheet']) ? (int)sanitize_key($_GET['sheet']) : 0; ?>;
        window.barcodes = {
            nativePage: true
        };
        window.ajaxurl = '<?php echo A4B_SITE_BASE_URL . '/wp-admin/admin-ajax.php'; ?>';
        window.a4bjs = {};
        window.a4bjs.pluginType = '<?php echo A4B_PLUGIN_TYPE; ?>';
        window.a4bjs.printPage = true;
        window.a4bjs.active_template_uol = '<?php echo (int) $chosenTemplateRow->uol_id === 2 ? 'in' : 'mm'; ?>';
        window.a4bjs.activeTemplateData = <?php echo json_encode($chosenTemplateRow); ?>;
        window.a4bjs.dimensions = <?php echo json_encode($dimensions); ?>;
        window.a4bjs.active_template = <?php echo json_encode(preg_replace("/\s\s?+/", ' ', trim($chosenTemplateRow->template))); ?>;
        window.a4barcodesL10n = <?php echo json_encode($jsL10n); ?>;
        window.a4barcodesGS = <?php echo json_encode($generalSettings); ?>;
        window.a4barcodesUS = <?php echo json_encode($userSettings); ?>;
        window.a4bjs.websiteUrl = '<?php echo $websiteUrl; ?>';
        window.a4bjs.uid = '<?php echo $uid; ?>';
    </script>
    <script src='<?php echo A4B_SITE_BASE_URL . '/wp-includes/js/jquery/jquery.js'; ?>'></script>
    <?php echo implode(USWBG_print_lScriptPath('assets/js/jszip.min-3.4.6-4a7b21a1.js')); ?>
<?php echo implode(USWBG_print_lScriptPath('assets/js/api-3.4.6-4a7b21a1.js')); ?>
<?php echo implode(USWBG_print_lScriptPath('public/dist/js/app_demo_3.4.6-4a7b21a1.js')); ?>
<?php echo implode(USWBG_print_lScriptPath('public/dist/js/chunk-vendors_demo_3.4.6-4a7b21a1.js')); ?>
</body>

</html>

<?php
?>