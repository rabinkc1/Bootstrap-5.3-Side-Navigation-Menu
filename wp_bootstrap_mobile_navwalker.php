<?php
if (!class_exists('WP_Bootstrap_Mobile_Navwalker')) :

    class WP_Bootstrap_Mobile_Navwalker extends Walker_Nav_Menu {

        public function start_lvl(&$output, $depth = 0, $args = null) {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat($t, $depth);
            $parent_slug = sanitize_title($this->parent_id); // Correct parent slug
            $output .= "{$n}{$indent}<div class=\"collapse\" id=\"sub-menu-{$parent_slug}\"><ul class=\"list-unstyled border-top mb-0 bg-light\">{$n}";
        }

        public function end_lvl(&$output, $depth = 0, $args = null) {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat($t, $depth);
            $output .= "$indent</ul></div>{$n}";
        }

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
            $classes[] = 'nav-item border-bottom';

            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

            $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
            $id = $id ? ' id="' . esc_attr($id) . '"' : '';

            $output .= $indent . '<li' . $id . $class_names . '>';

            $atts = array();
            $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
            $atts['target'] = !empty($item->target) ? $item->target : '';
            if (!empty($item->target) && '_blank' === $item->target && empty($item->xfn)) {
                $atts['rel'] = 'noopener noreferrer';
            } else {
                $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
            }
            $atts['href'] = !empty($item->url) ? $item->url : '';
            $atts['class'] = 'nav-link px-3 w-100';

            $attributes = '';
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value = 'href' === $attr ? esc_url($value) : esc_attr($value);
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $item_output = $args->before;

            // Add div for link and button
            $item_output .= '<div class="d-flex align-items-center justify-content-between">';
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '</a>';

            // Check if the item has children and add button
            if (in_array('menu-item-has-children', $classes)) {
                $parent_slug = sanitize_title($item->ID); // Correct parent slug
                $item_output .= '<button class="btn btn-light border-light" type="button" data-bs-toggle="collapse" data-bs-target="#sub-menu-' . $parent_slug . '" aria-expanded="false" aria-controls="sub-menu-' . $parent_slug . '"><i class="fa-solid fa-chevron-down nav-arrow-icon"></i></button>';
                // Save the current item ID for use in start_lvl
                $this->parent_id = $item->ID;
            }

            $item_output .= '</div>'; // Close div

            $item_output .= $args->after;

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }

        public function end_el(&$output, $item, $depth = 0, $args = null) {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $output .= "</li>{$n}";
        }

        public static function fallback($args) {
            if (current_user_can('edit_theme_options')) {
                $container = $args['container'];
                if ($container) {
                    $container_id = $args['container_id'];
                    $container_class = $args['container_class'];
                    echo '<' . esc_attr($container);
                    if ($container_id) {
                        echo ' id="' . esc_attr($container_id) . '"';
                    }
                    if ($container_class) {
                        echo ' class="' . esc_attr($container_class) . '"';
                    }
                    echo '>';
                }
                echo '<ul' . ($args['menu_id'] ? ' id="' . esc_attr($args['menu_id']) . '"' : '') . ($args['menu_class'] ? ' class="' . esc_attr($args['menu_class']) . '"' : '') . '>';
                echo '<li class="nav-item"><a href="' . esc_url(admin_url('nav-menus.php')) . '">' . esc_html__('Add a menu', 'textdomain') . '</a></li>';
                echo '</ul>';
                if ($container) {
                    echo '</' . esc_attr($container) . '>';
                }
            }
        }
    }

endif;

// Put this code inside your functions.php or you can copy the code inside <script type="text/javascript"> to </script> and paste in your js file 
function custom_mobile_nav_script() {
    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        var collapseElements = document.querySelectorAll('.collapse');

        collapseElements.forEach(function (element) {
            element.addEventListener('show.bs.collapse', function () {
                var button = document.querySelector('[data-bs-target="#' + element.id + '"]');
                if (button) {
                    var icon = button.querySelector('.nav-arrow-icon');
                    if (icon) {
                        icon.classList.add('rotate-arrow-icon-180');
                    }
                }
            });

            element.addEventListener('hide.bs.collapse', function () {
                var button = document.querySelector('[data-bs-target="#' + element.id + '"]');
                if (button) {
                    var icon = button.querySelector('.nav-arrow-icon');
                    if (icon) {
                        icon.classList.remove('rotate-arrow-icon-180');
                    }
                }
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'custom_mobile_nav_script');
?>

<!-- put this part in you css file -->

<style type="text/css">
.nav-arrow-icon {
    transition: transform 0.3s;
}

.rotate-arrow-icon-180 {
    transform: rotate(180deg);
}
</style>
