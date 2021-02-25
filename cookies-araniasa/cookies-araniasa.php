<?php

/**
 * Plugin Name: CookiesAraniasa
 * Plugin URI:  https://www.enutt.net/
 * Description: Sistema de cookies para Araniasa.com
 * Version:     1.0
 * Author:      Enutt S.L.
 * Author URI:  https://www.enutt.net/
 * License:     GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cookies-araniasa
 *
 * PHP 7.3
 * WordPress 5.5.3
 */

function cookies_araniasa_plugins_loaded() {
    load_plugin_textdomain('cookies-araniasa', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}
add_action('plugins_loaded', 'cookies_araniasa_plugins_loaded', 0 );

// Metemos CSS y JS
function cookies_araniasa_register_scripts() {
	wp_register_script('jquery-cookies', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js', array('jquery'), '1', false);
	wp_enqueue_script('jquery-cookies');
}
add_action('wp_enqueue_scripts', 'cookies_araniasa_register_scripts');

add_action('wp_footer', 'cookies_araniasa_add_header', 5); 
function cookies_araniasa_add_header() { 
    global $post;
    ob_start();
    if(isset($_COOKIE['cookies-araniasa'])) { 
        $cookies = explode(",", $_COOKIE['cookies-araniasa']);
        if(in_array('allowall', $cookies) || in_array('allowanalytics', $cookies)) { 
            echo stripslashes(get_option("_cookies_araniasa_analiticas")); 
        }
        if(in_array('allowall', $cookies) || in_array('allowfunctionals', $cookies)) { 
            echo stripslashes(get_option("_cookies_araniasa_funcionales"));
        }
        if(in_array('allowall', $cookies) || in_array('allowmarketing', $cookies)) { 
            echo stripslashes(get_option("_cookies_araniasa_marketing"));
        }
    } 
    $html = ob_get_clean(); 
    echo  $html;
}


add_action('wp_footer', 'cookies_araniasa_add_footer', 5); 
function cookies_araniasa_add_footer() { 
    global $post;
    ob_start(); ?>
    <?php if(!isset($_COOKIE['cookies-araniasa'])) { ?>
        <div id="cookie-shadow"></div>
        <div id="cookie-advise">
            <p class="title"><?php _e("Aviso de Cookies", "cookies-araniasa"); ?></p>
            <p><?php _e("Este sitio web utiliza cookies propias y de terceros para ofrecerte una mejor experiencia de usuario y para fines analíticos o estadísticos sobre su utilización.", "cookies-araniasa"); ?></p>
            <p style="margin-bottom: 30px;"><?php _e("Puedes obtener más información en nuestra<br/><a href='/politica-de-cookies/'>politica de cookies</a>.", "cookies-araniasa"); ?></p>
            <button id="button-accept"><?php _e("Aceptar cookies", "cookies-araniasa"); ?></button>
            <button id="button-reject"><?php _e("Rechazar cookies", "cookies-araniasa"); ?></button>
            <button id="button-options"><?php _e("Opciones", "cookies-araniasa"); ?></button>
            <div id="options">
                <p><input type="checkbox" id="option-basicas" checked="checked" value="allowbasics" /> <?php _e("Básicas", "cookies-araniasa"); ?></p>
                <?php if(get_option("_cookies_araniasa_analiticas") != '') { ?><p><input type="checkbox" id="option-analiticas" checked="checked" value="allowanalytics" /> <?php _e("Analíticas", "cookies-araniasa"); ?></p><?php } ?>
                <?php if(get_option("_cookies_araniasa_funcionales") != '') { ?><p><input type="checkbox" id="option-funcionales" checked="checked" value="allowfunctionals" /> <?php _e("Funcionales", "cookies-araniasa"); ?></p><?php } ?>
                <?php if(get_option("_cookies_araniasa_marketing") != '') { ?><p><input type="checkbox" id="option-marketing" checked="checked" value="allowmarketing" /> <?php _e("Marketing", "cookies-araniasa"); ?></p><?php } ?>
                <button id="button-accept-options"><?php _e("Acepto", "cookies-araniasa"); ?></button>
            </div>
        </div>
        <style>
            #cookie-shadow {
                position: fixed;
                top: 0px;
                left: 0px;
                height: 100vh;
                width: 100%;
                background-color: #299fe36e;
                z-index: 500;
            }
            #cookie-advise {
                position: fixed;
                top: 25vh;
                left: calc(50% - 201px);
                max-width: 320px;
                background-color: #299fe3;
                /*border: 1px solid #fff;*/
                z-index: 510;
                padding: 65px;
                text-align: center;
            }

            #cookie-advise p,
            #cookie-advise button {
                font-size: 12px;
                line-height: 120%;
                color: #fff;
                outline: none !important;
            }

            #cookie-advise button:hover,
            #cookie-advise button.checked {
                background-color: #fff;
                color: #299fe3; 
            }

            #cookie-advise p.title {
                font-weight: 700;
                font-size: 15px;
            }

            #cookie-advise p a {
                color: #fff;
                text-decoration: underline;
            }
            
            #cookie-advise button {
                display: block;
                border: 1px solid #fff;
                background-color: #299fe3;
                padding: 5px;
                margin: 10px auto 0px;
                min-width: 150px;
            }

            #cookie-advise #options {
                display: none;
            }
            #cookie-advise #options p {
                text-align: left;
                margin: 10px auto 0px;
                max-width: 120px;
            }
        </style>
        <script>
            jQuery("#button-options").click(function(e) {
                e.preventDefault();
                jQuery(this).toggleClass("checked");
                jQuery("#options").fadeToggle();
            });

            jQuery("#button-reject").click(function(e) {
                e.preventDefault();
                jQuery("#cookie-shadow,#cookie-advise").fadeOut();
                jQuery.cookie("cookies-araniasa", "rejectall", { expires : 365, path: '/' });
            });

            jQuery("#option-basicas").click(function(e) {
                e.preventDefault();
            });

            jQuery("#button-accept-options").click(function(e) {
                e.preventDefault();
                var string = new Array();
                string.push("allowbasics");
                if (jQuery("#option-analiticas").prop('checked')) {
                    string.push("allowanalytics");
                }
                if (jQuery("#option-funcionales").prop('checked')) {
                    string.push("allowfunctionals");
                }
                if (jQuery("#option-marketing").prop('checked')) {
                    string.push("allowmarketing");
                }
                if(string.length > 0) {
                    jQuery.cookie("cookies-araniasa", string.join(","), { expires : 365, path: '/' });
                    jQuery("#cookie-shadow,#cookie-advise").fadeOut();
                }
            });

            jQuery("#button-accept").click(function(e) {
                e.preventDefault();
                jQuery("#cookie-shadow,#cookie-advise").fadeOut();
                jQuery.cookie("cookies-araniasa", "allowall", { expires : 365, path: '/' });
            });
        </script>
    <?php } ?>
    <?php $html = ob_get_clean(); 
    echo  $html;
}

