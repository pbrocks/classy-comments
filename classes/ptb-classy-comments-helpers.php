<?php
defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );

class PTB_Classy_Comments_Helpers {

	private static $acf_review_type_field = 'review_type';

	private function __construct() {}

	public static function is_post_book_review( $post_id = 0 ) {
		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		return ( '1' === get_field( self::$acf_review_type_field, $post_id ) );
	}
}
