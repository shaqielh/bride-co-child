<?php
/**
 * Custom Mega Menu Walker
 *
 * An updated WordPress nav walker class to implement a mega menu navigation style
 * with image support for dropdown menus and proper handling of child sub-items.
 *
 * @package Bride_Co_Child
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Complete_Mega_Menu_Walker
 * 
 * Creates a mega menu for Bootstrap 5 with image support and nested sub-item handling
 */
class Complete_Mega_Menu_Walker extends Walker_Nav_Menu {

    /**
     * Unique ID for tracking current menu item
     *
     * @var string
     */
    private $mega_menu_id;

    /**
     * Store the current parent menu item ID
     *
     * @var int
     */
    private $current_parent_id = 0;

    /**
     * Track which column to place items in
     *
     * @var int
     */
    private $column_index = 0;
    
    /**
     * Keep track of current column
     *
     * @var boolean
     */
    private $in_second_column = false;

    /**
     * Track item categories for grouping
     * 
     * @var array
     */
    private $menu_categories = array();

    /**
     * Track if we're inside a category
     *
     * @var boolean
     */
    private $in_category = false;

    /**
     * Store the current category
     *
     * @var string
     */
    private $current_category = '';
	private $current_image = '';

    /**
     * Whether the items_wrap contains schema microdata or not.
     *
     * @var boolean
     */
    private $has_schema = false;

    /**
     * Starts the list before the elements are added.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);

        // Generate a unique ID for the menu
        if ($depth === 0) {
            $this->mega_menu_id = 'mega-menu-' . mt_rand(1000, 9999);
            $this->column_index = 0;
            $this->in_second_column = false;
            
            // Start mega menu container
            $output .= "{$n}{$indent}<div class=\"dropdown-menu mega-menu\" id=\"{$this->mega_menu_id}\" aria-labelledby=\"navbarDropdown\">{$n}";
            $output .= "{$indent}{$t}<div class=\"container-fluid\">{$n}";
            $output .= "{$indent}{$t}{$t}<div class=\"row\">{$n}";
            
            // Menu content column (left side)
            $output .= "{$indent}{$t}{$t}{$t}<div class=\"col-md-12\">{$n}";
            $output .= "{$indent}{$t}{$t}{$t}{$t}<div class=\"row\">{$n}";
			
			// Image column (right side)
            $output .= "{$indent}{$t}{$t}{$t}<div class=\"col-md-3 mega-menu-image position-relative\">{$n}";
            
            // Get the menu image for this parent
            $menu_image = '';
            if ($this->current_parent_id) {
                $menu_image = get_post_meta($this->current_parent_id, '_menu_item_image', true);
            }
            
            /*if (!empty($menu_image)) {
                $output .= "{$indent}{$t}{$t}{$t}{$t}<img src=\"" . esc_url($menu_image) . "\" alt=\"Menu Image\" class=\"img-fluid\">{$n}";
            } else {
                // Default or placeholder image
                $output .= "{$indent}{$t}{$t}{$t}{$t}<div class=\"menu-placeholder-image\">Menu Image</div>{$n}";
            }*/
            
			 
			if (!empty($this->current_image)) {
                $output .= "{$indent}{$t}{$t}{$t}{$t}<img src=\"" . esc_url($this->current_image) . "\" alt=\"Menu Image\" class=\"img-fluid\"> <a href=\"https://stage.brideandco.co.za/wp-content/uploads/2025/04/Brideco-Personal-Wedding-Planner.pdf\" class=\"btn custom-btn btn-primary position-absolute start-50 translate-middle-x\" target=\"_blank\" rel=\"noopener noreferrer\">
                Download Our Wedding Planner</a></img>{$n}"; 
            } else {
                // Default or placeholder image
                $output .= "{$indent}{$t}{$t}{$t}{$t}<div class=\"menu-placeholder-image\">Menu Image</div>{$n}";
            }
            // Close image column
            $output .= "{$indent}{$t}{$t}{$t}</div>{$n}";
            
            // First category column
            $output .= "{$indent}{$t}{$t}{$t}{$t}{$t}<div class=\"col-md-2 mega-menu-col\">{$n}";
            $output .= "{$indent}{$t}{$t}{$t}{$t}{$t}{$t}<h4 class=\"menu-column-heading\">Categories</h4>{$n}";

