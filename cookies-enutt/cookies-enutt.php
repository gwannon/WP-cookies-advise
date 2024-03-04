<?php

/**
 * Plugin Name: CookiesEñutt
 * Plugin URI:  https://www.enutt.net/
 * Description: Sistema de cookies para enutt.net
 * Version:     1.6.1
 * Author:      Enutt S.L.
 * Author URI:  https://www.enutt.net/
 * License:     GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: cookies-enutt
 *
 * PHP 8.2
 * WordPress 6.4.3
 */

function cookies_enutt_plugins_loaded() {
  load_plugin_textdomain('cookies-enutt', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}
add_action('plugins_loaded', 'cookies_enutt_plugins_loaded', 0 );

// Metemos CSS y JS
function cookies_enutt_register_scripts() {
  wp_register_script('jquery-cookies', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js', array('jquery'), '1', false);
  wp_enqueue_script('jquery-cookies');
}
add_action('wp_enqueue_scripts', 'cookies_enutt_register_scripts');

add_action('wp_head', 'cookies_enutt_add_header', 100); 
function cookies_enutt_add_header() { 
  ob_start(); ?>
  <script>
    // Define dataLayer and the gtag function.
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}

    if (typeof jQuery.cookie('cookies-enutt') != 'undefined') {
      var stringcookie = jQuery.cookie('cookies-enutt');
      //Analíticas
      var analytics_storage = (stringcookie.includes('allowall') || stringcookie.includes('allowanalytics') ? 'granted' : 'denied');
      //Marketing
      var ad_storage = (stringcookie.includes('allowall') || stringcookie.includes('allowmarketing') ? 'granted' : 'denied');
      var personalization_storage = (stringcookie.includes('allowall') || stringcookie.includes('allowmarketing') ? 'granted' : 'denied');
      var ad_user_data = (stringcookie.includes('allowall') || stringcookie.includes('allowmarketing') ? 'granted' : 'denied');
      //Preferencias
      var ad_personalization = (stringcookie.includes('allowall') || stringcookie.includes('allowfunctionals') ? 'granted' : 'denied');
      //Necesarias
      var security_storage = 'granted';
      gtag('consent', 'update', {
        'analytics_storage': analytics_storage,
        'ad_storage': ad_storage,
        'personalization_storage': personalization_storage,
        'ad_user_data': ad_user_data,
        'ad_personalization': ad_personalization,
        'security_storage': security_storage
      });
    } else {
      // Default consent
      gtag('consent', 'default', {
        'analytics_storage': 'denied',
        'ad_storage': 'denied',
        'personalization_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'security_storage': 'granted'
      });
    }

    <?php if(isset($_COOKIE['cookies-enutt'])) { 
      $cookies = explode(",", $_COOKIE['cookies-enutt']);
      if(in_array('allowall', $cookies) || in_array('allowanalytics', $cookies)) { 
        echo stripslashes(get_option("_cookies_enutt_analiticas")); 
      }
      if(in_array('allowall', $cookies) || in_array('allowfunctionals', $cookies)) { 
        echo stripslashes(get_option("_cookies_enutt_funcionales"));
      }
      if(in_array('allowall', $cookies) || in_array('allowmarketing', $cookies)) { 
        echo stripslashes(get_option("_cookies_enutt_marketing"));
      } 
    } ?>
  </script>
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','<?php echo get_option("_cookies_enutt_gtm_code"); ?>');</script>
  <!-- End Google Tag Manager -->
  <?php echo ob_get_clean(); 
}

add_action('wp_footer', 'cookies_enutt_add_footer', 5); 
function cookies_enutt_add_footer() { 
  ob_start(); ?>
  <div id="cookie-advise">
    <div>
      <p class="title"><?php _e("En Eñutt Agency ¡utilizamos cookies!", "cookies-enutt"); ?></p>
      <p><?php _e("Lo hacemos para seguir mejorando en nuestros servicios porque las cookies nos ayudan a analizar el tráfico de nuestra web, qué páginas interesan más, cuánto tiempo estás en cada sección…y poder mostrarte la publicidad que realmente te interesa.", "cookies-enutt"); ?></p>
      <p><?php printf(__("Pero, tú decides cuáles quieres aceptar y cuáles no. Porque para nosotros tus decisiones importan. Puedes aceptarlas todas, solo algunas o directamente no aceptarlas. Pincha <a href='%s' rel='nofollow'>aquí</a> para saber más.", "cookies-enutt"), get_option("_cookies_enutt_politica_cookies")); ?></p>
    </div>
    <div>
      <button id="button-accept"><?php _e("Aceptar cookies", "cookies-enutt"); ?></button>
      <button id="button-reject"><?php _e("Rechazar cookies", "cookies-enutt"); ?></button>
      <button id="button-options"><?php _e("Opciones", "cookies-enutt"); ?></button>
      <div id="options">
        <p><input type="checkbox" id="option-basicas" checked="checked" value="allowbasics" /> <?php _e("Básicas", "cookies-enutt"); ?></p>
        <?php if(get_option("_cookies_enutt_analiticas_show") == 1) { ?><p><input type="checkbox" id="option-analiticas" checked="checked" value="allowanalytics" /> <?php _e("Analíticas", "cookies-enutt"); ?></p><?php } ?>
        <?php if(get_option("_cookies_enutt_funcionales_show") == 1) { ?><p><input type="checkbox" id="option-funcionales" checked="checked" value="allowfunctionals" /> <?php _e("Preferencias", "cookies-enutt"); ?></p><?php } ?>
        <?php if(get_option("_cookies_enutt_marketing_show") == 1) { ?><p><input type="checkbox" id="option-marketing" checked="checked" value="allowmarketing" /> <?php _e("Marketing", "cookies-enutt"); ?></p><?php } ?>
        <button id="button-accept-options"><?php _e("Acepto", "cookies-enutt"); ?></button>
      </div>
    </div>
  </div>
  <?php
    $bg_color = get_option("_cookies_enutt_bg_color");
    $text_color = get_option("_cookies_enutt_text_color");
  ?>
  <style>
    #cookie-advise {
      position: fixed;
      bottom: 0px;
      left: 0px;
      width: 100%;
      background-color: <?=$bg_color;?>;
      z-index: 510;
      padding: 15px 28px;
      text-align: center;
      box-sizing: border-box;
      display: none;
    }

    #cookie-advise.show {
      display: block;
    }

    #cookie-advise p,
    #cookie-advise button {
      font-size: 14px;
      line-height: 120%;
      color: <?=$text_color;?>;
      outline: none !important;
    }
    
    #cookie-advise > div {
      padding: 5px;
      max-width: 1000px;
    }
    
    #cookie-advise > div > p:last-child {
      margin-bottom: 5px;
    }

    #cookie-advise button:hover,
    #cookie-advise button.checked {
      background-color: <?=$text_color;?>;
      color: <?=$bg_color;?>;
    }

    #cookie-advise p.title {
      font-weight: 700;
      font-size: 16px;
    }

    #cookie-advise p a {
      color: <?=$text_color;?>;
      text-decoration: underline;
    }
    
    #cookie-advise button {
      display: inline-block;
      border: 1px solid <?=$text_color;?>;
      background-color: <?=$bg_color;?>;
      padding: 5px;
      margin: 10px auto 0px;
    }

    #cookie-advise #options {
      display: none;
    }
    #cookie-advise #options p {
      text-align: left;
      margin: 10px auto 0px;
      max-width: 120px;
    }

    @media (min-width: 800px) {
      #cookie-advise {				
        justify-content: space-around;
        align-items: center;
      }

      #cookie-advise.show {
        display: flex;
      }

      #cookie-advise > div > p:last-child {
        margin-bottom: 0px;
      }

      #cookie-advise button {
        display: block;
        min-width: 150px;
      }
    }
  </style>
  <script>
    jQuery(document).ready(function() {
      if (typeof jQuery.cookie('cookies-enutt') === 'undefined') {
        jQuery("#cookie-advise").addClass("show");
      }
    });

    jQuery("#button-options").click(function(e) {
      e.preventDefault();
      jQuery(this).toggleClass("checked");
      jQuery("#options").fadeToggle();
    });

    jQuery("#button-reject").click(function(e) {
      e.preventDefault();
      jQuery.cookie("cookies-enutt", "rejectall", { expires : 365, path: '/' });
      jQuery("#cookie-advise").fadeOut();
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
        jQuery.cookie("cookies-enutt", string.join(","), { expires : 365, path: '/' });
        jQuery("#cookie-advise").fadeOut( "slow", function() {
          location.reload();
        });
      }
    });

    jQuery("#button-accept").click(function(e) {
      e.preventDefault();
      jQuery("#cookie-advise").fadeOut( "slow", function() {
        location.reload();
      });
      jQuery.cookie("cookies-enutt", "allowall", { expires : 365, path: '/' });
    });
  </script>
  <?php echo ob_get_clean(); 
}

