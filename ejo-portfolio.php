<?php
/**
 * Plugin Name:         EJO Portfolio
 * Plugin URI:          http://github.com/ejoweb/ejo-portfolio
 * Description:         Portfolio, the EJOweb way. 
 * Version:             0.2
 * Author:              Erik Joling
 * Author URI:          http://www.ejoweb.nl/
 *
 * GitHub Plugin URI:   https://github.com/erikjoling/ejo-portfolio
 * GitHub Branch:       dev 
 */

/* Portfolio */
EJO_Portfolio::init();

/**
 *
 */
final class EJO_Portfolio
{
	/* Version number of this plugin */
	public static $version = '0.2';

    /* Store the slug of this plugin */
    public static $slug = 'ejo-base';

	/* Store post type */
	public static $post_type = 'portfolio_project';

	/* Post type plural name */
	public static $post_type_plural = 'portfolio_projects';

	/* Post type archive */
	public static $post_type_archive = 'portfolio';

	/* Stores the directory path for this plugin. */
	public static $dir;

	/* Stores the directory URI for this plugin. */
	public static $uri;

	/* Holds the instance of this class. */
    private static $_instance = null;

    /* Only instantiate once */
    public static function init() 
    {
        if ( !self::$_instance )
            self::$_instance = new self;
        return self::$_instance;
    }

    //* No cloning
    private function __clone() {}

    /* Plugin setup. */
    private function __construct() 
    {
        //* Setup common plugin stuff
        self::setup();

        //* Immediatly include helpers
        self::helpers();

		/* Register Post Type */
		add_action( 'init', array( 'EJO_Portfolio', 'register_portfolio_post_type' ) );
    }

    
    /* Defines the directory path and URI for the plugin. */
    public static function setup() 
    {
        self::$dir = plugin_dir_path( __FILE__ );
        self::$uri = plugin_dir_url( __FILE__ );

		/* Load classes */
		include_once( self::$dir . 'includes/metabox-class.php' );
		include_once( self::$dir . 'includes/settings-class.php' );
    }

    /* Add helper functions */
    public static function helpers() 
    {
        /* Helper functions */
		include_once( self::$dir . 'includes/helpers.php' );
    }

	/* Register Post Type */
	public static function register_portfolio_post_type() 
	{
		/* Get portfolio settings */
		$portfolio_settings = get_option( 'portfolio_settings', array() );

		/* Archive title */
		$title = (isset($portfolio_settings['title'])) ? $portfolio_settings['title'] : self::$post_type_archive;

		/* Archive description */
		$description = (isset($portfolio_settings['description'])) ? $portfolio_settings['description'] : '';

		/* Archive slug */
		$archive_slug = (isset($portfolio_settings['archive-slug'])) ? $portfolio_settings['archive-slug'] : self::$post_type_archive;

		/* Register the Portfolio Project post type. */
		register_post_type(
			self::$post_type,
			array(
				'description'         => $description,
				'hierarchical'        => false,
				'menu_position'       => 26,
				'menu_icon'           => 'dashicons-portfolio',
				'public'              => true,
				'exclude_from_search' => false,
				'has_archive'         => $archive_slug,

				/* The rewrite handles the URL structure. */
				'rewrite' => array(
					'slug'       => $archive_slug,
					'with_front' => false,
				),

				/* What features the post type supports. */
				'supports' => array(
					'title',
					'editor',
					'excerpt',
					'author',
					'thumbnail'
				),

				/* Labels used when displaying the posts. */
				'labels' => array(
					'name'               => $title,
					'singular_name'      => __( 'Project',                    'ejo-portfolio' ),
					'menu_name'          => __( 'Portfolio',                  'ejo-portfolio' ),
					'name_admin_bar'     => __( 'Portfolio Project',          'ejo-portfolio' ),
					'add_new'            => __( 'Add New',                    'ejo-portfolio' ),
					'add_new_item'       => __( 'Add New Project',            'ejo-portfolio' ),
					'edit_item'          => __( 'Edit Project',               'ejo-portfolio' ),
					'new_item'           => __( 'New Project',                'ejo-portfolio' ),
					'view_item'          => __( 'View Project',               'ejo-portfolio' ),
					'search_items'       => __( 'Search Projects',            'ejo-portfolio' ),
					'not_found'          => __( 'No projects found',          'ejo-portfolio' ),
					'not_found_in_trash' => __( 'No projects found in trash', 'ejo-portfolio' ),
					'all_items'          => __( 'Projects',                   'ejo-portfolio' ),
				)
			)
		);
	}
}

