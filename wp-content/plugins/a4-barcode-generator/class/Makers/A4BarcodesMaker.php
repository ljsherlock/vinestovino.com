<?php
namespace UkrSolution\ProductLabelsPrinting\Makers;

use UkrSolution\ProductLabelsPrinting\Barcodes;
use UkrSolution\ProductLabelsPrinting\BarcodeTemplates\BarcodeTemplatesController;
use UkrSolution\ProductLabelsPrinting\Generators\ZATCA;
use UkrSolution\ProductLabelsPrinting\Helpers\UserSettings;
use UkrSolution\ProductLabelsPrinting\Profiles;

abstract class A4BarcodesMaker
{
    protected $items = array();
    protected $barcodes = array();
    protected $success = array();
    protected $errors = array();
    protected $showName = true;
    protected $showLine3 = true;
    protected $showLine4 = true;
    protected $a4barcodes;
    protected $activeTemplate;
    protected $pattern;
    protected $templateShortcodesArgs = array();
    protected $barcodeTemplateShortcodesArgs = array();
    protected $prodListTemplate = '';
    protected $prodListShortcode = '';
    protected $prodListShortcodesArgs = array();
    protected $tieredPriceTemplate = '';
    protected $tieredPriceShortcode = '';
    protected $tieredPriceShortcodesArgs = array();
    protected $data;
    protected $type;
    protected $profileId;

    public function __construct($data = array(), $type = '')
    {
        $this->data = $data;
        $this->type = $type;
        $this->a4barcodes = new Barcodes();

        $this->pattern  = '\[(([a-zA-Z0-9_-]+)=([^]]+))]'; 

        $customTemplatesController = new BarcodeTemplatesController();

        if (!empty($data['profileId']) && !empty(intval($data['profileId']))) {
            $profile = (new Profiles())->getProfile($data['profileId']);
            if (!empty($profile->templateId)) {
                $profileTemplate = $customTemplatesController->getTemplateById($profile->templateId);

                if (!empty($profileTemplate)) {
                    $this->activeTemplate = $profileTemplate;
                } else {
                    $this->activeTemplate = $customTemplatesController->getActiveTemplate();
                }
            } else {
                $this->activeTemplate = $customTemplatesController->getActiveTemplate();
            }
        } else {
            $this->activeTemplate = $customTemplatesController->getActiveTemplate();
        }
    }

    public function make($options = array())
    {
        $template = (isset($this->data["template"]) && $this->data["template"]) ? $this->data["template"] : $this->activeTemplate->template;

        $this->extractTemplateShortcodes($template, $this->templateShortcodesArgs);

        $this->getItems();

        if (isset($options["type"]) && $options["type"] === "custom") {
            $fileOptions = array(
                "quantity" => 1,
                "post_image" => "",
                "lineBarcode" => $this->data["lineBarcode"],
                "fieldLine1" => isset($this->data['fieldLine1']) ? $this->data['fieldLine1'] : "",
                "fieldLine2" => isset($this->data['fieldLine2']) ? $this->data['fieldLine2'] : "",
                "fieldLine3" => isset($this->data['fieldLine3']) ? $this->data['fieldLine3'] : "",
                "fieldLine4" => isset($this->data['fieldLine4']) ? $this->data['fieldLine4'] : "",
                "algorithm" => $this->data["format"],
                "showName" => 1,
                "showLine3" => true,
                "showLine4" => true,
                "replacements" => array(),
            );

            $fileImage = $this->a4barcodes->generateImageUrl($fileOptions, $options, true);

            $svgContent = "";

            $barcodeData = array(
                'ID' => "",
                'image' => $fileImage,
                'svgContent' => $svgContent,
                'post_image' => $fileOptions['post_image'],
                'lineBarcode' => $fileOptions['lineBarcode'],
                'fieldLine1' => $fileOptions['fieldLine1'],
                'fieldLine2' => $fileOptions['fieldLine2'],
                'fieldLine3' => $fileOptions['fieldLine3'],
                'fieldLine4' => $fileOptions['fieldLine4'],
                'format' => $fileOptions['algorithm'],
                'replacements' => $fileOptions['replacements'],
            );

            $this->barcodes[] = $barcodeData;
        } else {
            $this->generateBarcodes($options);
        }

        return $this->getResult();
    }