//SHORTCODE ------------------------------------
add_shortcode('cookies-enutt', 'cookies_enutt_shortcode');
function cookies_enutt_shortcode() { 
  $cookies = explode(",", $_COOKIE['cookies-enutt']);
  ob_start(); ?>
  <div id="sc-options">
    <p><b><?php _e("Opciones", "cookies-enutt"); ?></b></p>
    <p><input type="checkbox" id="sc-option-basicas" checked="checked" value="allowbasics" /> <?php _e("Básicas", "cookies-enutt"); ?></p>
    <?php if(get_option("_cookies_enutt_analiticas_show") == 1) { ?><p><input type="checkbox" id="sc-option-analiticas" <?php if(in_array('allowall', $cookies) || in_array('allowanalytics', $cookies)) { ?> checked="checked"<?php } ?>value="allowanalytics" /> <?php _e("Analíticas", "cookies-enutt"); ?></p><?php } ?>
    <?php if(get_option("_cookies_enutt_funcionales_show") == 1) { ?><p><input type="checkbox" id="sc-option-funcionales" <?php if(in_array('allowall', $cookies) || in_array('allowfunctionals', $cookies)) { ?> checked="checked"<?php } ?> value="allowfunctionals" /> <?php _e("Preferencias", "cookies-enutt"); ?></p><?php } ?>
    <?php if(get_option("_cookies_enutt_marketing_show") == 1) { ?><p><input type="checkbox" id="sc-option-marketing" <?php if(in_array('allowall', $cookies) || in_array('allowmarketing', $cookies)) { ?> checked="checked"<?php } ?> value="allowmarketing" /> <?php _e("Marketing", "cookies-enutt"); ?></p><?php } ?>
    <button id="sc-button-accept-options" class="button button-primary"><?php _e("Acepto", "cookies-enutt"); ?></button>
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
        jQuery.cookie("cookies-enutt", "", { expires : 365, path: '/' });
        jQuery.cookie("cookies-enutt", string.join(","), { expires : 365, path: '/' });
        location.reload();
      }
    });
  </script>
  <?php return ob_get_clean(); 
}

