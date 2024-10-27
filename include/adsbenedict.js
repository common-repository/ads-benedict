jQuery( document ).ready(function() {
  var zonloc=jQuery('.adsajaxhref');
  var zonename=zonloc.data("zone");
  jQuery.ajax({
    url : loadadsbenedict.ajax_url,
    type: 'post',
    data : {
      action: 'adsbenedict_load',
      zone: zonename
    },
    dataType: 'json',
    success : function( response ) {
		jQuery('.adsajaxhref').attr("href",response.shorturl)
		jQuery('.adsajaxsrc').attr("src",response.shortimg)
    }
  });
})