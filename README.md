Wrapper function for using [Swiftmailer](http://swiftmailer.org/) (in php)

Available on [packagist](https://packagist.org/packages/shadiakiki1986/swiftmailer-wrapper)

Install: `composer require shadiakiki1986/swiftmailer-wrapper:dev-master`

Use:
```php
require_once __DIR__.'/vendor/autoload.php';
\SwiftmailerWrapper\Utils::mail_attachment(
  array("/path/to/file1","/path/to/file2"),
  "to@email.com",
  "from@email.com",
  "From Name",
  "reply@email.com",
  "This is a subject",
  "This is a message. It <i>supports</i> html.",
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
