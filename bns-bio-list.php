<?php
/*
Plugin Name: BNS Bio List
Plugin URI: http://buynowshop.com/plugins/bns-bio/
Description: An extension plugin included with BNS Bio to output the layout in an unordered list
Version: 0.1
Text Domain: bns-bio-list
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * BNS Bio List
 * An extension plugin included with BNS Bio to output the layout in an
 * unordered list.
 *
 * @package     BNS_Bio
 * @subpackage  BNS_Bio_List
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

/** Sanity check - is the plugin active? */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'bns-bio/bns-bio.php' ) ) {
    /**
     * Add additional actions to change the layout
     * Set priority higher than default (read: action fires later than default)
     */
    add_action( 'bns_bio_before_all', function(){ echo '<ul class="bns-bio-list">'; }, 20 );
    add_action( 'bns_bio_after_all', function(){ echo '</ul><!-- .bns-bio-list -->'; }, 20 );

    function bns_bio_list_item(){
        echo '<li class="bns-bio-list-item">';
    }

    /** No closing 'li' tag is required under CSS3 - let's take advantage of that */
    add_action( 'bns_bio_before_author_name', 'bns_bio_list_item' );
    add_action( 'bns_bio_before_author_url', 'bns_bio_list_item' );
    add_action( 'bns_bio_before_author_email', 'bns_bio_list_item' );
    add_action( 'bns_bio_before_author_desc', 'bns_bio_list_item' );

} else {
    /** @var $exit_message string - Message to display if 'BNS Bio' is not activated */
    $exit_message = __( 'BNS Bio List requires the BNS Bio Plugin to be activated first.', 'bns-bio-list' );
    exit ( $exit_message );
}