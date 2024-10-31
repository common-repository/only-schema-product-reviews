jQuery(document).ready(function () {
    
jQuery.ajax({
    type: "POST",
    url: probj.ajaxurl,
    data: {
        action: 'scgen_prs_js',
        id: probj.id,
    },
    success: function (response) {
        var jsnd = response.data;
        if (jsnd.length > 1) {
            jQuery("<script>").attr("type", "application/ld+json").text(response.data).appendTo("head");
        }
    },
    error: function (xhr, status, error) {
        console.log(xhr);
    }
});
});