    public function extractTemplateShortcodes($template, &$shortcodesArgs)
    {
        $this->extractListTemplateData(
            $template,
            '[product-list-start', 
            '[product-list-end]',
            $this->prodListTemplate,
            $this->prodListShortcode,
            $this->prodListShortcodesArgs
        );
        $this->extractListTemplateData(
            $template,
            '[tiered-price-table-start', 
            '[tiered-price-table-end]',
            $this->tieredPriceTemplate,
            $this->tieredPriceShortcode,
            $this->tieredPriceShortcodesArgs
        );

        $shortcodesArgs = $this->getTemplateShortcodesArgs($template);
    }

    abstract protected function getItems();

    abstract protected function getFileOptions($itemData, $algorithm);

    protected function generateBarcodes($options = array())
    {
        $algorithm = $this->data['format'];

        $codePrefix = UserSettings::getOption('codePrefix', '');

        foreach ($this->items as $itemData) {
            $fileOptions = $this->getFileOptions($itemData, $algorithm);

            if (!empty($codePrefix)) {
                $fileOptions['lineBarcode'] = $codePrefix . $fileOptions['lineBarcode'];
            }

            $orderId = isset($itemData->ID) ? $itemData->ID : "";
            if (isset($options["templateId"]) && (int)$options["templateId"] === 10 && $orderId) {
                $sellerName =  isset($this->data["sellerName"]) ? $this->data["sellerName"] : "";
                $vatNumber =  isset($this->data["vatNumber"]) ? $this->data["vatNumber"] : "";

                $ZATCA = new ZATCA();
                $fileOptions['lineBarcode'] = $ZATCA->generateLineBarcodeData($itemData, $fileOptions['lineBarcode'],  $sellerName, $vatNumber);
            }

            $validationResult = $this->a4barcodes->validateBarcode($fileOptions['lineBarcode'], $fileOptions['algorithm'], $this->data);

            if ($validationResult['is_valid']) {
                if (isset($options['imageByUrl'])) {
                    $fileImage = $this->a4barcodes->generateImageUrl($fileOptions, $options, true);
                } else {
                    $fileImage = $this->a4barcodes->generateXml($fileOptions);
                    $fileImage = preg_replace('/<desc>.*<\/desc>/ms', '', $fileImage);
                }

                $svgContent = "";

                $orderItemId = "";
                if(isset($itemData->orderItem) && $itemData->orderItem) {
                    $orderItemId = @$itemData->orderItem->get_id();
                }

                $barcodeData = array(
                    'ID' => isset($itemData->ID) ? $itemData->ID : "",
                    'parentId' => isset($itemData->post_parent) ? $itemData->post_parent : "",
                    'image' => $fileImage,
                    'svgContent' => $svgContent,
                    'post_image' => $fileOptions['post_image'],
                    'lineBarcode' => $fileOptions['lineBarcode'],
                    'fieldLine1' => $fileOptions['fieldLine1'],
                    'fieldLine2' => $fileOptions['fieldLine2'],
                    'fieldLine3' => $fileOptions['fieldLine3'],
                    'fieldLine4' => $fileOptions['fieldLine4'],
                    'format' => $fileOptions['algorithm'],
                    'replacements' => $fileOptions['replacements'],
                    'orderItemId' => $orderItemId,
                );

                for ($i = $fileOptions['quantity']; $i > 0; --$i) {
                    $this->barcodes[] = $barcodeData;
                }
            } else { 
                $this->errors[] = array(
                    'id' => is_object($itemData) ? $itemData->ID : "",
                    'lineBarcode' => $validationResult['message'] ? $validationResult['message'] : $fileOptions['lineBarcode'],
                    'line1' => "",
                    'line2' => "",
                    'line3' => "",
                    'line4' => "",
                    'format' => $fileOptions['algorithm'],
                );
            }
        }
    }

    public function convertValueToBarcodeImageUrlIfNeed($field, $value)
    {
        if (
            !empty($value)
            && isset($field['args']['barcode-image'])
            && !empty($field['args']['barcode-image'])
        ) {
            $value = $this->getBarcodeImageUrl($value, $field['args']['barcode-image']);
        }

        return $value;
    }

