<?php

/**
 * 
 * @author pramodc <pramod@crawlcenter.com>
 */

class PRSCMN
{
    private $sbox_val;

    private $brand_name;
    private $description;
    private $mpn;
    private $itm_rt;
    private $purl;
    private $price;
    private $prvu;
    private $itm_cond;
    private $itm_av;
    private $sslr;
    private $prsku;
    private $itm_pr;
    private $itm_vd;
    private $itm_slr;
    private $itm_img;
    private $itm_curr;
    private $itm_title;


    function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'prsc_enq_css_js']);
        add_action('wp_enqueue_scripts', [$this, 'prsc_enq_nlscripts']);
        add_action('add_meta_boxes', [$this, 'prsc_schm_bx']);
        add_action('save_post', [$this, 'prsc_pst_sv']);
        add_action('wp_ajax_nopriv_scgen_prs_js', [$this, 'scgen_prs_js']);
    }

    public function scgen_prs_js()
    {
            require_once 'PRSCGEN.php';
            $scgo = new PRSCGEN();
            $mnarr =  $scgo->prs_scgen_gnsh();
            wp_send_json_success($mnarr);
            wp_die();
    }

    function prsc_enq_nlscripts()
    {
        global $post;
        wp_enqueue_script('jquery');
        $this->rnenqjsnl();
        wp_localize_script(
            'prscnl',
            'probj',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'id' => $post->ID,
            )
        );
    }

    function rnenqjs()
    {
        wp_register_script('prscjs', plugins_url('js/prscmn.js', __FILE__));
        wp_enqueue_script('prscjs', plugins_url('js/prscmn.js', __FILE__));
    }

    function rnenqjsnl()
    {
        wp_register_script('prscnl', plugins_url('js/prscnl.js', __FILE__));
        wp_enqueue_script('prscnl', plugins_url('js/prscnl.js', __FILE__));
    }


    function prsc_enq_css_js()
    {
        $this->rnenqjs();
        wp_enqueue_style('prsc', plugins_url('css/prscst.css', __FILE__));
    }

    function prsc_schm_bx()
    {
        add_meta_box(
            'prsc_schm_bx_1',
            'Product Review Schema',
            [$this, 'prsc_scm_cb'],
            'post',
            'normal',
            'high'
        );
    }

    function prsc_scm_cb()
    {
        $arr = [];
        global $post;
        if (!empty(get_post_meta($post->ID, 'pr_rv_status', true))) {
            $this->sbox_val = get_post_meta($post->ID, 'pr_rv_status', true);
        }
        if (!empty(get_post_meta($post->ID, 'pr_rv_sch_jsn', true))) {
            $arr =  json_decode(get_post_meta($post->ID, 'pr_rv_sch_jsn', true));
            //      echo print_r($arr);
        }

        foreach ($arr as $key => $value) {
            switch ($key) {
                case 'itm_bnm':
                    $this->brand_name = $value;
                    break;
                case 'itm_mpn':
                    $this->mpn = $value;
                    break;
                case 'itm_rat':
                    $this->itm_rt = $value;
                    break;
                case 'itm_cond':
                    $this->itm_cond = $value;
                    break;
                case 'itm_pr':
                    $this->itm_pr = $value;
                    break;
                case 'description':
                    $this->description = $value;
                    break;
                case 'pr_valid':
                    $this->itm_vd = $value;
                    break;
                case 'pr_ava':
                    $this->itm_av = $value;
                    break;
                case 'seller':
                    $this->itm_slr = $value;
                    break;
                case 'itm_sku':
                    $this->prsku = $value;
                case 'itm_curr':
                    $this->itm_curr = $value;
                    break;
                case 'itm_img':
                    $this->itm_img = $value;
                    break;
                case 'itm_title':
                    $this->itm_title = $value;
                    break;
            }
        }


        echo '<p>';
        echo '<label for="' . '"prs_sbox"' . '>Is this a product review?</label>';
        echo '</p>';
        echo '<p>';
        echo '<select name=' . '"prs_sbox" id="prs_sbox" value="' . esc_attr($this->sbox_val) . '">';
        echo '<option> </option';
        echo '<option></option>';
        echo $this->sbox_val == 'Yes' ? '<option selected>Yes</option>' : '<option>Yes</option>';
        echo $this->sbox_val == 'No' ? '<option selected>No</option>' : '<option>No</option>';
        echo '</select>';
        echo '</p>';
        if ($this->sbox_val == 'Yes') {
            echo '<div id="prs_rv_mn" name="prs_rv_mn" class="prs_sbx_sh">';
        } else {
            echo '<div id="prs_rv_mn" name="prs_rv_mn" class="prs_sbx_hdn">';
        }
        echo '<div class="prs_rv_dcl">';
        echo '<div class="prs_rv_dvr">';
        echo '<div class="prs_rv_c33">';
        echo '<input id="prs_rv_bnm" name="prs_rv_bnm" type="text" placeholder="Enter the brand Name" value="' . esc_attr($this->brand_name) . '"></input>';
        echo "</div>";
        echo '<div class="prs_rv_c33">';
        echo '<input id="prs_rv_mpn" name="prs_rv_mpn" type="text" placeholder="Enter the MPN" value="' . esc_attr($this->mpn) . '"></input>';
        echo "</div>";
        echo '<div class="prs_rv_c33">';
        echo '<input id="prs_rv_sku" name="prs_rv_sku" type="text" placeholder="Enter the sku" value="' . esc_attr($this->prsku) . '"></input>';
        echo "</div>";
        echo '</div>';

        echo '<div class="prs_rv_dvr prs_rv_mtb1">';
        echo '<div class="prs_rv_c33">';
        echo '<input id="prs_rv_itr" name="prs_rv_itr" type="text" placeholder="Your rating out of 5" value="' . esc_attr($this->itm_rt) . '"></input>';
        echo '</div>';
        echo '<div class="prs_rv_c33">';
        echo '<select id="prs_rv_itc" name="prs_rv_itc" value="' . esc_attr($this->itm_cond) . '">';
        echo '<option>Item Condition</option>';
        echo '<option>New</option>';
        echo '<option>Old</option>';
        echo '</select>';
        echo '</div>';
        echo '<div class="prs_rv_c33">';
        echo '<select id="prs_rv_pra" name="prs_rv_pra" value="' . esc_attr($this->itm_av) . '">';
        echo '<option>Item availability</option>';
        echo '<option value="https://schema.org/InStock">InStock</option>';
        echo '<option value="https://schema.org/OutOfStock">Not In Stock</option>';
        echo '<option value="https://schema.org/Discontinued">Discontinued</option>';
        echo '<option value="https://schema.org/LimitedAvailability">Limited Availability</option>';
        echo '<option value="https://schema.org/PreOrder">Pre order</option>';
        echo '<option value="https://schema.org/InStoreOnly">In Store Only</option>';
        echo '<option value="https://schema.org/BackOrder">Backorder</option>';
        echo '<option value="https://schema.org/OnlineOnly">Online Only</option>';
        echo '<option value="https://schema.org/LimitedAvailability">Limited Availability</option>';

        echo '</select>';
        echo '</select>';
        echo '</div>';
        echo '</div>';

        echo '<div class="prs_rv_dvr">';
        echo '<div class="prs_rv_c33">';
        echo '<input id="prs_rv_prp" name="prs_rv_prp" type="text" placeholder="Item Price" value="' . esc_attr($this->itm_pr) . '"></input>';
        echo '</div>';

        echo '<div class="prs_rv_c33">';
        echo '<input id="prs_rv_vd" name="prs_rv_vd" type="date" placeholder="Price valid till" value="' . esc_attr($this->itm_vd) . '"></input>';
        echo '</div>';

        echo '<div class="prs_rv_c33">';
        echo '<input id="prs_rv_slr" name="prs_rv_slr" type="text" placeholder="Seller" value="' . esc_attr($this->itm_slr) . '"></input>';
        echo '</div>';
        echo '</div>';

        echo '<div class="prs_rv_dvr prs_rv_mtb1">';
        echo '<div class="prs_rv_c33">';
        echo '<input id="prs_rv_itmc" name="prs_rv_itmc" type="text" placeholder="Currency" value="' . esc_attr($this->itm_curr) . '"></input>';

        echo '</div>';
        echo '<div class="prs_rv_c33">';
        echo '<input id="prs_rv_itm_title" name="prs_rv_itm_title" type="text" placeholder="Title/Product name" value="' . esc_attr($this->itm_title) . '"></input>';

        echo '</div>';
        echo '</div>';

        echo '<div class="prs_rv_dvr">';
        echo '<div class="prs_rv_c50">';
        echo '<textarea class="prs_rv_tx_fw" id="prs_rv_desc" name="prs_rv_desc" rows="4" placeholder="Enter the review description">' . esc_attr($this->description) . '</textarea>';
        echo '</div>';
        echo '<div class="prs_rv_c50">';
        echo '<textarea class="prs_rv_tx_fw" id="prs_rv_img" name="prs_rv_img" rows="4" placeholder="Enter the Image URL/URLs">' . esc_attr($this->itm_img) . '</textarea>';
        echo '</div>';
        echo '</div>';


        echo '</div>';
    }

    function prsc_pst_sv()
    {
        $sprs_sbx = "";
        $spid = "";
        if (isset($_POST['prs_sbox'])) {
            $sprs_sbox = sanitize_text_field($_POST['prs_sbox']);
        }
        if (isset($_POST['post_ID'])) {
            $spid = sanitize_text_field($_POST['post_ID']);
        }
        if (!empty($sprs_sbox)) {
            if (get_post_meta($spid, 'pr_rv_status', true) !== null) {
                update_post_meta($spid, 'pr_rv_status', $sprs_sbox);
            }
        }
        if (!empty(get_post_meta($spid, 'pr_rv_status', true))) {
            $this->sbox_val = get_post_meta($spid, 'pr_rv_status', true);
        }
        if ($this->sbox_val == 'Yes') {
            $rvData = array(
                'description' => sanitize_text_field($_POST['prs_rv_desc']),
                'seller' => sanitize_text_field($_POST['prs_rv_slr']),
                'pr_valid' => sanitize_text_field($_POST['prs_rv_vd']),
                'itm_pr' => sanitize_text_field($_POST['prs_rv_prp']),
                'pr_ava' => sanitize_text_field($_POST['prs_rv_pra']),
                'itm_cond' => sanitize_text_field($_POST['prs_rv_itc']),
                'itm_rat' => sanitize_text_field($_POST['prs_rv_itr']),
                'itm_sku' => sanitize_text_field($_POST['prs_rv_sku']),
                'itm_mpn' => sanitize_text_field($_POST['prs_rv_mpn']),
                'itm_bnm' => sanitize_text_field($_POST['prs_rv_bnm']),
                'itm_curr' => sanitize_text_field($_POST['prs_rv_itmc']),
                'itm_img' => sanitize_text_field($_POST['prs_rv_img']),
                'itm_title' => sanitize_text_field($_POST['prs_rv_itm_title'])

            );
            update_post_meta($spid, 'pr_rv_sch_jsn', json_encode($rvData));
        }
    }
}
