<?php
class yummi {

  protected $option_name = 'br-yummi';
  protected $data = array(
    //_e('Home')
     'yauto_add'            => 0
    ,'yhome_text'           => '🏠' //🏠 or &#x1f3e0; or &#127968;
    ,'ydelimiter'           => '&raquo;'
    ,'ydelimiter_cats'      => '&bull;'
    ,'ydelimiter_subcats'   => '&sbquo;'
    ,'ydelimiter_h_childs'  => '&#58;'
    ,'ysubcategory'         => 1
    ,'ydropbg'              => '#fff'
    ,'ydropshadow'          => '0px 2px 5px 0px rgba(0,0,0,0.5)'
    ,'yshow_current'        => 0
    ,'yshow_on_home'        => 1
    ,'yshow_home_link'      => 1
    ,'yshow_title'          => 1
    ,'ycustomfont'          => 0
    ,'yfont'                => 'inherit'
    ,'ycustomfontpa'        => 0
    ,'yfontpa'              => 'font-weight: inherit'
    ,'ytooltip'             => 1
    ,'ythanks'              => 1
    ,'customCSS'            => ''
  );

  function __construct() {
		$this->init();
		return $this;
	}

	function init() {
    //prr($this);
		$this->path = dirname( __FILE__ );
		$this->name = basename( $this->path );
		$this->url = plugins_url( "/{$this->name}/" );
    //prr($this->path);
    //prr($this->url);
    //prr($this->name);

    register_activation_hook(YUMMI_FILE, array($this, 'activate')); // Listen for the activate event
    register_deactivation_hook(YUMMI_FILE, array($this, 'deactivate'));// Deactivation plugin

		//load_plugin_textdomain( 'yummicarousel', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		//add_theme_support('post-thumbnails');

		if( is_admin() ) {
			add_action( 'admin_menu', array(&$this, 'admin_menu') );
		} else {
			//add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles_and_scripts'));
			//add_shortcode('yummicarousel', array(&$this, 'shortcode') );
		}
	}

  public function admin_menu() {
		/*add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );*/
    if( empty( $GLOBALS['admin_page_hooks']['yummi']) )
      $main_page = add_menu_page( 'yummi', 'Yummi Plugins', 'manage_options', 'yummi', array($this, 'yummi_plugins'), $this->url.'/includes/img/dashicons-yummi.png' );
		/*add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );*/
		$main_page = add_submenu_page( 'yummi','yummibreadcrumbs', __('Yummi Breadcrumbs', 'yummi-multicategory-breadcrumbs'), 'manage_options', 'yummibreadcrumbs', array( $this, 'options_do_page')  );

		//add_action( 'admin_print_styles-' . $main_page, array(&$this, 'admin_page_styles') );
		//add_action( 'admin_print_scripts-'. $main_page, array(&$this, 'admin_page_scripts') );
    register_setting('yummibreadcrumbs', $this->option_name, array($this, 'validate'));
	}

  function yummi_plugins() {
    if(!is_admin() || !current_user_can("manage_options"))
      die( 'yummi-oops' );
    if(!function_exists('yummi_plugins'))
      include_once( $this->path . '/includes/yummi-plugins.php' );
  }

  public function activate() {
    add_option('yummibreadcrumbs_redirect', true);
    update_option($this->option_name, $this->data);
  }

  public function deactivate() {
    //delete_option($this->option_name);
  }

