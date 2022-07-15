<?php 

$socialMediaLinks = get_field( 'social_media_links', 'general' );

if( $socialMediaLinks ) : 
	?>

	<div class="<?php echo $smContainerClass; ?>">
	    <ul>
	    	<?php foreach( $socialMediaLinks as $item ) : ?>
	        	<li><a href="<?= $item['link'] ?>" target="_blank" rel="noreferrer noopener" class="icon-<?php echo $item['type']; ?> has-circle"></a></li>
	    	<?php endforeach; ?>
	    </ul>
	</div>

<?php endif;