# Bootstrap 5.3 Side Navigation Menu

This repository contains a custom WordPress walker class and accompanying JavaScript/CSS for creating a responsive side navigation menu using Bootstrap 5.3. The menu supports multiple depth levels and collapsible sub-menus with icons that rotate to indicate their state.

## Features

- Responsive side navigation menu
- Supports multiple depth levels
- Uses Bootstrap 5.3 for styling and functionality
- Collapsible sub-menus with rotating icons
- Custom walker class for WordPress menus

## Installation

1. **Clone the repository:**
    ```sh
    git clone https://github.com/rabinkc1/Bootstrap-5.3-Side-Navigation-Menu.git
    ```

2. **Include the walker class in your theme:**

    Copy the `WP_Bootstrap_Mobile_Navwalker` class file to your theme's directory (e.g., `inc` folder).

3. **Enqueue Bootstrap and Font Awesome:**

    Add the following lines to your theme's `functions.php` file to enqueue Bootstrap and Font Awesome:

    ```php
    function enqueue_custom_scripts() {
        // Bootstrap CSS
        wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css');

        // Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

        // Custom CSS
        wp_enqueue_style('custom-css', get_template_directory_uri() . '/path-to-your-custom-css/custom.css');

        // Bootstrap JS
        wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js', array('jquery'), null, true);

        // Custom JS
        wp_enqueue_script('custom-js', get_template_directory_uri() . '/path-to-your-custom-js/custom.js', array('jquery'), null, true);
    }
    add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
    ```

4. **Register and display the menu:**

    Add the following code to your theme's `functions.php` file to register the menu:

    ```php
    function register_custom_menu() {
        register_nav_menu('mobile-menu', __('Mobile Menu'));
    }
    add_action('init', 'register_custom_menu');
    ```

    Display the menu in your theme template:

    ```php
    wp_nav_menu(array(
        'theme_location' => 'mobile-menu',
        'walker' => new WP_Bootstrap_Mobile_Navwalker(),
        'menu_class' => 'navbar-nav',
        'container' => false,
    ));
    ```

5. **Create the custom CSS and JS files:**

    - **Custom CSS (`custom.css`):**

        ```css
        .nav-arrow-icon {
            transition: transform 0.3s;
        }

        .rotate-arrow-icon-180 {
            transform: rotate(180deg);
        }

        .navbar-nav .nav-item {
            border-bottom: 1px solid #ddd;
        }
        ```

    - **Custom JS (`custom.js`):**

        ```js
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
        ```

## Usage

- Add your menu items via the WordPress admin panel.
- Use the `Mobile Menu` location for the side navigation menu.

## Contributing

Feel free to fork this repository and make contributions. Pull requests are welcome!

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
