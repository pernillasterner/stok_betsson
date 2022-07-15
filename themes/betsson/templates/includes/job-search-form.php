<?php
$taxonomies = array( 'category', 'location' );
$searchPlaceholder = get_field( 'search_placeholder', 'default-texts' );
$deptPlaceholder = get_field( 'department_placeholder', 'default-texts' );
$locPlaceholder = get_field( 'location_placeholder', 'default-texts' );
$searchButtonText = get_field( 'search_button_text', 'default-texts' );

foreach( $taxonomies as $taxonomy ) {
    $categoryArgs = array(
        'taxonomy'  => 'job-'. $taxonomy,
        'orderby'   => 'name',
        'order'     => 'ASC',
        'hide_empty'=> false,
	);
	
	if( $taxonomy === 'category' ) {
		$categories = get_terms( $categoryArgs ); 
	} elseif( $taxonomy === 'location' ) {
		$locations = get_terms( $categoryArgs ); 
	}
}

$jobsPageId = get_field( 'jobs_page', 'default-pages' );

if( !$jobsPageId ) {
	return;
}

$jobsPageLink = get_permalink( $jobsPageId );

// Modify id of fields if included by banner, this is differentiate banner search form and search form from sections
// This is a fix for select2.js
$idModifier = '';
if( isset( $hasSearchForm ) && $hasSearchForm ) {
	$idModifier = '-banner';
}
?>

<div class="job-search" style="visibility:hidden">
	<div class="job-fields">
        <form class="js-jobs-search-form" action="<?= $jobsPageLink ?>" method="get">
            <div class="inline-field">
                <input id="keyword<?= $idModifier ?>" name="keyword" type="text" placeholder="<?php echo $searchPlaceholder; ?>" />
            </div>
            <div class="inline-field">
                <select id="departments<?= $idModifier ?>" name="departments[]" class="department-multiple" multiple="multiple" data-placeholder="<?php echo $deptPlaceholder; ?>">
                    <?php 
                    if( $categories ) {
                        foreach( $categories as $category ) {
                            echo '<option value="'. $category->term_id .'">'. $category->name .'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="inline-field border-none">
                <select id="locations<?= $idModifier ?>" name="locations[]" class="location-multiple" multiple="multiple" data-placeholder="<?php echo $locPlaceholder; ?>">
                    <?php 
                    if( $locations ) {
                        foreach( $locations as $loc ) {
                            echo '<option value="'. $loc->term_id .'">'. $loc->name .'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <input type="submit" class="btn btn-primary" value="<?php echo $searchButtonText; ?>" />
        </form>
	</div>
</div>