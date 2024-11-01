<?php

if ($bro['yauto_add'] == 1) {
  add_filter( 'the_content', 'yummi_breadcumbs_auto_add' );
  function yummi_breadcumbs_auto_add( $content ) {
      $yummi_content = yummi_breadcrumbs();
      $yummi_content .= $content;
    return $yummi_content;
  }
}

if ($bro['ytooltip'] == 1){
  function yhead(){
    wp_enqueue_style( 'yummi-hint', plugins_url('/css/hint.min.css', __FILE__) );
  }
  add_action('wp_head', 'yhead');
};


if ($bro['ythanks'] == 1) {
  add_filter( 'the_content', 'yummi_filter_the_content' );
  function yummi_filter_the_content( $content ) {
    $content .= '<small class="gratitude">'.__('Gratitude', 'yummi-multicategory-breadcrumbs').' <a href="https://yummi.club/" target="_blank">yummi.club</a></small>';
    return $content;
  }
}
if($bro['ythanks'] == 2) {
  add_filter('wp_footer', 'yummi_filter_footer');
  function yummi_filter_footer () {
    echo '<small class="gratitude">'.__('Gratitude', 'yummi-multicategory-breadcrumbs').' <a href="https://yummi.club/" target="_blank">yummi.club</a></small>';
  }
  /*
  // before footer
  //add_filter('get_footer', 'your_function');
  function your_function() {
    $content = '<small style="font-size:10px;">'.__('Gratitude', 'yummi-multicategory-breadcrumbs').' <a href="https://yummi.club/" target="_blank">yummi.club</a></small>';
    echo $content;
  }
  // /before footer

  // after footer
  //add_filter('wp_footer', 'yummi_filter_footer');
  function yummi_filter_footer () {
    echo '<small style="font-size:10px;">'.__('Gratitude', 'yummi-multicategory-breadcrumbs').' <a href="https://yummi.club/" target="_blank">yummi.club</a></small>';
  }
  // /after footer

  //add_action( 'twentyten_credits', 'yummi_credits' );
  function yummi_credits () {
    $html = '';
    $html .= '<small style="font-size:10px;">'._e('Gratitude', 'yummi-multicategory-breadcrumbs').' <a href="https://yummi.club/" target="_blank">yummi.club</a></small>';
    echo $html;
  }*/
  /*if ($bro['ythanks'] == 1) {
  };
  if(is_front_page()&&!is_user_logged_in()) echo '<p class="plugin-info">'.__('The site works using the functionality of the plugin').'  <a target="_blank" href="http://wppost.ru/">Wp-Recall</a></p>';
  */
}

function yummi_css() {
  global $bro;
  if( $bro["ysubcategory"] == 'sub' ){
    wp_enqueue_style( 'yummi-multicategory-breadcrumbs', plugins_url('/css/dropdown.min.css', __FILE__) );
    echo '<style>
            .breadcrumbs ul li>ul.children{
              background:'.$bro["ydropbg"].';
              -webkit-box-shadow:'.$bro["ydropshadow"].';
              -moz-box-shadow:'.$bro["ydropshadow"].';
              box-shadow:'.$bro["ydropshadow"].';
            }
            .gratitude{
              font-size:10px;
              position:absolute;
              margin-top:-15px;
              right:0;
            }
            .hint--top, .current{font-family:'. $bro["yfont"] .'}
            .parent{'. $bro["yfontpa"].'}
            '.$bro['customCSS'].'
          </style>';
  }else{
    wp_enqueue_style( 'yummi-multicategory-breadcrumbs', plugins_url('/css/inline.min.css', __FILE__) );
  }
}
add_action('wp_print_styles', 'yummi_css'); // end yummi_breadcrumbs() ?>
