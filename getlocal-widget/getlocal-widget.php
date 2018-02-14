<?php
/**
 * Plugin Name: Getlocal Widget
 * Description: This plugin is for add getlocal widget.
 * Version: 1.0.0
 */
register_activation_hook(__FILE__, 'getlocal_widget_install');
function getlocal_widget_install() {
//    global $wpdb;
    $data['site_url'] = get_site_url();
    $data['site_name'] = get_bloginfo();
    $getlocalUrl = 'http://www.getlocal.is:8002';
    $url = $getlocalUrl."/widget/add-widget-affiliate";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $widget = curl_exec($ch);
    $widgetDetail = json_decode($widget,1);
    curl_close($ch);
    if($widgetDetail['widgetAffiliate']) {
        add_option('getlocal_widget_affiliate_id', $widgetDetail['widgetAffiliate']);
        add_option('getlocal_widget_url', $getlocalUrl);
    }else{
        trigger_error('There is something wrong to activate this plugin. Please try after sometime.', E_USER_ERROR);
    }
}
register_deactivation_hook(__FILE__, 'getlocal_widget_uninstall');
function getlocal_widget_uninstall(){

    $data['widgetAffiliateId'] = get_option( 'getlocal_widget_affiliate_id');
    $getlocalUrl = get_option( 'getlocal_widget_url');
    $url = $getlocalUrl."/widget/delete-widget-affiliate";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $widget = curl_exec($ch);
    $widgetDetail = json_decode($widget,1);
    curl_close($ch);
    delete_option('getlocal_widget_affiliate_id');
    delete_option('getlocal_widget_url');
    delete_option('getlocal_widget_options');
}
function getlocal_widget_settings_init() {
    // register a new setting for page
    register_setting( 'getlocal_widget', 'getlocal_widget_options' );

    add_settings_section(
        'getlocal_widget_section_developers',
        'Set widget global setting.',
        'getlocal_widget_section_developers_cb',
        'getlocal_widget'
    );

    add_settings_field(
        'getlocal_widget_field_tour_detail_page',
        'Tour Detail page',
        'getlocal_widget_field_render_cb',
        'getlocal_widget',
        'getlocal_widget_section_developers',
        [
            'label_for' => 'getlocal_widget_field_tour_detail',
            'class' => 'getlocal_widget_row',
            'getlocal_widget_custom_data' => 'custom',
        ]
    );
    add_settings_field(
        'getlocal_widget_field_vendor_page',
        'Vendor Detail page',
        'getlocal_widget_field_render_cb',
        'getlocal_widget',
        'getlocal_widget_section_developers',
        [
            'label_for' => 'getlocal_widget_field_vendor_page',
            'class' => 'getlocal_widget_row',
            'getlocal_widget_custom_data' => 'custom',
        ]
    );
    add_settings_field(
        'getlocal_widget_field_affiliate_code',
        'Affiliate code',
        'getlocal_widget_field_render_cb',
        'getlocal_widget',
        'getlocal_widget_section_developers',
        [
            'label_for' => 'getlocal_widget_field_affiliate_code',
            'class' => 'getlocal_widget_row',
            'getlocal_widget_custom_data' => 'custom',
        ]
    );
    add_settings_field(
        'getlocal_widget_field_tour_detail_widget',
        'Tour detail page widget code',
        'getlocal_widget_field_render_cb',
        'getlocal_widget',
        'getlocal_widget_section_developers',
        [
            'label_for' => 'getlocal_widget_field_tour_detail_widget',
            'class' => 'getlocal_widget_row',
            'getlocal_widget_custom_data' => 'custom',
        ]
    );
    add_settings_field(
        'getlocal_widget_field_vendor_widget',
        'Vendor tour list widget code',
        'getlocal_widget_field_render_cb',
        'getlocal_widget',
        'getlocal_widget_section_developers',
        [
            'label_for' => 'getlocal_widget_field_vendor_widget',
            'class' => 'getlocal_widget_row',
            'getlocal_widget_custom_data' => 'custom',
        ]
    );
}

