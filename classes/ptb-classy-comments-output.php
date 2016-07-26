<?php
defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );


/**
 * Loads PETA Presents Theme functions that are used in the Wordpress admin only.
 *
 * Called from peta-prime-classy-comments.php.
 *
 * @since 1.5.1
 */
class PTB_Classy_Comments_Output {

	/**
	 * Construct method, called when this class is instantiated as an object
	 *
	 * @since 1.5.1
	 */
	public function __construct() {

		add_action( 'comment_form', array( $this, 'adjust_list_comments') );

    }

/*

wp_list_comments( $args, $comments ); ?> 

Default Usage
$args = (array) (optional) The options for the function.

Default:
<?php $args = array(
	'walker'            => null,
	'max_depth'         => '',
	'style'             => 'ul',
	'callback'          => null,
	'end-callback'      => null,
	'type'              => 'all',
	'reply_text'        => 'Reply',
	'page'              => '',
	'per_page'          => '',
	'avatar_size'       => 32,
	'reverse_top_level' => null,
	'reverse_children'  => '',
	'format'            => 'html5', // or 'xhtml' if no 'HTML5' theme support
	'short_ping'        => false,   // @since 3.6
    'echo'              => true     // boolean, default is true
);
*/
	/**
	 * Called from ptb-classy-comments.php.
	 *
	 * @since 1.5.1
	 */
	public function adjust_list_comments( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment; ?>
			<div class="post">
			<strong><?php // printf(__(the_title().' your title %s'), get_comment_author_link()) ?></strong>
			<?php if ( '0' === $comment->comment_approved ) : ?>
			<span class="error"><?php esc_html( 'Your comment is awaiting moderation.' ) ?></span>
			<?php endif; ?>
			<div class="comment-meta commentmetadata">
			<p><?php comment_text() ?></p>
			</div>
			</div><!--End post-->
	<?php
	}
	/* EOC */
}
