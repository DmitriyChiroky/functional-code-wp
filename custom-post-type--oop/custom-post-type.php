<?php

class Custom_Post_Type {

    private $name;
    private $plural;
    private $taxonomy_name;
    private $taxonomy_plural;

    /**
     * Custom_Post_Type constructor.
     * @param $name
     * @param $plural
     */
    public function __construct( $name, $plural, $taxonomy_name = '', $taxonomy_plural = '' ) {

        $this->name = $name;
        $this->plural = $plural;
        $this->taxonomy_name = $taxonomy_name;
        $this->taxonomy_plural = $taxonomy_plural;

        add_action( 'init', array( &$this, 'register_post_type' ) );

        if ( ! empty( $taxonomy_name ) && ! empty( $taxonomy_plural ) ) {
            add_action( 'init', array( &$this, 'register_taxonomy' ) );
        }

    }

    /**
     * Register post type.
     */
    public function register_post_type() {

        // We set the amity labels based on the post type name and plural. We overwrite them with the given labels.
        $labels = array_merge(
            array(
                'name'                  => _x( $this->plural, 'post type general name' ),
                'singular_name'         => _x( $this->name, 'post type singular name' ),
                'add_new'               => _x( 'Add New', strtolower( $this->name ) ),
                'add_new_item'          => __( 'Add New ' . $this->name ),
                'edit_item'             => __( 'Edit ' . $this->name ),
                'new_item'              => __( 'New ' . $this->name ),
                'all_items'             => __( 'All ' . $this->plural ),
                'view_item'             => __( 'View ' . $this->name ),
                'search_items'          => __( 'Search ' . $this->plural ),
                'not_found'             => __( 'No ' . strtolower( $this->plural ) . ' found'),
                'not_found_in_trash'    => __( 'No ' . strtolower( $this->plural ) . ' found in Trash'),
                'parent_item_colon'     => '',
                'menu_name'             => $this->plural
            )
        );

        // Same principle as the labels. We set some amitys and overwrite them with the given arguments.
        $args = array_merge(
            array(
                'label'                 => $this->plural,
                'labels'                => $labels,
                'public'                => true,
                'show_ui'               => true,
                'supports'              => array( 'title', 'editor', 'thumbnail' ),
                'show_in_nav_menus'     => true,
                '_builtin'              => false,
                'has_archive'           => true,
            )
        );

        // Register the post type
        register_post_type( $this->name, $args );
    }

    /**
     * Register taxonomy.
     */
    public function register_taxonomy() {

        $args = array(
            'hierarchical'  => true,
            'labels'        => array(
                'name'              => _x( $this->taxonomy_plural, 'taxonomy general name' ),
                'singular_name'     => _x( $this->taxonomy_name, 'taxonomy singular name' ),
                'search_items'      =>  __( 'Search ' . $this->taxonomy_plural ),
                'all_items'         => __( 'All ' . $this->taxonomy_plural ),
                'parent_item'       => __( 'Parent ' . $this->taxonomy_name ),
                'parent_item_colon' => __( 'Parent ' . $this->taxonomy_name . ':' ),
                'edit_item'         => __( 'Edit ' . $this->taxonomy_name ),
                'update_item'       => __( 'Update ' . $this->taxonomy_name ),
                'add_new_item'      => __( 'Add New ' . $this->taxonomy_name ),
                'new_item_name'     => __( 'New ' . $this->taxonomy_name . ' Name' ),
                'menu_name'         => __( $this->taxonomy_name ),
            ),
            'show_ui'       => true,
            'query_var'     => true,
        );

        register_taxonomy( strtolower( $this->taxonomy_name ), strtolower( $this->name ), $args );
    }
}
