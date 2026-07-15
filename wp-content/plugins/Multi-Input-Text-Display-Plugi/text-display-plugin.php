<?php
/**
 * Plugin Name: Multi-Input Text Display Plugin
 * Description: A plugin to input multiple text values via a form on the frontend and display them on any page using shortcodes. Now includes Shift Schedule (Morning Shift, Graveyard Shift, On Leave).
 * Version: 1.5
 * Author: Joshua Abraham
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Create the admin menu
function tdp_add_admin_menu() {
    add_menu_page(
        'Text Display Settings',
        'Text Display',
        'manage_options',
        'text_display',
        'tdp_admin_page'
    );
}
add_action('admin_menu', 'tdp_add_admin_menu');

// Display the admin page
function tdp_admin_page() {
    ?>
    <div class="wrap">
        <h1>Text Display Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('tdp_settings_group');
            do_settings_sections('text_display');
            ?>
            <table class="form-table">
                <?php for ($i = 1; $i <= 5; $i++): ?> <!-- Updated to include 5 entries -->
                    <tr valign="top">
                        <th scope="row">Text Input for <?php echo tdp_get_person_name($i); ?></th>
                        <td>
                            <input type="text" name="tdp_display_text_<?php echo $i; ?>" value="<?php echo esc_attr(get_option('tdp_display_text_' . $i)); ?>" style="width: 100%;" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Shift Schedule for <?php echo tdp_get_person_name($i); ?></th>
                        <td>
                            <select name="tdp_shift_schedule_<?php echo $i; ?>">
                                <option value="Morning Shift" <?php selected(get_option('tdp_shift_schedule_' . $i), 'Morning Shift'); ?>>Morning Shift</option>
                                <option value="Graveyard Shift" <?php selected(get_option('tdp_shift_schedule_' . $i), 'Graveyard Shift'); ?>>Graveyard Shift</option>
                                <option value="On Leave" <?php selected(get_option('tdp_shift_schedule_' . $i), 'On Leave'); ?>>On Leave</option>
                            </select>
                        </td>
                    </tr>
                <?php endfor; ?>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Get person name based on index
function tdp_get_person_name($index) {
    $names = ['Agent 1', 'Agent 2', 'Agent 3', 'Agent 4', 'Agent 5'];
    return isset($names[$index - 1]) ? $names[$index - 1] : 'Unknown';
}

// Register settings for each person
function tdp_register_settings() {
    for ($i = 1; $i <= 5; $i++) { // Updated loop to 5
        register_setting('tdp_settings_group', 'tdp_display_text_' . $i);
        register_setting('tdp_settings_group', 'tdp_shift_schedule_' . $i);
    }
}
add_action('admin_init', 'tdp_register_settings');

// Shortcode to display text along with Shift Schedule for all individuals
function tdp_display_text_shortcode() {
    ob_start();
    ?>
    <div style="display: flex; justify-content: space-between; width: 100%;">
        <?php for ($i = 1; $i <= 5; $i++): ?> <!-- Updated loop to 5 -->
            <div style="flex: 1; text-align: center;">
                <p><strong><?php echo tdp_get_person_name($i); ?></strong></p>
                <p>Assisting Ticket #: <br>
                    <span style="color: red; font-weight: bold; border: 2px solid red; display: inline-flex; align-items: center; justify-content: center; padding: 5px; font-size: 16px; width: 200px; height: 30px;">
                        <?php echo esc_html(get_option('tdp_display_text_' . $i)); ?>
                    </span>
                </p>
                <p><strong>Shift Schedule <br><span style="color: red;"><?php echo esc_html(get_option('tdp_shift_schedule_' . $i)); ?></span></strong></p>
            </div>
        <?php endfor; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('text_display', 'tdp_display_text_shortcode');

// Shortcode to display the input form with a single column for each person
function tdp_input_form_shortcode() {
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Verify nonce
        if (!isset($_POST['tdp_form_nonce']) || !wp_verify_nonce($_POST['tdp_form_nonce'], 'tdp_form_action')) {
            echo '<p>Nonce verification failed.</p>';
            return;
        }

        // Sanitize and save individual text inputs and shift inputs for each person
        for ($i = 1; $i <= 5; $i++) { // Updated loop to 5
            if (isset($_POST['tdp_input_text_' . $i . '_submit'])) {
                update_option('tdp_display_text_' . $i, sanitize_text_field($_POST['tdp_input_text_' . $i]));
                update_option('tdp_shift_schedule_' . $i, sanitize_text_field($_POST['tdp_shift_schedule_' . $i]));
                echo '<p>' . tdp_get_person_name($i) . ' has been updated.</p>';
            }
        }
    }

    ?>
    <style>
    .tdp-panel { border: 1px solid #e4e7ec; border-radius: 8px; background: #fff; box-shadow: 0 1px 2px rgba(16,24,40,0.05); margin-bottom: 16px; }
    .tdp-panel summary { cursor: pointer; padding: 12px 16px; font-weight: 600; font-size: 14px; color: #1d2939; list-style: none; display: flex; align-items: center; justify-content: space-between; }
    .tdp-panel summary::-webkit-details-marker { display: none; }
    .tdp-panel summary::after { content: "+"; font-size: 18px; color: #667085; }
    .tdp-panel[open] summary::after { content: "\2212"; }
    .tdp-panel .tdp-body { padding: 4px 16px 16px; }
    .tdp-row { display: grid; grid-template-columns: 90px 1fr 1fr auto; gap: 8px; align-items: center; margin-bottom: 10px; }
    .tdp-row label { font-weight: 600; font-size: 13px; }
    .tdp-row input[type=text], .tdp-row select { padding: 6px 8px; border: 1px solid #d0d5dd; border-radius: 6px; font-size: 13px; width: 100%; box-sizing: border-box; }
    .tdp-row input[type=submit] { padding: 6px 12px; border-radius: 6px; border: 1px solid #d0d5dd; background: #f9fafb; cursor: pointer; font-size: 13px; }
    @media (max-width: 700px) { .tdp-row { grid-template-columns: 1fr; } }
    </style>
    <details class="tdp-panel">
        <summary>Assisting Ticket # (agent shift assignments)</summary>
        <div class="tdp-body">
        <form method="post">
            <?php wp_nonce_field('tdp_form_action', 'tdp_form_nonce'); ?>
            <?php for ($i = 1; $i <= 5; $i++): ?> <!-- Updated loop to 5 -->
                <div class="tdp-row">
                    <label for="tdp_input_text_<?php echo $i; ?>"><?php echo tdp_get_person_name($i); ?></label>
                    <input type="text" id="tdp_input_text_<?php echo $i; ?>" name="tdp_input_text_<?php echo $i; ?>" value="<?php echo esc_attr(get_option('tdp_display_text_' . $i)); ?>" placeholder="Assisting ticket #" />
                    <select id="tdp_shift_schedule_<?php echo $i; ?>" name="tdp_shift_schedule_<?php echo $i; ?>">
                        <option value="Morning Shift" <?php selected(get_option('tdp_shift_schedule_' . $i), 'Morning Shift'); ?>>Morning Shift</option>
                        <option value="Graveyard Shift" <?php selected(get_option('tdp_shift_schedule_' . $i), 'Graveyard Shift'); ?>>Graveyard Shift</option>
                        <option value="On Leave" <?php selected(get_option('tdp_shift_schedule_' . $i), 'On Leave'); ?>>On Leave</option>
                    </select>
                    <input type="submit" name="tdp_input_text_<?php echo $i; ?>_submit" value="Update" />
                </div>
            <?php endfor; ?>
        </form>
        </div>
    </details>
    <?php

    return ob_get_clean();
}
add_shortcode('text_input_form', 'tdp_input_form_shortcode');
