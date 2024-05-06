<?php
/**
 * Class Post Model
 * 
 * @package Eventin
 */
namespace Etn\Base;

/**
 * Class Post Model
 */
abstract class Post_Model {
    /**
     * Store post type
     *
     * @var string
     */
    protected $post_type;

    /**
     * Store id
     *
     * @var integer
     */
    public $id;

    /**
     * Constructor for Post Model Class
     *
     * @return  void
     */
    public function __construct( $post_id = 0 ) {
        if ( $post_id instanceof self ) {
            $this->id = $post_id;
        } elseif ( ! empty( $post_id->ID ) ) {
            $this->id = $post_id->ID;
        } elseif ( is_numeric( $post_id ) && $post_id > 0 ) {
            $this->id = $post_id;
        }
    }

    /**
     * Create post
     *
     * @param   array  $args
     *
     * @return  mixed
     */
    public function create( $args = [] ) {
        $defaults = [
            'post_type'     => $this->post_type,
            'post_status'   => 'publish',
        ];

        $args = wp_parse_args( $args, $defaults );
        $post_id = wp_insert_post( $args );

        if ( ! is_wp_error( $post_id ) ) {
            $this->id = $post_id;
            $this->update_meta( $args );

            return true;
        }

        return false;
    }


    /**
     * Update post
     *
     * @param   array  $args
     *
     * @return  bool
     */
    public function update( $args = [] ) {
        $defaults = [
            'ID'        => $this->id,
            'post_type' => $this->post_type,
        ];

        $args    = wp_parse_args( $args, $defaults );
        $post_id = wp_update_post( $args );

        if ( ! is_wp_error( $post_id ) ) {
            $this->id = $post_id;

            $this->update_meta( $args );

            return true;
        }

        return false;
    }

    /**
     * Delete post
     *
     * @return  bool
     */
    public function delete() {
        return wp_delete_post( $this->id );
    }

    /**
     * Update post meta
     *
     * @param   array  $data
     *
     * @return  void
     */
    public function update_meta( $data = [] ) {
        foreach( $data as $key => $value ) {
            update_post_meta( $this->id, $key, $value );
        }
    }

    /**
     * Assign post terms
     *
     * @param   Array  $terms
     *
     * @return mixed
     */
    public function assign_post_terms( $taxonomy, $terms = [] ) {
        wp_set_object_terms( $this->id, $terms, $taxonomy );
    }
}
