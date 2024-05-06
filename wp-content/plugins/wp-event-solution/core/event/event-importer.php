<?php
/**
 * Event Importer Class
 *
 * @package Eventin
 */
namespace Etn\Core\Event;

use Etn\Base\Importer\Post_Importer_Interface;
use Etn\Base\Importer\Reader_Factory;

/**
 * Class Event Importer
 */
class Event_Importer implements Post_Importer_Interface {
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
     * Event import
     *
     * @return  void
     */
    public function import( $file ) {
        $this->file  = $file;
        $file_reader = Reader_Factory::get_reader( $file );

        $this->data = $file_reader->read_file();
        $this->create_event();
    }

    /**
     * Create event
     *
     * @return  void
     */
    private function create_event() {
        $event     = new Event_Model();
        $file_type = ! empty( $this->file['type'] ) ? $this->file['type'] : '';

        $rows = $this->data;

        foreach ( $rows as $row ) {
            $args = [
                'post_title'                        => ! empty( $row['title'] ) ? sanitize_text_field( $row['title'] ) : '',
                'etn_select_speaker_schedule_type'  => ! empty( $row['schedule_type'] ) ? sanitize_text_field( $row['schedule_type'] ) : '',
                'etn_event_organizer'               => ! empty( $row['organizer'] ) ? sanitize_text_field( $row['organizer'] ) : '',
                'etn_event_speaker'                 => ! empty( $row['speaker'] ) ? sanitize_text_field( $row['speaker'] ) : '',
                'event_timezone'                    => ! empty( $row['timezone'] ) ? sanitize_text_field( $row['timezone'] ) : '',
                'etn_start_date'                    => ! empty( $row['start_date'] ) ? sanitize_text_field( $row['start_date'] ) : '',
                'etn_end_date'                      => ! empty( $row['end_date'] ) ? sanitize_text_field( $row['end_date'] ) : '',
                'etn_start_time'                    => ! empty( $row['start_time'] ) ? sanitize_text_field( $row['start_time'] ) : '',
                'etn_end_time'                      => ! empty( $row['end_time'] ) ? sanitize_text_field( $row['end_time'] ) : '',
                'etn_ticket_availability'           => ! empty( $row['ticket_availability'] ) ? sanitize_text_field( $row['ticket_availability'] ) : '',
                'etn_event_logo'                    => ! empty( $row['event_logo'] ) ? sanitize_text_field( $row['event_logo'] ) : '',
                'etn_event_calendar_bg'             => ! empty( $row['calendar_bg'] ) ? sanitize_text_field( $row['calendar_bg'] ) : '',
                'etn_event_calendar_text_color'     => ! empty( $row['calendar_text_color'] ) ? sanitize_text_field( $row['calendar_text_color'] ) : '',
                'etn_registration_deadline'         => ! empty( $row['registration_deadline'] ) ? sanitize_text_field( $row['registration_deadline'] ) : '',
                'attende_page_link'                 => ! empty( $row['attende_page_link'] ) ? sanitize_text_field( $row['attende_page_link'] ) : '',
                'etn_zoom_event'                    => ! empty( $row['zoom_event'] ) ? sanitize_text_field( $row['zoom_event'] ) : '',
                'etn_zoom_id'                       => ! empty( $row['zoom_id'] ) ? intval( $row['zoom_id'] ) : '',
                'etn_total_avaiilable_tickets'      => ! empty( $row['total_ticket'] ) ? sanitize_text_field( $row['total_ticket'] ) : '',
                'etn_total_sold_tickets'            => ! empty( $row['sold_tickets'] ) ? sanitize_text_field( $row['sold_tickets'] ) : '',
                'etn_google_meet'                   => ! empty( $row['google_meet'] ) ? sanitize_text_field( $row['google_meet'] ) : '',
                'etn_google_meet_link'              => ! empty( $row['google_meet_link'] ) ? sanitize_text_field( $row['google_meet_link'] ) : '',
                'etn_google_meet_short_description' => ! empty( $row['google_meet_description'] ) ? sanitize_text_field( $row['google_meet_description'] ) : '',
                'fluent_crm'                        => ! empty( $row['fluent_crm'] ) ? sanitize_text_field( $row['fluent_crm'] ) : '',
                'fluent_crm_webhook'                => ! empty( $row['fluent_crm_webhook'] ) ? sanitize_text_field( $row['fluent_crm_webhook'] ) : '',
            ];

            $location_type         = ! empty( $row['location_type'] ) ? sanitize_text_field( $row['location_type'] ) : '';
            $location              = ! empty( $row['location'] ) ? sanitize_text_field( $row['location'] ) : '';
            $ticket_variations     = ! empty( $row['ticket_variations'] ) ? $row['ticket_variations'] : '';
            $event_socials         = ! empty( $row['event_socials'] ) ? $row['event_socials'] : '';
            $event_schedule        = ! empty( $row['schedules'] ) ? $row['schedules'] : '';
            $event_faq             = ! empty( $row['faq'] ) ? $row['faq'] : '';
            $attendee_extra_fields = ! empty( $row['extra_fields'] ) ? $row['extra_fields'] : '';

            if ( 'new_location' == $location_type ) {
                $args['etn_event_location_list'] = $location;
            } else {
                $args['etn_event_location'] = $location;
            }

            $args['etn_event_location_type'] = $location_type;

            $args['etn_ticket_variations'] = $ticket_variations;
            $args['etn_event_socials']     = $event_socials;
            $args['etn_event_schedule']    = $event_schedule;
            $args['etn_event_faq']         = $event_faq;
            $args['attendee_extra_fields'] = $attendee_extra_fields;

            if ( 'text/csv' == $file_type ) {
                $args['etn_ticket_variations'] = etn_csv_column_multi_dimension_array( $ticket_variations );
                $args['etn_event_socials']     = etn_csv_column_multi_dimension_array( $event_socials );
                $args['etn_event_schedule']    = etn_csv_column_array( $event_schedule );
                $args['etn_event_faq']         = etn_csv_column_multi_dimension_array( $event_faq );
                $args['attendee_extra_fields'] = etn_csv_column_multi_dimension_array( $attendee_extra_fields );

                if ( 'new_location' == $location_type ) {
                    $args['etn_event_location_list'] = etn_csv_column_array( $location );
                }
            }

            $event->create( $args );
        }

    }
}