  public function options_do_page() { // Print the menu page itself
    $bro = get_option($this->option_name);
    $args = array('public'=>true, '_builtin'=>false);
    $output = 'names';
    $operator = 'and';
    $post_types = array();
    if (function_exists('get_post_types')) $post_types = get_post_types($args, $output, $operator);
    //prr($post_types);

    wp_enqueue_style( 'yummi-style', $this->url.'includes/css/admin_style.min.css' );
    wp_enqueue_style( 'yummi-hint', $this->url.'includes/css/hint.min.css' );
    //wp_enqueue_script( 'yummi-clipboard', plugins_url('js/ZeroClipboard.min.js', __FILE__) );

    //prr($bro);
?>
    <div class="wrap" id="br-yummi">

      <h2><?php _e('Yummi Breadcrumbs', 'yummi-multicategory-breadcrumbs') ?>: <?php _e('Settings', 'yummi-multicategory-breadcrumbs') ?></h2>
      <!-- <div style='float:right;margin-top: -27px;'><span style="font-size:1.3em">&starf;</span> <a href="https://wordpress.org/support/plugin/yummi-multicategory-breadcrumbs/reviews/#new-post" target="_blank"><?php _e('Rate')?></a> &ensp; ❤ <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SLHFMF373Z9GG&source=url" target="_blank"><?php _e('Donate', 'yummi-multicategory-breadcrumbs')?></a></div> -->

      <div class="rating-stars">
        <?php _e('Support project', 'yummi-multicategory-breadcrumbs') ?>  <?php _e('Rate', 'yummi-multicategory-breadcrumbs') ?>:<br/>
        <a href="//wordpress.org/support/plugin/yummi-multicategory-breadcrumbs/reviews/?rate=1#new-post" data-rating="1" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#ffb900 !important; text-decoration: none;"></span></a>
        <a href="//wordpress.org/support/plugin/yummi-multicategory-breadcrumbs/reviews/?rate=2#new-post" data-rating="2" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#ffb900 !important; text-decoration: none;"></span></a>
        <a href="//wordpress.org/support/plugin/yummi-multicategory-breadcrumbs/reviews/?rate=3#new-post" data-rating="3" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#ffb900 !important; text-decoration: none;"></span></a>
        <a href="//wordpress.org/support/plugin/yummi-multicategory-breadcrumbs/reviews/?rate=4#new-post" data-rating="4" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#ffb900 !important; text-decoration: none;"></span></a>
        <a href="//wordpress.org/support/plugin/yummi-multicategory-breadcrumbs/reviews/?rate=5#new-post" data-rating="5" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#ffb900 !important; text-decoration: none;"></span></a>
      </div>
      <script>
      jQuery(document).ready( function($) {
      	$('.rating-stars').find('a').hover(
      		function() {
      			$(this).nextAll('a').children('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
      			$(this).prevAll('a').children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
      			$(this).children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
      		}, function() {
      			var rating = $('input#rating').val();
      			if (rating) {
      				var list = $('.rating-stars a');
      				list.children('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
      				list.slice(0, rating).children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
      			}
      		}
      	);
      });
      </script>

      <form method="post" action="options.php">
        <?php settings_fields('yummibreadcrumbs');
        //prr( $bro ); //вывод всех настроек
        ?>

        <span id="yprev" style="display:block;transition:height 0.5s ease-out;<?php if($bro['ysubcategory'] == 'sub') echo 'height:90px'; ?>">

          <?php _e('How breadcrumbs look', 'yummi-multicategory-breadcrumbs') ?> ( <a href="https://yummi.club/characters" target="_blank" class="hint--top" data-hint="<?php _e('HTML Characters', 'yummi-multicategory-breadcrumbs') ?>"><?php _e('HTML Characters', 'yummi-multicategory-breadcrumbs') ?></a> ):<br/>

          <span id="yshow_home_link" class="hint--top" data-hint="<?php _e('Home link Text', 'yummi-multicategory-breadcrumbs') ?> (<?php _e('This text replace Home link text', 'yummi-multicategory-breadcrumbs') ?>)" style="<?php if($bro['yshow_home_link'] == 0) echo 'display:none'; ?>">
            <input type="text" name="<?php echo $this->option_name?>[yhome_text]" size="10" value="<?php echo $bro['yhome_text']; ?>" />
            <?php echo ' '.$bro['ydelimiter']; ?>
          </span>

          <?php _e('Recipes', 'yummi-multicategory-breadcrumbs') ?>
          <span class="hint--top" data-hint="<?php _e('Main Delimiter', 'yummi-multicategory-breadcrumbs') ?>">
            <input type="text" name="<?php echo $this->option_name?>[ydelimiter]" size="1" maxlength="3" value="<?php echo $bro['ydelimiter']; ?>" />
          </span>

          <span id="ysubcat_one" style="">
            <?php _e('Snacks', 'yummi-multicategory-breadcrumbs') ?>
              <span id="dropdown_lable_arrow" style="<?php if($bro['ysubcategory'] == 'first') echo 'display:none'; ?>" class="hint--top" data-hint="<?php _e('Delimiter Categories', 'yummi-multicategory-breadcrumbs') ?>">
                <input type="text" name="<?php echo $this->option_name?>[ydelimiter_cats]" size="1" maxlength="10" value="<?php echo $bro['ydelimiter_cats']; ?>" />
            </span>
          </span>

          <span id="dropdown_lable" style="<?php if($bro['ysubcategory'] == 'first') echo 'display:none'; ?>">
            <?php _e('Vegetarian', 'yummi-multicategory-breadcrumbs') ?>
          </span>

          <span id="dropdown_pre" style="<?php if($bro['ysubcategory'] == 'main' || $bro['ysubcategory'] == 'subinline' || $bro['ysubcategory'] == 'first') echo 'display:none'; ?>">&#x25BE;</span>

          <span id="dropdown" style="background:<?php echo $bro['ydropbg'] ?>;-webkit-box-shadow:<?php echo $bro['ydropshadow'] ?>;-moz-box-shadow:<?php echo $bro['ydropshadow'] ?>;box-shadow: <?php echo $bro['ydropshadow'] ?>;<?php if($bro['ysubcategory'] == 'main' || $bro['ysubcategory'] == 'subinline' || $bro['ysubcategory'] == 'first') echo 'display:none'; ?>">
            &bull; <?php _e('Vegan', 'yummi-multicategory-breadcrumbs') ?><br/>
            &bull; <?php _e('Raw foodists', 'yummi-multicategory-breadcrumbs') ?>
          </span>

          <span id="ysubcategory" class="hint--top" data-hint="<?php _e('Delimiters for Categories what have SubCategorie', 'yummi-multicategory-breadcrumbs') ?>" style="<?php if($bro['ysubcategory'] == 'main' || $bro['ysubcategory'] == 'sub') echo 'display:none'; ?>">
            <span id="ysubcat_one_c" style="<?php if($bro['ysubcategory'] == 'first') echo 'display:none'; ?>">
              <input type="text" name="<?php echo $this->option_name?>[ydelimiter_h_childs]" size="1" maxlength="10" value="<?php echo $bro['ydelimiter_h_childs']; ?>" />
              <?php _e('Vegan', 'yummi-multicategory-breadcrumbs') ?>
              <input type="text" name="<?php echo $this->option_name?>[ydelimiter_subcats]" size="1" maxlength="10" value="<?php echo $bro['ydelimiter_subcats']; ?>" />
            </span>

            <span id="ysubcat_one_cs" style="<?php if($bro['ysubcategory'] == 'main' || $bro['ysubcategory'] == 'sub' || $bro['ysubcategory'] == 'first') echo 'display:none'; ?>">
              <?php echo ' '.$bro['ydelimiter']; ?>
            </span>

            <span id="ysubcat_one_raw" style="<?php if($bro['ysubcategory'] == 'first') echo 'display:none'; ?>">
              <?php _e('Raw foodists', 'yummi-multicategory-breadcrumbs') ?>
            </span>

          </span>

          <?php echo $bro['ydelimiter']; ?>
          <span id="yshow_current" style="<?php if($bro['yshow_current'] == 0) echo 'display:none'; ?>">
            <?php _e('Recipe title', 'yummi-multicategory-breadcrumbs') ?>
          </span><br/>

        </span>

        <small><?php _e('Default', 'yummi-multicategory-breadcrumbs') ?>: &#127968; &raquo; <?php _e('Recipes', 'yummi-multicategory-breadcrumbs') ?> &raquo; <?php _e('Snacks', 'yummi-multicategory-breadcrumbs') ?> &bull; <?php _e('Vegetarian', 'yummi-multicategory-breadcrumbs') ?> &#58; <?php _e('Vegan', 'yummi-multicategory-breadcrumbs') ?>&sbquo; <?php _e('Raw foodists', 'yummi-multicategory-breadcrumbs') ?> &raquo; <?php _e('Recipe title', 'yummi-multicategory-breadcrumbs') ?></small>


        <div class="tabs">
          <input type="radio" name="tabs" id="options-tab" checked />
          <label for="options-tab"><?php _e('Settings', 'yummi-multicategory-breadcrumbs') ?></label>

          <input type="radio" name="tabs" id="category-tab" />
          <label for="category-tab"><?php _e('Categories', 'yummi-multicategory-breadcrumbs') ?> <span class="yselect"><?php _e('exclude', 'yummi-multicategory-breadcrumbs'); ?></span></label>

          <input type="radio" name="tabs" id="taxonomy-tab" />
          <label for="taxonomy-tab"><?php _e('Custom Post Types', 'yummi-multicategory-breadcrumbs') ?> <span class="yselect"><?php _e('exclude', 'yummi-multicategory-breadcrumbs'); ?></span></label>

          <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

            <!--  -->

          <div id="options-tab-content" class="tab-content">

              <table class="form-table">
                <tr valign="top">
                  <th scope="row">
                    <?php _e('Installation', 'yummi-multicategory-breadcrumbs') ?>:
                  </th>
                  <td>
                    <div class="switch-toggle switch-candy switch-candy-blue">

                      <input type="radio" name="<?php echo $this->option_name?>[yauto_add]" value="1" id="yauto_add_on" <?php checked( $bro['yauto_add'], 1 ); ?> onchange="document.getElementById('yauto_add').style.display='none';"/>
                      <label for="yauto_add_on"><?php _e('Auto', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[yauto_add]" value="0" id="yauto_add_off" <?php checked( $bro['yauto_add'], 0 ); ?> onchange="document.getElementById('yauto_add').style.display='';"/>
                      <label for="yauto_add_off"><?php _e('Manual', 'yummi-multicategory-breadcrumbs') ?></label>

                      <a></a>
                    </div>
                  </td>
                  <td rowspan="9" align="center" valign="top">
                    <h2><?php _e('Donate', 'yummi-multicategory-breadcrumbs') ?>:</h2>
                    <p>
                      <strong><?php _e('PayPal', 'yummi-multicategory-breadcrumbs') ?></strong><br/>
                      <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SLHFMF373Z9GG&source=url" target="_blank">
                        <img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" alt="<?php _e('PayPal - The safer, easier way to pay online!', 'yummi-multicategory-breadcrumbs') ?>" width="147">
                      </a>
                    </p>
                    <br/>
                    <p>
                      <strong><?php _e('WebMoney', 'yummi-multicategory-breadcrumbs') ?></strong><br/>
                      <div class="ewm-widget-donate" data-guid="4fbd3229-7deb-403b-930f-f2301b532f65" data-type="compact"></div>

                      <script type="text/javascript">//<!--
                      (function(w, d, id) {
                          w.ewmAsyncWidgets = function() { EWM.Widgets.init(); };
                          if (!d.getElementById(id)) {
                              var s = d.createElement('script'); s.id = id; s.async = true; s.src = '//events.webmoney.ru/js/ewm-api.js?11';
                              (d.getElementsByTagName('head')[0] || d.documentElement).appendChild(s);
                          }
                      })(window, document, 'ewm-js-api');
                      //-->
                      </script>
                    </p>
                    <br/>
                    <p>
                      <strong><?php _e('Yandex.Money', 'yummi-multicategory-breadcrumbs') ?></strong><br/>
                        <iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/donate.xml?account=41001556291842&quickpay=donate&payment-type-choice=off&default-sum=&targets=Yummi+Breadcrumbs&project-name=Yummi+Breadcrumbs&project-site=https%3A%2F%2Fwordpress.org%2Fplugins%2Fyummi-multicategory-breadcrumbs%2F&button-text=05&successURL=" width="422" height="91"></iframe>
                    </p>
                    <hr/>
                    <p>
                      <?php _e('Custom CSS', 'yummi-multicategory-breadcrumbs') ?>:
                      <textarea name="<?php echo $this->option_name?>[customCSS]" id="customCSS" rows="15" cols="55" placeholder="<?php _e('Custom CSS', 'yummi-multicategory-breadcrumbs') ?>"><?php echo $bro['customCSS']?></textarea>
                    </p>
                  </td>
                </tr>
                <tr valign="top" id="yauto_add" style="<?php if ($bro['yauto_add'] == 1) echo 'display:none;'; ?>">
                  <td colspan="2">
                    &emsp;<?php _e('Manual installation code', 'yummi-multicategory-breadcrumbs') ?>:<br/>
                    <pre id="ycopy" class="hint--top" data-hint="<?php _e('You need add this code to Template files, copy it and go to', 'yummi-multicategory-breadcrumbs') ?> <?php _e('Appearance', 'yummi-multicategory-breadcrumbs') ?> &gt; <?php _e('Editor', 'yummi-multicategory-breadcrumbs') ?>">&lt;?php if(function_exists('yummi_breadcrumbs')) yummi_breadcrumbs(); ?&gt;</pre><br/>
                    &emsp;<?php _e('and to AMP Tamlate files', 'yummi-multicategory-breadcrumbs') ?> (<a href="https://www.ampproject.org/" target="_blank"><?php _e('What Is AMP?', 'yummi-multicategory-breadcrumbs') ?></a>):<br/>
                    <pre id="ycopyamp" class="hint--top" data-hint="<?php _e('You need add this code to Template files, copy it and go to', 'yummi-multicategory-breadcrumbs') ?> <?php _e('Appearance', 'yummi-multicategory-breadcrumbs') ?> &gt; <?php _e('Editor', 'yummi-multicategory-breadcrumbs') ?>">&lt;?php if(function_exists('yummi_breadcrumbs_amp')) yummi_breadcrumbs_amp(); ?&gt;</pre><br/>
                    &emsp;<small><?php _e('Put this code to your template files', 'yummi-multicategory-breadcrumbs') ?>: <?php _e('Appearance', 'yummi-multicategory-breadcrumbs') ?> &gt; <?php _e('Editor', 'yummi-multicategory-breadcrumbs') ?></small>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <?php _e('Breadcrumbs view', 'yummi-multicategory-breadcrumbs') ?>:
                  </th>
                  <td>
                    <div class="switch-toggle switch-4 switch-candy switch-candy-yellow">

                      <input type="radio" name="<?php echo $this->option_name?>[ysubcategory]" value="subinline" id="ysubcategory_inline" <?php checked( $bro['ysubcategory'], 'subinline' ); ?> onchange="
                        document.getElementById('yprev').style.height='50px';
                        document.getElementById('dropdown').style.display='none';
                        document.getElementById('dropdown_lable').style.display='';
                        document.getElementById('dropdown_lable_arrow').style.display='';
                        document.getElementById('dropdown_pre').style.display='none';
                        document.getElementById('ysubcategory').style.display='';
                        document.getElementById('ysubcategory_dropi').style.display='none';
                        document.getElementById('ysubcat_one').style.display='';
                        document.getElementById('ysubcat_one_c').style.display='';
                        document.getElementById('ysubcat_one_cs').style.display='none';
                        document.getElementById('ysubcat_one_raw').style.display='';
                      "/>
                      <label for="ysubcategory_inline" class="hint--top" data-hint="<?php _e('Show SubCategories', 'yummi-multicategory-breadcrumbs') ?> <?php _e('Inline', 'yummi-multicategory-breadcrumbs') ?>"><?php _e('Sub Inline', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[ysubcategory]" value="sub" id="ysubcategory_drop" <?php checked( $bro['ysubcategory'], 'sub' ); ?> onchange="
                        document.getElementById('yprev').style.height='90px';
                        document.getElementById('dropdown').style.display='';
                        document.getElementById('dropdown_lable').style.display='';
                        document.getElementById('dropdown_lable_arrow').style.display='';
                        document.getElementById('dropdown_pre').style.display='';
                        document.getElementById('ysubcategory').style.display='none';
                        document.getElementById('ysubcategory_dropi').style.display='';
                        document.getElementById('ysubcat_one').style.display='';
                        document.getElementById('ysubcat_one_c').style.display='none';
                        document.getElementById('ysubcat_one_cs').style.display='';
                        document.getElementById('ysubcat_one_raw').style.display='';
                      "/>
                      <label for="ysubcategory_drop" class="hint--top" data-hint="<?php _e('Show SubCategories', 'yummi-multicategory-breadcrumbs') ?> <?php _e('as Dropdown List', 'yummi-multicategory-breadcrumbs') ?>"><?php _e('Sub Dropdown', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[ysubcategory]" value="main" id="ysubcategory_hide" <?php checked( $bro['ysubcategory'], 'main' ); ?> onchange="
                        document.getElementById('yprev').style.height='50px';
                        document.getElementById('dropdown').style.display='none';
                        document.getElementById('dropdown_lable').style.display='';
                        document.getElementById('dropdown_lable_arrow').style.display='';
                        document.getElementById('dropdown_pre').style.display='none';
                        document.getElementById('ysubcategory').style.display='none';
                        document.getElementById('ysubcategory_dropi').style.display='none';
                        document.getElementById('ysubcat_one').style.display='';
                        document.getElementById('ysubcat_one_c').style.display='none';
                        document.getElementById('ysubcat_one_cs').style.display='none';
                        document.getElementById('ysubcat_one_raw').style.display='';
                      "/>
                      <label for="ysubcategory_hide" class="hint--top" data-hint="<?php _e('Show only', 'yummi-multicategory-breadcrumbs') ?> <?php _e('Main', 'yummi-multicategory-breadcrumbs') ?>"><?php _e('Main', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[ysubcategory]" value="first" id="ysubcategory_one" <?php checked( $bro['ysubcategory'], 'first' ); ?> onchange="
                        document.getElementById('yprev').style.height='50px';
                        document.getElementById('dropdown').style.display='none';
                        document.getElementById('dropdown_lable').style.display='none';
                        document.getElementById('dropdown_lable_arrow').style.display='none';
                        document.getElementById('dropdown_pre').style.display='none';
                        document.getElementById('ysubcategory').style.display='';
                        document.getElementById('ysubcategory_dropi').style.display='none';
                        document.getElementById('ysubcat_one').style.display='';
                        document.getElementById('ysubcat_one_c').style.display='none';
                        document.getElementById('ysubcat_one_cs').style.display='none';
                        document.getElementById('ysubcat_one_raw').style.display='none';
                      "/>
                      <label for="ysubcategory_one" class="hint--top" data-hint="<?php _e('Show only', 'yummi-multicategory-breadcrumbs') ?> <?php _e('First', 'yummi-multicategory-breadcrumbs') ?>"><?php _e('First', 'yummi-multicategory-breadcrumbs') ?></label>

                      <a></a>
                    </div>
                  </td>
                </tr>
                <tr valign="top" id="ysubcategory_dropi" style="<?php if ($bro['ysubcategory'] == 'main' || $bro['ysubcategory'] == 'subinline' || $bro['ysubcategory'] == 'first') echo 'display:none;'; ?>">
                  <th scope="row">
                    <?php _e('Dropdown background color', 'yummi-multicategory-breadcrumbs') ?>:<br/>
                    <small><?php _e('Default', 'yummi-multicategory-breadcrumbs') ?>: <span class="yselect">#fff</span></small>
                    <br/><br/>
                    <?php _e('Dropdown shadow control', 'yummi-multicategory-breadcrumbs') ?>:<br/>
                    <small><?php _e('Default', 'yummi-multicategory-breadcrumbs') ?>: <span class="yselect">0px 2px 5px 0px rgba(0,0,0,0.5)</span></small>
                  </th>
                  <td>
                    <input type="text" name="<?php echo $this->option_name?>[ydropbg]" oninput="document.getElementById('dropdown').style.background=this.value;" value="<?php echo $bro['ydropbg']; ?>" />
                    <br/><br/><br/>
                    <input size="40" type="text" name="<?php echo $this->option_name?>[ydropshadow]" oninput="document.getElementById('dropdown').style.boxShadow=this.value;" value="<?php echo $bro['ydropshadow']; ?>" />
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <?php _e('Name of the current', 'yummi-multicategory-breadcrumbs') ?>:<br/>
                    <small><?php _e('Current Article / Page / etc.', 'yummi-multicategory-breadcrumbs') ?></small>
                  </th>
                  <td>
                    <div class="switch-toggle switch-candy">

                      <input type="radio" name="<?php echo $this->option_name?>[yshow_current]" value="1" id="yshow_current_show" <?php checked( $bro['yshow_current'], 1 ); ?> onchange="document.getElementById('yshow_current').style.display='';"/>
                      <label for="yshow_current_show"><?php _e('SHOW', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[yshow_current]" value="0" id="yshow_current_hide" <?php checked( $bro['yshow_current'], 0 ); ?> onchange="document.getElementById('yshow_current').style.display='none';"/>
                      <label for="yshow_current_hide"><?php _e('HIDE', 'yummi-multicategory-breadcrumbs') ?></label>

                      <a></a>
                    </div>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <?php _e('On the Home page', 'yummi-multicategory-breadcrumbs') ?>:<br/>
                    <small><?php _e('Breadcrumbs on the main page', 'yummi-multicategory-breadcrumbs') ?></small>
                  </th>
                  <td>
                    <div class="switch-toggle switch-candy">

                      <input type="radio" name="<?php echo $this->option_name?>[yshow_on_home]" value="1" id="yshow_on_home_show" <?php checked( $bro['yshow_on_home'], 1 ); ?> />
                      <label for="yshow_on_home_show"><?php _e('SHOW', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[yshow_on_home]" value="0" id="yshow_on_home_hide" <?php checked( $bro['yshow_on_home'], 0 ); ?> />
                      <label for="yshow_on_home_hide"><?php _e('HIDE', 'yummi-multicategory-breadcrumbs') ?></label>

                      <a></a>
                    </div>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    "<?php echo $bro['yhome_text'].' '.$bro['ydelimiter']; ?>" <?php _e('link', 'yummi-multicategory-breadcrumbs') ?>:
                  </th>
                    <td>
                    <div class="switch-toggle switch-candy">

                      <input type="radio" name="<?php echo $this->option_name?>[yshow_home_link]" value="1" id="yshow_home_link_show" <?php checked( $bro['yshow_home_link'], 1 ); ?> onchange="document.getElementById('yshow_home_link').style.display='';"/>
                      <label for="yshow_home_link_show"><?php _e('SHOW', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[yshow_home_link]" value="0" id="yshow_home_link_hide" <?php checked( $bro['yshow_home_link'], 0 ); ?> onchange="document.getElementById('yshow_home_link').style.display='none';"/>
                      <label for="yshow_home_link_hide"><?php _e('HIDE', 'yummi-multicategory-breadcrumbs') ?></label>

                      <a></a>
                    </div>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <?php _e('Tooltips', 'yummi-multicategory-breadcrumbs') ?>:
                  </th>
                  <td>
                    <div class="switch-toggle switch-candy">

                      <input type="radio" name="<?php echo $this->option_name?>[ytooltip]" value="1" id="ytooltip_show" <?php checked( $bro['ytooltip'], 1 ); ?> />
                      <label for="ytooltip_show"><?php _e('SHOW', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[ytooltip]" value="0" id="ytooltip_hide" <?php checked( $bro['ytooltip'], 0 ); ?> />
                      <label for="ytooltip_hide"><?php _e('HIDE', 'yummi-multicategory-breadcrumbs') ?></label>

                      <a></a>
                    </div>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php _e('"title=" for links', 'yummi-multicategory-breadcrumbs') ?>:</th>
                    <td>
                      <div class="switch-toggle switch-candy">
                        <input type="radio" name="<?php echo $this->option_name?>[yshow_title]" value="1" id="yshow_title_show" <?php checked( $bro['yshow_title'], 1 ); ?> />
                        <label for="yshow_title_show"><?php _e('SHOW', 'yummi-multicategory-breadcrumbs') ?></label>

                        <input type="radio" name="<?php echo $this->option_name?>[yshow_title]" value="0" id="yshow_title_hide" <?php checked( $bro['yshow_title'], 0 ); ?> />
                        <label for="yshow_title_hide"><?php _e('HIDE', 'yummi-multicategory-breadcrumbs') ?></label>

                        <a></a>
                    </div>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <?php _e('Custom font', 'yummi-multicategory-breadcrumbs') ?>:
                  </th>
                  <td>
                    <div class="switch-toggle switch-candy">

                      <input type="radio" name="<?php echo $this->option_name?>[ycustomfont]" value="1" id="ycustomfont_on" <?php checked( $bro['ycustomfont'], 1 ); ?> onchange="document.getElementById('ycustomfont').style.display = '';"/>
                      <label for="ycustomfont_on"><?php _e('ON', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[ycustomfont]" value="0" id="ycustomfont_off" <?php checked( $bro['ycustomfont'], 0 ); ?> onchange="document.getElementById('ycustomfont').style.display = 'none';"/>
                      <label for="ycustomfont_off"><?php _e('OFF', 'yummi-multicategory-breadcrumbs') ?></label>

                      <a></a>
                    </div>
                  </td>
                </tr>
                <tr valign="top" id="ycustomfont" style="<?php if ($bro['ycustomfont'] == 0) echo 'display:none;'; ?>">
                  <th scope="row">
                    <?php _e('Input font name', 'yummi-multicategory-breadcrumbs') ?>:<br/>
                    <small><?php _e('Fonts separate by commas', 'yummi-multicategory-breadcrumbs') ?>. <?php _e('Default', 'yummi-multicategory-breadcrumbs') ?>: <span class="yselect">inherit</span></small>
                  </th>
                  <td>
                    <input type="text" name="<?php echo $this->option_name?>[yfont]" value="<?php echo $bro['yfont']; ?>" />
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <?php _e('Custom Parent Category Style', 'yummi-multicategory-breadcrumbs') ?>:
                  </th>
                  <td>
                    <div class="switch-toggle switch-candy">

                      <input type="radio" name="<?php echo $this->option_name?>[ycustomfontpa]" value="1" id="ycustomfontpa_on" <?php checked( $bro['ycustomfontpa'], 1 ); ?> onchange="document.getElementById('ycustomfontpa').style.display = '';"/>
                      <label for="ycustomfontpa_on"><?php _e('ON', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[ycustomfontpa]" value="0" id="ycustomfontpa_off" <?php checked( $bro['ycustomfontpa'], 0 ); ?> onchange="document.getElementById('ycustomfontpa').style.display = 'none';"/>
                      <label for="ycustomfontpa_off"><?php _e('OFF', 'yummi-multicategory-breadcrumbs') ?></label>

                      <a></a>
                    </div>
                  </td>
                </tr>
                <tr valign="top" id="ycustomfontpa" style="<?php if ($bro['ycustomfontpa'] == 0) echo 'display:none;'; ?>">
                  <th scope="row">
                    <?php _e('Input style code', 'yummi-multicategory-breadcrumbs') ?>:<br/>
                    <small><?php _e('Default', 'yummi-multicategory-breadcrumbs') ?>: <span class="yselect">font-weight: inherit</span></small>
                  </th>
                  <td>
                    <input type="text" name="<?php echo $this->option_name?>[yfontpa]" value="<?php echo $bro['yfontpa']; ?>" />
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">
                    <?php _e('Your Gratitude', 'yummi-multicategory-breadcrumbs') ?>:<br/>
                    <small><?php _e('To display a link to the developer`s site', 'yummi-multicategory-breadcrumbs') ?></small>
                  </th>
                  <td>
                    <div class="switch-toggle switch-3 switch-candy">

                      <input type="radio" name="<?php echo $this->option_name?>[ythanks]" value="1" id="ythanks_show" <?php checked( $bro['ythanks'], 1 ); ?> />
                      <label for="ythanks_show"><?php _e('Content', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[ythanks]" value="2" id="ythanks_show_2" <?php checked( $bro['ythanks'], 2 ); ?> />
                      <label for="ythanks_show_2"><?php _e('Footer', 'yummi-multicategory-breadcrumbs') ?></label>

                      <input type="radio" name="<?php echo $this->option_name?>[ythanks]" value="0" id="ythanks_hide" <?php checked( $bro['ythanks'], 0 ); ?> />
                      <label for="ythanks_hide"><?php _e('HIDE', 'yummi-multicategory-breadcrumbs') ?></label>

                      <a></a>
                    </div>
                  </td>
                </tr>
              </table>

            </div>

            <!-- -->

            <div id="category-tab-content" class="tab-content">

              <small><?php _e('Please select', 'yummi-multicategory-breadcrumbs'); ?> "<?php _e('Categories', 'yummi-multicategory-breadcrumbs') ?>", <?php _e('what you want', 'yummi-multicategory-breadcrumbs'); ?> <span class="yselect"><?php _e('exclude', 'yummi-multicategory-breadcrumbs'); ?></span></small><br/>

              <?php $num_cats = wp_count_terms( 'category' );
              if ( $num_cats > 1000 ) {
                echo "Sorry, You have too many categories - " . $num_cats;
              } else {?>
                <div id="taxonomy-category" class="categorydiv">
                    <ul id="categorychecklist" class="list:category categorychecklist form-no-clear">
                      <?php	wp_category_checklist(0,0,$bro['exCats'],false); ?>
                    </ul>
                </div>
              <?php } ?>

            </div>

            <!-- -->

            <div id="taxonomy-tab-content" class="tab-content">

              <small><?php _e('Please select', 'yummi-multicategory-breadcrumbs'); ?> "<?php _e('Custom Post Types', 'yummi-multicategory-breadcrumbs') ?>", <?php _e('what you want', 'yummi-multicategory-breadcrumbs'); ?> <span class="yselect"><?php _e('exclude', 'yummi-multicategory-breadcrumbs'); ?></span></small><br/>
              <?php foreach ($post_types as $cptID=>$cptName){ ?>
                <input type="checkbox" name="<?php echo $this->option_name?>[<?php echo $cptName?>]" value="1" <?php checked( $bro['ycpt'][$cptName], 1 ); ?> />&nbsp;
                <?php
                  $obj = get_post_type_object( $cptName );
                  echo $obj->labels->singular_name; ?><br/>
              <?php } ?>

            </div>
          </div>
      </form>
    </div>
  <?php
    //prr( $bro );
  }

  public function validate($input) {

    $valid = array();
    $valid['yauto_add'] = $input['yauto_add'];
    $valid['yhome_text'] = mb_convert_encoding(sanitize_text_field($input['yhome_text']), 'HTML-ENTITIES');
    $valid['ydelimiter'] = mb_convert_encoding(sanitize_text_field($input['ydelimiter']), 'HTML-ENTITIES');
    $valid['ydelimiter_cats'] = mb_convert_encoding(sanitize_text_field($input['ydelimiter_cats']), 'HTML-ENTITIES');
    $valid['ydelimiter_subcats'] = mb_convert_encoding(sanitize_text_field($input['ydelimiter_subcats']), 'HTML-ENTITIES');
    $valid['ydelimiter_h_childs'] = mb_convert_encoding(sanitize_text_field($input['ydelimiter_h_childs']), 'HTML-ENTITIES');
    $valid['ysubcategory'] = $input['ysubcategory'];
    $valid['ydropbg'] = sanitize_text_field($input['ydropbg']);
    $valid['ydropshadow'] = sanitize_text_field($input['ydropshadow']);
    $valid['yshow_current'] = $input['yshow_current'];
    $valid['yshow_on_home'] = $input['yshow_on_home'];
    $valid['yshow_home_link'] = $input['yshow_home_link'];
    $valid['yshow_title'] = $input['yshow_title'];
    $valid['ycustomfont'] = $input['ycustomfont'];
    $valid['yfont'] = sanitize_text_field($input['yfont']);
    $valid['ycustomfontpa'] = $input['ycustomfontpa'];
    $valid['yfontpa'] = sanitize_text_field($input['yfontpa']);
    $valid['ytooltip'] = $input['ytooltip'];
    $valid['ythanks'] = $input['ythanks'];
    $valid['customCSS'] = sanitize_text_field($input['customCSS']);

    $args = array('public'=>true, '_builtin'=>false);
    $output = 'names';
    $operator = 'and';
    $post_types = array();
    $post_types = get_post_types($args, $output, $operator);
    $ycpt = array ();
    foreach ($post_types as $cptID=>$cptName) {
        $ycpt[$cptName] = $input[$cptName];
    }
    $valid['ycpt'] = $ycpt;

    $exCats = array();
    /*if(isset($input["post_category"])) {
      foreach($input["post_category"] AS $vv){
        //if(!empty($vv) && is_numeric($vv)) $exCats[] = intval($vv);
        $exCats[] = $vv;
      }
    }*/
    if ( isset( $_POST['post_category'] ) ) {
      $pk = $_POST['post_category'];
      /*if ( ! is_array( $pk ) ) {
        $pk = urldecode( $pk );
        parse_str( $pk );
      }
      remove_action( 'get_terms', 'order_category_by_id', 10 );*/
      //$cIds = get_terms( 'category', array( 'fields' => 'ids', 'get' => 'all' ) );
      if ( is_array( $pk ) ) {
        $bro['exCats'] = $pk;
      } else {
        $bro['exCats'] = '';
      }
    }
    $valid['exCats'] = $bro['exCats'];

    /*if (strlen($valid['yhome_text']) == 0) {
        add_settings_error(
                'yhome_text',                     // setting title
                'todourl_texterror',            // error ID
                'Please enter a Home name',     // error message
                'error'                         // type of message
        );
        # Set it to the default value
        $valid['yhome_text'] = $this->data['yhome_text'];
    }
    if (strlen($valid['ydelimiter']) == 0) {
        add_settings_error(
                'ydelimiter',                     // setting title
                'todourl_texterror',            // error ID
                'Please enter a valid URL',     // error message
                'error'                         // type of message
        );

        # Set it to the default value
        $valid['ydelimiter'] = $this->data['ydelimiter'];
    }
    if (strlen($valid['ydelimiter_subcats']) == 0) {
        add_settings_error(
                'ydelimiter_subcats',                     // setting title
                'todourl_texterror',            // error ID
                'Please enter a valid URL',     // error message
                'error'                         // type of message
        );

        # Set it to the default value
        $valid['ydelimiter_subcats'] = $this->data['ydelimiter_subcats'];
    }*/

    if ($valid['ydropbg'] == '') {
        $valid['ydropbg'] = '#fff';
    }
    if ($valid['ydropbg'] == '') {
        $valid['ydropbg'] = '0px 2px 5px 0px rgba(0,0,0,0.5)';
    }
    if ($valid['yfont'] == '') {
        $valid['yfont'] = 'inherit';
    }
    if ($valid['yfontpa'] == '') {
        $valid['yfontpa'] = 'font-weight: inherit';
    }
    if ($valid['ycustomfont'] == 0) {
        $valid['yfont'] = 'inherit';
    }
    if ($valid['ycustomfontpa'] == 0) {
        $valid['yfontpa'] = 'font-weight: inherit';
    }
    /*if ($valid['ytooltip'] == 1) {
        $valid['yshow_title'] = 1;
    }
    if ($valid['yshow_title'] == 0) {
        $valid['ytooltip'] = 0;
    }*/
    return $valid;
  }
}
