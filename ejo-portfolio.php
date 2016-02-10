<?php
/**
 * Plugin Name:         EJO Portfolio
 * Plugin URI:          http://github.com/ejoweb/ejo-portfolio
 * Description:         Portfolio, the EJOweb way. 
 * Version:             0.2
 * Author:              Erik Joling
 * Author URI:          http://www.ejoweb.nl/
 *
 * GitHub Plugin URI:   https://github.com/EJOweb/ejo-portfolio
 * GitHub Branch:       master
 */

// Store directory path of this plugin
define( 'EJO_PORTFOLIO_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'EJO_PORTFOLIO_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

/* Load classes */
include_once( EJO_PORTFOLIO_PLUGIN_DIR . 'includes/metabox-class.php' );
include_once( EJO_PORTFOLIO_PLUGIN_DIR . 'includes/settings-class.php' );

/* Portfolio */
EJO_Portfolio::init();

/* Metabox */
EJO_Portfolio_Metabox::init();

/* Settings */
EJO_Portfolio_Settings::init();

/**
 *
 */
final class EJO_Portfolio
{
	/* Version number of this plugin */
	public static $version = '0.2';

	/* Holds the instance of this class. */
	private static $_instance = null;

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

	/* Plugin setup. */
	protected function __construct() 
	{
		/* Load Helper Functions */
        add_action( 'plugins_loaded', array( $this, 'helper_functions' ), 1 );

		/* Add Theme Features */
        add_action( 'after_setup_theme', array( $this, 'theme_features' ) );

		/* Register Post Type */
		add_action( 'init', array( $this, 'register_portfolio_post_type' ) );
	}

    /* Add helper functions */
    public function helper_functions() 
    {
        /* Use this function to filter custom theme support with arguments */
        include_once( EJO_PORTFOLIO_PLUGIN_DIR . 'includes/theme-support-arguments.php' );

        /* Helper functions */
		include_once( EJO_PORTFOLIO_PLUGIN_DIR . 'includes/helpers.php' );
    }


    /* Add Features */
    public function theme_features() 
    {	
		/* Allow arguments to be passed for theme-support */
		add_filter( 'current_theme_supports-ejo-portfolio', 'ejo_theme_support_arguments', 10, 3 );
	}

	/* Register Post Type */
	public function register_portfolio_post_type() 
	{
		/* Get portfolio settings */
		$portfolio_settings = get_option( 'portfolio_settings', array() );

		/* Archive title */
		// $title = (isset($portfolio_settings['title'])) ? $portfolio_settings['title'] : 'Projects';

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
					'thumbnail',
					'custom-header'
				),

				/* Labels used when displaying the posts. */
				'labels' => array(
					'name'               => __( 'Projects',                   'ejo-portfolio' ),
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

	/* Returns the instance. */
	public static function init() 
	{
		if ( !self::$_instance )
			self::$_instance = new self;
		return self::$_instance;
	}
}

