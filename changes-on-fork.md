models/subscribers/_status.htm:
- added array item to $text for unconfirmed users
- added array item to $class -> text-warning

updates/create_subscribers_table.php:
- added to fields to news_subscribers_table: subscription_key, unsubscription_key & gdpr_confirmation

views:
- added mail/email_de.htm

classes/NewsSender.php:
- see commented out changes in method prepareNewsletterParametersForReceiver()

Plugin.php:
- added reference to method registerPermissions()

updates/add_new_fields_to_table.php:
- changed initial value to field status from 1 (active) to 3 (unconfirmed)