<?php
/*
  Plugin Name: Getlocal - Search Form
  Description:
  Version: 1
  Author: Getlocal ehf
 */

function importer_system_scripts() {

    wp_enqueue_style('style-getlcoal', plugins_url('getlocalsearchform/css/style.css'), true);
    
    wp_enqueue_script('merge-script', plugins_url('getlocalsearchform/js/merge-script.js'), array(), '20151215', true);
    wp_enqueue_script('get-local-common', plugins_url('getlocalsearchform/js/common.js'), array(), '20151215', true);
}

add_action('wp_enqueue_scripts', 'importer_system_scripts');

/**
 * Register widget area.
 *
 * @since Twenty Fifteen 1.0
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function getlocal_widgets_init() {
    register_sidebar(array(
        'name' => __('Get Local Search Form', 'getlocal-search-form'),
        'id' => 'get-local-search',
        'description' => __('Add widgets here to appear in your sidebar.', 'getlocal'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'getlocal_widgets_init');

function wpb_load_widget() {
    register_widget('wpb_widget');
}

add_action('widgets_init', 'wpb_load_widget');

class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
                // Base ID of your widget
                'wpb_widget',
                // Widget name will appear in UI
                __('Getlocal Search Form', 'wpb_widget_domain'),
                // Widget description
                array('description' => __('Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain'),)
        );

        add_shortcode('getlocalsearchform', array($this, 'getLocalSearchShortCode'));
    }

// Creating widget front-end
    public function widget($args, $instance) {
        $url = apply_filters('widget_title', $instance['url']);
        $affiliateid = apply_filters('widget_title', $instance['affiliateid']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($url))
        // echo $args['before_title'] . $url . $args['after_title'];
        // This is where you run the code and display the output
            
            ?>

        <div class="search-panel">
            <div class="sc-cols"><label>What would you like to do?</label>
                <div class="dropdown">
                    <input class="dropdown-toggle" value="Open for anything..." data-toggle="dropdown" readonly maxlength="5" type="text">
                    <i class="date-clear"></i>
                    <div class="dropdown-menu">
                        <div class="searchlist">
                            <p>Essentials</p>
                            <ul>
                                <li>Glacier Tours</li>
                                <li>Golden Circle Tours</li>
                                <li>Ice Cave Tours</li>
                                <li>South Coast Tours</li>
                                <li>Snæfellsnes Tours</li>
                                <li>Whale &amp; Puffin Tours</li>
                            </ul>
                        </div>
                        <div class="searchlist">
                            <p>Nature</p>
                            <ul>
                                <li>Fishing Tours</li>
                                <li>Hiking</li>
                                <li>Horseback Riding</li>
                                <li>Hot Springs</li>
                                <li>Lava Cave Tours</li>
                                <li>Northern Lights</li>                                                                                                 
                            </ul>
                        </div>
                        <div class="searchlist">
                            <p>Action</p>
                            <ul>
                                <li>Flightseeing</li>
                                <li>City Sightseeing</li>
                                <li>Food &amp; Drink</li>
                                <li>Helicopter Tours</li>
                                <li>Photography Tours</li>
                                <li>Spa &amp; Wellness</li>                                                                                               
                            </ul>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="sc-cols">
                <label>When are you coming?</label>
                <input type="hidden" value="s" id="selected-calender-option">
                <div id="dates" class="date-select">																			
                    <i class="date-clear"></i>
                    <span></span>
                </div>

            </div>
            <div class="sc-cols-last">
                <label>How many are you?</label>
                <div class="participant-list">
                    <div class="input-group number-spinner"> 
                        <div class="people-field">
                            <input type="number" class="form-control text-center" value="1" min="0" readonly max="40" id="partAdult">
                            <span>Person</span>
                        </div>

                        <span class="input-group-btn data-dwn">
                            <button class="btn btn-default btn-info" data-dir="dwn"><span class="minus"></span></button>
                        </span>

                        <span class="input-group-btn data-up">
                            <button class="btn btn-default btn-info" data-dir="up"><span class="plus"></span></button>
                        </span>
                    </div>
                </div>
            </div>
            <input type="hidden" id="redirect_url" value="<?php echo $url; ?>">
            <input type="hidden" id="affiliateid" value="<?php echo $affiliateid; ?>">
            <div class="search-button">
                <button type="button" id="getlocal-search">Search</button>
            </div>

        </div>

        <?php
        echo $args['after_widget'];
    }

    // Creating widget front-end

    public function getLocalSearchShortCode() {
        $url = get_option('get_local_url');
        $affiliateid = get_option('affiliateid');
        if (!empty($url))
            
            ?>

        <div class="search-panel">
            <div class="sc-cols"><label>What would you like to do?</label>
                <div class="dropdown">
                    <input class="dropdown-toggle" value="Open for anything..." data-toggle="dropdown" readonly maxlength="5" type="text">
                    <i class="date-clear"></i>
                    <div class="dropdown-menu">
                        <div class="searchlist">
                            <p>Essentials</p>
                            <ul>
                                <li>Glacier Tours</li>
                                <li>Golden Circle Tours</li>
                                <li>Ice Cave Tours</li>
                                <li>South Coast Tours</li>
                                <li>Snæfellsnes Tours</li>
                                <li>Whale &amp; Puffin Tours</li>
                            </ul>
                        </div>
                        <div class="searchlist">
                            <p>Nature</p>
                            <ul>
                                <li>Fishing Tours</li>
                                <li>Hiking</li>
                                <li>Horseback Riding</li>
                                <li>Hot Springs</li>
                                <li>Lava Cave Tours</li>
                                <li>Northern Lights</li>                                                                                                 
                            </ul>
                        </div>
                        <div class="searchlist">
                            <p>Action</p>
                            <ul>
                                <li>Flightseeing</li>
                                <li>City Sightseeing</li>
                                <li>Food &amp; Drink</li>
                                <li>Helicopter Tours</li>
                                <li>Photography Tours</li>
                                <li>Spa &amp; Wellness</li>                                                                                               
                            </ul>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="sc-cols">
                <label>When are you coming?</label>
                <input type="hidden" value="s" id="selected-calender-option">
                <div id="dates" class="date-select">																			
                    <i class="date-clear"></i>
                    <span></span>
                </div>

            </div>
            <div class="sc-cols-last">
                <label>How many are you?</label>
                <div class="participant-list">
                    <div class="input-group number-spinner"> 
                        <div class="people-field">
                            <input type="number" class="form-control text-center" value="1" min="0" readonly max="40" id="partAdult">
                            <span>Person</span>
                        </div>

                        <span class="input-group-btn data-dwn">
                            <button class="btn btn-default btn-info" data-dir="dwn"><span class="minus"></span></button>
                        </span>

                        <span class="input-group-btn data-up">
                            <button class="btn btn-default btn-info" data-dir="up"><span class="plus"></span></button>
                        </span>
                    </div>
                </div>
            </div>
            <input type="hidden" id="redirect_url" value="<?php echo $url; ?>">
            <input type="hidden" id="affiliateid" value="<?php echo $affiliateid; ?>">
            <div class="search-button">
                <button type="button" id="getlocal-search">Search</button>
            </div>

        </div>

        <?php
    }

// Widget Backend 
    public function form($instance) {
        if (isset($instance['url'])) {
            $url = $instance['url'];
        } else {
            $url = __('Add Url', 'wpb_widget_domain');
        }

        if (isset($instance['affiliateid'])) {
            $affiliateid = $instance['affiliateid'];
        } else {
            $affiliateid = __('Add Affiliate ', 'wpb_widget_domain');
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Url:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($url); ?>" />

            <label for="<?php echo $this->get_field_id('affiliateid'); ?>"><?php _e('Affiliate Id:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('affiliateid'); ?>" name="<?php echo $this->get_field_name('affiliateid'); ?>" type="text" value="<?php echo esc_attr($affiliateid); ?>" />
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['url'] = (!empty($new_instance['url']) ) ? strip_tags($new_instance['url']) : '';
        $instance['affiliateid'] = (!empty($new_instance['affiliateid']) ) ? strip_tags($new_instance['affiliateid']) : '';
        return $instance;
    }

}

add_action('admin_menu', 'getlocaloptionmenu');

function getlocaloptionmenu() {

    add_menu_page('Get Local Serach Form Option ', //page title
            'Get Local Serach Form Option', //menu title
            'manage_options', //capabilities
            'get_local_serach_form_option', //menu slug
            'get_local_serach_form_option' //function
    );
}

function get_Local_serach_form_option() {


    if (isset($_POST['url'])) {
        $url = $_POST['url'];
        $affiliateid = $_POST['affiliateid'];
        // echo $affiliateid;
        update_option('get_local_url', $url, '', 'yes');
        update_option('affiliateid', $affiliateid, '', 'yes');
    }
    ?>

    <div class="container">

        <div class="row">
            <div class="col-sm-06">
                <form method="post">
                    <h2>Enter Url</h2>
                    <input type="text" class="" name="url" id=""  value=" <?php echo get_option('get_local_url') ?>" style="width: 100%;" required=""> <br>

                    <br>
                    <h2>Enter Affiliate Id</h2>
                    <input type="text" class="" name="affiliateid" id="" value=" <?php echo get_option('affiliateid') ?>" style="width: 100%;" required=""> <br>

                    <input type='submit' name='save' class='save_badge' value='Save'>

                </form>


            </div>
        </div>
    </div>
    <?php
}

// Class wpb_widget ends here
