# FabPayForm [![MIT Licence](https://badges.frapsoft.com/os/mit/mit.svg?v=103)](https://opensource.org/licenses/mit-license.php)
This project is for automating FabLab's membership with FabManager API.

# Features
  - Automatically add's member to FabManager's database. [InDev]
  - Sends mail to member and FabLab's defined email. [InDev]
  - Provides secure PayPal payment, with Credit Card or PayPal account.

# Requirements
  - A web server, with PHP(Currently tested with 7.0).
  - A Buissness or Pro PayPal account, as we use PayPal API(s) to protect payments.
  - An SMTP server with user/password connection (Can be a standard email provider(Gmail supports it) or a domain email) to send mails to member/FabLab.
  - A MySQL(or MariaDB :D) database to store member information before payment and apply them after(We doesn't wanted to send member's datas to PayPal).
  - A FabManager, with his API key.
  - A little bit of time to configure :D

# How-to use ?!
[Later, when finished...]

# Attribution
This website uses the wonderful PHPMailer library to send emails, which can be found here :  
https://github.com/PHPMailer/PHPMailer

# Copy/Paste code for later use

```php
try {
  $oDatabase = new PDO('mysql:host=' . $config['dataBaseConf']['dbHost']
    . ';port=' . $config['dataBaseConf']['dbPort']
    . ';dbname=' . $config['dataBaseConf']['dbName']
    . ';charset=utf8', $config['dataBaseConf']['dbUser'], $config['dataBaseConf']['dbPass']);
} catch(Exception $e) {
  error_log("Database connection fail !");
  exit;
}
```
