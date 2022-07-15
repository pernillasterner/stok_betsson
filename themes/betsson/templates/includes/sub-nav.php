<?php
use Lib\Classes\GeneralHelper;

$helper = new GeneralHelper();
$subPages = null;
$pageId = null;

if( is_singular( 'job' ) ) {
	$jobsPageId = get_field( 'jobs_page', 'default-pages' );

	if( !$jobsPageId ) {
		return;
	}

	$parentOfJobsPage = get_post_ancestors( $jobsPageId );

	if( $parentOfJobsPage ) {
		$subPages = get_pages( array( 
            'post_type' => 'page',
            'parent' => $parentOfJobsPage[0],
            'sort_column' => 'menu_order',
        ) );
	}

	$pageAncestors = array( $jobsPageId );
} elseif( is_singular( 'post' ) ) {
	$categories = get_the_category();
	
	if( !$categories )
		return;

	$firstCategory = $categories[0];

	if( $firstCategory->slug === 'people' ) {
		$pageId = get_field( 'people_page', 'default-pages' );
	} elseif( $firstCategory->slug === 'benefits' ) {
		$pageId = get_field( 'benefits_page', 'default-pages' );
	} elseif( $firstCategory->slug === 'news' ) {
		$pageId = get_field( 'news_page', 'default-pages' );
	}

	$parentOfPage = get_post_ancestors( $pageId );

	if( $parentOfPage ) {
		$subPages = get_pages( array( 
            'post_type' => 'page',
            'parent' => $parentOfPage[0],
            'sort_column' => 'menu_order',
        ) );
	}

	$pageAncestors = array( $pageId );
} else {
	$subPages = $helper->get_sub_navigation();
	$pageAncestors = get_post_ancestors( $post );
}

if( $subPages ) :
	?>
	<div class="<?php echo $subnavClass; ?>">
		<ul class="js-submenu-carousel">

			<?php 
			foreach( $subPages as $page ) : 
				$isActive = ( $post->ID === $page->ID || in_array( $page->ID, $pageAncestors ) );
				?>
				<li class="carousel-cell <?php echo $isActive ? 'is-active' : null; ?>">
					<a href="<?php echo get_permalink( $page ); ?>"><?php echo get_the_title( $page ); ?></a>
				</li>
			<?php endforeach; ?>

		</ul>
	</div>
<?php endif; 