            $output .= "{$indent}{$t}{$t}{$t}{$t}{$t}{$t}<ul class=\"list-unstyled mega-menu-column\">{$n}";

        } elseif ($depth === 1) {
            // This is a submenu of a dropdown item - check if it's a category
            if ($this->in_category) {
                // If inside a category, this is a normal submenu
                $output .= "{$indent}{$t}<ul class=\"submenu-list list-unstyled\">{$n}";
            } else {
                // Otherwise it's a standard submenu
                $output .= "{$indent}{$t}<ul class=\"dropdown-menu\">{$n}";
            }
        } else {
            // Even deeper levels (3rd level and beyond)
            $output .= "{$indent}{$t}<ul class=\"submenu-list-nested list-unstyled\">{$n}";
        }
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);

        if ($depth === 0) {
			
			
            // Close first column
            $output .= "{$indent}{$t}{$t}{$t}{$t}{$t}{$t}</ul>{$n}";
            $output .= "{$indent}{$t}{$t}{$t}{$t}{$t}</div>{$n}";
            
            // Second category column 
            $output .= "{$indent}{$t}{$t}{$t}{$t}{$t}<div class=\"col-md-3 mega-menu-col\">{$n}";
            $output .= "{$indent}{$t}{$t}{$t}{$t}{$t}{$t}<ul class=\"list-unstyled mega-menu-column\">{$n}";
            
            // Add second column items - items will be automatically added by the walker
            $output .= "{$indent}{$t}{$t}{$t}{$t}{$t}{$t}</ul>{$n}";
            $output .= "{$indent}{$t}{$t}{$t}{$t}{$t}</div>{$n}";
            
            // Close menu content row and column
            $output .= "{$indent}{$t}{$t}{$t}{$t}</div>{$n}";
            $output .= "{$indent}{$t}{$t}{$t}</div>{$n}";
            
            
            
            // Close container divs
            $output .= "{$indent}{$t}{$t}</div>{$n}";
            $output .= "{$indent}{$t}</div>{$n}";
            $output .= "{$indent}</div>{$n}";
        } elseif ($depth === 1) {
            if ($this->in_category) {
                // Close the submenu list for categories
                $output .= "{$indent}{$t}</ul>{$n}";
            } else {
                // Close regular dropdown
                $output .= "{$indent}</ul>{$n}";
            }
        } else {
            // Close nested submenu
            $output .= "{$indent}{$t}</ul>{$n}";
        }
    }

    /**
     * Starts the element output.
     *
     * @param string   $output            Used to append additional content (passed by reference).
     * @param WP_Post  $item              Menu item data object.
     * @param int      $depth             Depth of menu item.
     * @param stdClass $args              An object of wp_nav_menu() arguments.
     * @param int      $id                Current item ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        // Store parent ID for image use
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $this->current_parent_id = $item->ID;
        }
		
		$thumbnail_id = get_post_meta($item->ID, 'menu_featured_image', true);

if ($thumbnail_id) {
    $file = get_post_meta($thumbnail_id, '_wp_attached_file', true);
    $upload_dir = wp_upload_dir();
    $this->current_image = $upload_dir['baseurl'] . '/' . $file;
}else{
    $this->current_image ='';
}

        // Check if this is a category item (first level item with children)
        if ($depth === 1 && in_array('menu-item-has-children', $classes)) {
            $this->in_category = true;
            $this->current_category = $item->title;
            
            // Add category class
            $classes[] = 'dropdown-category';
            $classes[] = 'has-submenu';
        }

        // Increment column index for first level items
        if ($depth === 1) {
            $this->column_index++;
            
            // After a certain number of items or if marked with column-break class, 
            // move to the second column
            $column_break = in_array('column-break', $classes);
            
            if (($this->column_index > 6 || $column_break) && !$this->in_second_column) {
                $this->in_second_column = true;
                
                // Only insert column break if not already at the start of a column
                if ($this->column_index > 1 && !$column_break) {
                    // Close the current column and start a new one
                    $output .= "{$n}</ul>{$n}";
                    $output .= "</div>{$n}";
                    $output .= "<div class=\"col-md-6 mega-menu-col\">{$n}";
                    $output .= "<ul class=\"list-unstyled mega-menu-column\">{$n}";
                }
            }
        }

        // Add Bootstrap specific classes
        $classes[] = 'menu-item-' . $item->ID;
        
        if ($depth === 0) {
            $classes[] = 'nav-item';
            
            // Add dropdown class to parent items
            if (in_array('menu-item-has-children', $classes)) {
                $classes[] = 'dropdown';
            }
        } elseif ($depth === 2) {
            // Add submenu item class for third level
            $classes[] = 'submenu-item';
            
            // If it has children, mark it
            if (in_array('menu-item-has-children', $classes)) {
                $classes[] = 'has-nested-submenu';
            }
        }
        
        // Handle active state
        if (in_array('current-menu-item', $classes) || in_array('current-menu-parent', $classes)) {
            $classes[] = 'active';
        }
        
        // Filter the arguments for a single nav menu item
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);
        
        // Allow filtering the classes
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        // Filter the ID
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . '>';
        
        // Check if this is a category header and has children
        if ($depth === 1 && in_array('menu-item-has-children', $classes) && in_array('dropdown-category', $classes)) {
            // For category headers, output a heading
            $output .= '<h4>' . esc_html($item->title) . '</h4>';
            
            // Skip the regular link output
            $item_output = '';
        } else {
            // Initialize link attributes
            $atts = array();
            $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
            $atts['target'] = !empty($item->target) ? $item->target : '';
            $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
            $atts['href']   = !empty($item->url) ? $item->url : '#';
            
            // Set link classes based on depth
            if ($depth === 0) {
                $atts['class'] = 'nav-link';
                
                // If item has children, add dropdown toggle
                if (in_array('menu-item-has-children', $classes)) {
                    $atts['class'] .= ' dropdown-toggle';
                    $atts['data-bs-toggle'] = 'dropdown';
                    $atts['aria-expanded'] = 'false';
                }
            } elseif ($depth === 1) {
                $atts['class'] = 'dropdown-item';
                
                // If this item has children but is not a category, add submenu toggle
                if (in_array('menu-item-has-children', $classes) && !in_array('dropdown-category', $classes)) {
                    $atts['class'] .= ' dropdown-toggle submenu-toggle';
                    $atts['data-bs-toggle'] = 'dropdown';
                    $atts['aria-expanded'] = 'false';
                }
            } else {
                $atts['class'] = 'dropdown-item submenu-item';
                
                // Even deeper nested items
                if (in_array('menu-item-has-children', $classes)) {
                    $atts['class'] .= ' nested-submenu-toggle';
                }
            }
            
            // Filter the link attributes
            $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
            
            // Build attribute string
            $attributes = '';
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }
            
            // This filter is documented in wp-includes/post-template.php
            $title = apply_filters('the_title', $item->title, $item->ID);
            $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
            
            // Build item output
            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . $title . $args->link_after;
            
            // Add dropdown indicator for items with children
            if (($depth === 0 || $depth >= 1) && in_array('menu-item-has-children', $classes)) {
                $item_output .= ' <i class="dropdown-indicator"></i>';
            }
            
            $item_output .= '</a>';
            $item_output .= $args->after;
        }
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    /**
     * Ends the element output, if needed.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param WP_Post  $item   Page data object. Not used.
     * @param int      $depth  Depth of page. Not used.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        
        // Reset category flag at the end of category item
        if ($depth === 1 && $this->in_category && in_array('menu-item-has-children', (array) $item->classes)) {
            $this->in_category = false;
            $this->current_category = '';
        }
        
        $output .= "</li>{$n}";
    }
    
    /**
     * Menu Fallback
     * 
     * If this function is assigned to the wp_nav_menu's fallback_cb variable
     * and a menu has not been assigned, the function will display a message.
     *
     * @param array $args passed from the wp_nav_menu function.
     */
    public static function fallback($args) {
        if (current_user_can('edit_theme_options')) {
            echo '<ul class="navbar-nav">';
            echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(admin_url('nav-menus.php')) . '">Add a menu</a></li>';
            echo '</ul>';
        }
    }
}