<?php
/*
Plugin Name: UVA Health/School of Medicine Search Function for Genesis Framework
Plugin URI: http://technology.med.virginia.edu/digitalcommunications
Description: Replaces the default WordPress/Genesis search with combined Custom Google Search and UVA People Search.
Version: 0.1
Author: Cathy Finn-Derecki
Author URI: http://transparentuniversity.com
Copyright 2012  Cathy Finn-Derecki  (email : cad3r@virginia.edu)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//Check if Genesis is active. If not, throw an error.
//if ( ! function_exists( 'genesis_get_option' ) )
//{
/*Add options to the Genesis page for public or private search*/
require_once( trailingslashit( get_template_directory() ) . 'lib/classes/class-genesis-admin.php');
require_once( trailingslashit( get_template_directory() ) . 'lib/classes/class-genesis-admin-boxes.php');
require_once(dirname( __FILE__ ). '/uvasomsearch_options.php');
// Add a stylesheet for custom post admin panels
//
add_action('admin_init', 'uvasomsearch_admin_css');
function uvasomsearch_admin_css() {
   wp_register_style( 'uvasomsearch-admin-css', plugins_url(). '/uvasomsearch/styles/genesisnav_admin.css' );
   wp_enqueue_style( 'uvasomsearch-admin-css' );
}
/***************************************************************************/
//WIDGET STUFF
/***************************************************************************/
//link to the file with the new search widget class
require_once(dirname( __FILE__ ). '/search_widget.php');
//remove default search widget
function remove_search_widget() {
	unregister_widget('WP_Widget_Search');
}

add_action( 'widgets_init', 'remove_search_widget' );
/***************************************************************************/
//DISPLAY STUFF
/***************************************************************************/
function uvasomsearchstylesheet()
{
?>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/uvasomsearch/styles/uvasomsearch.css"  />
<?php
/*<!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/uvasomsearch/styles/ie.css"  />
<![endif]-->
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/uvasomsearch/styles/ie7.css"  />
<![endif]-->
<!--[if gte IE 9]>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/uvasomsearch/styles/ie9.css"  />
<![endif]-->*/

}
add_action( 'wp_head', 'uvasomsearchstylesheet',14 );
/************** Site search function *********************/
function uvasom_sitesearch_form($local)
{
	if ($local=="sitewide") {
		$uvasomsearchlocal = '';
		$uvasomsearchvalue = 'Search School of Medicine...';
	}
	elseif ($local=="local") {
		$uvasomsearchlocal = 'onsubmit="document.getElementById(\'q_local\').value += \'                                                                                                                                                                                                                                                                                          inurl:'.home_url().'\';"';
		$uvasomsearchvalue = 'Search this site...';
	}
?>
	<div id="uvasom_sitesearch">
        <form id="search-form_<?php echo $local;?>" action="<?php echo home_url();?>" method="get" <?php echo $uvasomsearchlocal ?>>
          <fieldset>
            <input id="cx_<?php echo $local;?>" type="hidden" name="cx" value="009548005491705796603:WMX1307513326"/>
            <input type="submit" class="uvasomsearchsubmit" alt="Search"/>
            <input type="text" class="uvasomsearchtext" name="q" id="q_<?php echo $local;?>" value="<?php echo $uvasomsearchvalue ?>" onfocus="this.value='';"/><input id="cof_<?php echo $local;?>" type="hidden" name="cof" value="FORID:11"/>
           </fieldset>
        </form>
    </div>
<?php
}
/*********add jquery for the clinical departments **************/
function uvasomsearch_slidetoggle() {
	$uvasomtheme = get_stylesheet();
///Determine if this is a clinical department, or UVA home page, which gets a different top bar
	if(($uvasomtheme === 'uvasom_clinical') || ($uvasomtheme === 'uvasom_parallax')) {
	wp_enqueue_script( 'uvasomsearch_slidetoggle', plugins_url(). '/uvasomsearch/js/uvasomslidetoggle.js', array('jquery'), '', '' );
	}
}
add_action('wp_enqueue_scripts', 'uvasomsearch_slidetoggle');

/***************************************************************************/
//TEMPLATE STUFF
/***************************************************************************/
//********Function to redirect to search results template***********//
add_action("template_redirect", 'uvasomsearch_results_redirect');
function uvasomsearch_results_redirect() {
	$plugindir = dirname( __FILE__ );
	$templatefilename = 'searchresults_paginated.php';
	//See if it is a search result
	if (strpos($_SERVER["REQUEST_URI"], 'q='))
		{
			include($plugindir . '/' . $templatefilename);
		}
	if (strpos($_SERVER["REQUEST_URI"], 's=')&& !strpos($_SERVER["REQUEST_URI"], '/cmecoursesearch?'))
		{
			include(get_stylesheet_directory(). '/search.php');
		}
}

?>
