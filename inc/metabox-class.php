<?php

/**
 *
 */
class EJO_Portfolio_Metabox 
{
	/* Holds the instance of this class. */
	private static $_instance;

	/* Plugin setup. */
	public function __construct() 
	{
		/* Add Referentie Metabox */
		add_action( 'add_meta_boxes_portfolio_project', array( $this, 'add_portfolio_metabox' ) );

		/* Save Referentie Metadata */
		add_action( 'save_post', array( $this, 'save_portfolio_metadata' ) );
	}

	/* */
	public function add_portfolio_metabox() 
	{
		add_meta_box( 
			'portfolio_metabox', 
			'Portfolio Informatie', 
			array( $this, 'render_portfolio_metabox' ), 
			'portfolio_project', 
			'normal', 
			'high' 
		);
	}

	/* */
	public function render_portfolio_metabox( $post )
	{
		// Noncename needed to verify where the data originated
		wp_nonce_field( 'portfolio-metabox-' . $post->ID, 'portfolio-meta-nonce' );

		/* Load Project URL */
		$project_url = get_post_meta( $post->ID, 'url', true );

		/* Load Featured Image */
		$project_client = get_post_meta( $post->ID, 'client', true );

		/* Load Featured Image */
		$project_start_date = get_post_meta( $post->ID, 'start_date', true );

		/* Load Featured Image */
		$project_end_date = get_post_meta( $post->ID, 'end_date', true );

		/* Load Featured Image */
		$project_location = get_post_meta( $post->ID, 'location', true );
		?>

		<table class="form-table">
			<tr>
				<th scope="row" style="width: 140px">
					<label for="project-client">Client</label>
				</th>
				<td>
					<input
						id="project-client"
						value="<?php echo $project_client; ?>"
						type="text"
						name="client"
						class="text large-text"
					>
					<!-- <span class="description">Wanneer de referentie-titel niet de auteur is.</span> -->
				</td>
			</tr>
		</table>
		<?php	
	}

	// Manage saving Metabox Data
	public function save_portfolio_metadata($post_id) 
	{
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

		/* Client */
		if ( isset( $_POST['client'] ) )
			update_post_meta( $post_id, 'client', $_POST['client'] );
	}

	/* Returns the instance. */
	public static function init() 
	{
		if ( !self::$_instance )
			self::$_instance = new self;

		return self::$_instance;
	}
}