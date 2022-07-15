<?php
$jobCategories = get_terms( array(
	'taxonomy' => 'job-category',
	'hide_empty' => true
) );

if( !$jobCategories ) {
	return;
}
?>

<div class="departments-filter">
    <div class="container">
        <h6><?= get_field( 'our_departments_text' ) ?></h6>
        <div class="departments">
            <ul>
				<?php
				foreach( $jobCategories as $category ):
					$iconImage = '<img src="'.get_template_directory_uri() . '/dist/images/placeholders/department-placeholder-150x150.svg" alt="'. get_bloginfo( 'name' ) .'"/>';

					if( $icon = get_field( 'icon', $category->taxonomy . '_' . $category->term_id ) ) {
						$iconImage = wp_get_attachment_image( $icon, 'thumbnail', false, array( 'class' => 'img-responsive' ) );
					}
				?>					
					<li>
						<a href="#" class="js-job-department" data-id="<?= $category->term_id ?>">
							<figure><?= $iconImage ?></figure>
							<div class="text"><?= $category->name ?></div>
						</a>
					</li>
				<?php endforeach; ?>  
            </ul>
        </div>
    </div>
</div>