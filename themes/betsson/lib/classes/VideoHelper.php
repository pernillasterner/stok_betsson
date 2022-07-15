<?php

namespace Lib\Classes;

class VideoHelper {
	public static function getVideoTag( $videoURL, $videoLoop = false, $videoAddControls = true, $videoAutoplay = false, $videoBackground = false ) {
		// Determine video provider through URL
		if( strpos( $videoURL, 'youtube' ) !== false ) {
			$videoSource = 'youtube';
		} else if( strpos( $videoURL, 'vimeo' ) !== false ) {
			$videoSource = 'vimeo';

			if( strpos( $videoURL, 'external' ) !== false ) {
				$videoSource = 'vimeo-external';
			}
		} else {
			throw new \Exception( 'Cannot get video source from URL.' );
		}
				
		// Extract video ID and convert to embed to URL
		if( $videoSource === 'vimeo' ) {
			$videoURL = 'https://player.vimeo.com/video/' . substr( $videoURL, strrpos( $videoURL, '/' ) + 1 ) . '?title=0&byline=0&portrait=0&autoplay=1';
	
			if ( $videoLoop ) {
				$videoURL .= '&loop=1';
			}

			if( $videoBackground ) {
				$videoURL .= "&background=1&mute=1";
			}			
		} else if( $videoSource === 'youtube' ) {
			$breakString = 'watch?v=';
	
			if( strpos( $videoURL, $breakString ) ) {
				$videoID = substr( $videoURL, strpos( $videoURL, $breakString ) + strlen( $breakString ) );
			} else {
				$videoID = substr( $videoURL, strrpos( $videoURL, '/' ) + 1 );
			}
	
			$videoURL = "https://www.youtube.com/embed/$videoID?rel=0&showinfo=0&controls=0&autoplay=1";			
	
			if ( $videoLoop ) {
				$videoURL .= "&loop=1&playlist=$videoID";
			}		
	
			if ( isset( $videoAddControls ) && $videoAddControls ) {
				$videoURL = str_replace( 'controls=0', 'controls=1', $videoURL );
			}
		} else if( $videoSource === 'vimeo-external' ) {
			$videoLoop = $videoLoop ? 'loop' : '';
			$videoAutoplay = $videoAutoplay ? 'autoplay' : '';
			
			return '<video playsinline ' . $videoAutoplay . ' muted ' . $videoLoop . '><source src="' . $videoURL . '" type="video/mp4">Your browser does not support the video tag.</video>';
		}
	
		if( isset( $videoAutoplay ) && $videoAutoplay ) {
			if( $videoSource === 'vimeo' ) {				
				return '<iframe src="' . $videoURL . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			} else if( $videoSource === 'youtube' ) {
				$videoScriptTag = '<script>var tag=document.createElement("script");tag.src="https://www.youtube.com/player_api";var firstScriptTag=document.getElementsByTagName("script")[0];firstScriptTag.parentNode.insertBefore(tag,firstScriptTag);var player;function onYouTubePlayerAPIReady(){player=new YT.Player("js-yt",{videoId:"' .$videoID.'",playerVars:{"controls":0,"showinfo":0,"rel":0,"loop":1,"autoplay":1,"mute":1},events:{onStateChange:function(e){if(e.data===YT.PlayerState.ENDED){player.playVideo();}}}});}</script>';
				
				return  '<div id="js-yt"></div>' . $videoScriptTag;
			}
		} else {
			return '<iframe src="" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen data-url="' . $videoURL . '" style="display:none"></iframe>';
		}		
	}
}