jQuery(document).ready(function () {

    var sbxv;
    jQuery("#prs_sbox").on("change", function () {
        var sbx = jQuery("#prs_sbox").val();
        sbx == 'Yes' ? jQuery("#prs_rv_mn").attr("class", "prs_sbx_sh") : jQuery("#prs_rv_mn").attr("class", "prs_sbx_hdn");
    });
});