Wrapper function for using [Swiftmailer](http://swiftmailer.org/) (in php)

Available on [packagist](https://packagist.org/packages/shadiakiki1986/swiftmailer-wrapper)

Install: `composer require shadiakiki1986/swiftmailer-wrapper:dev-master`

Use:
```php
require_once __DIR__.'/vendor/autoload.php';

\SwiftmailerWrapper\Utils::mail_attachment(

  // attachments
  array("/path/to/file1","/path/to/file2"),

  // to, from, reply emails
  "to@email.com",
  "from@email.com",
  "From Name",
  "reply@email.com",

  // subject, body
  "This is a subject",
  "This is a message. It <i>supports</i> html.",

  // config array
  array(
    "host"=>"smtp.server.com",
    "port"=>"123",
    "username"=>"myUser",
    "password"=>"pizza"
  )

);
```

Files can be renamed in attachment by passing files parameter as follows:
```php
  array("newName1"=>"/path/to/file1","newName2"=>"/path/to/file2"),
```

To use [encrypted smtp](http://swiftmailer.org/docs/sending.html#encrypted-smtp), add `security` key to the config array. Valid values are: `false, ssl, tls`

To add a backup SMTP server and credentials, add a `backup` key similar to the original config array, i.e. with `host, port, username, password, security`.

# Testing
```bash
composer install
SWIFTMAILER_WRAPPER_EML1=my@gmail.com \
  SWIFTMAILER_WRAPPER_PWD1=password1 \
  SWIFTMAILER_WRAPPER_EML2=another@gmail.com \
  SWIFTMAILER_WRAPPER_PWD2=password2 \
  composer test
```

Note that emails above should be gmail accounts because the tests define the gmail server explicitly
