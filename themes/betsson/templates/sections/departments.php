<?php
	
	$departments = null;
	$deptParentPage = get_field( 'all_departments_page', 'departments' );

	if( $deptParentPage ) {
		$args = null;
		$args['parent'] = url_to_postid( $deptParentPage );

		if( get_sub_field( 'section_displays' ) === 'random' ) {
			$args['number'] = get_sub_field( 'count' );
			$args['sort_column'] = 'rand';
		} else {
			$args['sort_column'] = 'menu_order';
		}

		$departments = get_pages( $args );
	}

	if( $title || $text || $cta || $departments ) : 
		?>

		<section class="section recruiters <?php echo $sectionClass; ?>">
			<div class="container">

                <?php
                $sectionParts = array( 'title', 'text', 'button-before' ); 
                foreach( $sectionParts as $part ) { 
                    include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
                }
                ?>

				<?php if( $departments ) : ?>

					<div class="recruiter-cards hidden-xs">
						<?php 
						$itemCtr = 0;
						foreach( $departments as $item ) {
							$itemCtr = $itemCtr === 4 ? 0 : $itemCtr;
							$itemCtr++;
							$cardClass = $itemCtr === 1 ? 'no-margin--left' : null;
							$isDepartment = true;

							include( locate_template( 'templates/includes/recruiter-item.php' ) );
						}	
						?>
					</div>

					<div class="cards-carousel visible-xs">
						<?php 
						foreach( $departments as $item ) {
							$cardClass = null;
							$isDepartment = true;
							include( locate_template( 'templates/includes/recruiter-item.php' ) );
						}
						?>
					</div>

				<?php endif; ?>

				<?php include( locate_template( 'templates/section-parts/section-button-after.php' ) ); ?>

			</div>
		</section>

	<?php endif; 