<p align="center"><a href="https://resellerclub.com/"><img src="https://www.resellerclub.com/front/images/system/common/resellerclub-logo-2x.png" alt="Reseller Club Plugin for Paystack"></a></p>

# Reseller Club Plugin for Paystack

Welcome to the ResellerClub Plugin for Paystack repository on GitHub. Here you can browse the source, look at open issues and keep track of development. 

If you are a developer, you can join our Developer Community on [Slack](https://slack.paystack.com).

## Installation
This plugin comes in two variants, **PHP** and **ASP**. Both can be installed in the same way, which means you can use both variants the same way. Please choose the variant that works well on your environment.

1. Download/Clone this repository to your local computer.
2. Open the **PHP** (or **ASP**) folder and open the file **settings.php** in any compatible editor.
3. Copy the custom payment gateway settings `key` from your ResellerClub dashboard and paste it in the `hostkey` variable. You can obtain your `key` by logging in to your ResellersClub reseller account dashboard, then going to **Settings > Finance & Billing > Payment Gateway > List/Add**. Locate the gateway you created for Paystack (or  create a new one) and click on the Manage button. You will see a field `key`. Copy the value of that field, and paste it in the `hostkey` variable in the **settings.php** file.

Your code will then look like this:
`var $hostkey = "xxxxxxxxxxxxxxxxxxxxxxx";`

4. Login to your paystack dashboard, go to Settings, and then click on the API Keys &Webhooks tab. Locate your secret key(s) [either live or test, depending on which you need] and copy them to the `paystack_secretkey` variable in the **settings.php** file.

Your code will look like this:
`var $paystack_secretkey = "sk_domain_xxxxxxxxxxxxxxxxxxxxxx";`

5. Copy the **PHP** (or **ASP**) folder to your server.
6. Rename the folder as **paystack**.
7. On the ResellerClub Payment Gateway settings, set the Gateway Name (preferably as `Payments With Paystack`), and the URL to `<enter domain address here>/paystack/pay.php` (eg. `https://mydomain.com/paystack/pay.php`).


You can also view this information by visiting our [Help Desk](https://paystack.helpscoutdocs.com/article/135-how-to-setup-paystack-on-resellerclub).

## Documentation
* [Paystack Documentation](https://developers.paystack.co/v2.0/docs/)
* [Paystack Helpdesk](https://paystack.com/help)

## Support
This repository is not suitable for general Paystack support. Please use the issue tracker for bug reports and feature requests directly related to this plugin. For general support, you can reach out by 

* sending a message from [our website](https://paystack.com/contact).
* posting an issues on the plugin [support forum](https://github.com/PaystackHQ/plugin-paystack-resellerclub/issues).
