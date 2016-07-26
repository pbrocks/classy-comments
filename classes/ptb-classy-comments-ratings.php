<?php
defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

/**
 *
 * Called from ptb-classy-comments.php.
 *
 * @since 1.5.1
 */
class PTB_Classy_Comments_Ratings {

	public function __construct() {

		add_action( 'comment_post', array( $this, 'comment_post' ), 20, 2 );
		add_action( 'comment_unapproved_to_approved', array( $this, 'comment_status_changed' ), 10, 1 );
		add_action( 'comment_approved_to_unapproved', array( $this, 'comment_status_changed' ), 10, 1 );
		add_action( 'deleted_comment', array( $this, 'comment_status_changed' ), 10, 1 );
		add_action( 'trashed_comment', array( $this, 'comment_status_changed' ), 10, 1 );
		add_action( 'spammed_comment', array( $this, 'comment_status_changed' ), 10, 1 );
		add_action( 'undeleted_comment', array( $this, 'comment_status_changed' ), 10, 1 );
		add_action( 'untrashed_comment', array( $this, 'comment_status_changed' ), 10, 1 );
		add_action( 'unspammed_comment', array( $this, 'comment_status_changed' ), 10, 1 );
		add_shortcode( 'commentrating', array( $this, 'average_book_reviews_shortcode' ) );
	}

	public function calculate_post_rating_data( $post_id = 0 ) {
		// Retrieve all comments
		$comments = get_approved_comments( $post_id );

		// Retrieve ratings from comments
		$number_of_ratings = 0;
		$ratings_total_sum = 0;

		foreach ( $comments as $comment ) {
			$comment_rating = get_comment_meta( $comment->comment_ID, 'rating', true );

			if ( empty( $comment_rating ) ) {
				// no rating supplied for this - move on to the next comment
			} else {
				$ratings_total_sum += intval( $comment_rating );
				$number_of_ratings ++;
			}
		}

		// Calculate average
		$rating_average = $ratings_total_sum / $number_of_ratings;

		$rating = array(
			'average'	=> $rating_average,
			'count'		=> $number_of_ratings,
		);

		return $rating;
	}

	public function update_post_rating_data_meta( $post_id ) {
		$post_rating = $this->calculate_post_rating_data( $post_id );

		update_post_meta( $post_id, 'reviewavg', $post_rating['average'] );
		update_post_meta( $post_id, 'reviewnum', $post_rating['count'] );
	}

	public function get_cached_post_rating_data( $post_id ) {
		$rating = array(
			'average' => get_post_meta( $post_id, 'reviewavg', true ),
			'count'   => get_post_meta( $post_id, 'reviewnum', true )
		);

		return $rating;
	}

	public function comment_post( $comment_ID, $comment_approved ) {
		$this->update_post_rating_for_comment( $comment_ID );
	}

	public function comment_status_changed( $comment_ID ) {
		$this->update_post_rating_for_comment( $comment_ID );
	}

	private function update_post_rating_for_comment( $comment_ID ) {
		$comment_data = get_comment( $comment_ID );
		$post_id = $comment_data->comment_post_ID;

		if ( get_field( 'review_type', $post_id ) == '1' ) {
			$this->update_post_rating_data_meta( $post_id );
		}
	}

	public function average_book_reviews_shortcode( $atts ) {

		$post_id = get_the_ID();

		if ( isset( $atts['post_id'] ) ) {
			$post_id = $atts['post_id'];
		}

		$rating_data = $this->get_cached_post_rating_data( $post_id );

		echo '<div id="book-review-shortcode">';

		echo '<h3>';
		$round = round( $rating_data['average'], 1 );
		echo $round;
		echo '</h3>';

		echo '<div id="avg-stars"><img style="border: none;" src="' . plugins_url( 'images/5star.gif', __FILE__ ) . '" class="aligncenter no-border" /></div>';
		echo '<span style="font-size: 1rem;">';

		echo '<br>' . $rating_data['count']  . ' ';

		the_field( 'after_stars' );
		echo '</span>';
		echo '<br><a href="#add-a-comment">';
		the_field( 'add_your_review' );
		echo '</a></div>';
	}
}
