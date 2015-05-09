<?php
/*
Copyright 2013 - Jean-Sebastien Morisset - http://surniaulula.com/

This script is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 3 of the License, or (at your option) any later
version.

This script is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details at
http://www.gnu.org/licenses/.
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'Sorry, you cannot call this webpage directly.' );

if ( ! class_exists( 'adobeXMPforWPShortCodes' ) ) {

	class adobeXMPforWPShortCodes {

		function __construct() {
        		add_shortcode( 'xmp', array( &$this, 'xmp_shortcode' ) );
		}

		function xmp_shortcode( $atts, $content = null ) { 
			// using extract method here turns each key in the merged array into its own variable
			// $atts or the default array will not be modified after the call to shortcode_atts()
			extract( shortcode_atts( array( 
				'id' => null,
				'ngg_id' => null,
				'include' => 'all',
				'exclude' => null,
				'show_title' => 'yes',
				'not_keyword' => null,
			), $atts ) );

			global $adobeXMP;
			$pids = array();
			$html = '';

			if ( ! empty( $id ) ) 
				$pids = explode( ',', $id );

			if ( ! empty( $ngg_id ) )
				foreach ( explode( ',', $ngg_id ) as $pid )
					$pids[] = 'ngg-'.$pid;

			// create and associative array of excluded titles and keywords for easy lookup
			$exclude_title = array();
			if ( ! empty( $exclude ) ) {
				foreach ( explode( ',', $exclude ) as $val )
				$exclude_title[ strtolower($val) ] = 1;
			}
			$exclude_keyword = array();
			if ( ! empty( $not_keyword ) ) {
				foreach ( explode( ',', $not_keyword ) as $val )
				$exclude_keyword[ strtolower($val) ] = 1;
			}

			foreach ( $pids as $pid ) {

				if ( empty( $pid ) ) continue;
				$xmp = $adobeXMP->get_xmp( $pid );
				if ( $include == 'all' ) $include = implode( ',', array_keys( $xmp ) );

				$html .= "\n".'<dl class="xmp_shortcode">'."\n";
				foreach ( explode( ',', $include ) as $dt ) {

					if ( ! empty( $exclude_title[ strtolower( $dt ) ] ) ) continue;

					$class = preg_replace( '/ /', '_', strtolower( $dt ) );
					if ( $show_title == 'yes' ) $html .= '<dt class="xmp_'.$class.'">'.$dt.'</dt>'."\n";
	
					// first dimension
					if ( is_array( $xmp[$dt] ) ) {

						// check for second dimension
						foreach ( $xmp[$dt] as $dd ) {

							// second dimension arrays are printed with multiple <dd> tags
							if ( is_array( $dd ) ) {

								switch ( $dt ) {
									// check for hierarchical strings to ignore
									case 'Hierarchical Keywords' :
										if ( ! empty( $exclude_keyword ) ) {
											$kws = strtolower( implode( '-', array_values( $dd ) ) );
											if ( ! empty( $exclude_keyword[ $kws ] ) ) continue 2;
										}
										break;
								}
								$html .= '<dd class="xmp_'.$class.'">'.implode( ' &gt; ', array_values( $dd ) ).'</dd>'."\n";

							// print simple arrays as a comma delimited list, and break the foreach loop
							} else {
								switch ( $dt ) {
									case 'Keywords' :
										if ( ! empty( $exclude_keyword ) )
											foreach ( $xmp[$dt] as $el => $val )
												if ( ! empty( $exclude_keyword[ strtolower( $val ) ] ) )
													unset ( $xmp[$dt][$el] );
										break;
								}
								$html .= '<dd class="xmp_'.$class.'">'.implode( ', ', array_values( $xmp[$dt] ) ).'</dd>'."\n";
								// get another element from the $include array
								break;
							}
						}
					// value is a simple string
					} else $html .= '<dd class="xmp_'.$class.'">'.$xmp[$dt].'</dd>'."\n";
				}
				$html .= '</dl>'."\n";
			}
			return $html;
		}
	}
}
?>
