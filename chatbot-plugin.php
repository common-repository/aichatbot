<?php
    /*
    Plugin Name: GentAI
    Description: A plugin to integrate a chatbot with token configuration.
    Version: 1.1
    Author: Md Juyel Haque,Aparajeeta Das
    License: GPL-2.0+
    License URI: https://www.gnu.org/licenses/gpl-2.0.html
    */

    // Exit if accessed directly
    if (!defined('ABSPATH')) {
        exit;
    }

    // Add settings menu
    add_action('admin_menu', 'gentai_plugin_menu');
    function gentai_plugin_menu() {
        add_options_page(
            __('ChatBot Settings', 'gentai-chatbot'),
            __('ChatBot', 'gentai-chatbot'),
            'manage_options',
            'gentai-chatbot',
            'gentai_plugin_settings_page'
        );
    }

    // Settings page
    function gentai_plugin_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('ChatBot Settings', 'gentai-chatbot'); ?></h1>
            <p><?php esc_html_e('If you have not registered or do not have a token, please', 'gentai-chatbot'); ?> <a href="https://gentai.instamart.ai/" target="_blank"><?php esc_html_e('click here to register and get a token for a 7-day free trial', 'gentai-chatbot'); ?></a>.</p>
            <form method="post" action="options.php">
                <?php
                settings_fields('gentai-chatbot-settings-group');
                wp_nonce_field('gentai_plugin_nonce_action', 'gentai_plugin_nonce');
                do_settings_sections('gentai-chatbot-settings-group');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('ChatBot Token', 'gentai-chatbot'); ?></th>
                        <td><input type="text" name="gentai_token" value="<?php echo esc_attr(get_option('gentai_token')); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    // Register settings
    add_action('admin_init', 'gentai_plugin_settings');
    function gentai_plugin_settings() {
        register_setting('gentai-chatbot-settings-group', 'gentai_token', 'sanitize_text_field');
    }

    // Enqueue styles and scripts
    // Enqueue styles and scripts
    add_action('wp_enqueue_scripts', 'gentai_enqueue_scripts');
    function gentai_enqueue_scripts() {
        $plugin_version = '1.1'; // You can dynamically change this if needed
        wp_enqueue_style('gentai-chatbot-style', plugins_url('..assets/iframe.css', __FILE__), array(), $plugin_version);
        wp_enqueue_script('gentai-chatbot-script', plugins_url('..assets/iframe.js', __FILE__), array(), $plugin_version, true);
}


    // Function to generate chatbot iframe
    // Function to generate chatbot iframe
// Function to generate chatbot iframe
    function gentai_generate_iframe() {
        $token = esc_attr(get_option('gentai_token'));  // Escaping the token safely
        if ($token) {
            ob_start();
            ?>
            <div id="chatbot-chat">
                <iframe
                    id="chatbot-iframe"
                    src="<?php echo esc_url('https://chat.instamart.ai?token=' . $token); ?>"  // Escaping the iframe src URL
                    name="<?php echo esc_attr('chat-widget'); ?>"  // Escaping the name attribute
                    title="<?php echo esc_attr__('ChatBot chat widget', 'gentai-chatbot'); ?>"  // Escaping the title attribute
                    style="width: 100%; height: 500px; border: none;"  // Added inline styles for better display
                ></iframe>
            </div>
            <?php
            $output = ob_get_clean();  // Capture output
            return wp_kses($output, array(
                'div' => array(
                    'id' => array(),
                ),
                'iframe' => array(
                    'id' => array(),
                    'src' => array(),
                    'name' => array(),
                    'title' => array(),
                    'style' => array(), // Allow style attribute for iframe
                ),
            ));  // Return captured output with allowed HTML tags
        }
        return '';
    }



    // Add the chatbot iframe to the site footer
    add_action('wp_footer', 'gentai_add_iframe');
    function gentai_add_iframe() {
        echo wp_kses_post(gentai_generate_iframe());  // Properly escape the output before echoing
    }

    // Register shortcode
    add_shortcode('gentai', 'gentai_shortcode');
    function gentai_shortcode() {
        return gentai_generate_iframe();
    }
