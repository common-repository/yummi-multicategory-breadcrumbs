<?php

$bro = get_option('br-yummi');

function yummi_breadcrumbs_amp() {
  global $post;
  global $count;
  global $author;
  global $bro;
  $taxonomy_cats = '';
  $count = 1;
  /* === ОПЦИИ === */
  $text['home']     = $bro['yhome_text']; // текст ссылки "Главная"
  $text['category'] = __('Archive', 'yummi-multicategory-breadcrumbs').' "%s"'; // текст для страницы рубрики: Архив рубрики "%s"
  $text['search']   = __('Results for', 'yummi-multicategory-breadcrumbs').' "%s"'; // текст для страницы с результатами поиска: Результаты поиска по запросу "%s"
  $text['tag']      = __('Entries tagged with', 'yummi-multicategory-breadcrumbs').' "%s"'; // текст для страницы тега: Записи с тегом "%s"
  $text['author']   = __('Articles by author', 'yummi-multicategory-breadcrumbs').' %s'; // текст для страницы автора: Статьи автора %s
  $text['404']      = __('Error 404', 'yummi-multicategory-breadcrumbs'); // текст для страницы 404: Ошибка 404

  $ysubcategory     = $bro['ysubcategory']; // Показать: 'sub' - все подкатегории в виде DropDown, 'subinline' - все подкатегории InLine, 'main' - только Главные, 'first' - только первые
  $ydropbg          = $bro['ydropbg'];
  $yshow_current    = $bro['yshow_current']; // 1 - показывать название текущей статьи/страницы/рубрики, 0 - не показывать
  $yshow_on_home    = $bro['yshow_on_home']; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
  $yshow_home_link  = $bro['yshow_home_link']; // 1 - показывать ссылку "Главная", 0 - не показывать
  $yshow_title      = $bro['yshow_title']; // 1 - показывать подсказку (title) для ссылок, 0 - не показывать
  $ydelimiter       = '<span class="ybrs ys"> '.$bro['ydelimiter'].' </span>'; // разделить между "крошками"
  $ydelimiter_cats  = '<span class=ybrs>'.$bro['ydelimiter_cats'].'</span>'; // разделить между разделами в "крошках"
  $ybefore          = '<span class="current">'; // тег перед текущей "крошкой"
  $yafter           = '</span>'; // тег после текущей "крошки"
  $yterm_exclude    = $bro['ycpt'];
  $exCats           = $bro['exCats'];
  /* === КОНЕЦ ОПЦИЙ === */

  $yhome_link = home_url('/');
  $ylink_before = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">'; //itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"
  $ylink_after = '</span>';
  $ylink_attr = ''; // itemprop="item"
  $ylink = $ylink_before . '<a' . $ylink_attr . ' href="%1$s">%2$s</a>' . $ylink_after;
  $parent_id = $parent_id_2 = $post->post_parent;
  $userdata = get_userdata($author);
  $frontpage_id = get_option('page_on_front');

  $term =	get_queried_object();
  ( $term->name ) ? $current_tax_label = $term->name : null;
  ( get_term_link($term->term_id) ) ? $current_url = get_term_link($term->term_id) : null;
  ( !$term->post_type ) ? $pt = $term->post_type : $pt = 'post';
  ( get_term($term->parent, $pt) ) ? $parent_tax_id = get_term($term->parent, $pt) : null;

  $post_type = get_post_type_object( get_query_var('post_type') );//get_post_type_object( $post->post_type );

  if( empty($post_type) && is_category() ){
    $post_type = get_category( get_query_var('cat') );
    $label = $post_type->name;
    $has_archive = $post_type->slug;
  }elseif( is_tax() ){
    $tax = $term->taxonomy;
    $taxonomy = get_taxonomy( $tax );
    $label = $taxonomy->label;
    if( $taxonomy->hierarchical == 1 ) $has_archive = $taxonomy->rewrite['slug'];
  }else{
    $label = !empty($post_type) ? $post_type->label : null;
    $has_archive = !empty($post_type) ? $post_type->has_archive : null;
    $has_archive == 1 ? $has_archive = $post_type->rewrite['slug'] : null;
  }

if ( $yshow_on_home == 0 && !is_front_page() )
  echo '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList"><div>';
elseif( $yshow_on_home == 1 )
  echo '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList"><div>';

  if ( $yshow_home_link == 1 ){
    $taxonomy_cats = '<a itemprop="item" href="' . $yhome_link . '" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Go', 'yummi-multicategory-breadcrumbs' ).' %s', $text['home'] ) ) .'" title="'. esc_attr( sprintf( __( 'Go', 'yummi-multicategory-breadcrumbs' ).' %s', $text['home'] ) ) .'"><span itemprop="name">' . $text['home'] . '</span><meta itemprop="position" content="'.$count.'"></a>'.$ydelimiter;
    $count++;
  }

  if ( is_home() || is_front_page() ) {
      if ( $yshow_on_home == 0 ) $taxonomy_cats = ''; $ydelimiter = ''; $yshow_current = '';

  } elseif ( is_category() || is_archive() && !$author && !is_day() && !is_month() && !is_year() && !is_tag() ) {

    if( $term->parent == 0 ) {

  		if ( $post_type->cat_ID !== 1 ){ //Uncategorized
  		  if ( $yshow_home_link == 1 ){

          if( $has_archive ){
            $taxonomy_cats .= '<span><a href="'. $yhome_link . $has_archive .'" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $label ) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $label ) ) . '"><span itemprop="name">'. $label .'</span><meta itemprop="position" content="'.$count.'"></a></span>'.$ydelimiter;
      			$count++;
          }else{
            $taxonomy_cats .= '<span><span itemprop="name">'. $label .'</span><meta itemprop="position" content="'.$count.'"></span>'.$ydelimiter;
      			$count++;
          }

  		  }else{
    			$taxonomy_cats .= '<a href="'. $yhome_link . $has_archive .'" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $label ) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $label ) ) . '"><span itemprop="name">'. $label .'</span><meta itemprop="position" content="'.$count.'"></a>'.$ydelimiter;
    			$count++;
  		  }
  		}

    }else{

      if( empty($post_type->hierarchical) && $ysubcategory != 'first' ){

          $parents = array();
          $i=0;

          while( $parent_tax_id >= 0 ):
            $i++;
            if ( $parent_tax_id > 0) {
                $getParent = get_term($parent_tax_id);
                $parent_tax_id = $getParent->parent;
                $parents[$i] = array(
                  'label' => $getParent->name,
                  'url' => get_term_link($getParent->term_id)
                );
            }else{
              break;
            }

          endwhile;

          foreach (array_reverse($parents) as $key => $parent) {
            $taxonomy_cats .= '<a href="'. $parent['url'] .'" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $parent['label'] ) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $parent['label'] ) ) . '"><span itemprop="name">'. $parent['label'] .'</span><meta itemprop="position" content="'.$count.'"></a>'.$ydelimiter;
            $count++;
          }

        }

  		// if ( $post_type->cat_ID !== 1  ){ // ссылка на текущую страницу //filter Uncategorized
  		//   if ( $yshow_home_link == 1 ){
      //
    	// 		$taxonomy_cats .= '<span><a href="'. $current_url .'" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $current_tax_label ) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $current_tax_label ) ) . '"><span itemprop="name">'. $current_tax_label .'</span><meta itemprop="position" content="'.$count.'"></a></span>'.$ydelimiter;
    	// 		$count++;
  		//   }else{
    	// 		$taxonomy_cats .= '<a href="'. $current_url .'" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $current_tax_label ) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $current_tax_label ) ) . '"><span itemprop="name">'. $current_tax_label .'</span><meta itemprop="position" content="'.$count.'"></a>'.$ydelimiter;
    	// 		$count++;
  		//   }
  		// }

      if( !empty($post_type->hierarchical) ){

        $taxonomy_cats .= '<a href="'. $yhome_link . $has_archive .'" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $label ) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $label ) ) . '"><span itemprop="name">'. $label .'</span><meta itemprop="position" content="'.$count.'"></a>'.$ydelimiter;
        $count++;

        $parents = array();
        $i=0;

        while( $parent_tax_id >= 0 ):
          $i++;
          if ( $parent_tax_id > 0) {
              $getParent = get_term($parent_tax_id);
              $parent_tax_id = $getParent->parent;
              $parents[$i] = array(
                'label' => $getParent->name,
                'url' => get_term_link($getParent->term_id)
              );
          }else{
            break;
          }

        endwhile;

        foreach (array_reverse($parents) as $key => $parent) {
          $taxonomy_cats .= '<a href="'. $parent['url'] .'" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $parent['label'] ) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $parent['label'] ) ) . '"><span itemprop="name">'. $parent['label'] .'</span><meta itemprop="position" content="'.$count.'"></a>'.$ydelimiter;
          $count++;
        }

        // $taxonomy_cats .= '<a href="'. $current_url .'" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $current_tax_label ) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"',$current_tax_label ) ) . '"><span itemprop="name">'. $current_tax_label .'</span><meta itemprop="position" content="'.$count.'"></a>'.$ydelimiter;
        // $count++;
      }

    }

  } elseif ( is_archive() && $author ) {

  	if ( $yshow_home_link == 1 ){
  		$taxonomy_cats .= '<span><a itemprop="item" href="'. get_author_posts_url($userdata->id, $userdata->user_nicename) .'" class="hint--top" data-hint="'. esc_attr( sprintf($text['author'], $userdata->display_name) ) .'" title="' . esc_attr( sprintf($text['author'], $userdata->display_name) ) . '"><span itemprop="name">'. sprintf($text['author'], $userdata->display_name) .'</span><meta itemprop="position" content="'.$count.'"></a></span>';
  		$count++;
  	}else{
  		$taxonomy_cats .= '<a itemprop="item" href="'. get_author_posts_url($userdata->id, $userdata->user_nicename) .'" class="hint--top" data-hint="'. esc_attr( sprintf($text['author'], $userdata->display_name) ) .'" title="' . esc_attr( sprintf($text['author'], $userdata->display_name) ) . '"><span itemprop="name">'. sprintf($text['author'], $userdata->display_name) .'</span><meta itemprop="position" content="'.$count.'"></a>';
  		$count++;
  	}

  } elseif ( $author && !is_archive() ) {

  	if ( $yshow_home_link == 1 ){
  		$taxonomy_cats .= '<span><a itemprop="item" href="'. get_author_posts_url($userdata->id, $userdata->user_nicename) .'" class="hint--top" data-hint="'. esc_attr( sprintf($text['author'], $userdata->display_name) ) .'" title="' . esc_attr( sprintf($text['author'], $userdata->display_name) ) . '"><span itemprop="name">'. sprintf($text['author'], $userdata->display_name) .'</span><meta itemprop="position" content="'.$count.'"></a></span>';
  		$count++;
  	}else{
  		$taxonomy_cats .= '<a itemprop="item" href="'. get_author_posts_url($userdata->id, $userdata->user_nicename) .'" class="hint--top" data-hint="'. esc_attr( sprintf($text['author'], $userdata->display_name) ) .'" title="' . esc_attr( sprintf($text['author'], $userdata->display_name) ) . '"><span itemprop="name">'. sprintf($text['author'], $userdata->display_name) .'</span><meta itemprop="position" content="'.$count.'"></a>';
  		$count++;
  	}

  } elseif ( is_single() ) {
    if ( $has_archive ){
      if ( $yshow_home_link == 1 ){
        $taxonomy_cats .= '<span><a itemprop="item" href="'. $yhome_link . $has_archive .'" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $label ) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $label ) ) . '"><span itemprop="name">'. $label .'</span><meta itemprop="position" content="'.$count.'"></a></span>'.$ydelimiter;
        $count++;
      }else{
        $taxonomy_cats .= '<a itemprop="item" href="'. $yhome_link . $has_archive .'" class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $label ) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $label ) ) . '"><span itemprop="name">'. $label .'</span><meta itemprop="position" content="'.$count.'"></a>'.$ydelimiter;
        $count++;
      }
    }

    if( $ysubcategory == 'main' ){ // одна категория
      $ydepth = 1;
    }else{ // много категорий
      $ydepth = 0;
    }

    if ( get_post_type() != 'post' ) {  // для не стандартных таксономий
      $taxonomies = $post_type->taxonomies;
      if ( !empty( $taxonomies ) && !is_wp_error( $taxonomies ) ){
        $search = array_search('category', $taxonomies); // Custom Post Type UI fix
        if( !empty($search) )
          $taxonomy = $post_type->taxonomies[$search];
        else
          $taxonomy = $post_type->taxonomies[0];
      }else{
        $taxonomy = $has_archive;
      }
      $post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) ); // get the term IDs assigned to post.
    }else{
      $taxonomy = 'category';
      $post_terms = wp_get_post_categories( $post->ID, array( 'fields' => 'ids' ) ); // get the term IDs assigned to post
    }

    if ( !empty( $post_terms ) && !is_wp_error( $post_terms ) ) {
      if ( !empty( $exCats ) && !is_wp_error( $exCats ) ) $post_terms = array_diff( $post_terms, $exCats );
      $term_ids = implode( ',' , $post_terms);
      $walker = new Yummi_Walker_Category_amp($bro);
      if( $ysubcategory == 'first' ){
        $style = 'none';
      }else{
        $style = 'list';
      }

      $args = array(
         'show_option_all'    => ''
        ,'orderby'            => 'name'
        ,'order'              => 'ASC'
        ,'style'              => $style // 'list', 'none'
        ,'show_count'         => 0
        ,'hide_empty'         => 1
        ,'use_desc_for_title' => 0
        ,'child_of'           => 0
        ,'feed'               => ''
        ,'feed_type'          => ''
        ,'feed_image'         => ''
        ,'exclude'            => ''
        ,'exclude_tree'       => ''
        ,'include'            => $term_ids
        ,'hierarchical'       => 1
        ,'title_li'           => 0 //__( 'Categories' )
        ,'show_option_none'   => ''
        ,'number'             => null
        ,'echo'               => 0
        ,'depth'              => $ydepth
        ,'current_category'   => 0
        ,'pad_counts'         => 0
        ,'taxonomy'           => $taxonomy
        ,'walker'             => $walker
      );
      $taxonomy_catsi = wp_list_categories($args); // wp_list_categori( 'title_li=0&style=list&echo=0&walker=Walker_Category2&taxonomy=' . $taxonomy . '&include=' . $term_ids );
    }
    //$taxonomy_catsi = preg_replace('/<br \/>/iU', '',$taxonomy_catsi);

    if( $ysubcategory == 'first' ){
      $count--;
      $taxonomy_catsi = preg_split('/\n/',$taxonomy_catsi);
      $taxonomy_catsi = preg_replace('/<a(.*)>(.*)<\/a>‚/', '<a$1><span itemprop="name">$2</span><meta itemprop="position" content="'. $count .'"></a>',$taxonomy_catsi);
      //$taxonomy_catsi[1] = str_replace('</a>', '</a><meta itemprop="position" content="'. $count .'">', $taxonomy_catsi[1]);
      //$taxonomy_cats .= $taxonomy_catsi[0].$ydelimiter.$taxonomy_catsi[1];
      $taxonomy_cats .= $taxonomy_catsi[0];
    }else{
      //$taxonomy_catsi = substr_replace($taxonomy_catsi, '', strripos($output, $ydelimiter_cats) );
      $countCats = -1*strlen('<li>'.$ydelimiter_cats.'</li>'); //trim
      $taxonomy_catsi = substr_replace($taxonomy_catsi, "", $countCats);
      $taxonomy_cats .= '<ul>'.$taxonomy_catsi.'</ul>'; //implode(PHP_EOL, $taxonomy_catsi).
    }
  } elseif ( is_attachment() ) {

    $parent = get_post($parent_id);
    $cat = get_the_category($parent->ID); $cat = $cat[0];
    if ($cat) {
      $cats = get_category_parents($cat, TRUE, $ydelimiter);
      $cats = str_replace('<a', $ylink_before . '<a' . $ylink_attr, $cats);
      $cats = str_replace('</a>', '</a>' . $ylink_after, $cats);
      if ($yshow_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
      echo $cats;
    }
    printf($ylink, get_permalink($parent), $parent->post_title);
    if ($yshow_current == 1) echo $ydelimiter . $ybefore . get_the_title() . $yafter;

  } elseif ( is_page() && !$parent_id ) {

    if ($yshow_current == 1) $taxonomy_cats .= $ybefore . get_the_title() . $yafter;

  } elseif ( is_page() && $parent_id ) {
    if ($parent_id != $frontpage_id) {
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        if ($parent_id != $frontpage_id) {
        $breadcrumbs[] = sprintf($ylink, get_permalink($page->ID), get_the_title($page->ID));
        }
        $parent_id = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        $taxonomy_cats .= $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) $taxonomy_cats .= $ydelimiter;
      }
    }
    if ($yshow_current == 1) {
      if ($yshow_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) $taxonomy_cats .= $ydelimiter;
      $taxonomy_cats .= $ybefore . get_the_title() . $yafter;
    }

  } elseif ( is_tag() ) {
    $taxonomy_cats .= sprintf($text['tag'], single_tag_title('', false));

  } elseif ( is_404() ) {
    $taxonomy_cats .= $ybefore . $text['404'] . $yafter;

  } elseif ( has_post_format() && !is_singular() ) {
    $taxonomy_cats .= get_post_format_string( get_post_format() );

  } elseif ( is_search() ) {
    $taxonomy_cats .= $ybefore . sprintf($text['search'], get_search_query());

  } elseif ( is_day() ) {
    //https://codex.wordpress.org/Formatting_Date_and_Time
    $year_name = get_the_time('Y');
    $month_name = get_the_time('F');
    $day_name = get_the_time('d');
    $year_link = get_year_link(get_the_time('Y'));
    $month_link = get_month_link(get_the_time('Y'),get_the_time('m'));
    $day_link = get_day_link(get_the_time('Y'),get_the_time('m'),get_the_date('d'));

    if ( $yshow_home_link == 1 ){
      $taxonomy_cats .= '<span><a itemprop="item" class="hint--top parent" data-hint="'. esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" href="' . $year_link . '"><span itemprop="name">'. $year_name .'</span><meta itemprop="position" content="'.$count.'"></a></span>' . $ydelimiter;
      $count++;
    }else{
      $taxonomy_cats .= '<a itemprop="item" class="hint--top parent" data-hint="'. esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" href="' . $year_link . '"><span itemprop="name">'. $year_name .'</span><meta itemprop="position" content="'.$count.'"></a>' . $ydelimiter;
      $count++;
    }

    $taxonomy_cats .= '<span><a itemprop="item" class="hint--top parent" data-hint="'. esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s %s"', $month_name, $year_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $month_name) ) .'" href="' . $month_link . '"><span itemprop="name">'. $month_name .'</span><meta itemprop="position" content="'.$count.'"></a></span>' . $ydelimiter;
    $count++;
    $taxonomy_cats .= '<span><a itemprop="item" class="hint--top parent" data-hint="'. esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s %s %s"', $day_name,  $month_name, $year_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s %s %s"', $day_name,  $month_name, $year_name) ) .'" href="' . $day_link . '"><span itemprop="name">'. $day_name .'</span><meta itemprop="position" content="'.$count.'"></a></span>' . $ydelimiter;

  } elseif ( is_month() ) {
    //https://codex.wordpress.org/Formatting_Date_and_Time
    $year_name = get_the_time('Y');
    $month_name = get_the_time('F');
    $year_link = get_year_link(get_the_time('Y'));
    $month_link = get_month_link(get_the_time('Y'),get_the_time('m'));

    if ( $yshow_home_link == 1 ){
      $taxonomy_cats .= '<span><a itemprop="item" class="hint--top parent" data-hint="'. esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" href="' . $year_link . '"><span itemprop="name">'. $year_name .'</span><meta itemprop="position" content="'.$count.'"></a></span>' . $ydelimiter;
      $count++;
    }else{
      $taxonomy_cats .= '<a itemprop="item" class="hint--top parent" data-hint="'. esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" href="' . $year_link . '"><span itemprop="name">'. $year_name .'</span><meta itemprop="position" content="'.$count.'"></a>' . $ydelimiter;
      $count++;
    }
    $taxonomy_cats .= '<span><a itemprop="item" class="hint--top parent" data-hint="'. esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s %s"', $month_name, $year_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $month_name) ) .'" href="' . $month_link . '"><span itemprop="name">'. $month_name .'</span><meta itemprop="position" content="'.$count.'"></a></span>' . $ydelimiter;

  } elseif ( is_year() ) {
    //https://codex.wordpress.org/Formatting_Date_and_Time
    $year_name = get_the_time('Y');
    $year_link = get_year_link(get_the_time('Y'));

    if ( $yshow_home_link == 1 ){
      $taxonomy_cats .= '<span><a itemprop="item" class="hint--top parent" data-hint="'. esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" href="' . $year_link . '"><span itemprop="name">'. $year_name .'</span><meta itemprop="position" content="'.$count.'"></a></span>' . $ydelimiter;
    }else{
      $taxonomy_cats .= '<a itemprop="item" class="hint--top parent" data-hint="'. esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles for', 'yummi-multicategory-breadcrumbs' ).' "%s"', $year_name) ) .'" href="' . $year_link . '"><span itemprop="name">'. $year_name .'</span><meta itemprop="position" content="'.$count.'"></a>' . $ydelimiter;
    }

  } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() && !is_author() ) {
    //echo "elseif nothing";
  }
  /* вывод */
  $term_exc = array();
  if ( $yterm_exclude ){
    foreach ( $yterm_exclude as $key => $value ){
      //echo "Ключ: $key; Значение: $value<br />\n";
      if ( $value > 0 ){
        array_push( $term_exc, $key );
      }
    }
  }

  //echo '$term_exc:'; prr($term_exc);
  //echo '$post_type->name:'; prr($post_type->name);
  if ( !empty($post_type) && !in_array( $post_type->name, $term_exc ) || is_single() ){
    //if ( $yshow_current == 0 ) $taxonomy_cats = preg_replace("#^(.+)$ydelimiter$#", "$1", $taxonomy_cats);
    if ( $yshow_on_home == 1 ) $taxonomy_cats = preg_replace("#^(.+)$ydelimiter$#", "$1", $taxonomy_cats);
    $taxonomy_cats = str_replace('<a', $ylink_before . '<a' . $ylink_attr, $taxonomy_cats);
    $taxonomy_cats = str_replace('</a>', '</a>' . $ylink_after, $taxonomy_cats);
    if ( $yshow_title == 0 ) $taxonomy_cats = preg_replace('/ title="(.*?)"/', '', $taxonomy_cats);
    echo $taxonomy_cats;
    // if ( is_page() && !$post->post_parent ) {
    // }elseif( !is_home() || !is_front_page() ){
    //   echo $ydelimiter.'1';
    // }

    if ( $yshow_on_home == 1 && $yshow_home_link == 0 || $yshow_on_home == 0 && $yshow_home_link == 0 || $yshow_on_home == 0 && $yshow_home_link == 1 ){

      if ( is_page() && !$post->post_parent ) {
      }elseif( is_single() ){
        echo $ydelimiter;
      }else{
        if ( !is_home() && $yshow_home_link == 0 && $yshow_on_home == 1 || !is_front_page() && $yshow_home_link == 0 && $yshow_on_home == 1 ){
          echo $ydelimiter;
          //if ( $post_type->cat_ID !== 1 ) echo $ydelimiter;
        }
      }

    }else{
      if( !is_paged() && !is_day() ) echo $ydelimiter;
    }
    if ( $yshow_current == 1 ){

      if ( !is_home() && !is_archive() && !is_search() && !$term || !is_front_page() && !is_archive() && !is_search() && !$term ){
        echo $ybefore . get_the_title() . $yafter;
      }elseif( $term && !$author && !is_tag() ){
        //prr($term);
        if ( is_single() ) {
          echo $ybefore . $term->post_title . $yafter;
        } else {
          if( $term->label ):
            echo $ybefore . $term->label . $yafter;
          else:
            echo $ybefore . $term->name . $yafter;
          endif;
        }
        //echo $ybefore . $userdata->data->display_name . $yafter;
      }elseif( $author ){
        //echo $ybefore . $userdata->data->display_name . $yafter;
      }
    }
  }
  /* /вывод */

  if ( get_query_var('paged') ) {
    if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ){
      if( get_query_var('paged') !== 1 ){
        if ($yshow_current == 1) echo $ydelimiter;
          echo sprintf( __( 'Page', 'yummi-multicategory-breadcrumbs' ) ) . ' ' . get_query_var('paged');
      }
    }
  }
  if ( $yshow_on_home == 0 && !is_front_page() )
    echo '</div></div>';
  elseif( $yshow_on_home == 1 )
    echo '</div></div>';
}

