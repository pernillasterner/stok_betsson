<?php
if( !is_singular( 'post' ) ) {
	return;
}

global $post;

$readArticle = get_sub_field( 'read_article_text' );
$postCount = get_sub_field( 'count' );

$args = array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => $postCount,
	'post__not_in' => array( $post->ID ),
	'orderby' => 'ID',      
	'order' => 'DESC'
);

$categories = get_the_category();
if( count( $categories ) > 0 ) {
	$args['cat'] = $categories[0]->cat_ID;
}

$wpQuery = new WP_Query( $args );
$posts = $wpQuery->posts;

if( !$title && !$text && !$cta && !$posts ) {
	return;
}

$columnsPerRow = 4;
?>

<section class="section latest-news <?php echo $sectionClass; ?>">
	<div class="container">

		<?php
		$sectionParts = array( 'title', 'text', 'button-before' ); 
		foreach( $sectionParts as $part ) { 
			include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
		}
		?>

		<?php
		if( $posts ) {
			include( locate_template( 'templates/includes/latest_news_items.php' ) );
		}
		?>

		<div class="text-center">
			<div class="loader" style="display:none">Loading...</div>
		</div>		

		<?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>

	</div>
</section>