/**
 * register our settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'getlocal_widget_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function getlocal_widget_section_developers_cb( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Set widget parameters.', 'getlocal_widget' ); ?></p>
    <?php
}

// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function getlocal_widget_field_render_cb( $args ) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option( 'getlocal_widget_options' );
    // output the field
    if($args['label_for'] == 'getlocal_widget_field_vendor_widget' || $args['label_for'] == 'getlocal_widget_field_tour_detail_widget'){
        $widgetAffiliateId = get_option( 'getlocal_widget_affiliate_id');

        if($args['label_for'] == 'getlocal_widget_field_vendor_widget'){
            $widgetType = 'vendor';
        }else{
            $widgetType = 'tour_detail';
        }
        $data = ['affiliate_id'=>$widgetAffiliateId,'type'=>$widgetType];
        $field_string = http_build_query($data);
        $getlocalUrl = get_option( 'getlocal_widget_url');
        $url = $getlocalUrl."/widget/get-widget-detail";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
        $widgetDetail = curl_exec($ch);
        $widgetScript = json_decode($widgetDetail,1);
        curl_close($ch);
    ?>
    <textarea rows="10" cols="150" readonly><?php echo $widgetScript['widgetScript']; ?></textarea>
    <?php
    }else {
        ?>
        <input type="text" id="<?php echo esc_attr($args['label_for']); ?>"
               data-custom="<?php echo esc_attr($args['getlocal_widget_custom_data']); ?>"
               name="getlocal_widget_options[<?php echo esc_attr($args['label_for']); ?>]"
               required value="<?php echo $options[$args['label_for']] ?>"
            >
        <?php
    }
}

/**
 * top level menu
 */
function getlocal_widget_options_page() {
    // add top level menu page
    add_menu_page(
        'Getlocal widget Options',
        'Getlocal Widget Options',
        'manage_options',
        'getlocal_widget',
        'getlocal_widget_options_page_html'
    );
}

/**
 * register our getlocal_widget_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'getlocal_widget_options_page' );

/**
 * top level menu:
 * callback functions
 */
