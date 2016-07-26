<?php
defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );


/**
 * Loads PETA Presents Theme functions that are used in the Wordpress admin only.
 *
 * Called from ptb-classy-comments.php.
 *
 * @since 1.5.1
 */
class PTB_Classy_Comments_Renaming {

	private $acf_review_type_field = 'review_type';

	/**
	 * Construct method, called when this class is instantiated as an object
	 */
	public function __construct() {

		add_filter( 'comment_form_defaults' , array( $this, 'ptb_alter_comments_defaults' ) );
		add_filter( 'comment_form_default_fields', array( $this, 'alter_comment_fields' ) );
		add_action( 'comment_form_after', array( $this, 'additional_fields' ) );
		add_action( 'comment_form_logged_in_after ', array( $this, 'logged_in_additional_fields' ) );
		add_action( 'comment_post', array( $this, 'save_comment_meta_data' ) );
		add_action( 'init', array( $this, 'load_scripts' ) );

		add_filter( 'preprocess_comment', array( $this, 'verify_comment_meta_data' ) );
		add_action( 'edit_comment', array( $this, 'alter_comment_edit_metafields' ) );
		add_filter( 'comment_author', array( $this, 'add_rating_to_author' ) );
		add_filter( 'comment_post_redirect', array( $this, 'filter_comment_post_redirect' ), 10, 2 );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

	}


	/**
	 *	Save the comment meta data along with comment
	 */
	public function load_scripts() {

		wp_enqueue_style( 'rating-style', plugin_dir_url( __FILE__ ) . '../inc/css/ratings.css' );
	}


	/**
	 * Changing the view for user viewing comment form
	 */
	public function ptb_alter_comments_defaults( $defaults ) {
		global $post;

		if (  '1' === get_field( $this->acf_review_type_field ) ) {

			$defaults = array(

				'title_reply'          => '<a name="add-a-comment"></a>' . __( get_field( 'add_a_comment' ), 'ptb-classy-comments' ) . ' ',
				'cancel_reply_link'    => __( 'Cancel Book Review', 'ptb-classy-comments' ),
				'label_submit'         => __( get_field( 'submit_button' ), 'ptb-classy-comments' ),
				'comment_notes_before'	=> '<p class="comment-notes">' . __( get_field( 'text_before_comment_box' ), 'ptb-classy-comments' )  . '</p>
		<p class="comment-form-rating">
			<label for="rating">' . __( get_field( 'ratings_label' ), 'ptb-classy-comments' ) . '<span class="required"> * </span></label>
			<span class="starRating">
				<input id="rating5" type="radio" name="rating" value="5" checked="">
				<label for="rating5">5</label>
				<input id="rating4" type="radio" name="rating" value="4">
				<label for="rating4">4</label>
				<input id="rating3" type="radio" name="rating" value="3">
				<label for="rating3">3</label>
				<input id="rating2" type="radio" name="rating" value="2">
				<label for="rating2">2</label>
				<input id="rating1" type="radio" name="rating" value="1">
				<label for="rating1">1</label>
			</span>
		</p>
				',
				'comment_field' 		=> '<p class="comment-form-comment"><label for="comment">' .
				_x( get_field( 'comment_box_label' ), 'ptb-classy-comments' ) .
				' * </label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
			);

		} else {

			$defaults = array(

			'title_reply' 		=> __( 'Add a Comment', 'ptb-classy-comments' ),
			'label_submit'		=> __( 'Submit Comment', 'ptb-classy-comments' ),
			);

		}
			return $defaults;
	}



	/**
	 *  Changing the order that the comment form fields appear
	 */
	public function alter_comment_fields( $fields ) {
		global $post;

	//if ( '1' === get_field( 'acf_review_type_field', $post_id ) ) {

			$commenter = wp_get_current_commenter();
			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );

			$fields['author'] = '<p class="comment-form-author">'.
			'<label for="author">' . __( 'First Name' ) . ' '.
			( $req ? '<span class="required">*</span>' : '' ).
			'<input id="author" name="author" type="text"
			 size="30" ' . $aria_req . ' /></label></p>';

