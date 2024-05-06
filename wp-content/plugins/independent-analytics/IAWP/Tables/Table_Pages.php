<?php

namespace IAWP_SCOPED\IAWP\Tables;

use IAWP_SCOPED\IAWP\Rows\Pages;
use IAWP_SCOPED\IAWP\Statistics\Page_Statistics;
use IAWP_SCOPED\IAWP\Tables\Columns\Column;
use IAWP_SCOPED\IAWP\Tables\Groups\Group;
use IAWP_SCOPED\IAWP\Tables\Groups\Groups;
/** @internal */
class Table_Pages extends Table
{
    protected function table_name() : string
    {
        return 'views';
    }
    protected function groups() : Groups
    {
        $groups = [];
        $groups[] = new Group('page', \__('Page', 'independent-analytics'), Pages::class, Page_Statistics::class);
        return new Groups($groups);
    }
    protected function local_columns() : array
    {
        return [new Column(['id' => 'title', 'label' => \esc_html__('Title', 'independent-analytics'), 'visible' => \true, 'type' => 'string', 'database_column' => 'cached_title']), new Column(['id' => 'visitors', 'label' => \esc_html__('Visitors', 'independent-analytics'), 'visible' => \true, 'type' => 'int']), new Column(['id' => 'views', 'label' => \esc_html__('Views', 'independent-analytics'), 'visible' => \true, 'type' => 'int']), new Column(['id' => 'sessions', 'label' => \esc_html__('Sessions', 'independent-analytics'), 'visible' => \false, 'type' => 'int']), new Column(['id' => 'average_view_duration', 'label' => \esc_html__('View Duration', 'independent-analytics'), 'visible' => \true, 'type' => 'int', 'filter_placeholder' => 'Seconds']), new Column(['id' => 'bounce_rate', 'label' => \esc_html__('Bounce Rate', 'independent-analytics'), 'visible' => \true, 'type' => 'int']), new Column(['id' => 'visitors_growth', 'label' => \esc_html__('Visitors Growth', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'exportable' => \false]), new Column(['id' => 'views_growth', 'label' => \esc_html__('Views Growth', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'exportable' => \false]), new Column(['id' => 'entrances', 'label' => \esc_html__('Entrances', 'independent-analytics'), 'visible' => \false, 'type' => 'int']), new Column(['id' => 'exits', 'label' => \esc_html__('Exits', 'independent-analytics'), 'visible' => \false, 'type' => 'int']), new Column(['id' => 'exit_percent', 'label' => \esc_html__('Exit Rate', 'independent-analytics'), 'visible' => \false, 'type' => 'int']), new Column(['id' => 'url', 'label' => \esc_html__('URL', 'independent-analytics'), 'visible' => \true, 'type' => 'string', 'database_column' => 'cached_url']), new Column(['id' => 'author', 'label' => \esc_html__('Author', 'independent-analytics'), 'visible' => \false, 'type' => 'select', 'options' => $this->author_options(), 'database_column' => 'cached_author_id', 'is_nullable' => \true]), new Column(['id' => 'type', 'label' => \esc_html__('Page Type', 'independent-analytics'), 'visible' => \true, 'type' => 'select', 'options' => $this->type_options(), 'database_column' => 'cached_type', 'is_nullable' => \true]), new Column(['id' => 'date', 'label' => \esc_html__('Publish Date', 'independent-analytics'), 'visible' => \false, 'type' => 'date', 'database_column' => 'cached_date', 'is_nullable' => \true]), new Column(['id' => 'category', 'label' => \esc_html__('Post Category', 'independent-analytics'), 'visible' => \false, 'type' => 'select', 'options' => $this->category_options(), 'database_column' => 'cached_category', 'is_nullable' => \true]), new Column(['id' => 'comments', 'label' => \esc_html__('Comments', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'is_nullable' => \true]), new Column(['id' => 'wc_orders', 'label' => \esc_html__('Orders', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'requires_woocommerce' => \true]), new Column(['id' => 'wc_gross_sales', 'label' => \esc_html__('Gross Sales', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'requires_woocommerce' => \true]), new Column(['id' => 'wc_refunds', 'label' => \esc_html__('Refunds', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'requires_woocommerce' => \true]), new Column(['id' => 'wc_refunded_amount', 'label' => \esc_html__('Refunded Amount', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'requires_woocommerce' => \true]), new Column(['id' => 'wc_net_sales', 'label' => \esc_html__('Net Sales', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'requires_woocommerce' => \true]), new Column(['id' => 'wc_conversion_rate', 'label' => \esc_html__('Conversion Rate', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'requires_woocommerce' => \true]), new Column(['id' => 'wc_earnings_per_visitor', 'label' => \esc_html__('Earnings Per Visitor', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'requires_woocommerce' => \true]), new Column(['id' => 'wc_average_order_volume', 'label' => \esc_html__('Average Order Volume', 'independent-analytics'), 'visible' => \false, 'type' => 'int', 'requires_woocommerce' => \true])];
    }
    private function author_options() : array
    {
        return \array_map(function ($author) {
            return [$author->ID, $author->display_name];
        }, $this->get_authors());
    }
    private function get_authors() : array
    {
        $roles_that_can_edit_posts = [];
        foreach (\wp_roles()->roles as $role_name => $role_obj) {
            if ($role_obj['capabilities']['edit_posts'] ?? \false) {
                $roles_that_can_edit_posts[] = $role_name;
            }
        }
        return \get_users(['role__in' => $roles_that_can_edit_posts]);
    }
    private function type_options() : array
    {
        $options = [];
        $options[] = ['post', \esc_html__('Post', 'independent-analytics')];
        $options[] = ['page', \esc_html__('Page', 'independent-analytics')];
        $options[] = ['attachment', \esc_html__('Attachment', 'independent-analytics')];
        foreach (\get_post_types(['public' => \true, '_builtin' => \false]) as $custom_type) {
            $options[] = [$custom_type, \get_post_type_object($custom_type)->labels->singular_name];
        }
        $options[] = ['category', \esc_html__('Category', 'independent-analytics')];
        $options[] = ['post_tag', \esc_html__('Tag', 'independent-analytics')];
        foreach (\get_taxonomies(['public' => \true, '_builtin' => \false]) as $taxonomy) {
            $label = \get_taxonomy_labels(\get_taxonomy($taxonomy))->singular_name;
            /**
             * WooCommerce category and tag taxonomies have the same singular name as WordPress
             * category and tag taxonomies, so use the name here instead
             */
            if (\in_array($taxonomy, ['product_cat', 'product_tag'])) {
                $label = \get_taxonomy_labels(\get_taxonomy($taxonomy))->name;
            }
            $options[] = [$taxonomy, \ucwords($label)];
        }
        $options[] = ['blog-archive', \esc_html__('Blog Home', 'independent-analytics')];
        $options[] = ['author-archive', \esc_html__('Author Archive', 'independent-analytics')];
        $options[] = ['date-archive', \esc_html__('Date Archive', 'independent-analytics')];
        $options[] = ['search-archive', \esc_html__('Search Results', 'independent-analytics')];
        $options[] = ['not-found', \esc_html__('404', 'independent-analytics')];
        return $options;
    }
    private function category_options() : array
    {
        return \array_map(function ($category) {
            return [$category->term_id, $category->name];
        }, \get_categories());
    }
}