//SHORTCODE ------------------------------------
add_shortcode('cookies-araniasa', 'cookies_araniasa_shortcode');
function cookies_araniasa_shortcode() { 
    global $post;
    $cookies = explode(",", $_COOKIE['cookies-araniasa']);
    ob_start(); ?>
    <div id="sc-options">
        <p><b><?php _e("Opciones", "cookies-araniasa"); ?></b></p>
        <p><input type="checkbox" id="sc-option-basicas" checked="checked" value="allowbasics" /> <?php _e("Básicas", "cookies-araniasa"); ?></p>
        <?php if(get_option("_cookies_araniasa_analiticas") != '') { ?><p><input type="checkbox" id="sc-option-analiticas" <?php if(in_array('allowall', $cookies) || in_array('allowanalytics', $cookies)) { ?> checked="checked"<?php } ?>value="allowanalytics" /> <?php _e("Analíticas", "cookies-araniasa"); ?></p><?php } ?>
        <?php if(get_option("_cookies_araniasa_funcionales") != '') { ?><p><input type="checkbox" id="sc-option-funcionales" <?php if(in_array('allowall', $cookies) || in_array('allowanalytics', $cookies)) { ?> checked="checked"<?php } ?> value="allowfunctionals" /> <?php _e("Funcionales", "cookies-araniasa"); ?></p><?php } ?>
        <?php if(get_option("_cookies_araniasa_marketing") != '') { ?><p><input type="checkbox" id="sc-option-marketing" <?php if(in_array('allowall', $cookies) || in_array('allowmarketing', $cookies)) { ?> checked="checked"<?php } ?> value="allowmarketing" /> <?php _e("Marketing", "cookies-araniasa"); ?></p><?php } ?>
        <button id="sc-button-accept-options"><?php _e("Acepto", "cookies-araniasa"); ?></button>
    </div>
    <script>
        jQuery("#sc-option-basicas").click(function(e) {
            e.preventDefault();
        });
        jQuery("#sc-button-accept-options").click(function(e) {
            e.preventDefault();
            var string = new Array();
            string.push("allowbasics");
            if (jQuery("#sc-option-analiticas").prop('checked')) {
                string.push("allowanalytics");
            }
            if (jQuery("#sc-option-funcionales").prop('checked')) {
                string.push("allowfunctionals");
            }
            if (jQuery("#sc-option-marketing").prop('checked')) {
                string.push("allowmarketing");
            }
            if(string.length > 0) {
                jQuery.cookie("cookies-araniasa", "", { expires : 365, path: '/' });
                jQuery.cookie("cookies-araniasa", string.join(","), { expires : 365, path: '/' });
            }
        });
    </script>
    <?php $html = ob_get_clean(); 
    return  $html;
}

