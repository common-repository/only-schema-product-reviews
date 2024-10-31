<?php

/**
 * 
 * @author pramodc <pramod@crawlcenter.com>
 */

class PRSCGEN
{

    private $of_cnd = [];
    private $of_av = [];
    private $of_pvu = [];
    private $of_pr = [];
    private  $of_ur = [];

    private  $athd;
    private $off;
    private $rvb;
    private $brn;
    private $itm_img;
    private  $rvar = [];
    private  $sub_rv = [];
    private  $mrv = [];

    private $itm_desc = "";
    private $itm_mpn = "";
    private  $itm_sku = "";
    private   $itm_title = "";
    private $itm_curr = "";
    private $itm_pr = "";

    private $ofa = [];
    private $au = [];
    private $jsn = [];
    private  $brar = [];
    private  $mnArr = [];
    private  $auth_ar = [];


    function prs_scgen_gnsh()
    {

        $pidfp = sanitize_text_field($_POST['id']);
        $psts = get_post_meta($pidfp, "pr_rv_status", true);
        $jsssc = "";
        if (!empty($psts) && $psts == 'Yes') {
            $post = get_post($pidfp);
            if (get_post_meta($post->ID, "pr_rv_sch_jsn", true) !== null) {
                $jsn = json_decode(get_post_meta($post->ID, "pr_rv_sch_jsn", true));
            }

            foreach ($jsn as $key => $value) {

                if ($key == 'itm_rat' && isset($value)) {
                    $sub_rv = array("@type" => "Rating", "ratingValue" => $value, "bestRating" => "5");
                    $rvar = array("@type" => "Review", "reviewRating" => $sub_rv);
                    $this->mrv = $rvar;
                }

                if ($key == 'itm_cond' && isset($value)) {
                    $this->of_cnd = array("itemCondition" => $value);
                }
                if ($key == 'pr_ava' && isset($value)) {
                    $this->of_av = array("availability" => $value);
                }
                if ($key == 'itm_curr' && isset($value)) {
                    $this->itm_curr = array("priceCurrency" => $value);
                }
                if ($key == 'pr_valid' && isset($value)) {
                    $this->of_pr = array("priceValidUntil" => $value);
                }
                if ($key == 'itm_bnm' && isset($value)) {
                    $this->brar = array("@type" => "Brand", "name" => $value);
                }

                if ($key == 'itm_img' && isset($value)) {
                    $urlsA = explode(',', $value);
                    $narr = [];
                    if (count($urlsA) > 1) {
                        foreach ($urlsA as $key => $value) {
                            array_push($narr, $value);
                        }
                        $this->itm_img = $narr;
                    } else {
                        $this->itm_img = $value;
                    }
                }

                if ($key == "itm_pr" && isset($value)) {
                    $this->itm_pr = array("price" => $value);
                }

                if ($key == 'description' && isset($value)) {
                    $this->itm_desc = $value;
                }

                if ($key == 'itm_mpn' && isset($value)) {
                    $this->itm_mpn = $value;
                }

                if ($key == 'itm_sku' && isset($value)) {
                    $this->itm_sku = $value;
                }

                if ($key == 'itm_title' && isset($value)) {
                    $this->itm_title = $value;
                }
            }

            $this->of_ur = array("url" => get_permalink($post->ID));
            $this->ofa = array_merge(array("@type" => "Offer"), $this->of_cnd, $this->of_pr, $this->of_pvu, $this->of_av, $this->of_ur, $this->itm_curr, $this->itm_pr);

            $rv_combined = [];
            $an = $this->prs_scgen_authorNameFromDb($post->post_author);
            if (isset($an) && !empty($an)) {
                $this->au  = array("@type" => "Person", "name" => $an);
                $rv_combined = array_merge($this->mrv, array("author" => $this->au));
            }


            $mnArr =   array(
                "@context" => "https://schema.org/",
                "@type" => "Product",
            );

            if (isset($this->itm_desc)  && !empty($this->itm_desc)) {
                $mnArr["description"] = $this->itm_desc;
            }
            if (isset($this->itm_title) && !empty($this->itm_title)) {
                $mnArr["name"] = $this->itm_title;
            }
            if (isset($this->itm_img)) {
                $mnArr['image'] = $this->itm_img;
            }
            if (isset($this->brar) && !empty($this->brar['name'])) {
                $mnArr["brand"] = $this->brar;
            }
            if (isset($rv_combined)) {
                $mnArr["review"] = $rv_combined;
            }
            if (isset($this->itm_mpn) && !empty($this->itm_mpn)) {
                $mnArr['mpn'] = $this->itm_mpn;
            }
            if (isset($this->itm_sku) && !empty($this->itm_sku)) {
                $mnArr['sku'] = $this->itm_sku;
            }

            if (isset($this->ofa)) {
                $mnArr['offers'] = $this->ofa;
            }

            $jsssc = json_encode($mnArr);
        }

        return $jsssc;
    }


    function prs_scgen_authorNameFromDb($id)
    {
        global $wpdb;
        $auth_name = "";
        $auth_name_ar = $wpdb->get_results("select display_name from {$wpdb->prefix}users where ID='" . $id . "'", OBJECT);
        foreach ($auth_name_ar as $key => $row) {
            $auth_name = $row->display_name;
        }
        return $auth_name;
    }
}