//ADMIN -----------------------------------------
add_action('admin_menu', 'cookies_enutt_plugin_menu');
function cookies_enutt_plugin_menu() {
  add_options_page(__('Cookies', "cookies-enutt"), __('Cookies', "cookies-enutt"), 'manage_options', "cookies-enutt", 'cookies_enutt_page_settings');
}

function cookies_enutt_page_settings() { 
  if(isset($_REQUEST['send']) && $_REQUEST['send'] != '') { 
    update_option('_cookies_enutt_bg_color', $_POST['_cookies_enutt_bg_color']);
    update_option('_cookies_enutt_text_color', $_POST['_cookies_enutt_text_color']);
    update_option('_cookies_enutt_gtm_code', $_POST['_cookies_enutt_gtm_code']);
    update_option('_cookies_enutt_analiticas', $_POST['_cookies_enutt_analiticas']);
    update_option('_cookies_enutt_analiticas_show', $_POST['_cookies_enutt_analiticas_show']);
    update_option('_cookies_enutt_funcionales', $_POST['_cookies_enutt_funcionales']);
    update_option('_cookies_enutt_funcionales_show', $_POST['_cookies_enutt_funcionales_show']);
    update_option('_cookies_enutt_marketing', $_POST['_cookies_enutt_marketing']);
    update_option('_cookies_enutt_marketing_show', $_POST['_cookies_enutt_marketing_show']);
    update_option('_cookies_enutt_politica_cookies', $_POST['_cookies_enutt_politica_cookies']);
    update_option('_cookies_enutt_position_x', $_POST['_cookies_enutt_position_x']);
    update_option('_cookies_enutt_position_y', $_POST['_cookies_enutt_position_y']);
    ?><p style="border: 1px solid green; color: green; text-align: center;"><?php _e("Datos guardados correctamente.", "cookies-enutt"); ?></p><?php
  } ?>
  <form method="post">
    <h1><?php _e("Cookies", "cookies-enutt"); ?></h1>
    <h2><?php _e("Color de fondo", "cookies-enutt"); ?></h2>
    <input type="text" name="_cookies_enutt_bg_color" value="<?php echo get_option("_cookies_enutt_bg_color"); ?>" placeholder='<?php _e("#ffffff", 'cookies-enutt'); ?>' style="width: 100%;" />		
    <h2><?php _e("Color texto", "cookies-enutt"); ?></h2>
    <input type="text" name="_cookies_enutt_text_color" value="<?php echo get_option("_cookies_enutt_text_color"); ?>" placeholder='<?php _e("#ffffff", 'cookies-enutt'); ?>' style="width: 100%;" />		
    <h2><?php _e("Identificado de GTM", "cookies-enutt"); ?></h2>
    <input type="text" name="_cookies_enutt_gtm_code" value="<?php echo get_option("_cookies_enutt_gtm_code"); ?>" placeholder='<?php _e("GTM-XXXXXXXXXX", 'cookies-enutt'); ?>' style="width: 100%;" />		
    <h2><?php _e("Cookies analítica", "cookies-enutt"); ?></h2>
    <textarea rows="10" style="width: 100%;" name="_cookies_enutt_analiticas"><?php echo stripslashes(get_option("_cookies_enutt_analiticas")); ?></textarea>
    <input type="checkbox" name="_cookies_enutt_analiticas_show" value="1" <?=(get_option("_cookies_enutt_analiticas_show") == 1 ? " checked='checked'" : "")?>><?php _e("Hay cookies de analitica", "cookies-enutt"); ?><br/>
    <h2><?php _e("Cookies funcionales", "cookies-enutt"); ?></h2>
    <textarea rows="10" style="width: 100%;" name="_cookies_enutt_funcionales"><?php echo stripslashes(get_option("_cookies_enutt_funcionales")); ?></textarea>
    <input type="checkbox" name="_cookies_enutt_marketing_show" value="1" <?=(get_option("_cookies_enutt_funcionales_show") == 1 ? " checked='checked'" : "")?>><?php _e("Hay cookies de funcionales", "cookies-enutt"); ?><br/>
    <h2><?php _e("Cookies marketing", "cookies-enutt"); ?></h2>
    <textarea rows="10" style="width: 100%;" name="_cookies_enutt_marketing"><?php echo stripslashes(get_option("_cookies_enutt_marketing")); ?></textarea>
    <input type="checkbox" name="_cookies_enutt_marketing_show" value="1" <?=(get_option("_cookies_enutt_marketing_show") == 1 ? " checked='checked'" : "")?>><?php _e("Hay cookies de marketing", "cookies-enutt"); ?><br/>
    <h2><?php _e("URL a la politica de cookies", "cookies-enutt"); ?></h2>
    <input type="text" name="_cookies_enutt_politica_cookies" value="<?php echo get_option("_cookies_enutt_politica_cookies"); ?>" placeholder='<?php _e("URL a la politica de cookies", 'cookies-enutt'); ?>' style="width: 100%;" />		
    <h2><?php _e("Posición botón personalizar cookies", "cookies-enutt"); ?></h2>
    <select name="_cookies_enutt_position_x">
      <option value="left"<?=(get_option('_cookies_enutt_position_x') == 'left' ? " selected='selected'" : ""); ?>><?php _e("Izquierda", 'cookies-enutt'); ?></option>
      <option value="right"<?=(get_option('_cookies_enutt_position_x') == 'right' ? " selected='selected'" : ""); ?>><?php _e("Derecha", 'cookies-enutt'); ?></option>
    </select>
    <select name="_cookies_enutt_position_y">
      <option value="calc(100% - 45px)"<?=(get_option('_cookies_enutt_position_y') == 'calc(100% - 45px)' ? " selected='selected'" : ""); ?>><?php _e("Abajo", 'cookies-enutt'); ?></option>
      <option value="calc(50% - 15px)"<?=(get_option('_cookies_enutt_position_y') == 'calc(50% - 15px)' ? " selected='selected'" : ""); ?>><?php _e("Medio", 'cookies-enutt'); ?></option>
      <option value="10px"<?=(get_option('_cookies_enutt_position_y') == '10px' ? " selected='selected'" : ""); ?>><?php _e("Arriba", 'cookies-enutt'); ?></option>
    </select>
    <br/><br/><input type="submit" name="send" class="button button-primary" value="<?php _e("Guardar"); ?>" />
  </form>
<?php }

//FOOTER -----------------------------------------
function cookies_enutt_footer() {
  ?><div style="position: fixed; z-index: 20; <?=get_option('_cookies_enutt_position_x'); ?>: 10px; top: <?=get_option('_cookies_enutt_position_y'); ?>; border-radius: 15px; padding: 0px 10px; background-color: <?=get_option("_cookies_enutt_bg_color");?>;">
    <a href="<?=get_option("_cookies_enutt_politica_cookies");?>#sc-options" style="color: <?=get_option("_cookies_enutt_text_color");?>;" title="<?php _e("Personalizar cookies", 'cookies-enutt'); ?>"><?php _e("Cookies", 'cookies-enutt'); ?></a>
  </div><?php
}
add_action( 'wp_footer', 'cookies_enutt_footer' );