//ADMIN -----------------------------------------

add_action('admin_menu', 'cookies_araniasa_plugin_menu');
function cookies_araniasa_plugin_menu() {
	add_options_page(__('Cookies', "cookies-araniasa"), __('Cookies', "cookies-araniasa"), 'manage_options', "cookies-araniasa", 'cookies_araniasa_page_settings');
}

function cookies_araniasa_page_settings() { 
	if(isset($_REQUEST['send']) && $_REQUEST['send'] != '') { 
		update_option('_cookies_araniasa_analiticas', $_POST['_cookies_araniasa_analiticas']);
        update_option('_cookies_araniasa_funcionales', $_POST['_cookies_araniasa_funcionales']);
        update_option('_cookies_araniasa_marketing', $_POST['_cookies_araniasa_marketing']);
		?><p style="border: 1px solid green; color: green; text-align: center;"><?php _e("Datos guardados correctamente.", "cookies-araniasa"); ?></p><?php
	} ?>
	<form method="post">
		<h1><?php _e("Cookies", "cookies-araniasa"); ?></h1>
        <h2><?php _e("Cookies analítica", "cookies-araniasa"); ?></h2>
		<textarea rows="10" style="width: 100%;" name="_cookies_araniasa_analiticas"><?php echo stripslashes(get_option("_cookies_araniasa_analiticas")); ?></textarea>
        <h2><?php _e("Cookies funcionales", "cookies-araniasa"); ?></h2>
		<textarea rows="10" style="width: 100%;" name="_cookies_araniasa_funcionales"><?php echo stripslashes(get_option("_cookies_araniasa_funcionales")); ?></textarea>
        <h2><?php _e("Cookies marketing", "cookies-araniasa"); ?></h2>
		<textarea rows="10" style="width: 100%;" name="_cookies_araniasa_marketing"><?php echo stripslashes(get_option("_cookies_araniasa_marketing")); ?></textarea>
    	<br/><br/><input type="submit" name="send" class="button button-primary" value="<?php _e("Guardar"); ?>" />
	</form>
	<?php
}
