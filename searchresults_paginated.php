<?php
//admin page
/**
 * This file handles the search results page.
*/
/***************change the doctype for IE and Google***************/
function uvasomsearch_do_doctype() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<?php
}
remove_action( 'genesis_doctype', 'genesis_do_doctype' );
add_action( 'genesis_doctype', 'uvasomsearch_do_doctype' );
/*********add jquery for people search pagination **************/
function uvasomsearch_paginate() {
	wp_enqueue_script( 'load_paginate_js', plugins_url(). '/uvasomsearch/js/jquery.pajinate.js', array('jquery'), '', '' );
	wp_enqueue_script( 'uvasomsearch_paginate_js', plugins_url(). '/uvasomsearch/js/uvasomsearch_paginate.js', array('jquery'), '', '' );
}    
add_action('wp_enqueue_scripts', 'uvasomsearch_paginate');
/*********Make it a full-width layout with no sidebars.**************/
//* Unregister content/sidebar layout setting
genesis_unregister_layout( 'content-sidebar' );
 
//* Unregister sidebar/content layout setting
genesis_unregister_layout( 'sidebar-content' );
 
//* Unregister content/sidebar/sidebar layout setting
genesis_unregister_layout( 'content-sidebar-sidebar' );
 
//* Unregister sidebar/sidebar/content layout setting
genesis_unregister_layout( 'sidebar-sidebar-content' );
 
//* Unregister sidebar/content/sidebar layout setting
genesis_unregister_layout( 'sidebar-content-sidebar' );
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');
/*********Add the search class to the page body for optional theme styling**************/
function uvasomsearch_add_classes( $classes ) {
		if (strpos($_SERVER["REQUEST_URI"], 'q=')) {
	$classes[] = 'google';
		}
	$classes[] = 'search';
	return $classes;
}
add_filter( 'body_class', 'uvasomsearch_add_classes' );
/**************************************************************************************************/
//THESE LAYOUT ADJUSTMENTS ARE  SPECIFIC TO THE UVASOM BIMS THEME ONLY//////////////////////////////
/**************************************************************************************************/
/*********Move the page title from its default location, per the BIMS Theme**************/
if (get_stylesheet() =='uvasom_bims') {
add_action( 'genesis_post_title','genesis_do_post_title' );
add_action( 'genesis_after_header', 'uvasom_do_search_title' );
}
if (get_stylesheet() =='uvasom_news') {
add_action( 'genesis_post_title','genesis_do_post_title' );
add_action( 'genesis_before_loop', 'uvasom_do_search_title' );
}

/*********Get rid of the home page layout stuff if this is the UVASOM News Theme**************/
/**************************************************************************************************/
function uvasom_do_search_title() {
	if (strpos($_SERVER["REQUEST_URI"], 'inurl')) {
		$title = sprintf( '<div class="clearfix"></div><div id="uvasom_page_title">'.genesis_do_breadcrumbs().'<h1 class="archive-title">%s %s</h1>', apply_filters( 'genesis_search_title_text', __( 'Search Results for:', 'genesis' ) ), (substr($_GET['q'], 0, strpos($_GET['q'], "inurl:"))) ).'</div>';
		}
		else {
		$title = sprintf( '<div class="clearfix"></div><div id="uvasom_page_title">'.genesis_do_breadcrumbs().'<h1 class="archive-title">%s %s</h1>', apply_filters( 'genesis_search_title_text', __( 'Search Results for:', 'genesis' ) ), $_GET['q']).'</div>';
		}
	echo apply_filters( 'genesis_search_title_output', $title ) . "\n";
}
/** Remove default sidebar */
        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
 
        // Remove the Secondary Sidebar from the Secondary Sidebar area.
        remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_post_content', 'genesis_do_post_content' );
