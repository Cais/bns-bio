<?php
/*
Plugin Name: BNS Bio Samples
Plugin URI: http://buynowshop.com/plugins/bns-bio/
Description: An extension plugin included with BNS Bio to provide samples for your own extensions
Version: 0.1
Text Domain: bns-bio-samples
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * BNS Bio Samples
 * An extension plugin included with BNS Bio to provide samples for your own
 * extensions.
 *
 * @package     BNS_Bio
 * @subpackage  BNS_Bio_Samples
 * @link        http://buynowshop.com/plugins/bns-bio/
 * @link        https://github.com/Cais/bns-bio/
 * @link        http://wordpress.org/extend/plugins/bns-bio/
 * @version     0.1
 * @author      Edward Caissie <edward.caissie@gmail.com>
 * @copyright   Copyright (c) 2012, Edward Caissie
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.
 *
 * You may NOT assume that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to:
 *
 *      Free Software Foundation, Inc.
 *      51 Franklin St, Fifth Floor
 *      Boston, MA  02110-1301  USA
 *
 * The license for this software can also likely be found here:
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

function bns_bio_samples_styles_and_scripts() {
    /** Enqueue Scripts */
    wp_enqueue_script( 'bns_bio_sample', plugin_dir_url( __FILE__ ) . 'bns-bio-samples.js', array( 'jquery' ), 0.1 );
}
add_action( 'wp_enqueue_scripts', 'bns_bio_samples_styles_and_scripts' );

function bns_bio_open_table() {
    return '<table>';
}

function bns_bio_close_table() {
    return '</table>';
}

function bns_bio_open_row() {
    return '<tr>';
}

function bns_bio_close_row() {
    return '</tr>';
}

/** Sanity check - is the plugin active? */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'bns-bio/bns-bio.php' ) ) {

    /** Wrap output in table */
    add_action( 'bns_bio_before_all', 'bns_bio_open_table' );
    add_action( 'bns_bio_after_all', 'bns_bio_close_table' );

    add_action( 'bns_bio_before_author_name', 'bns_bio_open_row' );
    add_action( 'bns_bio_after_author_name', 'bns_bio_close_row' );

    add_action( 'bns_bio_before_author_url', 'bns_bio_open_row' );
    add_action( 'bns_bio_after_author_url', 'bns_bio_close_row' );

    add_action( 'bns_bio_before_author_email', 'bns_bio_open_row' );
    add_action( 'bns_bio_after_author_email', 'bns_bio_close_row' );

    add_action( 'bns_bio_before_author_desc', 'bns_bio_open_row' );
    add_action( 'bns_bio_after_author_desc', 'bns_bio_close_row' );

    /** Hide the email address by returning a null string to the filter */
    add_filter( 'bns_bio_author_email_text', '__return_null' );
    add_filter( 'bns_bio_author_email', '__return_null' );

} else {
    /** @var $exit_message string - Message to display if 'BNS Bio' is not activated */
    $exit_message = __( 'BNS Bio Samples requires the BNS Bio Plugin to be activated first.', 'bns-bio-samples' );
    exit ( $exit_message );
}