<?php
/**
 * Admin Hooks Class
 *
 * @package Eventin
 */
namespace Etn\Core\Admin;

use Etn\Base\Exporter\Post_Exporter;
use Etn\Base\Importer\Post_Importer;
use Etn\Traits\Singleton;
use WP_Error;

/**
 * Admin Hooks Class
 */
class Hooks {
    use Singleton;

    /**
     * Initialize
     *
     * @return  void
     */
    public function init() {
        // Add export and import tab on post types
        add_action( 'manage_posts_extra_tablenav', [$this, 'add_export_import_button'] );

        add_action( 'admin_init', [$this, 'export_data'] );

        add_action( 'wp_ajax_etn_file_import', [ $this, 'import_file' ] );

        add_action( 'save_post', [ $this, 'add_flush_rules' ] );
    }

    /**
     * Add export and import button
     *
     * @return  void
     */
    public function add_export_import_button( $which ) {
        
        if ( 'top' != $which ) {
            return;
        }

        global $post_type_object;

        $export_posts = ['etn', 'etn-schedule', 'etn-speaker', 'etn-attendee'];
        $import_posts = ['etn-schedule', 'etn-speaker', 'etn', 'etn-attendee'];
        $nonce_action = 'etn_data_export_nonce_action';
        $nonce_name   = 'etn_data_export_nonce';

        $url      = admin_url( 'edit.php?post_type=' . $post_type_object->name );
        $json_url = $url . '&etn-action=export&format=json';
        $csv_url  = $url . '&etn-action=export&format=csv';

        // Export button.
        if ( in_array( $post_type_object->name, $export_posts ) ) {
            printf( '
            <div class="dropdown">
                <a href="#" class="button etn-post-export">%s</a>
                    <div class="dropdown-content">
                        <a href="%s">%s</a>
                        <a href="%s">%s</a>
                    </div>
            </div>
        ', __( 'Export', 'eventin' ), wp_nonce_url( $json_url, $nonce_action, $nonce_name ),  __( 'Export JSON Format', 'eventin' ), wp_nonce_url( $csv_url, $nonce_action, $nonce_name ), __( 'Export CSV Format', 'eventin' ) );
        }

        // Import Button.
        if ( in_array( $post_type_object->name, $import_posts ) ) {
            printf( '
            <a href="%s" class="button etn-post-import">%s</a>
        ', $url . '&action=import', __( 'Import', 'eventin' ) );
        
        }
    }

    /**
     * Export data
     *
     * @return  void
     */
    public function export_data() {
        $nonce = isset( $_GET['etn_data_export_nonce'] ) ? sanitize_text_field( $_GET['etn_data_export_nonce'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'etn_data_export_nonce_action' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $action    = isset( $_GET['etn-action'] ) ? sanitize_text_field( $_GET['etn-action'] ) : '';
        $post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
        $format    = isset( $_GET['format'] ) ? sanitize_text_field( $_GET['format'] ) : '';

        if ( 'export' != $action ) {
            return;
        }

        $post_ids      = $this->get_post_ids( $post_type );
        $post_exporter = Post_Exporter::get_post_exporter( $post_type );

        $post_exporter->export( $post_ids, $format );
    }

    /**
     * Get post ids
     *
     * @param   string  $post_type
     *
     * @return  array
     */
    private function get_post_ids( $post_type ) {
        $args = [
            'post_type'   => $post_type,
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields'      => 'ids',
        ];

        $posts = get_posts( $args );

        return $posts;
    }

    /**
     * Import file
     *
     * @return  void
     */
    public function import_file() {
        $nonce      = isset( $_POST['etn_data_import_nonce'] ) ? sanitize_text_field( $_POST['etn_data_import_nonce'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'etn_data_import_action' ) ) {
            return;
        }

        $file       = isset( $_FILES['file'] ) ? $_FILES['file'] : '';
        $post_type  = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : '';

        if ( ! $file ) {
            return new WP_Error( 'file_error', __( 'File can not be empty', 'eventin' ) );
        }

        $importer = Post_Importer::get_importer( $post_type );
        $importer->import( $file );

        wp_send_json_success( [
            'success'   => 1,
            'message'   => __( 'Successfully imported file', 'eventin' )
        ] );
    }

    /**
     * Add flush rewrite rules after saving a post
     *
     * @param   integer  $pos_id
     *
     * @return  void
     */
    public function add_flush_rules( $pos_id ) {
        $post_type = ! empty( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : '';

        $post_types = [
            'etn', 
            'etn-schedule', 
            'etn-speaker', 
            'etn-attendee', 
            'etn-zoom-meeting',
        ];

        if ( ! in_array( $post_type, $post_types ) ) {
            return;
        }

        flush_rewrite_rules();
    }
}