remove_action( 'genesis_post_content', 'genesis_do_post_image' );
add_action( 'genesis_loop', 'uvasom_select_google_cse_content' );
remove_action( 'genesis_before_post_content', 'genesis_post_info' );
function uvasom_select_google_cse_content() { ?>

<div id="search-results">
<h1>Web Search Results</h1>
<script>
  (function() {
    var cx = '009548005491705796603:WMX1307513326';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    //var s = document.getElementsByTagName('script')[0];
    //s.parentNode.insertBefore(gcse, s);
	document.body.appendChild(gcse);
  })();
</script>
<gcse:searchresults-only></gcse:searchresults-only>
</div>
<div id="people-results">
<?php
echo '<h1>People Search Results</h1>';
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/uvasomsearch/ldap.php');
//If this is a local site search, clean up the q field to eliminate the 
//local url string and retrieve only the user-entered value.
global $ldapConnect;
	if (strpos($_SERVER["REQUEST_URI"], 'inurl'))
	{
		$ldap = new Ldap("name", substr($_GET['q'], 0, strpos($_GET['q'], "                                   inurl:")));
	}
	else
	{
		$ldap = new Ldap("name", $_GET['q']);
	
	}
$ldapInfo = $ldap->search();
$numEntries = count($ldapInfo);
	if ($numEntries > 7) {
		echo '<div class="page_navigation"></div>';
	}
echo ' <ul class="content">';

    for ($x = 0; $x < ($numEntries-1); $x++) {
		if (!empty($ldapInfo[$x]["cn"][0])) {
        $name   = $ldapInfo[$x]["cn"][0];
		}
 		if (!empty($ldapInfo[$x]["uid"][0])) {
	    $compId = $ldapInfo[$x]["uid"][0];
		}
		if (!empty($ldapInfo[$x]["mail"][0])) {
        $email  = $ldapInfo[$x]["mail"][0];
		}
 		if (!empty($ldapInfo[$x]["description"][0])) {
        $affil  = $ldapInfo[$x]["description"][0];
		}
		if (!empty($ldapInfo[$x]["uvadisplaydepartment"][0])) {
        $dept   = $ldapInfo[$x]["uvadisplaydepartment"][0];
		}
		if (!empty($ldapInfo[$x]["telephonenumber"][0])) {
        $phone  = $ldapInfo[$x]["telephonenumber"][0];
 		}
       	if (!empty($ldapInfo[$x]["physicaldeliveryofficename"][0])) {
		$addr   = $ldapInfo[$x]["physicaldeliveryofficename"][0];
		}
		
        if (!empty($name)) {
			if (!empty($dept)){ $deptsep = ", ";} else { $deptsep = "";}
			if (!empty($email)){ $printemail = "<a href=\"mailto:".$email."\">"            . $email  . "</a>";} else { $printemail = "<span class=\"none\">No current email</span>";}
			if (!empty($phone)){ $officephone = "  <p><span class=\"label\">Tel:</span> "     . $phone  . "</p>\n" ;} else { $officephone = "";}
			if (!empty($addr)){ $officeaddr = "  <p><span class=\"label\">Office Address:</span><br />"   . $addr   . "</p>\n";} else { $officeaddr = "";}
            echo " <li>\n" .
                  "  <p class=\"name\">" . $name . "</p>\n" .
                  //"  <p>UVa Computing ID: " . $compId . "</p>\n" .
                  "  <p><span class=\"label\">Email: </span>".$printemail."</p>\n" .
                 "  <p>". $affil  . $deptsep. $dept   ."</p>\n" .
                 //"  <p><strong>Department:</strong> "       . $dept   . "</p>\n" .
                  $officephone .
                  $officeaddr .
                  " </li>\n";
        }
		
		
	}
			if ($numEntries === 1)
 		{
			echo '<p>No People Search results.</p>';
		}
		//ldap_close($ldapConnect);
?>
</ul>
</div>
<?php
if ($numEntries > 7) {
		echo '<div class="page_navigation"></div>';
	}
 }