class Yummi_Walker_Category_amp extends Walker_Category {
  /*private $_options;
  public function __construct($bro){
    $this->_options=$bro;
    // prr($this->_options);
  }*/
  public function start_lvl( &$output, $depth = 0, $args = array() ) {
    global $bro;
    if ( 'list' != $args['style'] )
      return;
    $indent = str_repeat("\t", $depth);
    if( $bro["ysubcategory"] == 'sub' ){
      $output .= $indent.'&#x25BE;'." <ul class='children'>\n"; // &rsaquo; = ›
    }else{
      $output .= $indent.'<span class="ybrs h_childs">'.$bro["ydelimiter_h_childs"]."</span><ul class='children'>\n"; // &rsaquo; = ›
    }
  }
  public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;
		$indent = str_repeat("\t", $depth);
    //echo strripos($output, $ydelimiter_subcats);
    //$output = substr_replace($output, "", -3);
    //$output = substr_replace($output, '', strripos($output, $ydelimiter_subcats) );
    //prr( $output );
		$output .= $indent."</ul>\n";
	}

  function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
    global $bro;
    global $count;
    extract($args);
    $cat_name = esc_attr( $category->name );
    $cat_name = apply_filters( 'list_cats', $cat_name, $category );

    $ydelimiter_subcats = '<span class="ybrs ysubcats">'.$bro['ydelimiter_subcats'].'</span>'; // разделить между разделами в "крошках"

    //echo 'проход: '.$category->term_id.'<br/>';
    //prr( 'Name:'.$category->name.' children:'.count($termchildren).' \ parent:'.$category->parent );

    //$termchildren = get_term_children( $category->term_id, $category->taxonomy );

    if( $category->parent == 0 || $bro['ysubcategory'] == 'first' ){ //3 count($termchildren) > 0
      $ylink = ' <span><a itemprop="item" class="hint--top parent" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $cat_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $cat_name) ) .'" href="'.esc_url( get_term_link($category) ).'"><span itemprop="name">'.$cat_name.'</span><meta itemprop="position" content="'.$count.'"></a></span>';
      $count++;
    }else{
        $ylink = ' <a class="hint--top" data-hint="'. esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $cat_name) ) .'" title="' . esc_attr( sprintf( __( 'Articles in category', 'yummi-multicategory-breadcrumbs' ).' "%s"', $cat_name) ) .'" href="' . esc_url( get_term_link($category) ) . '">';
        if( $bro['ysubcategory'] == 'subinline' ){
          $ylink .= $cat_name . '</a>'.$ydelimiter_subcats;
        }else{ //2 - все подкатегории DropDown, 0 - Главные
          $ylink .= $cat_name . '</a>';
        }// Показать: 'sub' - все подкатегории в виде DropDown, 'subinline' - все подкатегории InLine, 'main' - только Главные, 'first' - только первые
    }
    // prr($ylink);
    if ( !empty($show_count) )
      $ylink .= ' (' . intval($category->count) . ')';
      if ( 'list' == $args['style'] ) {
        $output .= "\t<li";
        $class = 'cat-item cat-item-' . $category->term_id;
        if ( !empty($current_category) ) {
          $_current_category = get_term( $current_category, $category->taxonomy );
          if ( $category->term_id == $current_category )
            $class .=  ' current-cat';
          elseif ( $category->term_id == $_current_category->parent )
            $class .=  ' current-cat-parent';
        }
        $output .=  ' class="' . $class . '"';
        $output .= ">$ylink\n";
      } else {
        $output .= "\t$ylink\n";
      }
    }
    public function end_el( &$output, $category, $depth = 0, $args = array() ) {
      global $bro;

      $ydelimiter_subcats = '<span class="ybrs ysubcats">'.$bro['ydelimiter_subcats'].'</span>'; // разделить между разделами в "крошках"

  		if ( 'list' != $args['style'] )
  			return;

      if ( $depth > 0 ){
        $output .= '</li>';
      }else{
        if( strpos( $output, $ydelimiter_subcats ) !== false )
          $output = substr_replace($output, '', strripos( $output, $ydelimiter_subcats ), strlen($ydelimiter_subcats) );
        $output .= '</li><li class="ybrs y_li">'.$bro["ydelimiter_cats"].'</li>'; //for this trim <li></li>
      }
  	}
}
?>
