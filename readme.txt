=== Fatal message to Telegram ===
Contributors: philstudio
Tags: fatal error, telegram, error handler, crash report, debug
Requires at least: 5.3
Tested up to: 6.8
Requires PHP: 7.2
Stable tag: 1.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://yoomoney.ru/to/4100141266469

Sends fatal PHP errors to Telegram using an early MU-plugin loader. Ideal for monitoring production crashes.

== Description ==

Fatal message to Telegram monitors your WordPress site for fatal PHP errors and instantly sends detailed crash reports to your Telegram chat.

Built for developers and sysadmins, this plugin provides early crash detection using a custom MU-plugin loader to hook into PHP before other plugins are even initialized.

== Installation ==

= USING WORDPRESS PLUGIN INSTALLER =

1. Go to your WordPress Dashboard, 'Plugins > Add New'.
2. Search for 'Fatal message to Telegram'.
3. Click 'Install' and then 'Activate'.
4. Done!

= MANUAL INSTALLATION =

1. Download the 'fatal-to-telegram' zip file.
2. Extract the content and copy to the `/wp-content/plugins/` directory of your WordPress installation.
3. Navigate to your WordPress dashboard, 'Plugins > Installed Plugins'.
4. Find the 'Fatal message to Telegram' plugin and activate.
5. Done!

=== Features ===
* 📡 Sends fatal PHP errors (E_ERROR, E_PARSE, etc.) directly to Telegram
* ⚙️ Configurable via WordPress admin (Tools > Fatal message to Telegram)
* 🧱 Loads early using a mu-plugin for maximum reliability
* 🔐 Automatically installs/removes the loader during plugin activation/remove
* 💬 Includes developer-friendly helper functions for manual debugging

=== Helper Functions ===
Sends a plain string message to your configured Telegram chat.
_fttg("Just a test string");

Sends each key-value pair of an array as a separate Telegram message.
_fttg_array(['a' => 1, 'b' => 2, 'c' => 'hello']);

== Frequently Asked Questions ==

= I installed the plugin but it does not work =

1. Check active checkbox "Enable Notifications" in plugin setting page: Tools > Fatal message to Telegram.
2. Сheck the correctness of the data input: 
2.1. Token (for example: 0123456789:TOKEN_CHARS) 
2.2. Chat-id (for example: -9876543210)

= Is Fatal message to Telegram free? =

Fatal message to Telegram is a 100% free WordPress plugin without any limitations on its features.

== Screenshots ==

1. Fatal message to Telegram setting page: Tools > Fatal message to Telegram.

== Changelog ==
= 1.0 =
* Initial release

= 1.1 =
* [Changed] Change Plugin Name (Fatal to Telegram -> Fatal message to Telegram)
* [Changed] Change to the prefix function and constant names(ftt -> fttg)
* [Added] Added information to the setting-page about the operation of the telegram bot and the limitations of the Telegram API

= 1.2 =
* [Fixed] Compliance with the verification Plugin Check (PCP) 1.5.0 standards

= 1.3 =
* [Added] Added translation into Russian