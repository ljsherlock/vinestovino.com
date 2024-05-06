<?php
/**
 * Speaker Importer Class
 *
 * @package Eventin
 */
namespace Etn\Core\Speaker;

use Etn\Base\Importer\Post_Importer_Interface;
use Etn\Base\Importer\Reader_Factory;

/**
 * Class Speaker Importer
 */
class Speaker_Importer implements Post_Importer_Interface {
    /**
     * Store File
     *
     * @var string
     */
    private $file;

    /**
     * Store data
     *
     * @var array
     */
    private $data;
    
    /**
     * Schedule import
     *
     * @return  void
     */
    public function import( $file ) {
        $this->file  = $file;
        $file_reader = Reader_Factory::get_reader( $file );

        $this->data = $file_reader->read_file();

        $this->create_speaker();
    }

    /**
     * Create schedule
     *
     * @return  void
     */
    private function create_speaker() {
        $speaker    = new Speaker_Model();
        $file_type  = ! empty( $this->file['type'] ) ? $this->file['type'] : '';
        $rows       = $this->data;
        
        foreach( $rows as $row ) {
            $args = [
                'etn_speaker_title'         => ! empty( $row['name'] ) ? $row['name'] : '',
                'etn_speaker_designation'   => ! empty( $row['designation'] ) ? $row['designation'] : '',
                'etn_speaker_website_email' => ! empty( $row['email'] ) ? $row['email'] : '',
                'etn_speaker_summery'       => ! empty( $row['summary'] ) ? $row['summary'] : '',
                'etn_speaker_socials'       => ! empty( $row['social'] ) ? $row['social'] : '',
                'etn_speaker_company_logo'  => ! empty( $row['company_logo'] ) ? $row['company_logo'] : '',
                'etn_speaker_url'           => ! empty( $row['company_url'] ) ? $row['company_url'] : '',
            ];

            $category =  'text/csv' == $file_type ? etn_csv_column_array( $row['category'] ) : $row['category'];

	    $args['etn_speaker_socials'] =  'text/csv' == $file_type ? etn_csv_column_multi_dimension_array( $row['social'] ) : $row['social'];

            $speaker->create( $args );
            $speaker->assign_post_terms( 'etn_speaker_category', $category );
        }
    }
}
