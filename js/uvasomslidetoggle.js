jQuery(document).ready(function($) {
	//$("#uvasomclinical_topbar #uvasom_wpsearch").hide( "fast" );
  $("#uvasomclinical_topbar span.searchbutton a").click(function(){
    $("#uvasomclinical_topbar #uvasom_wpsearch").toggle( "slow","linear" );
	$(this).toggleClass("active");
  });
    $("#uvasom_parallax_topbar span.searchbutton").click(function(){
    $("#uvasom_parallax_topbar #uvasom_wpsearch #uvasom_sitesearch").toggle( "slow","linear" );
	$(this).toggleClass("active");
  });

});

