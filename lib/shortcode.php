<?php
/*
 * This script is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 3 of the License, or (at your option) any later
 * version.
 * 
 * This script is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details at
 * http://www.gnu.org/licenses/.
 * 
 * Copyright 2012-2017 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, you cannot call this webpage directly.' );
}

if ( ! class_exists( 'adobeXMPforWPShortcode' ) ) {

	class adobeXMPforWPShortcode {

		private static $instance;

		public function __construct() {
        		add_shortcode( 'xmp', array( &$this, 'xmp_shortcode' ) );
		}

		public static function &get_instance() {
			if ( ! isset( self::$instance ) )
				self::$instance = new self;
			return self::$instance;
		}

		public function xmp_shortcode( $atts, $content = null ) { 

			// using extract method here turns each key in the merged array into its own variable
			// $atts or the default array will not be modified after the call to shortcode_atts()
			extract( shortcode_atts( array( 
				'id' => null,
				'ngg_id' => null,
				'include' => 'all',
				'exclude' => null,
				'not_keyword' => null,
				'show_title' => true,
				'show_empty' => false,
			), $atts ) );

			global $adobeXMP;
			$pids = array();
			$html = '';

			if ( ! empty( $id ) ) 
				$pids = array_map( 'trim', explode( ',', $id ) );

			if ( ! empty( $ngg_id ) )
				foreach ( explode( ',', $ngg_id ) as $pid )
					$pids[] = 'ngg-'.trim( $pid );

			$include_dt = $this->explode_csv( $include );		// lowercase associative array
			$exclude_dt = $this->explode_csv( $exclude );		// lowercase associative array
			$exclude_kw = $this->explode_csv( $not_keyword );	// lowercase associative array

			$show_title = $this->get_bool( $show_title );		// sanitize true/false
			$show_empty = $this->get_bool( $show_empty );		// sanitize true/false

			foreach ( $pids as $pid ) {

				if ( empty( $pid ) )	// just in case
					continue;

				$image_xmp = $adobeXMP->get_xmp( $pid );

				if ( empty( $image_xmp ) ) {
					$html .= '<p class="xmp_error">'.sprintf( __( 'No XMP found for image ID %s.',
						'adobe-xmp-for-wp' ), $pid ).'</p>';
					continue;
				}

				if ( $include === 'all' ) {
					foreach( array_keys( $image_xmp ) as $val ) {
						$val = trim( strtolower( $val ), '\'" ' );
						$include_dt[$val] = true;
					}
				}

				$html .= "\n".'<dl class="xmp_shortcode">'."\n";

				foreach ( array_keys( $image_xmp ) as $dt ) {

					$dt_lower = strtolower( $dt );
					if ( empty( $include_dt[$dt_lower] ) ||
						! empty( $exclude_dt[$dt_lower] ) )
							continue;

					$css_class = 'xmp_'.sanitize_key( str_replace( ' ', '_', $dt_lower ) );

					if ( ! $show_empty && 
						empty( $image_xmp[$dt] ) )
							continue;

					if ( $show_title ) 
						$html .= '<dt class="'.$css_class.'">'.$dt.'</dt>'."\n";
	
					// first dimension
					if ( is_array( $image_xmp[$dt] ) ) {

						// check for second dimension
						foreach ( $image_xmp[$dt] as $dd ) {

							// second dimension arrays are printed with multiple <dd> tags
							if ( is_array( $dd ) ) {

								switch ( $dt ) {
									// check for hierarchical strings to ignore
									case 'Hierarchical Keywords' :
										if ( ! empty( $exclude_kw ) ) {
											$kws = strtolower( implode( '-', array_values( $dd ) ) );
											if ( ! empty( $exclude_kw[$kws] ) ) 
												continue 2;
										}
										break;
								}
								$html .= '<dd class="'.$css_class.'">'.
									implode( ' &gt; ', array_values( $dd ) ).'</dd>'."\n";

							// print simple arrays as a comma delimited list, and break the foreach loop
							} else {
								switch ( $dt ) {
									case 'Keywords' :
										if ( ! empty( $exclude_kw ) ) {
											foreach ( $image_xmp[$dt] as $el => $val ) {
												if ( ! empty( $exclude_kw[ strtolower( $val ) ] ) ) {
													unset ( $image_xmp[$dt][$el] );
												}
											}
										}
										break;
								}
								$html .= '<dd class="'.$css_class.'">'.
									implode( ', ', array_values( $image_xmp[$dt] ) ).'</dd>'."\n";

								// get another element from the $include array
								break;
							}
						}
					// value is a simple string
					} else $html .= '<dd class="'.$css_class.'">'.$image_xmp[$dt].'</dd>'."\n";
				}
				$html .= '</dl>'."\n";
			}
			return $html;
		}

		private function explode_csv( $str ) {
			$ret = array();
			foreach ( explode( ',', $str ) as $val ) {
				$val = trim( strtolower( $val ), '\'" ' );
				$ret[$val] = true;
			}
			return $ret;
		}

		// converts string to boolean
		private function get_bool( $mixed ) {
			return is_string( $mixed ) ?
				filter_var( $mixed, FILTER_VALIDATE_BOOLEAN ) : (bool) $mixed;
		}
	}

	adobeXMPforWPShortcode::get_instance();
}

?>
