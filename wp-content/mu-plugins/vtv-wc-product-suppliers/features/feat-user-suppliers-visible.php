<?php

/**
 * Feature: User Suppliers Visible 
 * 
 * - [x] Toggle suppliers visible user from the profile edit page.
 * - [ ] Show and hide products with matching suppliers 
 */

class User_Suppluers_Visible {
    
    public function __construct () {
        /**
         * View profile page of any user.
         * Confirm fields are rendered
         */
        add_filter( 'show_user_profile', array( $this, 'add_html_profile_fields' ));
        add_filter( 'edit_user_profile', array( $this, 'add_html_profile_fields' ) );

        /**
         * View profile page of any user.
         * Change all fields
         * Confirm that changes have been saved.
         */
        add_filter( 'personal_options_update', array( $this, 'save_html_profile_fields' ) );
        add_filter( 'edit_user_profile_update', array( $this, 'save_html_profile_fields' ) );      
        
        
        add_filter( 'pre_get_posts', array( $this, 'exclude_single_posts_home' ) );      
    }

    /**
     * @param User_Object $user 
     * 
     * @return void 
     */
    public function add_html_profile_fields ( $user ) {

        $suppliers = get_terms( array(
            'taxonomy'   => 'suppliers',
            'hide_empty' => false
        ) );
        

        $suppliers_visible = get_user_meta( $user->ID, 'visible_suppliers', true );

        if( ! is_array( $suppliers_visible ) ) {
            $suppliers_visible = explode(', ', $suppliers_visible);
        }

        ?>
        <h3><?php _e("Suppliers Visible", "blank"); ?></h3>
        <p>A checked supplier is visible and visa versa.</p>
    
        <table class="form-table" style="max-width 300px;">
            <tr>
                <td> 
                    <?php

                    foreach ( $suppliers as $supplier ) {
                    ?>
                        <label style="margin:0 8px 8px 0; display: inline-block;"> 
                                <input
                                    type="checkbox"
                                    name="suppliers[]"
                                    value="<?= $supplier->name ?>"
                                    <?= ( ! in_array($supplier->name, $suppliers_visible) ? 'checked="checked"' : "") ?>
                                    />
                                <?= $supplier->name ?> 
                        </label>
                
                    <?php } ?>
                </td>
            </tr>
        </table>
        <?php 
    }

    /**
     * @param int $user_id 
     * 
     * @return void | false
     */
    public function save_html_profile_fields ( $user_id ) {
    
        if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
            return;
        }
        
        if ( ! current_user_can( 'edit_user', $user_id ) ) { 
            return false; 
        }

        $suppliers = get_terms( array(
            'taxonomy'   => 'suppliers',
            'hide_empty' => false
        ) );

        $terms_string = wp_list_pluck($suppliers, 'name');

        if( is_array( $_POST['suppliers'] ) ) {

            $terms_to_add = [];

            foreach ( $terms_string as $term ) {

                if ( ! in_array( $term, $_POST['suppliers'] )) {
                    $terms_to_add[] = $term;
                }
            }

            update_user_meta( $user_id, 'visible_suppliers', $terms_to_add );
        } else {
            update_user_meta( $user_id, 'visible_suppliers', $terms_string );
        }
    }

    public function get_user_hidden_terms ( $user_id ) {

        return get_user_meta( $user_id, 'visible_suppliers', true );
    }

    /**
     * @todo the tax query is being added to all Queries and causing a lot of silent errors. 
     */
    function exclude_single_posts_home ( $wp_query ) {

        if ( is_admin() ) {
            return $wp_query;
        }

        // $response = telegram_log( "Archive Page: " . chr(10) . json_code_block( $wp_query->get('s') ) );

        /**
         *  This now filters queries of type 'product' and  no_found_rows to get
         *  product posts query.
         * @todo check that this is correctly hidding the right products (EK).
         */
        if ( $wp_query->get('post_type') === 'product' 
            && $wp_query->get('no_found_rows') === false || ! empty( $wp_query->get('product_cat') )
            || ! empty( $wp_query->get('s') ) 
        ) {
            
            $current_user_id = get_current_user_id();
            
            if ($current_user_id) {

                $terms = $this->get_user_hidden_terms( $current_user_id );
                $tax_query = array(
                    array(
                        "taxonomy" => "suppliers",
                        "field" => "slug",
                        "terms" => $terms,
                        "operator" => "NOT IN",
                    ), 
                    'relation' => 'AND',
                );
            }

            $wp_query->tax_query->queries[] = $tax_query; 
            $wp_query->query_vars['tax_query'] = $wp_query->tax_query->queries;

            $response = telegram_log( "Filtered Query for hidden suppliers: " . chr(10) . json_code_block( $wp_query->tax_query ) );
            
        }
    
            // $current_user_id = get_current_user_id();

            // if ($current_user_id) {
            //     $terms = $this->get_user_hidden_terms( $current_user_id );
            //     $add_to_tax_query = array(
            //         array(
            //             "taxonomy" => "suppliers",
            //             "field" => "slug",
            //             "terms" => $terms,
            //             "operator" => "NOT IN",
            //         ), 
            //         'relation' => 'AND',
            //     );
        
            //     if( $wp_query->is_main_query() ) {
            //         $wp_query->set( 'tax_query', $add_to_tax_query );
            //     } else {
            //         $wp_query->query_vars['tax_query'][] = $add_to_tax_query;
            //         $wp_query->query['tax_query'][] = $add_to_tax_query;
            //     }
            // }

        return $wp_query;
        
        // ! taxonomy_exists('suppliers')
        // if(  v || isset( $wp_query->query['fields'] ) && $wp_query->query['fields'] === 'ids' ) {
        //     return $wp_query;
        // }
        // if( isset( $wp_query->query['tax_query'] ) ) {
        //     return $wp_query;
        // }
        // if ( is_admin() || ! taxonomy_exists('suppliers') || $wp_query->query['post_type'] !== 'product' ) {
        //     return $wp_query;
        // }

        // die( var_dump( $wp_query->query ) );
        //  && $wp_query->query['fields'] === 'ids'
    }
}

function code_block ( $code ) {
    return chr(96).chr(96).chr(96).'json'.chr(10).
    json_encode($code )
    .chr(10).chr(96).chr(96).chr(96);
}

function pretty_print_dump ($variable) {
    ?> <pre> <?php
        var_dump( $variable );
    ?> </pre> <?php
}