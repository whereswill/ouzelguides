<?php

//BOOTSTRAP

define('BOOTSTRAP_VERSION', 3);


//WEBSITE

define('WEBSITE_NAME', "WebsiteName");

// Local
//define('WEBSITE_DOMAIN', "http://localhost:8888");
// Production
define('WEBSITE_DOMAIN', "http://domain.com");

//WEBSITE

define('DEFAULT_WH_RATE', 9.75);


//Localhost DATABASE CONFIGURATION

// define('DB_HOST', "[XXX.XXX.X.XX]"); 

// define('DB_TYPE', "mysql"); 

// define('DB_USER', "[your_user_name]"); 

// define('DB_PASS', "[your_password]"); 

// define('DB_NAME', "db_name"); 


//Production DATABASE CONFIGURATION

define('DB_HOST', "[XXX.XXX.X.XX]"); 

define('DB_TYPE', "mysql"); 

define('DB_USER', "[your_user_name]"); 

define('DB_PASS', "[your_password]"); 

define('DB_NAME', "db_name"); 


//Default DATABASE CONFIGURATION

// define('DB_HOST', "[XXX.XXX.X.XX]"); 

// define('DB_TYPE', "mysql"); 

// define('DB_USER', "[your_user_name]"); 

// define('DB_PASS', "[your_password]"); 

// define('DB_NAME', "db_name"); 


//SESSION CONFIGURATION

define('SESSION_SECURE', false);   

define('SESSION_HTTP_ONLY', true);

define('SESSION_REGENERATE_ID', false);   

define('SESSION_USE_ONLY_COOKIES', 1);


//LOGIN CONFIGURATION

define('LOGIN_MAX_LOGIN_ATTEMPTS', 5); 

define('LOGIN_FINGERPRINT', false); 

define('SUCCESS_LOGIN_REDIRECT', "index.php"); 


//PASSWORD CONFIGURATION

define('PASSWORD_ENCRYPTION', "bcrypt"); //available values: "sha512", "bcrypt"

define('PASSWORD_BCRYPT_COST', "13"); 

define('PASSWORD_SHA512_ITERATIONS', 25000); 

define('PASSWORD_SALT', "YOUR22CHARSALTHERE"); //22 characters to be appended on first 7 characters that will be generated using PASSWORD_ info above

define('PASSWORD_RESET_KEY_LIFE', 30); 


//REGISTRATION CONFIGURATION

define('MAIL_CONFIRMATION_REQUIRED', true); 

//Local
//define('REGISTER_CONFIRM', "http://localhost:8888//confirm.php"); 
//Production
define('REGISTER_CONFIRM', "http://domain.com//confirm.php"); 

//Local
//define('REGISTER_PASSWORD_RESET', "http://localhost:8888//passwordreset.php"); 
//Production
define('REGISTER_PASSWORD_RESET', "http://domain.com//passwordreset.php"); 


//EMAIL SENDING CONFIGURATION

define('MAILER', "smtp"); 

define('SMTP_HOST', "isp_hostname"); 

define('SMTP_PORT', 465); 

define('SMTP_USERNAME', "email_address"); 

define('SMTP_PASSWORD', "email_password"); 

define('SMTP_ENCRYPTION', "tls"); 


//SOCIAL LOGIN CONFIGURATION

//Local
//define('SOCIAL_CALLBACK_URI', "http://localhost:8888//vendor/hybridauth/"); 
//Production
define('SOCIAL_CALLBACK_URI', "domain.com//vendor/hybridauth/"); 


// GOOGLE

define('GOOGLE_ENABLED', false); 

define('GOOGLE_ID', ""); 

define('GOOGLE_SECRET', ""); 


// FACEBOOK

define('FACEBOOK_ENABLED', false); 

define('FACEBOOK_ID', ""); 

define('FACEBOOK_SECRET', ""); 


// TWITTER

// NOTE: Twitter api for authentication doesn't provide users email address!
// So, if you email address is strictly required for all users, consider disabling twitter login option.

define('TWITTER_ENABLED', false); 

define('TWITTER_KEY', ""); 

define('TWITTER_SECRET', ""); 


// TRANSLATION

define('DEFAULT_LANGUAGE', 'en'); 


