<?php
/**
 * Speaker Exporter Class
 *
 * @package Eventin
 */
namespace Etn\Core\Speaker;

use Etn\Base\Exporter\Exporter_Factory;
use Etn\Base\Exporter\Post_Exporter_Interface;

/**
 * Class Speaker Exporter
 *
 * Export Speaker Data
 */
class Speaker_Exporter implements Post_Exporter_Interface {
    /**
     * Store file name
     *
     * @var string
     */
    private $file_name = 'speaker-data';

    /**
     * Store attendee data
     *
     * @var array
     */
    private $data;

    /**
     * Export attendee data
     *
     * @return void
     */
    public function export( $data, $format ) {
        $this->data = $data;

        $rows      = $this->prepare_data();
        $columns   = $this->get_columns();
        $file_name = $this->file_name;

        $exporter = Exporter_Factory::get_exporter( $format );

        $exporter->export( $rows, $columns, $file_name );
    }

    /**
     * Prepare data to export
     *
     * @return  array
     */
    private function prepare_data() {
        $ids           = $this->data;
        $exported_data = [];

        foreach ( $ids as $id ) {
            $term_obj_list  = get_the_terms( $id, 'etn_speaker_category' );
            $terms_string   = wp_list_pluck( $term_obj_list, 'slug');

            $speaker_data = [
                'id'           => $id,
                'name'         => get_post_meta( $id, 'etn_speaker_title', true ),
                'email'        => get_post_meta( $id, 'etn_speaker_website_email', true ),
                'designation'  => get_post_meta( $id, 'etn_speaker_designation', true ),
                'summary'      => get_post_meta( $id, 'etn_speaker_summery', true ),
		'social'      => get_post_meta( $id, 'etn_speaker_socials', true ),
                'company_logo' => get_post_meta( $id, 'etn_speaker_company_logo', true ),
                'company_url'  => get_post_meta( $id, 'etn_speaker_url', true ),
                'category'     => $terms_string,
            ];

            array_push( $exported_data, $speaker_data );
        }

        return $exported_data;
    }

    /**
     * Get columns
     *
     * @return  array
     */
    private function get_columns() {
        return [
            'id'           => esc_html__( 'Id', 'eventin' ),
            'name'         => esc_html__( 'Name', 'eventin' ),
            'designation'  => esc_html__( 'Designation', 'eventin' ),
            'email'        => esc_html__( 'Email', 'eventin' ),
            'summary'      => esc_html__( 'Summary', 'eventin' ),
	    'social'      => esc_html__( 'Social', 'eventin' ),
            'company_logo' => esc_html__( 'Company Logo', 'eventin' ),
            'company_url'  => esc_html__( 'Company Url', 'eventin' ),
            'category'     => esc_html__( 'Category', 'eventin' ),
        ];
    }
}
