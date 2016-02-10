<?php

class EJO_Portfolio_Settings 
{
	/* Holds the instance of this class. */
	private static $_instance;

	/* Plugin setup. */
	public function __construct() 
	{
		/* Add Settings Page */
		add_action( 'admin_menu', array( $this, 'add_portfolio_setting_menu' ) );

		/* Register Settings for Settings Page */
		add_action( 'admin_init', array( $this, 'initialize_portfolio_settings' ) );

		/* Save settings (before init, because post type registers on init) */
		/* I probably should be using Settings API.. */
		add_action( 'init', array( $this, 'save_portfolio_settings' ), 1 );

		/* Add scripts to settings page */
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts_and_styles' ) ); 
	}

	/***********************
	 * Settings Page
	 ***********************/

	/* */
	public function add_portfolio_setting_menu()
	{
		add_submenu_page( 
			"edit.php?post_type=".EJO_Portfolio::$post_type, 
			'Portfolio Instellingen', 
			'Instellingen', 
			'edit_theme_options', 
			'portfolio-settings', 
			array( $this, 'portfolio_settings_page' ) 
		);
	}

	/* Register settings */
	public function initialize_portfolio_settings() 
	{
		// Add option if not already available
		if( false == get_option( 'portfolio_settings' ) ) {  
			add_option( 'portfolio_settings' );
		} 
	}

	/* Save portfolio settings */
	public function save_portfolio_settings()
	{
		if (isset($_POST['submit']) && !empty($_POST['portfolio-settings']) ) :

			/* Escape slug */
			$_POST['portfolio-settings']['archive-slug'] = sanitize_title( $_POST['portfolio-settings']['archive-slug'] );

			/* Strip slashes */
			$_POST['portfolio-settings']['description'] = stripslashes( $_POST['portfolio-settings']['description'] );

			/* Update settings */
			update_option( "portfolio_settings", $_POST['portfolio-settings'] ); 

		endif;
	}

	/* */
	public function portfolio_settings_page()
	{
	?>
		<div class='wrap' style="max-width:960px;">
			<h2>Portfolio Instellingen</h2>

			<?php 
			/* Let user know the settings are saved */
			if (isset($_POST['submit']) && !empty($_POST['portfolio-settings']) ) {

				flush_rewrite_rules(); /* Flush rewrite rules because archive slug could have changed */

				echo "<div class='updated'><p>Portfolio settings updated successfully.</p></div>";
			}
			?>

			<form action="<?php echo esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) ); ?>" method="post">
				<?php wp_nonce_field('portfolio-settings', 'portfolio-settings-nonce'); ?>

				<?php self::show_portfolio_settings(); ?>

				<?php submit_button( 'Wijzigingen opslaan' ); ?>
				<?php // submit_button( 'Standaard Instellingen', 'secondary', 'reset' ); ?>

			</form>

		</div>
	<?php
	}


    public function show_portfolio_settings() 
    {	
    	/* Get post type object */
    	$portfolio_project_post_type = get_post_type_object( EJO_Portfolio::$post_type );

    	/* Load settings */
    	$portfolio_settings = get_option('portfolio_settings', array());

		/* Archive title */
		$title = (isset($portfolio_settings['title'])) ? $portfolio_settings['title'] : $portfolio_project_post_type->labels->name;

		/* Archive description */
		$description = (isset($portfolio_settings['description'])) ? $portfolio_settings['description'] : $portfolio_project_post_type->description;

		/* Archive slug */
		$archive_slug = (isset($portfolio_settings['archive-slug'])) ? $portfolio_settings['archive-slug'] : $portfolio_project_post_type->has_archive;
		
    	?>
    	<table class="form-table">
			<tbody>

				<tr>					
					<th scope="row">
						<label for="portfolio-title">Title</label>
					</th>
					<td>
						<input
							id="portfolio-title"
							value="<?php echo $title; ?>"
							type="text"
							name="portfolio-settings[title]"
							class="text"
							style="width"
						>
						<p class="description">Wordt getoond op de archiefpagina, breadcrumbs en meta's tenzij anders aangegeven</p>
					</td>
				</tr>

				<tr>					
					<th scope="row">
						<label for="portfolio-description">Beschrijving</label>
					</th>
					<td>
						<textarea
							id="portfolio-description"
							name="portfolio-settings[description]"
							class="text"
						><?php echo $description; ?></textarea>
						<p class="description">De beschrijving kan getoond worden op de archiefpagina (afhankelijk van het thema)</p>
					</td>
				</tr>

				<tr>					
					<th scope="row">
						<label for="portfolio-slug">Archive Slug</label>
					</th>
					<td>
						<input
							id="portfolio-slug"
							value="<?php echo $archive_slug; ?>"
							type="text"
							name="portfolio-settings[archive-slug]"
							class="text"
							style="width"
						>
						<p class="description">Bepaalt de <i>slug</i> van de archiefpagina</p>
					</td>
				</tr>
				
			</tbody>
		</table>
		<?php
    }

	/* Manage admin scripts and stylesheets */
	public function add_scripts_and_styles()
	{
		/* Settings Page */
		if (isset($_GET['page']) && $_GET['page'] == 'portfolio-settings') :

			/* Settings page javascript */
			wp_enqueue_script( 'portfolio-admin-settings-page-js', EJO_PORTFOLIO_PLUGIN_URL . 'js/admin-settings-page.js', array('jquery'));

			/* Settings page stylesheet */
			wp_enqueue_style( 'portfolio-admin-settings-page-css', EJO_PORTFOLIO_PLUGIN_URL . 'css/admin-settings-page.css' );

		endif;
	}

	/* Returns the instance. */
	public static function init() 
	{
		if ( !self::$_instance )
			self::$_instance = new self;
		return self::$_instance;
	}
}