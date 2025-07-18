<?php
/*
 * Plugin Name: JSM Adobe XMP / IPTC for WordPress
 * Text Domain: adobe-xmp-for-wp
 * Domain Path: /languages
 * Plugin URI: https://surniaulula.com/extend/plugins/adobe-xmp-for-wp/
 * Assets URI: https://jsmoriss.github.io/adobe-xmp-for-wp/assets/
 * Author: JS Morisset
 * Author URI: https://surniaulula.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Provides Adobe XMP / IPTC information from Media Library or NextGEN Gallery images using a shortcode or PHP class method.
 * Requires PHP: 7.4.33
 * Requires At Least: 5.9
 * Tested Up To: 6.8.2
 * Version: 1.6.0
 *
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes and/or incompatible API changes (ie. breaking changes).
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 *
 * Copyright 2012-2025 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'Sorry, you cannot call this webpage directly.' );
}

if ( ! class_exists( 'adobeXMPforWP' ) ) {

	class adobeXMPforWP {

		public $use_cache  = true;
		public $max_size   = 512000;	// Maximum size read.
		public $chunk_size = 65536;	// Read 64k at a time.

		private $avail     = array();	// Assoc array for function/class/method checks.
		private $cache_dir = '';
		private $cache_xmp = array();

		private static $instance = null;	// adobeXMPforWP class object.

		public function __construct() {

			add_action( 'init', array( $this, 'init_plugin' ) );

			add_action( 'plugins_loaded', array( $this, 'init_textdomain' ) );
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init_textdomain() {

			load_plugin_textdomain( 'adobe-xmp-for-wp', false, 'adobe-xmp-for-wp/languages/' );
		}

		public function init_plugin() {

			$this->avail = $this->get_avail();

			$this->cache_dir = trailingslashit( apply_filters( 'adobe_xmp_cache_dir', dirname( __FILE__ ) . '/cache/' ) );

			require_once dirname ( __FILE__ ) . '/lib/shortcode.php';
		}

		public function get_avail() {

			$ret = array();

			$ret[ 'media' ][ 'ngg' ] = class_exists( 'nggdb' ) && method_exists( 'nggdb', 'find_image' ) ? true : false;

			return $ret;
		}

		public function get_xmp( $pid ) {

			if ( isset( $this->cache_xmp[ $pid ] ) ) {

				return $this->cache_xmp[ $pid ];
			}

			if ( is_string( $pid ) && 'ngg-' === substr( $pid, 0, 4 ) ) {

				return $this->cache_xmp[ $pid ] = $this->get_ngg_xmp( substr( $pid, 4 ), false );
			}

			return $this->cache_xmp[ $pid ] = $this->get_media_xmp( $pid, false );
		}

		public function get_ngg_xmp( $pid ) {

			$xmp_arr = array();

			if ( ! empty( $this->avail[ 'media' ][ 'ngg' ] ) ) {

				global $nggdb;

				$image = $nggdb->find_image( $pid );

				if ( ! empty( $image->imagePath ) ) {

					$xmp_raw = $this->get_xmp_raw( $image->imagePath );

					if ( ! empty( $xmp_raw ) ) {

						$xmp_arr = $this->get_xmp_array( $xmp_raw );
					}
				}
			}

			return $xmp_arr;
		}

		public function get_media_xmp( $pid ) {

			$xmp_arr = array();

			if ( $file_path = get_attached_file( $pid ) ) {

				$xmp_raw = $this->get_xmp_raw( $file_path );

				if ( ! empty( $xmp_raw ) ) {

					$xmp_arr = $this->get_xmp_array( $xmp_raw );
				}
			}

			return $xmp_arr;
		}

		public function get_xmp_raw( $file_path ) {

			if ( ! file_exists( $file_path ) ) {	// Just in case.

				return false;
			}

			$start_tag  = '<x:xmpmeta';
			$end_tag    = '</x:xmpmeta>';
			$cache_file = $this->cache_dir . md5( $file_path ) . '.xml';
			$xmp_raw    = null;

			if ( $this->use_cache &&
				file_exists( $cache_file ) &&
					filemtime( $cache_file ) > filemtime( $file_path ) &&
						$cache_fh = fopen( $cache_file, 'rb' ) ) {

				$xmp_raw = fread( $cache_fh, filesize( $cache_file ) );

				fclose( $cache_fh );

			} elseif ( $file_fh = fopen( $file_path, 'rb' ) ) {

				$chunk     = '';
				$file_size = filesize( $file_path );

				while ( ( $file_pos = ftell( $file_fh ) ) < $file_size  && $file_pos < $this->max_size ) {

					$chunk .= fread( $file_fh, $this->chunk_size );

					if ( false !== ( $end_pos = strpos( $chunk, $end_tag ) ) ) {

						if ( false !== ( $start_pos = strpos( $chunk, $start_tag ) ) ) {

							$xmp_raw = substr( $chunk, $start_pos, $end_pos - $start_pos + strlen( $end_tag ) );

							if ( $this->use_cache && $cache_fh = fopen( $cache_file, 'wb' ) ) {

								fwrite( $cache_fh, $xmp_raw );

								fclose( $cache_fh );
							}
						}

						break;	// Stop reading after finding the xmp data.
					}
				}

				fclose( $file_fh );
			}

			return $xmp_raw;
		}

		public function get_xmp_array( $xmp_raw ) {

			$xmp_arr = array();

			foreach ( array(
				'Creator Email'		=> '<Iptc4xmpCore:CreatorContactInfo[^>]+?CiEmailWork="([^"]*)"',
				'Owner Name'		=> '<rdf:Description[^>]+?aux:OwnerName="([^"]*)"',
				'Creation Date'		=> '<rdf:Description[^>]+?xmp:CreateDate="([^"]*)"',
				'Modification Date'	=> '<rdf:Description[^>]+?xmp:ModifyDate="([^"]*)"',
				'Label'			=> '<rdf:Description[^>]+?xmp:Label="([^"]*)"',
				'Credit'		=> '<rdf:Description[^>]+?photoshop:Credit="([^"]*)"',
				'Source'		=> '<rdf:Description[^>]+?photoshop:Source="([^"]*)"',
				'Headline'		=> '<rdf:Description[^>]+?photoshop:Headline="([^"]*)"',
				'City'			=> '<rdf:Description[^>]+?photoshop:City="([^"]*)"',
				'State'			=> '<rdf:Description[^>]+?photoshop:State="([^"]*)"',
				'Country'		=> '<rdf:Description[^>]+?photoshop:Country="([^"]*)"',
				'Country Code'		=> '<rdf:Description[^>]+?Iptc4xmpCore:CountryCode="([^"]*)"',
				'Location'		=> '<rdf:Description[^>]+?Iptc4xmpCore:Location="([^"]*)"',
				'Title'			=> '<dc:title>\s*<rdf:Alt>\s*(.*?)\s*<\/rdf:Alt>\s*<\/dc:title>',
				'Description'		=> '<dc:description>\s*<rdf:Alt>\s*(.*?)\s*<\/rdf:Alt>\s*<\/dc:description>',
				'Creator'		=> '<dc:creator>\s*<rdf:Seq>\s*(.*?)\s*<\/rdf:Seq>\s*<\/dc:creator>',
				'Rights'		=> '<dc:rights>\s*<rdf:Alt>\s*(.*?)\s*<\/rdf:Alt>\s*<\/dc:rights>',
				'Keywords'		=> '<dc:subject>\s*<rdf:Bag>\s*(.*?)\s*<\/rdf:Bag>\s*<\/dc:subject>',
				'Hierarchical Keywords'	=> '<lr:hierarchicalSubject>\s*<rdf:Bag>\s*(.*?)\s*<\/rdf:Bag>\s*<\/lr:hierarchicalSubject>'
			) as $key => $regex ) {

				/*
				 * Get a single text string.
				 */
				$xmp_arr[ $key ] = preg_match( '/' . $regex . '/is', $xmp_raw, $match ) ? $match[1] : '';

				/*
				 * If string contains a list, then re-assign the variable as an array with the list elements.
				 */
				$xmp_arr[ $key ] = preg_match_all( '/<rdf:li[^>]*>([^>]*)<\/rdf:li>/is', $xmp_arr[ $key ], $match ) ? $match[1] : $xmp_arr[ $key ];

				/*
				 * Hierarchical keywords need to be split into a third dimension.
				 */
				if ( ! empty( $xmp_arr[ $key ] ) && $key == 'Hierarchical Keywords' ) {

					foreach ( $xmp_arr[ $key ] as $li => $val ) {

						$xmp_arr[ $key ][ $li ] = explode( '|', $val );
					}

					unset( $li, $val );
				}
			}

			return $xmp_arr;
		}
	}

	global $adobeXMP;

	$adobeXMP =& adobeXMPforWP::get_instance();
}
