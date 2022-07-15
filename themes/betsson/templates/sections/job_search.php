<section class="section search-job <?php echo $sectionClass; ?>">
	<div class="container">

        <?php

        $sectionParts = array( 'title', 'text', 'button-before' ); 
        foreach( $sectionParts as $part ) { 
            include( locate_template( 'templates/section-parts/section-'. $part .'.php' ) );
        }

        include( locate_template( 'templates/includes/job-search-form.php' ) ); 
        include( locate_template( 'templates/section-parts/section-button-after.php' ) );
        ?>
        
	</div>
</section>