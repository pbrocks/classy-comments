<?php
defined( 'ABSPATH' ) or die( 'File cannot be accessed directly' );


/**
 * Loads a menu for devs in Wordpress admin only.
 *
 * Called from ptb-classy-comments.php.
 *
 * @since 1.5.1
 */
class PTB_Classy_Comments_Dev_Menu {

	/**
	 * Construct method, called when this class is instantiated as an object
	 *
	 * @since 1.5.1
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'ptb_comments_temp_menu' ), 15 );

	}

	/**
	 * Menu item will allow us to load the page to display the table
	 */
	public function ptb_comments_temp_menu() {

		add_dashboard_page(
			'PTB Comments Dev Menu',
			'PTB Comments Dev Menu',
			'manage_options',
			'comments-dev-page.php',
			array( $this, 'get_rating_test_page' )
		);
	}

	public function get_rating_test_page() {
		$post_id = 33;
		?>
		<h1>Average rating for post <?php _e( $post_id ); ?></h1>
		<p>
			<?php

			echo '<h3>Still to do</hs>';

			echo '<pre>';
			echo 'You can find this file in  <br>';
			echo  __FILE__;
			echo '<br>';
			echo '</pre>';

			echo '<br><hr>';

	$test = new PTB_Classy_Comments_Ratings();

echo "Comment IDs for $post_id";
echo '<pre>';
//	print_r( $test->ptb_get_post_comment_ids($post_id));
echo '</pre>';

	$test1 = new PTB_Classy_Comments_Ratings();


echo "Ratings for $post_id";
echo '<pre>';
the_field( 'acf_review_type_field', 33 );
//	print_r( $test1->ptb_get_comment_rating_data($post_id));
echo '</pre>';

/*
	$count = new PTB_Classy_Comments_Ratings();

echo "Count for $post_id";
echo '<pre>';
	echo $count->ptb_calc_comment_rating($post_id);
echo '</pre>';

*/
	$average = new PTB_Classy_Comments_Ratings();

echo "Count for $post_id";
echo '<pre>';
//	print_r($average->ptb_calc_comment_rating($post_id));
echo '</pre>';




/*					$country_dropdown = new PETA_Presents_Payus_Dropdown_Country();
					echo $country_dropdown->get_html();

//		$comments = get_approved_comments( '17251' );

	$postID = 17251;
	$comment_array = get_approved_comments($postID);
/*
   foreach($comment_array as $comment){
      echo $comment->comment_ID." => ".$comment->comment_post_ID."\n";
   }
*/

   echo 'Setting Array <br>';
/*
   $c_id = array();

foreach($comment_array as $comment){
      array_push( $c_id, $comment->comment_ID );
   }


		echo "comments Meta: <br>";
			echo '<pre>';
			print_r( $c_id );
			echo '</pre>';


 $arrlength = count($c_id);


    echo "<br>Array length = $arrlength <br>";


$x = 0;
$total = 0;
$rating = array();

foreach ($c_id as $key=>$value) {

 $rating[$x]  = get_comment_meta( $value, 'rating', true );

//	echo "[$x] = " . $value ;

$x++;
 //   echo "<br>";
}


		echo "comments Meta: <br>";
			echo '<pre>Rating';
			print_r( $rating );
			echo '</pre>';


			echo 'Array Sum = ' . array_sum($rating) . '<br>';

			echo 'Average rating = ' . round( (array_sum($rating) /  $arrlength = count($c_id) ), 1 );


/*
			echo "comments Meta: <br>";
			echo '<pre>';
			print_r( $comments[0] );
			echo '</pre>';

$args = array(
    'post__not_in' => $unwanted,
    'post_parent' => 223
);
$comments_query = new WP_Comment_Query;
$comments = $comments_query->query( $args );
*/

/*
echo array_shift( $comments['comment_ID']);

//	echo	 get_comment_meta( $comment->comment_ID, 'rating', true ); 

/*
foreach ($comments as $key => $value) {
	# code...
	echo $key . ' => ' . $value . '<br>';
}
*/

/*
//			$meta = get_post_meta( $post_id );
			$meta_values = get_comment_meta( $comments->comment_id, $key, $single ); 
			echo '<br>';
			echo "Post Meta: <br>";
			echo '<pre>';
			print_r( $comments );
			echo '</pre>';

			echo '<br>is this post a book review? ' . $meta['book_review'][0];

			print_r(get_post_meta($post_id, 'reviewnum'));

			$number = get_post_meta($post_id, 'reviewnum');

			echo '<br>' . $number[0];

			$rating_below_first = new PTB_Classy_Comments_Ratings();
			$rating_below = $rating_below_first->get_cached_post_rating_data( $post_id );

			print_r($rating_below);
			echo '<br>';

			echo $rating_below['count'];

			echo '<br>';
			echo '<br>Current average = ';

			echo round($rating_below['average'], 1);

*/

//		 $number = $comments->comment_ID;

//		$children = array_column($comments[0], 'comment_ID');
//		$comment_ID = array_shift(array_column($a, 'comment_ID'));
/*		print_r($comment_ID);
			echo "comments Meta: <br>";
			echo '<pre>';
			print_r( array_chunk($a,2) );
			echo '</pre>';
*/

/* $arrlength = count($children);

for($x = 0; $x < $arrlength; $x++) {
    echo $children[$x];
    echo "<br>";
}

*/

/*
			echo '<br>';

			echo '<br>Current average = ';

			echo round($rating_below['average'], 1);

			echo '<br>';

			$meta = get_post_meta( $post_id );
			echo '<br>';
			echo "Post Meta: <br>";
			echo '<pre>';
			print_r( $meta );
			echo '</pre>';

			echo '<br>is this post a book review? ' . $meta['book_review'][0];

			print_r(get_post_meta($post_id, 'reviewnum'));

			$number = get_post_meta($post_id, 'reviewnum');

			echo '<br>' . $number[0];

*/

			?>
		</p>
		<?php
	}

/* EOC */
}
