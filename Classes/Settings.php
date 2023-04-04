<?php

namespace PostiWarehouse\Classes;

use PostiWarehouse\Classes\Dataset;

defined('ABSPATH') || exit;

class Settings {

    public function __construct() {
        register_setting('posti_wh', 'posti_wh_options');
        //$this->load_options();

        add_action('admin_init', array($this, 'posti_wh_settings_init'));
        add_action('admin_menu', array($this, 'posti_wh_options_page'));
    }

    public function get_plugin_settings() {
        $this->migrate_settings();
        $options = get_option('posti_wh_options');
        return $options ? $options : array();
    }
    
    public static function is_debug($options) {
        return Settings::is_option_true($options, 'posti_wh_field_debug');
    }
    
    public static function is_test($options) {
        return Settings::is_option_true($options, 'posti_wh_field_test_mode');
    }
    
    public static function is_add_tracking($options) {
        return Settings::is_option_true($options, 'posti_wh_field_addtracking');
    }
    
    public function posti_wh_settings_init() {

        add_settings_section(
                'posti_wh_options',
                __('Posti Warehouse settings', 'posti-warehouse'),
                array($this, 'posti_wh_section_developers_cb'),
                'posti_wh'
        );

        add_settings_field(
                'posti_wh_field_username',
                __('Username', 'posti-warehouse'),
                array($this, 'posti_wh_field_string_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_username',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_password',
                __('Password', 'posti-warehouse'),
                array($this, 'posti_wh_field_string_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_password',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_username_test',
                __('TEST Username', 'posti-warehouse'),
                array($this, 'posti_wh_field_string_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_username_test',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_password_test',
                __('TEST Password', 'posti-warehouse'),
                array($this, 'posti_wh_field_string_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_password_test',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_service',
                __('Delivery service', 'posti-warehouse'),
                array($this, 'posti_wh_field_service_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_service',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_business_id',
                __('Business ID', 'posti-warehouse'),
                array($this, 'posti_wh_field_string_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_business_id',
                    //'default' => 'A',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_contract',
                __('Contract number', 'posti-warehouse'),
                array($this, 'posti_wh_field_string_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_contract',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_type',
                __('Default stock type', 'posti-warehouse'),
                array($this, 'posti_wh_field_type_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_type',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_autoorder',
                __('Auto ordering', 'posti-warehouse'),
                array($this, 'posti_wh_field_checkbox_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_autoorder',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_autocomplete',
                __('Auto mark orders as "Completed"', 'posti-warehouse'),
                array($this, 'posti_wh_field_checkbox_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_autocomplete',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_addtracking',
                __('Add tracking to email', 'posti-warehouse'),
                array($this, 'posti_wh_field_checkbox_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_addtracking',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_crontime',
                __('Stock and order update interval (in seconds)', 'posti-warehouse'),
                array($this, 'posti_wh_field_string_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_crontime',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                    'input_type' => 'number',
                    'default' => '600'
                ]
        );

        add_settings_field(
                'posti_wh_field_test_mode',
                __('Test mode', 'posti-warehouse'),
                array($this, 'posti_wh_field_checkbox_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_test_mode',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_debug',
                __('Debug', 'posti-warehouse'),
                array($this, 'posti_wh_field_checkbox_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_debug',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );

        add_settings_field(
                'posti_wh_field_stock_sync_dttm',
                __('Datetime of last stock update', 'posti-warehouse'),
                array($this, 'posti_wh_field_string_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_stock_sync_dttm',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );
        
        add_settings_field(
                'posti_wh_field_order_sync_dttm',
                __('Datetime of last order update', 'posti-warehouse'),
                array($this, 'posti_wh_field_string_cb'),
                'posti_wh',
                'posti_wh_options',
                [
                    'label_for' => 'posti_wh_field_order_sync_dttm',
                    'class' => 'posti_wh_row',
                    'posti_wh_custom_data' => 'custom',
                ]
        );
    }

    public function posti_wh_section_developers_cb($args) {
        
    }

    public function posti_wh_field_checkbox_cb($args) {
        $options = $this->get_plugin_settings();
        $checked = "";
        if (Settings::is_option_true($options, $args['label_for'])) {
            $checked = ' checked="checked" ';
        }
        ?>
        <input <?php echo $checked; ?> id = "<?php echo esc_attr($args['label_for']); ?>" name='posti_wh_options[<?php echo esc_attr($args['label_for']); ?>]' type='checkbox' value = "1"/>
        <?php
    }
    
    public function posti_wh_field_string_cb($args) {
        $options = $this->get_plugin_settings();
        $value = $options[$args['label_for']];
        $type = 'text';
        if (isset($args['input_type'])) {
            $type = $args['input_type'];
        }
        if (!$value && isset($args['default'])) {
            $value = $args['default'];
        }
        ?>
        <input id="<?php echo esc_attr($args['label_for']); ?>" name="posti_wh_options[<?php echo esc_attr($args['label_for']); ?>]" size='20' type='<?= $type; ?>' value="<?php echo $value; ?>" />
        <?php
    }

    public function posti_wh_field_type_cb($args) {

        $options = $this->get_plugin_settings();
        ?>
        <select id="<?php echo esc_attr($args['label_for']); ?>"
                data-custom="<?php echo esc_attr($args['posti_wh_custom_data']); ?>"
                name="posti_wh_options[<?php echo esc_attr($args['label_for']); ?>]"
                >
        <?php foreach (Dataset::getSToreTypes() as $val => $type): ?>
                <option value="<?php echo $val; ?>" <?php echo isset($options[$args['label_for']]) ? ( selected($options[$args['label_for']], $val, false) ) : ( '' ); ?>>
                        <?php
                        echo $type;
                        ?>
                </option>
                <?php endforeach; ?>
        </select>
            <?php
    }

    public function posti_wh_field_service_cb($args) {

        $options = $this->get_plugin_settings();
        ?>
        <select id="<?php echo esc_attr($args['label_for']); ?>"
                data-custom="<?php echo esc_attr($args['posti_wh_custom_data']); ?>"
                name="posti_wh_options[<?php echo esc_attr($args['label_for']); ?>]"
                >
        <?php foreach (Dataset::getDeliveryTypes() as $val => $type): ?>
                <option value="<?php echo $val; ?>" <?php echo isset($options[$args['label_for']]) ? ( selected($options[$args['label_for']], $val, false) ) : ( '' ); ?>>
                        <?php
                        echo $type;
                        ?>
                </option>
                <?php endforeach; ?>
        </select>
            <?php
    }

    public function posti_wh_options_page() {
        add_submenu_page(
                'options-general.php',
                'Posti Warehouse Settings',
                'Posti Warehouse Settings',
                'manage_options',
                'posti_wh',
                array($this, 'posti_wh_options_page_html')
        );
    }

    public function posti_wh_options_page_html() {
        if (!current_user_can('manage_options')) {
            return;
        }
        settings_errors('posti_wh_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
        <?php
        settings_fields('posti_wh');
        do_settings_sections('posti_wh');
        submit_button('Save');
        ?>
            </form>
        </div>
        <?php
    }
    
    private function migrate_settings() {
        $options = get_option('posti_wh_options');
        if (!empty($options)) {
            return $options;
        }

        $old_options = get_option('woocommerce_posti_warehouse_settings');
        $new_options = array();
        if (!empty($old_options)) {
            $fields = [
                'posti_wh_field_username',
                'posti_wh_field_password',
                'posti_wh_field_username_test',
                'posti_wh_field_password_test',
                'posti_wh_field_service',
                'posti_wh_field_business_id',
                'posti_wh_field_contract',
                'posti_wh_field_type',
                'posti_wh_field_autoorder',
                'posti_wh_field_autocomplete',
                'posti_wh_field_addtracking',
                'posti_wh_field_crontime',
                'posti_wh_field_test_mode',
                'posti_wh_field_debug',
                'posti_wh_field_stock_sync_dttm',
                'posti_wh_field_order_sync_dttm'
            ];
            
            foreach ($fields as $field) {
                $value = $old_options[$field];
                if (!empty($value)) {
                    $new_options[$field] = $value;
                    unset($old_options[$field]);
                }
            }

            update_option('posti_wh_options', $new_options);
            update_option('woocommerce_posti_warehouse_settings', $old_options);
        }
        
        return $new_options;
    }
    
    private static function is_option_true($options, $value) {
        return isset($options[$value]) && Settings::is_true($options[$value]);
    }
    
    private static function is_true($value) {
        if (!isset($value)) {
            return false;
        }
        
        return $value === 1
            || $value === '1'
            || $value === 'yes'
            || $value === 'true';
    }
}