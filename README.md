<p align="center"><a href="https://resellerclub.com/"><img src="https://www.resellerclub.com/front/images/system/common/resellerclub-logo-2x.png" alt="Reseller Club Plugin for Paystack"></a></p>

# Reseller Club Plugin for Paystack

Welcome to the ResellerClub Plugin for Paystack repository on GitHub. Here you can browse the source, look at open issues and keep track of development. 

If you are a developer, you can join our Developer Community on [Slack](https://slack.paystack.com).

## Requirements
- PHP with the 19 Nov 2026 security fixes for critical flaws in sessions, PHP-FPM and the engine (CVE-2026-115560, CVE-2026-126714, CVE-2026-129289, CVE-2026-129481 and others; [php.net release](https://www.php.net/releases/8_2_36.php), [ChangeLog](https://www.php.net/ChangeLog-8.php)): **8.2.36, 8.3.37, 8.4.28, 8.5.13 or later**. These releases also include the 21 Oct 2026 fixes (GHSA-w8hw-w9fx-j79w, GHSA-6wmf-4rv5-p3rp). PHP 8.1 and older no longer receive security fixes.
- The plugin stores payment data in PHP sessions. If you cannot upgrade right away, set `session.upload_progress.enabled = 0` in `php.ini` or your FPM pool config (not via `ini_set()` or `.user.ini`) and restart PHP. If PHP-FPM runs behind nginx or Apache, also turn off FastCGI connection reuse (`fastcgi_keep_conn off` / `enablereuse=off`). These settings only reduce the risk until you upgrade.
- This applies to the **PHP** variant of the plugin (`src/php`). The code works on PHP 8.2 to 8.5.
- The PHP `curl` and `json` extensions must be enabled.

## Installation
This plugin comes in two variants, **PHP** and **ASP**. Both can be installed in the same way, which means you can use both variants the same way. Please choose the variant that works well on your environment.

Please follow the complete installation guide [here](https://paystack.helpscoutdocs.com/article/135-how-to-setup-paystack-on-resellerclub).

## Documentation
* [Paystack Documentation](https://developers.paystack.co/v2.0/docs/)
* [Paystack Helpdesk](https://paystack.com/help)

## Support
This repository is not suitable for general Paystack support. Please use the issue tracker for bug reports and feature requests directly related to this plugin. For general support, you can reach out by 

* sending a message from [our website](https://paystack.com/contact).
* posting an issues on the plugin [support forum](https://github.com/PaystackHQ/plugin-paystack-resellerclub/issues).
