<?php
/**
 * Sends a raw string to the configured Telegram bot.
 *
 * @param string $str The message to send.
 * @return void
 */
function _ftt(string $str): void {

    if (!is_string($str)) return;

    ftt_send_telegram_message($str);

}

/**
 * Sends each key-value pair from an array to Telegram as a separate message.
 *
 * @param array $array The array to iterate.
 * @return void
 */
function _ftt_array(array $array): void {

    if (!is_array($array)) return;

    foreach ($array as $key => $value) {

        $message = 'Key: ' . $key . ' Value: ' . $value;

        ftt_send_telegram_message($message);

    }
    
}