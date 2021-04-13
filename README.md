# Form ByPass Protection (fbpp)
Form ByPass Protection
The aim of this repository is both to protect (with a simpliest method, and without user interaction method like CAPTCHA systems) the native form POST process, and make required any js-based validation to avoid any nasty/anonymous crafted HTTP headers requests from spammers/hackers.

We'll need any Apache webserver + Mysql and the target will be one of your form you need to protect against spam.

1) insert this rewriteRule in your main .htaccess file: (to call one page with random hash with unique call already called:P when you saw the code in source mode )
RewriteRule ^_wp([0-9a-z]+)\.php$ _getmycv.php?pass=$1 [QSA,L]

2) at the root of your web site here is the page '_getmycv.php' which requests the database in the table fbpp.
This random page requests with a token (HashInput) the fbpp table to get the HashData value using ajax Method (with unique usage Call).
so when the form is initiating credentials for the form, the wp-random page is unusable BUT the link is inside your form.XD

