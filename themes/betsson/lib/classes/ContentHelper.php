<?php

namespace Lib\Classes;

class ContentHelper {
	/**
	 * Undocumented function
	 *
	 * @param [type] $content
	 * @param string $contentToAppend
	 * @param [type] $paragraph
	 * @param string $contentClass
	 * @param string $layout
	 * @param [type] $contentBetween The content between the big paragraph and the rest of the content
	 * @return void
	 */
	public static function modify_first_paragraph( $content, $contentToAppend = '', $paragraph = null, $contentClass = 'is-medium', $layout = 'text', $contentBetween = null ) {
		$encoding = 'UTF-8';
		$contentBefore = null;


		if( !$paragraph ) {
			// Get content of first paragraph
			$start = mb_strpos( $content, '<p>', 0, $encoding );
			$end = mb_strpos( $content, '</p>', $start, $encoding );
			$paragraph = mb_substr( $content, $start, $end - $start + 4, $encoding );

			// Get content before first paragraph
			$contentBefore = mb_substr( $content, 0, $start, $encoding );

			// Get content after first paragraph
			$contentAfter = mb_substr( $content, $end + 4, mb_strlen( $content, $encoding ), $encoding );
		} else {
			$contentAfter = $content;
		}

		if( $layout === 'quote' ) {
			$html = self::render_quote( $contentBefore, $paragraph, $contentToAppend, $contentAfter, $contentClass, $contentBetween );
		} else {
			$html = self::render_preamble( $contentBefore, $paragraph, $contentToAppend, $contentAfter, $contentClass, $contentBetween );
		}

		// Remove &nbsp;
		$html = htmlentities( $html, null, 'utf-8' );
		$html = str_replace( '&nbsp;', ' ', $html );
		$html = html_entity_decode( $html );

		return $html;
	}

	public static function render_preamble( $contentBefore, $paragraph, $contentToAppend, $contentAfter, $contentClass, $contentBetween ) {
		$image = null;
		$preamble = null;

		if( $contentToAppend ) {
			$image = '<div class="col-md-5">'. $contentToAppend .'</div>';
		}

		if( $paragraph ) {
			$preamble = '<div class="col-md-'. ( $contentToAppend ? '6' : '10' ) .' col-md-offset-1">
							<div class="large">'.$contentBefore.$paragraph.'</div>
						</div>';
		}

		return <<<HTML
		<div class="row content-top has-pad-mobile">
			{$preamble}
			{$image}
		</div>
		{$contentBetween}
		<div class="content {$contentClass}">{$contentAfter}</div>
HTML;
	}

	public static function render_quote( $contentBefore, $paragraph, $contentToAppend, $contentAfter, $contentClass, $contentBetween ) {
		$image = null;
		$quote = null;

		if( $contentToAppend ) {
			$image = '<figure class="center-image">'. $contentToAppend .'</figure>';
		}

		if( $paragraph ) {
			$quote = '<div class="quote"><div class="content"><p>'. $paragraph .'<p></div></div>';
		}

		return <<<HTML
		<div class="blockquote-container">
			<div class="container">
				<div class="image">{$image}</div>
				{$quote}
			</div>
		</div>
		{$contentBetween}
		<div class="content {$contentClass}">{$contentAfter}</div>
HTML;
	}	
}