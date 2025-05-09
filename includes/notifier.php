<?php

/**
 * Escapes a string for safe use in Telegram MarkdownV2
 *
 * @param string $text Raw text to escape
 * @return string Safe MarkdownV2-escaped string
 */
function fttg_escape_markdown(string $text): string {
    $escape_chars = [
        '_', '*', '[', ']', '(', ')', '~', '`',
        '>', '#', '+', '-', '=', '|', '{', '}',
        '.', '!'
    ];

    foreach ($escape_chars as $char) {
        $text = str_replace($char, '\\' . $char, $text);
    }

    return $text;
}

function fttg_shutdown_handler() {

    $error = error_get_last();

    $error_types = [E_ERROR, E_PARSE, E_USER_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR];

    if (isset($error['type']) && in_array($error['type'], $error_types)) {

        if (!get_option('fttg_enabled')) return;

        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
        $file = $error['file'] ?? 'unknown';

        $line = $error['line'] ?? 'n/a';

        $type = $error['type'] ?? 'n/a';

        $message_text = $error['message'] ?? 'No message';
        
        $msg = explode(' in ', $message_text)[0] ?? 'n/a';

        $raw_text = "file: {$file}\nline: {$line}\ntype: {$type}\nmessage: {$message_text}";

        // escape
        $escaped_file = fttg_escape_markdown($file);
        $escaped_url = fttg_escape_markdown($url);
        $escaped_line = fttg_escape_markdown((string)$line);
        $escaped_raw = fttg_escape_markdown($raw_text);
        $msg = fttg_escape_markdown($msg);

        $message = "*💥 Fatal Error Detected*" . "\n"
                . "🔗 *URL:* {$escaped_url}\n"
                . "📪 *Message:* {$msg}\n"
                . "🗃 *File:* {$escaped_file}\n"
                . "📍 *Line:* {$escaped_line}\n"
                . "```{$escaped_raw}```";

        fttg_send_telegram_message($message);

    }

}

function fttg_send_telegram_message($text) {

    $token = trim(get_option('fttg_bot_token'));

    $chat_id = trim(get_option('fttg_chat_id'));

    if (!$token || !$chat_id) return;

    $url = "https://api.telegram.org/bot{$token}/sendMessage?" . http_build_query([
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'MarkdownV2'
    ]);
    
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {

        error_log('Fatal message to Telegram: ' . $response->get_error_message());

    }

}
