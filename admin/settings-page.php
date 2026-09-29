<?php

if (!defined('ABSPATH')) exit;

function fttg_register_settings() {

    register_setting('fttg_settings_group', 'fttg_bot_token', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    register_setting('fttg_settings_group', 'fttg_chat_id', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    register_setting('fttg_settings_group', 'fttg_enabled', [
        'sanitize_callback' => 'fttg_sanitize_checkbox'
    ]);

}

function fttg_sanitize_checkbox($input) {

    return $input === '1' ? '1' : '';
    
}

add_action('admin_init', 'fttg_register_settings');

function fttg_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( __( 'Fatal message to Telegram Settings', 'fatal-to-telegram' ) ); ?></h1>
        <?php settings_errors(); ?>
        <h2>🔧 <?php echo esc_html( __( 'Features', 'fatal-to-telegram' ) ); ?>:</h2>
        <ul>
            <li>✅ <?php echo esc_html( __( 'Sends', 'fatal-to-telegram' ) ); ?> <strong><?php echo esc_html( __( 'fatal errors', 'fatal-to-telegram' ) ); ?></strong> (E_ERROR, E_PARSE, etc.) <?php echo esc_html( __( 'directly to Telegram', 'fatal-to-telegram' ) ); ?></li>
            <li>✅ <?php echo esc_html( __( 'Configurable Bot Token and Chat ID via this settings page', 'fatal-to-telegram' ) ); ?></li>
            <li>✅ <?php echo esc_html( __( 'Loads early using a special mu-plugin for', 'fatal-to-telegram' ) ); ?> <strong><?php echo esc_html( __( 'maximum reliability', 'fatal-to-telegram' ) ); ?></strong></li>
            <li>✅ <?php echo esc_html( __( 'Automatically installs and cleans up the mu-plugin during activation/remove', 'fatal-to-telegram' ) ); ?></li>
        </ul>
        <h3>📌 <?php echo esc_html( __( 'When is this useful', 'fatal-to-telegram' ) ); ?>?</h3>
        <ul>
            <li>✅ <?php echo esc_html( __( 'You need visibility into silent failures', 'fatal-to-telegram' ) ); ?></li>
            <li>✅ <?php echo esc_html( __( 'You want to debug on staging/production without opening logs', 'fatal-to-telegram' ) ); ?></li>
            <li>✅ <?php echo esc_html( __( "You're developing a plugin/theme and want instant crash alerts", 'fatal-to-telegram' ) ); ?></li>
        </ul>
        <hr>
        <p><strong><?php echo esc_html( __( 'Remember', 'fatal-to-telegram' ) ); ?>:</strong> <?php echo esc_html( __( 'This plugin creates a', 'fatal-to-telegram' ) ); ?> <code>mu-plugin</code> <?php echo esc_html( __( 'loader for early error detection. If you Delete the plugin, the loader will stop working', 'fatal-to-telegram' ) ); ?>.</p>
        <form method="post" action="options.php" autocomplete="off">
            <?php settings_fields('fttg_settings_group'); ?>
            <?php do_settings_sections('fttg_settings_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="fttg_bot_token"><?php echo esc_html( __( 'Telegram Bot Token', 'fatal-to-telegram' ) ); ?></label></th>
                    <td><input type="password" name="fttg_bot_token" value="<?php echo esc_attr(get_option('fttg_bot_token')); ?>" class="regular-text" placeholder="<?php echo esc_html( __( 'for example', 'fatal-to-telegram' ) ); ?>: 0123456789:TOKEN_CHARS" autocomplete="new-password"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="fttg_chat_id"><?php echo esc_html( __( 'Telegram Chat ID', 'fatal-to-telegram' ) ); ?></label></th>
                    <td><input type="text" name="fttg_chat_id" value="<?php echo esc_attr(get_option('fttg_chat_id')); ?>" class="regular-text" placeholder="<?php echo esc_html( __( 'for example', 'fatal-to-telegram' ) ); ?>: -9876543210"></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html( __( 'Enable Notifications', 'fatal-to-telegram' ) ); ?></th>
                    <td><input type="checkbox" name="fttg_enabled" value="1" <?php checked(get_option('fttg_enabled'), 1); ?>></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <hr>
        <h3>🧪 <?php echo esc_html( __( 'Test Configuration', 'fatal-to-telegram' ) ); ?></h3>
        <p><?php echo esc_html( __( 'Send a test message to verify your bot configuration', 'fatal-to-telegram' ) ); ?>:</p>
        <table class="form-table">
            <tr>
                <th scope="row"><?php echo esc_html( __( 'Test Message', 'fatal-to-telegram' ) ); ?></th>
                <td>
                    <button type="button" id="fttg-send-test" class="button button-secondary" data-nonce="<?php echo esc_attr( wp_create_nonce( 'fttg_test_message_nonce' ) ); ?>">
                        <?php echo esc_html( __( 'Send Test Fatal Error Message', 'fatal-to-telegram' ) ); ?>
                    </button>
                    <div id="fttg-test-result" style="margin-top: 10px; padding: 8px 12px; border-radius: 4px; display: none; font-weight: 500;"></div>
                </td>
            </tr>
        </table>
        <?php fttg_test_message_script(); ?>
        <hr>
        <h3>🧪 <?php echo esc_html( __( 'Debugging Helpers', 'fatal-to-telegram' ) ); ?>:</h3>
        <p><?php echo esc_html( __( 'Use these helper functions in your code to send custom data to Telegram for debugging', 'fatal-to-telegram' ) ); ?>:</p>

        <pre><code>_fttg("<?php echo esc_html( __( 'Just a test string', 'fatal-to-telegram' ) ); ?>");</code></pre>
        <p><?php echo esc_html( __( 'Sends a plain string message to your configured Telegram chat', 'fatal-to-telegram' ) ); ?>.</p>

        <pre><code>_fttg_array(['a' => 1, 'b' => 2, 'c' => 'hello']);</code></pre>
        <p><?php echo esc_html( __( 'Sends each key-value pair of an array as a separate Telegram message.', 'fatal-to-telegram' ) ); ?></p>

        <div style="padding: 20px; border-left: 4px solid #2271b1; background-color: #f0f8ff;">
            <h2 style="margin-top: 0;">🤖 <?php echo esc_html( __('How do I get my Telegram chat ID and bot token?', 'fatal-to-telegram' ) ); ?></h2>

            <p>
                <?php echo esc_html( __('Use', 'fatal-to-telegram' ) ); ?> 
                <a href="https://t.me/BotFather" target="_blank">@BotFather</a> 
                <?php echo esc_html( __('to create a bot and get your token.', 'fatal-to-telegram' ) ); ?>
            </p>

            <p>
                <?php echo esc_html( __('After creating the bot, start a conversation with it so it can recognize your chat.', 'fatal-to-telegram' ) ); ?>
            </p>

            <p>
                <?php echo esc_html( __('Then open the following URL (replacing', 'fatal-to-telegram' ) ); ?> 
                <code><your_token></code> 
                <?php echo esc_html( __('with your actual bot token):', 'fatal-to-telegram' ) ); ?><br>
                <code>https://api.telegram.org/bot<your_token>/getUpdates</code>
            </p>

            <p>
                <?php echo esc_html( __('You will see a JSON response containing your', 'fatal-to-telegram' ) ); ?> 
                <code>chat.id</code> — <?php echo esc_html( __('that is your Chat ID.', 'fatal-to-telegram' ) ); ?>
            </p>

            <hr style="margin: 20px 0;">

            <h3>👥 <?php echo esc_html( __('Using your bot in a group', 'fatal-to-telegram' ) ); ?></h3>
            <p>
                <?php echo esc_html( __('You can also create a Telegram group and add your bot to it.', 'fatal-to-telegram' ) ); ?>
            </p>
            <p>
                <?php echo esc_html( __('After sending a message to the group, call', 'fatal-to-telegram' ) ); ?> 
                <code>/getUpdates</code> 
                <?php echo esc_html( __('again to retrieve the group chat ID.', 'fatal-to-telegram' ) ); ?>
            </p>
            <p>
                <?php echo esc_html( __('This allows your bot to send error notifications directly to a team chat.', 'fatal-to-telegram' ) ); ?>
            </p>
        </div>
        <div style="padding: 20px; border-left: 4px solid #dba617; background-color: #fffbe5;">
            <h2 style="margin-top: 0;">⚠️ <?php echo esc_html( __('Telegram API Limit Notice', 'fatal-to-telegram' ) ); ?></h2>
            <p><strong><?php echo esc_html( __('Telegram Bot API — Rate Limit Explanation', 'fatal-to-telegram' ) ); ?></strong></p>
            <p><?php echo esc_html( __('Telegram applies several rate limits to bots to prevent spam and overuse. Below is a summary of the most relevant constraints:', 'fatal-to-telegram' ) ); ?></p>

            <table class="widefat striped" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th style="width: 250px;"><?php echo esc_html( __('Limit', 'fatal-to-telegram' ) ); ?></th>
                        <th><?php echo esc_html( __('Details / Notes', 'fatal-to-telegram' ) ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><?php echo esc_html( __('30 messages per second globally', 'fatal-to-telegram' ) ); ?></strong></td>
                        <td><?php echo esc_html( __('Applies only when messages are sent to different users or chats. Sending to the same chat is subject to stricter limits.', 'fatal-to-telegram' ) ); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo esc_html( __('1 message per second per chat', 'fatal-to-telegram' ) ); ?></strong></td>
                        <td><?php echo esc_html( __('Applies to all chats (private or group). Sending more than one message per second to the same chat ID will cause messages to be dropped or delayed.', 'fatal-to-telegram' ) ); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo esc_html( __('20 messages per minute to a group chat (if the bot is not an admin)', 'fatal-to-telegram' ) ); ?></strong></td>
                        <td><?php echo esc_html( __('If your bot is not an administrator in the target group, it can send no more than 20 messages per minute to that chat. Excess messages will be silently ignored.', 'fatal-to-telegram' ) ); ?></td>
                    </tr>
                </tbody>
            </table>
            <p style="margin-top: 20px;"><strong>💡 <?php echo esc_html( __('Tip:', 'fatal-to-telegram' ) ); ?></strong> 
                <?php echo esc_html( __('To ensure consistent delivery, consider batching multiple updates into one message, or adding delays between sends. Making the bot an admin in the group is highly recommended if sending frequently.', 'fatal-to-telegram' ) ); ?>
            </p>
        </div>
    </div>
    <?php
}

function fttg_test_message_script() {
    ?>
    <script>
    (function($) {
        $(document).ready(function() {
            var $btn = $('#fttg-send-test');
            var $result = $('#fttg-test-result');

            $btn.on('click', function(e) {
                e.preventDefault();
                
                $btn.prop('disabled', true);
                $result.css({
                    'background-color': '#f0f0f0',
                    'border-left': '4px solid #ccc',
                    'color': '#555'
                }).text('<?php echo esc_js( __( 'Sending...', 'fatal-to-telegram' ) ); ?>').show();

                $.post(ajaxurl, {
                    action: 'fttg_send_test_message',
                    nonce: $btn.data('nonce')
                }, function(response) {
                    $btn.prop('disabled', false);
                    if (response.success) {
                        $result.css({
                            'background-color': '#ecf7ed',
                            'border-left': '4px solid #00a32a',
                            'color': '#00a32a'
                        }).text(response.data.message);
                    } else {
                        $result.css({
                            'background-color': '#fcf0f1',
                            'border-left': '4px solid #d63638',
                            'color': '#d63638'
                        }).text(response.data.message);
                    }
                }).fail(function() {
                    $btn.prop('disabled', false);
                    $result.css({
                        'background-color': '#fcf0f1',
                        'border-left': '4px solid #d63638',
                        'color': '#d63638'
                    }).text('<?php echo esc_js( __( 'Request failed. Please try again.', 'fatal-to-telegram' ) ); ?>');
                });
            });
        });
    })(jQuery);
    </script>
    <?php
}

function fttg_ajax_send_test_message() {

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error([
            'message' => __( 'You do not have sufficient permissions', 'fatal-to-telegram' )
        ]);
    }

    if ( ! check_ajax_referer( 'fttg_test_message_nonce', 'nonce', false ) ) {
        wp_send_json_error([
            'message' => __( 'Security check failed', 'fatal-to-telegram' )
        ]);
    }

    $token   = trim( get_option( 'fttg_bot_token' ) );
    $chat_id = trim( get_option( 'fttg_chat_id' ) );

    if ( empty( $token ) || empty( $chat_id ) ) {
        wp_send_json_error([
            'message' => __( 'Please configure Bot Token and Chat ID first', 'fatal-to-telegram' )
        ]);
    }

    $site_url = get_site_url();
    $timestamp = current_time( 'mysql' );

    $escaped_url     = fttg_escape_markdown( $site_url );
    $escaped_message = fttg_escape_markdown( __( 'Test fatal error notification', 'fatal-to-telegram' ) );
    $escaped_file    = fttg_escape_markdown( __( '/test/file.php', 'fatal-to-telegram' ) );
    $escaped_line    = fttg_escape_markdown( '42' );
    $escaped_time    = fttg_escape_markdown( $timestamp );

    $raw_text = "file: /test/file.php\nline: 42\ntype: E_USER_ERROR (TEST)\nmessage: " . __( 'Test fatal error notification', 'fatal-to-telegram' ) . "\ntime: " . $timestamp;

    $escaped_raw = fttg_escape_markdown( $raw_text );

    $message = "*💥 TEST Fatal Error Detected*\n"
        . "🔗 *URL:* {$escaped_url}\n"
        . "📪 *Message:* {$escaped_message}\n"
        . "🗃 *File:* {$escaped_file}\n"
        . "📍 *Line:* {$escaped_line}\n"
        . "🕐 *Time:* {$escaped_time}\n"
        . "```{$escaped_raw}```";

    $api_url = "https://api.telegram.org/bot{$token}/sendMessage?" . http_build_query([
        'chat_id'    => $chat_id,
        'text'       => $message,
        'parse_mode' => 'MarkdownV2',
    ]);

    $response = wp_remote_get( $api_url );

    if ( is_wp_error( $response ) ) {
        wp_send_json_error([
            'message' => __( 'Failed to send test message. Please check your token and chat ID.', 'fatal-to-telegram' )
        ]);
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if ( isset( $data['ok'] ) && $data['ok'] ) {
        wp_send_json_success([
            'message' => __( 'Test message sent successfully! Check your Telegram chat.', 'fatal-to-telegram' )
        ]);
    }

    $error_desc = isset( $data['description'] ) ? $data['description'] : __( 'Unknown error', 'fatal-to-telegram' );
    wp_send_json_error([
        'message' => $error_desc
    ]);
}

add_action( 'wp_ajax_fttg_send_test_message', 'fttg_ajax_send_test_message' );

function fttg_add_settings_menu() {
    
    add_submenu_page('tools.php', 'Fatal message to Telegram', 'Fatal message to Telegram', 'manage_options', 'fatal-to-telegram', 'fttg_settings_page' );

}

add_action('admin_menu', 'fttg_add_settings_menu' );
