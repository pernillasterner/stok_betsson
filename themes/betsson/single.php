<?php
use Lib\Classes\ContentHelper;
use Lib\Classes\GeneralHelper;

global $post;

$general = new GeneralHelper();
$publishedText = get_field( 'published_date', 'default-texts' );
$contentGridClass = 'wide';
$preambleLayout = 'text';
$bgColorClass = null;
$postCategoryDetails = null;

// Get post categories
$categories = $general->get_post_categories();

if( $categories ) {
	$postCategoryDetails = wp_list_pluck( $categories, 'name' );

	// Get first category
	$category = $categories[0];
	$acfId = $category->taxonomy . '_' . $category->term_id;

	$backgroundColor = get_field( 'background_color', $acfId );
	$contentGrid = get_field( 'post_content_grid', $acfId );
	$preambleLayout = get_field( 'post_preamble_layout', $acfId );

	if( $backgroundColor === 'blue' ) {
		$bgColorClass = 'is-primary';
	} elseif( $backgroundColor === 'white' ) {
		$bgColorClass = '';
	} else {
		$bgColorClass = 'is-secondary';
	}

	if( $contentGrid === 'narrow' ) {
		$contentGridClass = 'is-medium';
	}
}

$imageContent = null;
if( has_post_thumbnail() ) {
	$imageContent = get_the_post_thumbnail( $post, '360x270', array( 'class' => 'alignright' ) );
}
?>

<section class="section is-first selected-page">

	<?php get_template_part( 'templates/includes/share-the-page' ); ?>

    <div class="selected-page-wrap">
        <div class="bg-block <?php echo $bgColorClass; ?>"></div>
        <div class="container">
            <div class="row has-pad-mobile">
                <div class="col-md-10">
                    <div class="selected-page-header">

                    	<?php if( $postCategoryDetails ) : ?>
                        <ul class="selected-page-category small">
                        	<?php
                        	foreach( $postCategoryDetails as $key=>$cat ) {
                        		echo '<li class="'. ($key===0 ? 'is-active' : null) .'">'. $cat .'</li>';
                        	}
                        	?>
                        </ul>
                     	<?php endif; ?>

                        <h1 class="h2"><?php the_title(); ?></h1>

                        <?php if( get_field( 'show_published_date' ) ) : ?>
	                        <ul class="hidden-xs date-list">
	                            <li><?php echo $publishedText ? $publishedText.': ' : null; ?><strong><?php echo get_the_date( 'd/m/y' ); ?></strong></li>
	                        </ul>
	                    <?php endif; ?>

                    </div>
                </div>
			</div>

			<?php
			if( is_single() && get_post_type() === 'post' ) {
				echo ContentHelper::modify_first_paragraph(
					apply_filters( 'the_content', $post->post_content )
					, $imageContent
					, get_field( 'preamble' )
					, $contentGridClass
					, $preambleLayout
				);
			} else {
				echo '<div class="content">' . apply_filters( 'the_content', $post->post_content ) . '</div>';
			}
			?>

		</div>
	</div>
</section>

<?php include( locate_template( 'templates/sections.php' ) ); ?>