function getlocal_widget_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {

        $widgetAffiliateId = get_option( 'getlocal_widget_affiliate_id');

        $options = get_option( 'getlocal_widget_options' );
        $data = [
            ['affiliate_id'=>$widgetAffiliateId,'type'=>'tour_detail','detail_page_url'=>$options['getlocal_widget_field_tour_detail']],
            ['affiliate_id'=>$widgetAffiliateId,'type'=>'vendor','detail_page_url'=>$options['getlocal_widget_field_vendor_page']]
        ];
        $field_string = http_build_query($data);
        $getlocalUrl = get_option( 'getlocal_widget_url');
        $url = $getlocalUrl."/widget/add-widgets";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
        $widget = curl_exec($ch);
        curl_close($ch);

        // add settings saved message with the class of "updated"
        add_settings_error( 'getlocal_widget_messages', 'getlocal_widget_message', __( 'Settings Saved', 'getlocal_widget' ), 'updated' );
    }

    // show error/update messages
    settings_errors( 'getlocal_widget_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "getlocal_widget"
            settings_fields( 'getlocal_widget' );
            // output setting sections and their fields
            // (sections are registered for "getlocal_widget", each field is registered to a specific section)
            do_settings_sections( 'getlocal_widget' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}
add_action( 'add_meta_boxes', 'add_custom_page_attributes_meta_box' );
function add_custom_page_attributes_meta_box(){
    global $post;
    //if ( 'page' != $post->post_type && post_type_supports($post->post_type, 'page-attributes') ) {
        add_meta_box( 'getlocalWidget', 'Select vendor and tour', 'getlocal_attributes_meta_box', NULL, 'normal', 'core');
    //}
}

function getlocal_attributes_meta_box($post) {
    $getlocalUrl = get_option( 'getlocal_widget_url');
    $url = $getlocalUrl."/widget/get-vendor-list";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $vendors = curl_exec($ch);
    $vendorList = json_decode($vendors,1);
    curl_close($ch);

    $url = $getlocalUrl."/widget/get-tour-list";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tours = curl_exec($ch);
    $tourList = json_decode($tours,1);

    curl_close($ch);
    $vendorId = get_post_meta($post->ID,'_getlocal_vendor_id',1);
    $tourId = get_post_meta($post->ID,'_getlocal_tour_id',1);
    $vendorName = get_post_meta($post->ID,'_getlocal_vendor_name',1);
    ?>
    <select name="vendor_id" id="vendor_id">
    <option value="">Select operator</option>
    <?php foreach($vendorList as $vendor){ ?>
        <option value="<?php echo $vendor['actualVendorId'] ?>" <?php echo $vendor['actualVendorId'] == $vendorId ? "selected='selected'":"" ?>><?php echo $vendor['actualVendor'] ?></option>
    <?php } ?>
    </select>
    <input type="hidden" name="vendor_name" id="vendor_name" value="<?php echo $vendorName; ?>">
    <select name="tour_id" id="tour_id">
    <option value="" data-vendor="0">Select tour</option>
    <?php foreach($tourList as $tour){
        $optionVisibility = "";
        if($vendorId && $vendorId != $tour['actualVendorId']){
            $optionVisibility="display:none";
        }
    ?>
        <option style="<?php echo $optionVisibility ?>" value="<?php echo $tour['boxedId'] ?>" data-vendor="<?php echo $tour['actualVendorId'] ?>"  <?php echo $tour['boxedId'] == $tourId ? "selected='selected'":"" ?>><?php echo $tour['title'] ?></option>
    <?php } ?>
    </select><?php
}

add_action( 'save_post', 'save_getlocal_attributes_meta_box' );
function save_getlocal_attributes_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
        update_post_meta( $post_id, '_getlocal_vendor_id', $_POST['vendor_id'] );
        update_post_meta( $post_id, '_getlocal_tour_id', $_POST['tour_id'] );
        update_post_meta( $post_id, '_getlocal_vendor_name', $_POST['vendor_name'] );
}
function render_post_content_with_widget( $content ) {

    global $post;
    $vendorId = get_post_meta($post->ID,'_getlocal_vendor_id',1);
    $tourId = get_post_meta($post->ID,'_getlocal_tour_id',1);
    $vendorName = get_post_meta($post->ID,'_getlocal_vendor_name',1);
    $options = get_option( 'getlocal_widget_options' );
    $content .= '<div id="getlocalWidget" class="widget-actions">';
    if($tourId) {
        $url = $options['getlocal_widget_field_tour_detail'] . '?tid=' . $tourId;
        if(isset($options['getlocal_widget_field_affiliate_code']) && !empty($options['getlocal_widget_field_affiliate_code'])){
            $url = $options['getlocal_widget_field_tour_detail'] . '?tid=' . $tourId .'&aid='.$options['getlocal_widget_field_affiliate_code'];
        }
        $content .= '<div class="tour-booking"><a href="' . $url . '">Book Now</a></div>';
    }
    if($tourId) {
        $url = $options['getlocal_widget_field_vendor_page'] . '?vendor=' . $vendorId;
        if(isset($options['getlocal_widget_field_affiliate_code']) && !empty($options['getlocal_widget_field_affiliate_code'])){
            $url = $options['getlocal_widget_field_vendor_page'] . '?vendorid=' . $vendorId .'&aid='.$options['getlocal_widget_field_affiliate_code'];
        }

        $content .= '<div class="vendor-list"><a href="' . $url . '">See all tours from '.$vendorName.'</a></div>';
    }
    $content .= '</div>';
    return $content;
    // modify post object here
    //print_R($post_object);

}
add_filter( 'the_content', 'render_post_content_with_widget' );

wp_register_script('custom_script', plugins_url('js/widget.js', __FILE__), array(), '1.0', TRUE);
wp_enqueue_script('custom_script');
wp_localize_script('custom_script', 'customScript', array());