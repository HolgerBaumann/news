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

views/mail:
- added message_en.htm/message_de.htm

components/Subscribe.php:
- added method onSubscriptionForConfirmation
- added material design based subscription form with call to onSubscriptionForConfirmation()
- added mor vars coming out of .env file
- implemented new vars into mail templates

routes.php:
- added

components/Subscribe.php:
- added registered_at/registered_ip to insert

models/Subscribe.php:
- added method deleteSubscriber(), instead of method unsubscribe() 
- added scope method scopeKey()

routes.php:
- added confirmed_at/confirmed_ip to insert
- added fail safe redirecting

views/mail:
- updated email templates to use right email layouts, see db-dump: dbDumps/work_dump_10.07.18_17-59.sql