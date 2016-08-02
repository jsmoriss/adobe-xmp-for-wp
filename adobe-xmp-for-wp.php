<?php
/*
 * Plugin Name: JSM's Adobe XMP / IPTC for WordPress
 * Plugin URI: http://surniaulula.com/extend/plugins/adobe-xmp-for-wp/
 * Author: JS Morisset
 * Author URI: http://surniaulula.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Description: Read Adobe XMP / IPTC information, using a Shortcode or PHP Class, from Media Library and NextGEN Gallery images.
 * Requires At Least: 3.0
 * Tested Up To: 4.6
 * Version: 1.2.1-1
 * 
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
 * Copyright 2012-2016 Jean-Sebastien Morisset (http://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) )
	die( 'Sorry, you cannot call this webpage directly.' );

if ( ! class_exists( 'adobeXMPforWP' ) ) {

	class adobeXMPforWP {

		var $is_active = array();	// assoc array for function/class/method checks
		var $plugin_name = '';
		var $cache_dir = '';
		var $use_cache = true;
		var $xmp = array();

		function __construct() {
			$this->load_dependencies();
			add_action( 'init', array( &$this, 'init_plugin' ) );
		}

		function load_dependencies() {
			require_once ( dirname ( __FILE__ ).'/lib/shortcodes.php' );
		}

		function init_plugin() {
			$this->load_is_active();
			$this->xmp_shortcodes = new adobeXMPforWPShortCodes();
			$this->plugin_name = basename( dirname( __FILE__ ) ).'/'.basename( __FILE__ );
			$this->cache_dir = dirname ( __FILE__ ).'/cache/';
			if ( ! is_dir( $this->cache_dir ) ) 
				mkdir( $this->cache_dir );
		}

		function load_is_active() {
			$this->is_active['ngg'] = class_exists( 'nggdb' ) && 
				method_exists( 'nggdb', 'find_image' ) ? 1 : 0;
		}

		function get_xmp( $pid, $ret_xmp = true ) {
			if ( is_string( $pid ) && substr( $pid, 0, 4 ) == 'ngg-' ) {
				$this->get_ngg_xmp( substr( $pid, 4 ), false );
			} else $this->get_media_xmp( $pid, false );

			if ( $ret_xmp == true ) 
				return $this->xmp;
		}

		function get_ngg_xmp( $pid, $ret_xmp = true ) {
			$this->xmp = array();	// reset the variable
			if ( ! empty( $this->is_active['ngg'] ) ) {
				global $nggdb;
				$image = $nggdb->find_image( $pid );
				if ( ! empty( $image ) ) {
					$xmp_raw = $this->get_xmp_raw( $image->imagePath );
					if ( ! empty( $xmp_raw ) ) 
						$this->xmp = $this->get_xmp_array( $xmp_raw );
				}
			}
			if ( $ret_xmp == true ) 
				return $this->xmp;
		}

		function get_media_xmp( $pid, $ret_xmp = true ) {
			$this->xmp = array();	// reset the variable
			$xmp_raw = $this->get_xmp_raw( get_attached_file( $pid ) );
			if ( ! empty( $xmp_raw ) ) 
				$this->xmp = $this->get_xmp_array( $xmp_raw );
			if ( $ret_xmp == true ) 
				return $this->xmp;
		}

		function get_xmp_array( &$xmp_raw ) {
			$xmp_arr = array();
			foreach ( array(
				'Creator Email'	=> '<Iptc4xmpCore:CreatorContactInfo[^>]+?CiEmailWork="([^"]*)"',
				'Owner Name'	=> '<rdf:Description[^>]+?aux:OwnerName="([^"]*)"',
				'Creation Date'	=> '<rdf:Description[^>]+?xmp:CreateDate="([^"]*)"',
				'Modification Date'	=> '<rdf:Description[^>]+?xmp:ModifyDate="([^"]*)"',
				'Label'		=> '<rdf:Description[^>]+?xmp:Label="([^"]*)"',
				'Credit'	=> '<rdf:Description[^>]+?photoshop:Credit="([^"]*)"',
				'Source'	=> '<rdf:Description[^>]+?photoshop:Source="([^"]*)"',
				'Headline'	=> '<rdf:Description[^>]+?photoshop:Headline="([^"]*)"',
				'City'		=> '<rdf:Description[^>]+?photoshop:City="([^"]*)"',
				'State'		=> '<rdf:Description[^>]+?photoshop:State="([^"]*)"',
				'Country'	=> '<rdf:Description[^>]+?photoshop:Country="([^"]*)"',
				'Country Code'	=> '<rdf:Description[^>]+?Iptc4xmpCore:CountryCode="([^"]*)"',
				'Location'	=> '<rdf:Description[^>]+?Iptc4xmpCore:Location="([^"]*)"',
				'Title'		=> '<dc:title>\s*<rdf:Alt>\s*(.*?)\s*<\/rdf:Alt>\s*<\/dc:title>',
				'Description'	=> '<dc:description>\s*<rdf:Alt>\s*(.*?)\s*<\/rdf:Alt>\s*<\/dc:description>',
				'Creator'	=> '<dc:creator>\s*<rdf:Seq>\s*(.*?)\s*<\/rdf:Seq>\s*<\/dc:creator>',
				'Keywords'	=> '<dc:subject>\s*<rdf:Bag>\s*(.*?)\s*<\/rdf:Bag>\s*<\/dc:subject>',
				'Hierarchical Keywords'	=> '<lr:hierarchicalSubject>\s*<rdf:Bag>\s*(.*?)\s*<\/rdf:Bag>\s*<\/lr:hierarchicalSubject>'
			) as $key => $regex ) {

				// get a single text string
				$xmp_arr[$key] = preg_match( "/$regex/is", $xmp_raw, $match ) ? $match[1] : '';

				// if string contains a list, then re-assign the variable as an array with the list elements
				$xmp_arr[$key] = preg_match_all( "/<rdf:li[^>]*>([^>]*)<\/rdf:li>/is", $xmp_arr[$key], $match ) ? $match[1] : $xmp_arr[$key];

				// hierarchical keywords need to be split into a third dimension
				if ( ! empty( $xmp_arr[$key] ) && $key == 'Hierarchical Keywords' ) {
					foreach ( $xmp_arr[$key] as $li => $val ) $xmp_arr[$key][$li] = explode( '|', $val );
					unset ( $li, $val );
				}
			}
			return $xmp_arr;
		}

		function get_xmp_raw( $filepath ) {

			$max_size = 512000;	// maximum size read
			$chunk_size = 65536;	// read 64k at a time
			$start_tag = '<x:xmpmeta';
			$end_tag = '</x:xmpmeta>';
			$cache_file = $this->cache_dir.md5( $filepath ).'.xml';
			$xmp_raw = null; 

			if ( $this->use_cache == true && file_exists( $cache_file ) && 
				filemtime( $cache_file ) > filemtime( $filepath ) && 
				$cache_fh = fopen( $cache_file, 'rb' ) ) {

				$xmp_raw = fread( $cache_fh, filesize( $cache_file ) );
				fclose( $cache_fh );

			} elseif ( $file_fh = fopen( $filepath, 'rb' ) ) {

				$chunk = '';
				$file_size = filesize( $filepath );
				while ( ( $file_pos = ftell( $file_fh ) ) < $file_size  && $file_pos < $max_size ) {
					$chunk .= fread( $file_fh, $chunk_size );
					if ( ( $end_pos = strpos( $chunk, $end_tag ) ) !== false ) {
						if ( ( $start_pos = strpos( $chunk, $start_tag ) ) !== false ) {

							$xmp_raw = substr( $chunk, $start_pos, 
								$end_pos - $start_pos + strlen( $end_tag ) );

							if ( $this->use_cache == true && $cache_fh = fopen( $cache_file, 'wb' ) ) {

								fwrite( $cache_fh, $xmp_raw );
								fclose( $cache_fh );
							}
						}
						break;	// stop reading after finding the xmp data
					}
				}
				fclose( $file_fh );
			}
			return $xmp_raw;
		}
	}

        global $adobeXMP;
	$adobeXMP = new adobeXMPforWP();
}

?>
