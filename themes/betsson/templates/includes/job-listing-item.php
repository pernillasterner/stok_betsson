<?php
$categories = wp_get_post_terms( $job->ID, 'job-category' );
$locations = wp_get_post_terms( $job->ID, 'job-location' );

if( $categories ) {
	$categories = implode( ',', array_map( function( $category ){
		return $category->name;
	}, $categories ) );
} else {
	$categories = '';
}

if( $locations ) {
	$locations = implode( ',', array_map( function( $location ){
		return $location->name;
	}, $locations ) );
} else {
	$locations = '';
}
?>

<div class="tr">
	<a href="<?= get_permalink( $job ) ?>">
		<div class="td job">
		<?= $job->post_title ?>
			<span class="department mobile"><?= $categories ?></span>
		</div>
		<div class="td department"><?= $categories ?></div>
		<div class="td location"><?= $locations ?></div>
	</a>
</div>