<?php

/* Metabox */
EJO_Portfolio_Metabox::init();

/**
 *
 */
final class EJO_Portfolio_Metabox 
{
	// public static $default_meta;
	public static $default_meta = array(
		'url',
		'client',
		'start-date',
		'end-date',
		'location'
	);

	/* Holds the instance of this class. */
	private static $_instance;

	/* Returns the instance. */
	public static function init() 
	{
		if ( !self::$_instance )
			self::$_instance = new self;

		return self::$_instance;
	}

	/* Plugin setup. */
	private function __construct() 
	{
		// self::$default_meta = array(
		// 	'url',
		// 	'client',
		// 	'start-date',
		// 	'end-date',
		// 	'location'
		// );

		/* Add Referentie Metabox */
		add_action( 'add_meta_boxes_portfolio_project', array( 'EJO_Portfolio_Metabox', 'add_portfolio_metabox' ) );

		/* Save Referentie Metadata */
		add_action( 'save_post', array( 'EJO_Portfolio_Metabox', 'save_portfolio_metadata' ) );
	}

	/* */
	public static function add_portfolio_metabox() 
	{
		$portfolio_meta = apply_filters('ejo_portfolio_meta', self::$default_meta);

		if (empty($portfolio_meta)) {
			return;
		}

		/* Add meta_box */
		add_meta_box( 
			'portfolio_metabox', 
			'Portfolio Informatie', 
			array( 'EJO_Portfolio_Metabox', 'render_portfolio_metabox' ), 
			'portfolio_project', 
			'normal', 
			'high' 
		);
	}

	/* */
	public static function render_portfolio_metabox( $post )
	{
		// Noncename needed to verify where the data originated
		wp_nonce_field( 'portfolio-metabox-' . $post->ID, 'portfolio-meta-nonce' );

		$portfolio_meta = apply_filters('ejo_portfolio_meta', self::$default_meta);

		/* Check if theme supports portfolio url */
		if ( in_array('url', $portfolio_meta ) )
			$project_url = get_post_meta( $post->ID, 'url', true );

		/* Check if theme supports portfolio client */
		if ( in_array('client', $portfolio_meta ) )
			$project_client = get_post_meta( $post->ID, 'client', true );

		/* Check if theme supports portfolio start-date */
		if ( in_array('start-date', $portfolio_meta ) )
			$project_start_date = get_post_meta( $post->ID, 'start_date', true );

		/* Check if theme supports portfolio end-date */
		if ( in_array('end-date', $portfolio_meta ) )
			$project_end_date = get_post_meta( $post->ID, 'end_date', true );

		/* Check if theme supports portfolio location */
		if ( in_array('location', $portfolio_meta ) )
			$project_location = get_post_meta( $post->ID, 'location', true );
		?>

		<table class="form-table">

		<?php if (isset($project_url)) : ?>
			<tr>
				<th scope="row" style="width: 140px">
					<label for="project-url">URL</label>
				</th>
				<td>
					<input
						id="project-url"
						value="<?php echo $project_url; ?>"
						type="text"
						name="project-url"
						class="text large-text"
					>
					<!-- <span class="description">Wanneer de referentie-titel niet de auteur is.</span> -->
				</td>
			</tr>
		<?php endif; /* End project_url check */ ?>

		<?php if (isset($project_client)) : ?>
			<tr>
				<th scope="row" style="width: 140px">
					<label for="project-client">Client</label>
				</th>
				<td>
					<input
						id="project-client"
						value="<?php echo $project_client; ?>"
						type="text"
						name="project-client"
						class="text large-text"
					>
					<!-- <span class="description">Wanneer de referentie-titel niet de auteur is.</span> -->
				</td>
			</tr>
		<?php endif; /* End project_client check */ ?>

		<?php if (isset($project_start_date)) : ?>
			<tr>
				<th scope="row" style="width: 140px">
					<label for="project-start-date">Start Date</label>
				</th>
				<td>
					<input
						id="project-start-date"
						value="<?php echo $project_start_date; ?>"
						type="text"
						name="project-start-date"
						class="text large-text"
					>
					<!-- <span class="description">Wanneer de referentie-titel niet de auteur is.</span> -->
				</td>
			</tr>
		<?php endif; /* End project_start_date check */ ?>
		
		<?php if (isset($project_end_date)) : ?>
			<tr>
				<th scope="row" style="width: 140px">
					<label for="project-end-date">End Date</label>
				</th>
				<td>
					<input
						id="project-end-date"
						value="<?php echo $project_end_date; ?>"
						type="text"
						name="project-end-date"
						class="text large-text"
					>
					<!-- <span class="description">Wanneer de referentie-titel niet de auteur is.</span> -->
				</td>
			</tr>
		<?php endif; /* End project_end_date check */ ?>
		
		<?php if (isset($project_location)) : ?>
			<tr>
				<th scope="row" style="width: 140px">
					<label for="project-location">Location</label>
				</th>
				<td>
					<input
						id="project-location"
						value="<?php echo $project_location; ?>"
						type="text"
						name="project-location"
						class="text large-text"
					>
					<!-- <span class="description">Wanneer de referentie-titel niet de auteur is.</span> -->
				</td>
			</tr>
		<?php endif; /* End project_location check */ ?>

		</table>
			
		<?php	
	}

	// Manage saving Metabox Data
	public static function save_portfolio_metadata($post_id) 
	{	
		$portfolio_meta = apply_filters('ejo_portfolio_meta', self::$default_meta);

		if (empty($portfolio_meta)) {
			return;
		}

		/* Don't try to save the data under autosave, ajax, or future post. */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			return;
		if ( defined( 'DOING_CRON' ) && DOING_CRON )
			return;

		/* Don't save if WP is creating a revision (same as DOING_AUTOSAVE?) */
		if ( wp_is_post_revision( $post_id ) )
			return;

		/* Check that the user is allowed to edit the post */
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		/* Verify where the data originated */
		if ( !isset($_POST['portfolio-meta-nonce']) || !wp_verify_nonce( $_POST['portfolio-meta-nonce'], "portfolio-metabox-$post_id" ) )
			return;

		/* URL */
		if ( isset( $_POST['project-url'] ) )
			update_post_meta( $post_id, 'url', $_POST['project-url'] );

		/* Client */
		if ( isset( $_POST['project-client'] ) )
			update_post_meta( $post_id, 'client', $_POST['project-client'] );

		/* Start Date */
		if ( isset( $_POST['project-start-date'] ) )
			update_post_meta( $post_id, 'start_date', $_POST['project-start-date'] );

		/* End Date */
		if ( isset( $_POST['project-end-date'] ) )
			update_post_meta( $post_id, 'end_date', $_POST['project-end-date'] );

		/* Location */
		if ( isset( $_POST['project-location'] ) )
			update_post_meta( $post_id, 'location', $_POST['project-location'] );
	}
}