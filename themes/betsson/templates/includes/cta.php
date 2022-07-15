<?php

	if( $cta ) :
		$newCta = $cta;
		$newCtaStyle = $ctaStyle;
		$newCtaClass = !empty($ctaClass) ? $ctaClass : null;
		$newCtaBtnAttr = null;
		$newCtaColor = $ctaColor;

		if( !empty( $newCtaStyle ) && $newCtaStyle === 'link' ) {
			$newCtaBtnClass = 'btn-underlined';
		} elseif( $newCtaStyle === 'button' && $newCtaColor === 'blue' ) {
			$newCtaBtnClass = 'btn-primary';
		} else {
			$newCtaBtnClass = 'btn-secondary';
		}

		if( !empty( $ctaButtonClass ) ) {
			$newCtaBtnClass .= ' '.$ctaButtonClass;
		}

		if( !empty( $ctaButtonAttr ) ) {
			$newCtaBtnAttr = $ctaButtonAttr;
		}
		?>

		<div class="<?php echo $newCtaClass; ?>">
			<a 
				href="<?php echo $newCta['url']; ?>" 
				class="btn <?php echo $newCtaBtnClass; ?>"
				<?php echo $newCtaBtnAttr; ?>
				<?php echo $newCta['target'] ? 'target="_blank" rel="nofollow noreferrer"' : null; ?>>
				<?php echo $newCta['title']; ?>
			</a>
		</div>

		<?php
		$newCta = null;
		$newCtaStyle= null;
		$newCtaClass = null;
		$newCtaColor = null;

	endif;