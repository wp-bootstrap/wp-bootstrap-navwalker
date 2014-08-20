<?php
/**
 * Class Name: wp_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
class wp_bootstrap_navwalker extends Walker_Nav_Menu {
  /**
   * @see Walker::start_lvl()
   * @since 3.0.0
   *
   * @param string $output Passed by reference. Used to append additional content.
   * @param int $depth Depth of page. Used for padding.
   */
    public function start_lvl( &$output, $depth = 0, $args = [] ) {
      parent::start_lvl($output, $depth, $args);
      $pos = strrpos($output, '">', -1);
      $output = substr_replace(
        $output, ' dropdown-menu" role="menu">', $pos);
    }
  /**
   * Traverse elements to create list from elements.
   *
   * Display one element if the element doesn't have any children otherwise,
   * display the element and its children. Will only traverse up to the max
   * depth and no ignore elements under that depth.
   *
   * This method shouldn't be called directly, use the walk() method instead.
   *
   * @see Walker::start_el()
   * @since 2.5.0
   *
   * @param object $element Data object
   * @param array $children_elements List of elements to continue traversing.
   * @param int $max_depth Max depth to traverse.
   * @param int $depth Depth of current element.
   * @param array $args
   * @param string $output Passed by reference. Used to append additional content.
   * @return null Null on failure with no changes to parameters.
   */
  public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
    if (!$element) return;

    $id_field = $this->db_fields['id'];

    $this->has_children = !empty($children_elements[ $element->$id_field ]);

    parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
  }

  /**
   * Menu Fallback
   * =============
   * If this function is assigned to the wp_nav_menu's fallback_cb variable
   * and a manu has not been assigned to the theme location in the WordPress
   * menu manager the function with display nothing to a non-logged in user,
   * and will add a link to the WordPress menu manager if logged in as an admin.
   *
   * @param array $args passed from the wp_nav_menu function.
   *
   */
  public static function fallback( $args ) {
    if ( current_user_can( 'manage_options' ) ) {

      extract( $args );

      $fb_output = null;

      if ( $container ) {
        $fb_output = '<' . $container;

        if ( $container_id )
          $fb_output .= ' id="' . $container_id . '"';

        if ( $container_class )
          $fb_output .= ' class="' . $container_class . '"';

        $fb_output .= '>';
      }

      $fb_output .= '<ul';

      if ( $menu_id )
        $fb_output .= ' id="' . $menu_id . '"';

      if ( $menu_class )
        $fb_output .= ' class="' . $menu_class . '"';

      $fb_output .= '>';
      $fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
      $fb_output .= '</ul>';

      if ( $container )
        $fb_output .= '</' . $container . '>';

      echo $fb_output;
    }
  }
}

/**
 * Filters
 * =======
 *
 *  It is more robust to rely on Wordpress' filter/hook framework than
 *  to subclass Walker_Nav_Menu class, which duplicates a lot of code.
 *-/
 *
 *   This filter is used to customize the <li> classes.
 */
add_filter('nav_menu_css_class', function($classes, $item, $args) {
/*
 *  Append the dropdown class to the output class array.
 */
  if ($args->walker->has_children) {
    $classes[] = 'dropdown';
  }
  if (in_array('current-menu-item', $classes)){
    $classes[] = 'active';
  }
  if (strpos($item->attr_title, 'glyphicon-') === false) {
  /*
   *  Add the title attribute defined in WPs Appereance → Menus
   *  dashboard as a class. Ignore glyphicons which are appended
   *  inside another element such as a <span/> or <i/>
   */
    $classes[] = $item->attr_title;
  }
  return $classes;
},10, 3); # $priority, $accepted_args

/*
 *   This filter is used to customize the <li> attributes.
 */
add_filter('nav_menu_link_attributes', function($atts, $item, $args) {
  if ($args->walker->has_children) {
  /*
   *  Append the data-toggle and dropdown attributes to the
   *  anchor element inside the list item dropdown.
   */
    $atts['data-toggle']  = 'dropdown';
    $atts['class'] = 'dropdown-toggle';
    $atts['aria-haspopup']  = 'true';
    0 === $args->depth and $args->link_after = ' <i class="caret"></i>';
  }
  if (strpos($item->attr_title, 'glyphicon-') !== false) {
    $title = esc_attr($item->attr_title);
    $args->link_before = '<i class="glyphicon '. $title .'"></i> ';
  }
  return $atts;
},10, 3); # $priority, $accepted_args

/*
 *   This filter is used to customize the <li> final output.
 */
add_filter('walker_nav_menu_start_el',
  function($item_output, $item, $depth, $args) {
    if ('dropdown-header' === strtolower($item->attr_title) && $depth === 1) {
      $indent = str_repeat( "\t", $depth );
      return $indent . '<li role="presentation" class="dropdown-header">' . esc_attr($item->title);
    }
    $args->link_before = '';
    $args->link_after = '';
    /*
     *  Reset the before and after link strings previously used
     *  to append the glyphicon and caret to avoid side effects.
     */
    return $item_output;
}, 10, 4); # $priority, $accepted_args
