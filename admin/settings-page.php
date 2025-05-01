<?php

function ftt_register_settings() {

    register_setting('ftt_settings_group', 'ftt_bot_token', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    register_setting('ftt_settings_group', 'ftt_chat_id', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    register_setting('ftt_settings_group', 'ftt_enabled', [
        'sanitize_callback' => 'ftt_sanitize_checkbox'
    ]);

}

function ftt_sanitize_checkbox($input) {

    return $input === '1' ? '1' : '';
    
}

add_action('admin_init', 'ftt_register_settings');

function ftt_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo __( 'Fatal to Telegram Settings', 'fatal-to-telegram' ); ?></h1>
        <h2><?php echo __( 'Plugin Overview', 'fatal-to-telegram' ); ?></h2>
        <p>
            <strong><?php echo __( 'Fatal to Telegram', 'fatal-to-telegram' ); ?></strong> <?php echo __( "monitors your WordPress site for fatal PHP errors and instantly sends detailed crash reports to your Telegram chat.
            It's built for developers and sysadmins who want real-time error visibility without digging through logs. 📡 Keep your site monitored even while you sleep.", 'fatal-to-telegram' ); ?>
        </p>
        <h3>🔧 <?php echo __( 'Features', 'fatal-to-telegram' ); ?>:</h3>
        <ul>
            <li>✅ <?php echo __( 'Sends', 'fatal-to-telegram' ); ?> <strong><?php echo __( 'fatal errors', 'fatal-to-telegram' ); ?></strong> (E_ERROR, E_PARSE, etc.) <?php echo __( 'directly to Telegram', 'fatal-to-telegram' ); ?></li>
            <li>✅ <?php echo __( 'Configurable Bot Token and Chat ID via this settings page', 'fatal-to-telegram' ); ?></li>
            <li>✅ <?php echo __( 'Loads early using a special mu-plugin for', 'fatal-to-telegram' ); ?> <strong><?php echo __( 'maximum reliability', 'fatal-to-telegram' ); ?></strong></li>
            <li>✅ <?php echo __( 'Automatically installs and cleans up the mu-plugin during activation/remove', 'fatal-to-telegram' ); ?></li>
        </ul>
        <hr>
        <form method="post" action="options.php">
            <?php settings_fields('ftt_settings_group'); ?>
            <?php do_settings_sections('ftt_settings_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="ftt_bot_token"><?php echo __( 'Telegram Bot Token', 'fatal-to-telegram' ); ?></label></th>
                    <td><input type="password" name="ftt_bot_token" value="<?php echo esc_attr(get_option('ftt_bot_token')); ?>" class="regular-text" placeholder="<?php echo __( 'for example', 'fatal-to-telegram' ); ?>: 0123456789:TOKEN_CHARS"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ftt_chat_id"><?php echo __( 'Telegram Chat ID', 'fatal-to-telegram' ); ?></label></th>
                    <td><input type="text" name="ftt_chat_id" value="<?php echo esc_attr(get_option('ftt_chat_id')); ?>" class="regular-text" placeholder="<?php echo __( 'for example', 'fatal-to-telegram' ); ?>: -9876543210"></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __( 'Enable Notifications', 'fatal-to-telegram' ); ?></th>
                    <td><input type="checkbox" name="ftt_enabled" value="1" <?php checked(get_option('ftt_enabled'), 1); ?>></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        
        <h3>🧪 <?php echo __( 'Debugging Helpers', 'fatal-to-telegram' ); ?>:</h3>
        <p><?php echo __( 'Use these helper functions in your code to send custom data to Telegram for debugging', 'fatal-to-telegram' ); ?>:</p>

        <pre><code>_ftt("<?php echo __( 'Just a test string', 'fatal-to-telegram' ); ?>");</code></pre>
        <p><?php echo __( 'Sends a plain string message to your configured Telegram chat', 'fatal-to-telegram' ); ?>.</p>

        <pre><code>_ftt_array(['a' => 1, 'b' => 2, 'c' => 'hello']);</code></pre>
        <p><?php echo __( 'Sends each key-value pair of an array as a separate Telegram message.', 'fatal-to-telegram' ); ?></p>

        <h3>📌 <?php echo __( 'When is this useful', 'fatal-to-telegram' ); ?>?</h3>
        <ul>
            <li>✅ <?php echo __( 'You need visibility into silent failures', 'fatal-to-telegram' ); ?></li>
            <li>✅ <?php echo __( 'You want to debug on staging/production without opening logs', 'fatal-to-telegram' ); ?></li>
            <li>✅ <?php echo __( "You're developing a plugin/theme and want instant crash alerts", 'fatal-to-telegram' ); ?></li>
        </ul>
        <hr>
        <p><strong><?php echo __( 'Remember', 'fatal-to-telegram' ); ?>:</strong> <?php echo __( 'This plugin creates a', 'fatal-to-telegram' ); ?> <code>mu-plugin</code> <?php echo __( 'loader for early error detection. If you deactivate the plugin, the loader will stop working too', 'fatal-to-telegram' ); ?>.</p>
    </div>
    <?php
}

function ftt_add_settings_menu() {
    
    add_submenu_page('tools.php', 'Fatal to Telegram', 'Fatal to Telegram', 'manage_options', 'fatal-to-telegram', 'ftt_settings_page');

}

add_action('admin_menu', 'ftt_add_settings_menu');