			$fields['last_name'] = '<p class="comment-form-last_name">'.
			'<label for="last_name">' . __( 'Last Name' ) . ' '.
			( $req ? '<span class="required">*</span> <span class="smaller-font">' . get_field( 'last_name_note' ) . '</span>' : '' ).
			'<input id="last_name" name="last_name" type="text" size="30" ' . $aria_req . ' /></label></p>';

			$fields['email'] = '<p class="comment-form-email">'.
			'<label for="email">' . __( 'Email' ) . ' '.
			( $req ? '<span class="required">*</span> <span class="smaller-font">' . get_field( 'email_note' ) . '</span>' : '' ).
			'<input id="email" name="email" type="text" size="30" ' . $aria_req . ' /></label></p>';

			unset( $fields['url'] );

			$last_name = $fields['last_name'];
			$comment_email = $fields['email'];
			unset( $fields['last_name'] );
			unset( $fields['email'] );
			$fields['last_name'] = $last_name;
			$fields['email'] = $comment_email;

		// }

		return $fields;

	}


	// Add fields after default fields above the comment box, always visible
	function logged_in_additional_fields() {

		if ( '1' === get_field( $this->acf_review_type_field ) ) {

?>
		<p class="comment-form-rating">
			<label for="rating">
			<?php echo get_field( 'ratings_label' ) .  '<span class="required"> * </span></label>';
?>
			<span class="starRating">
				<input id="rating5" type="radio" name="rating" value="5" checked="">
				<label for="rating5">5</label>
				<input id="rating4" type="radio" name="rating" value="4">
				<label for="rating4">4</label>
				<input id="rating3" type="radio" name="rating" value="3">
				<label for="rating3">3</label>
				<input id="rating2" type="radio" name="rating" value="2">
				<label for="rating2">2</label>
				<input id="rating1" type="radio" name="rating" value="1">
				<label for="rating1">1</label>
			</span>
		</p>
<?php
		}
	}
		/**
	 * Add fields after default fields above the comment box, always visible
	 */
	public function additional_fields() {

		if ( '1' === get_field( $this->acf_review_type_field ) ) {

			echo '<div class="comment-form-legalese">';
			the_field( 'ptb_legalese' );
			echo '</div>';
		}
	}

	/**
	 *	Save the comment meta data along with comment
	 */
	public function save_comment_meta_data( $comment_id ) {

		if ( ( isset( $_POST['last_name'] ) ) && ( '' !== $_POST['last_name'] ) )
		$last_name = wp_filter_nohtml_kses($_POST['last_name']);
		add_comment_meta( $comment_id, 'last_name', $last_name );

		if ( ( isset( $_POST['rating'] ) ) && ( '' !== $_POST['rating'] ) )
		$rating = wp_filter_nohtml_kses( $_POST['rating'] );
		add_comment_meta( $comment_id, 'rating', $rating );
		// TODO add $rating to options
		update_option( 'new_rating', $rating );

	}

	/**
	 *
	 */
	public function verify_comment_meta_data( $commentdata ) {

		if ( '1' === get_field( $this->acf_review_type_field ) ) {

			if ( ! isset( $_POST['rating'] ) ) {
				wp_die( __( 'Hold on a minute: You did not add your rating. Use the BACK button of your browser to resubmit your comment with a rating.' ) );
			}
		}
		return $commentdata;
	}



	/**
	 *
	 */
	public function alter_comment_meta_box( $comment ) {

		if ( '1' === get_field( $this->acf_review_type_field ) ) {

		$last_name = get_comment_meta( $comment->comment_ID, 'last_name', true );
		$rating = get_comment_meta( $comment->comment_ID, 'rating', true );
		wp_nonce_field( 'alter_comment_update', 'alter_comment_update', false );
		?>
		<p>
		    <label for="rating"><?php esc_html_e( 'Rating: ' ); ?></label>
				<span class="commentratingbox">
				<?php for ( $i = 1; $i <= 5; $i++ ) {
					echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"';
					if ( $i === $rating ) echo ' checked="checked"';
						echo ' />'. /* $i */ ' </span>';
					}
				?>
				</span>
		</p>
		<p>
		    <label for="last_name"><?php esc_html_e( 'Last Name' ); ?></label>
		    <input type="text" name="last_name" value="<?php echo esc_attr( $last_name ); ?>" class="widefat" />
		</p>
		<?php
		}
	}


	/**
	 *	Update comment meta data from comment edit screen
	 */
	public function alter_comment_edit_metafields( $comment_id ) {

		if ( '1' === get_field( $this->acf_review_type_field ) ) {

			if ( ! isset( $_POST['alter_comment_update'] ) || ! wp_verify_nonce( $_POST['alter_comment_update'], 'alter_comment_update' ) ) return;

			if ( ( isset( $_POST['last_name'] ) ) && ( $_POST['last_name'] != '' ) ) :
				$last_name = wp_filter_nohtml_kses($_POST['last_name']);
				update_comment_meta( $comment_id, 'last_name', $last_name );
			else :
				delete_comment_meta( $comment_id, 'last_name' );
			endif;

			if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '' ) ):
				$rating = wp_filter_nohtml_kses( $_POST['rating'] );
				update_comment_meta( $comment_id, 'rating', $rating );
			else :
				delete_comment_meta( $comment_id, 'rating' );
			endif;
		}
	}

	/**
	 *	Add the comment meta (saved earlier) to the comment text.
	 * You can also output the comment meta values directly in comments template
	 */
	public function add_rating_to_author() {

		if ( '1' === get_field( $this->acf_review_type_field ) ) {

			$floatright = '';

			if ( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
				$commentrating = '<p class="comment-rating">	<img src="'. plugins_url( '/images/', __FILE__ )
				. $commentrating . 'star.gif"/><br/>Book Rating: <strong>'. $commentrating . ' / 5</strong></p>';
				$floatright = $commentrating;
				return $floatright;
			} else {
				return $floatright;
			}
		}
	}

	/**
	 *	Setting the URL to be directed to after submitting comment
	 */
	public function filter_comment_post_redirect( $location, $comment ) {

		// Check to see if this post is a Book Review or not
		if ( '1' === get_field( $this->acf_review_type_field, $comment->comment_post_ID ) ) {

			// Get the redirect URL if this is a Book Review
			$new_redirect_location = get_field( 'redirect_url', $comment->comment_post_ID );

			if ( ! empty( $new_redirect_location ) ) {

				wp_redirect( $new_redirect_location );
				exit();
			}
		}

		return $location;
	}

	/**
	 *	http://shibashake.com/wordpress-theme/add-a-metabox-to-the-edit-comments-screen
	 */
	public function add_meta_boxes() {
		if ( '1' === get_field( $this->acf_review_type_field ) ) {

			add_meta_box( 'ptb_comment_xtra_box', __( 'Extra Comment Info' ), array( $this, 'comment_metabox' ), 'comment', 'normal' );
		}
	}



	/**
	 *
	 */
	public function comment_metabox( $comment ) {

		if ( '1' === get_field( $this->acf_review_type_field ) ) {
	?>
		<table class="form-table editcomment comment_xtra">
		<tbody>
		<tr valign="top">
		    <td class="first"><?php esc_html_e( 'Comment on Post ID:' ); ?></td>
		    <td><input type="text" id="ptb_comment_post_ID" name="ptb_comment_post_ID" size="10" class="code" value="<?php echo esc_attr( $comment->comment_post_ID ); ?>" tabindex="1" /></td>
		</tr>
		<tr valign="top">
		    <td class="first"><?php esc_html_e( 'Author IP:' ); ?></td>
		    <td><input type="text" id="ptb_comment_author_IP" name="ptb_comment_author_IP" size="20" class="code" value="<?php echo esc_attr( $comment->comment_author_IP ); ?>" tabindex="1" /></td>
		</tr>
		<tr valign="top">
		    <td class="first"><?php esc_html_e( 'Rating:' ); ?></td>
		    <td><?php /* ?><input type="text" id="ptb_comment_author_IP" name="ptb_comment_author_IP" size="20" class="code" value="<?php echo esc_attr( $comment->comment_author_IP ); ?>" tabindex="1" /><?php */ ?></td>
		</tr>
		</tbody>
		</table>
		<?php

		}

	}
	/* EOC */
}