    protected function getBarcodeImageUrl($value, $barcodeType)
    {
        $barcodeType = strtoupper($barcodeType);

        switch ($barcodeType) {
            case 'CODE128':
                $barcodeType = 'C128';
                break;
            case 'CODE39':
                $barcodeType = 'C39';
                break;
        }

        $validationResult = $this->a4barcodes->validateBarcode($value, $barcodeType, $this->data);

        if ($validationResult['is_valid']) {
            $imageUrl = $this->a4barcodes->generateXml(array(
                'lineBarcode' => $value,
                'algorithm' => $barcodeType,
            ));
            $imageUrl = preg_replace('/<desc>.*<\/desc>/ms', '', $imageUrl);
            $imageUrl = "data:image/svg+xml;base64," . base64_encode($imageUrl);
        } else {
            $imageUrl = '';
        }

        return $imageUrl;
    }

    protected function getResult()
    {
        $result = array(
            'listItems' => $this->barcodes,
            'success' => $this->success,
            'error' => $this->errors,
        );

        return $result;
    }

    protected function getTemplateReplacements($item, $shortcodesArgs)
    {
        return new \ArrayObject();
    }

    protected function getTemplateShortcodesArgs($template)
    {
        $shortcodesArgs = array();
        $shortcodesTagsList = array(
            'category',
            'product_dimensions',
            'order-single-item-qty',
            'order-total-items',
            'creation-date',
            'order-create-date',
            'order-completed-date',
            'product-name',
            'main-product-name',
            'product-description',
            'main-product-description',
            'creation-time',
            'sale-price-with-tax',
            'regular-price-with-tax',
            'actual-price-with-tax',
            'order-id',
            'order-product-qty',
            'order-tax',
            'items-subtotal',
            'main_product_image_url',
            'qty-range-amount',
            'qty-range-price',
            'random-digits',
            'current-date-time',
            'wprm-nutrition-label',
            'discount',
            'pickup-location-address',
            'pickup-location-title',
            'pickup-date',
            'product-store-link',

            'order-shipping-first-name',
            'order-shipping-last-name',
            'order-shipping-city',
            'order-shipping-address-1',
            'order-shipping-address-2',
            'order-shipping-postcode',
            'order-shipping-phone',
            'order-shipping-country',
            'order-shipping-country-full-name',
            'order-shipping-state',
            'order-shipping-state-full-name',
            'order-shipping-company',
            'order-shipping-full-name',

            'order-billing-first-name',
            'order-billing-last-name',
            'order-billing-city',
            'order-billing-address-1',
            'order-billing-address-2',
            'order-billing-postcode',
            'order-billing-phone',
            'order-billing-email',
            'order-billing-country',
            'order-billing-country-full-name',
            'order-billing-state',
            'order-billing-state-full-name',
            'order-billing-company',
            'order-billing-full-name',

            'order-tax',
            'order-shipping',
            'order-cart-discount',
            'order-subtotal',
            'order-total',
            'order-shipping-method',
            'order-customer-provided-note',

            'coupon-code',
            'coupon-expire-date',
            'coupon-creation-date',
            'coupon-type',
            'coupon-amount',

            'user-id',
            'user-name',
            'user-display-name',
            'user-email',
            'user-username',
            'user-register-date',
            'user-role',
            'autologin-links',
            'user-shipping-first-name',
            'user-shipping-last-name',
            'user-shipping-city',
            'user-shipping-address-1',
            'user-shipping-address-2',
            'user-shipping-postcode',
            'user-shipping-country',
            'user-shipping-country-full-name',
            'user-shipping-state',
            'user-shipping-state-full-name',
            'user-shipping-company',
            'user-shipping-phone',
            'user-shipping-full-name',
            'user-billing-first-name',
            'user-billing-last-name',
            'user-billing-city',
            'user-billing-address-1',
            'user-billing-address-2',
            'user-billing-postcode',
            'user-billing-phone',
            'user-billing-email',
            'user-billing-country',
            'user-billing-country-full-name',
            'user-billing-state',
            'user-billing-state-full-name',
            'user-billing-company',
            'user-billing-full-name',

            'product_image_url',
            'main_gallery',
            'variation-all-attr',
            'product_id_prefix',

            'atum-order-id',
            'atum-order-name',
            'atum-order-supplier',
            'atum-order-status',
            'atum-order-total',
            'atum-order-cart-tax',
            'atum-order-discount-total',
            'atum-order-discount-tax',
            'atum-order-shipping-total',
            'atum-order-shipping-tax',
            'atum-order-total-tax',
            'atum-order-date-created',
            'atum-order-date-expected',
            'atum-order-item-meta-field',
            'atum-order-supplier-name',
            'atum-order-supplier-code',
            'pbet-product-expire-date',
            'pbet-product-batch',
        );

        $shortcodesTagsList = apply_filters('barcode_generator_register_shortcodes_hook', $shortcodesTagsList);

        if (preg_match_all('/' . $this->pattern . '/s', $template, $matches)) {
            foreach ($matches[0] as $key => $shortCode) {
                $shortcodesArgs[$shortCode] = $this->templateParseAtts($matches[1][$key]);
            }
        }

        foreach ($shortcodesTagsList as $shortcodeTag) {
            if (preg_match_all("/\[$shortcodeTag(.*?)]/s", $template, $matches)) {
                foreach ($matches[0] as $key => $shortCode) {
                    $shortcodesArgs[$shortCode] = $this->templateParseAtts(trim($matches[0][$key], '[]'));
                }
            }
        }



        return $shortcodesArgs;
    }

