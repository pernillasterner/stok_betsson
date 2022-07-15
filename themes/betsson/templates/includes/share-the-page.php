<?php
$url = urlencode( get_the_permalink() );
$title = urlencode( get_the_title() );

$content = urlencode( sprintf( '%s | Betsson Group %s', get_the_title(), get_the_permalink() ) );
if( is_singular( 'job' ) ) {
	$content = urlencode( sprintf( '%s | Jobs | Betsson Group %s', get_the_title(), get_the_permalink() ) );
}

$iconColor = isset($iconColor) ? $iconColor : 'is-primary';
?>

<div class="<?php echo !empty($smContainerClass) ? $smContainerClass : 'selected-page-social'; ?>">
	<ul>
		<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?= $url ?>" rel="nofollow noreferrer" target="_blank" class="icon-facebook has-circle <?php echo $iconColor; ?>"></a></li>
		<li><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $url ?>&title=<?= $title ?>" rel="nofollow noreferrer" target="_blank" class="icon-linkedin has-circle <?php echo $iconColor; ?>"></a></li>
		<li><a href="https://twitter.com/home?status=<?= $content ?>" rel="nofollow noreferrer" target="_blank" class="icon-twitter has-circle <?php echo $iconColor; ?>"></a></li>
		<li><a href="https://plus.google.com/share?url=<?= $url ?>" rel="nofollow noreferrer" target="_blank" class="icon-googleplus has-circle <?php echo $iconColor; ?>"></a></li>
	</ul>
</div>