    protected function templateParseAtts($attsString)
    {
        $result = array('term_meta' => null);
        $params = shortcode_parse_atts($attsString);

        $i = 1;
        foreach ($params as $key => $value) {
            if ($i === 1) {
                $result['type'] = 0 !== $key ? $key : $value;
                $result['value'] = $value;
            } elseif ('term' === $key) {
                $result['term_meta'] = $value;
            } else {
                $result[$key] = $value;
            }
            $i++;
        }

        return $result;
    }

    protected function extractListTemplateData($labelTemplate, $listStartShortcode, $listEndShortcode, &$listTemplate, &$listShortcode, &$listShortcodeArgs)
    {

        $prodListStart = strpos($labelTemplate, $listStartShortcode);
        $prodListEnd = strpos($labelTemplate, $listEndShortcode);

        if (false !== $prodListStart && false !== $prodListEnd) {
            $prodListStartCloseBracer = strpos($labelTemplate, ']', $prodListStart);
            $prodListEndCloseBracer = strpos($labelTemplate, ']', $prodListEnd);

            $listTemplate = trim(substr($labelTemplate, $prodListStartCloseBracer + 1, $prodListEnd - $prodListStartCloseBracer - 1));

            $listShortcode = substr($labelTemplate, $prodListStart, $prodListEndCloseBracer - $prodListStart + 1);

            $labelTemplate = substr($labelTemplate, 0, $prodListStart) . substr($labelTemplate, $prodListEndCloseBracer + 1);
            $this->activeTemplate->template = $labelTemplate;
        }

        if (!empty($listTemplate)) {
            $listShortcodeArgs = $this->getTemplateShortcodesArgs($listTemplate);
        }
    }

    protected function twoColumnLineFormat($fieldLine1, $fieldSepLine1)
    {
        if (!empty($fieldLine1) && !empty($fieldSepLine1)) {
            $result = $fieldLine1 . '&nbsp;' . $fieldSepLine1;
        } elseif (!empty($fieldLine1)) {
            $result = $fieldLine1;
        } elseif (!empty($fieldSepLine1)) {
            $result = $fieldSepLine1;
        } else {
            $result = '';
        }

        return ($this->activeTemplate->is_base && !empty($result))
            ? "<span>{$result}</span>"
            : $result;
    }

    public static function getTemplateShortcodes($template)
    {
        $shortcodesArgs = array();
        $barcodesGenerator = new WoocommercePostsA4BarcodesMaker(array());
        $barcodesGenerator->extractTemplateShortcodes($template, $shortcodesArgs);

        return $shortcodesArgs;
    }
}
