<?php

namespace SwiftmailerWrapper;

if(!defined('ME1')) define('ME1', "shadiakiki1986@gmail.com");
if(!defined('ME2')) define('ME2', "ffaprivatebank@gmail.com");

class UtilsTest extends \PHPUnit_Framework_TestCase {
  public function setUp() {
    $this->pwd = [];
    $this->pwd[ME1] = getenv("SWIFTMAILER_WRAPPER_PWD1");
    $this->pwd[ME2] = getenv("SWIFTMAILER_WRAPPER_PWD2");

    $msg = "Please set env var SWIFTMAILER_WRAPPER_PWD";
    if(!$this->pwd[ME1]) $this->markTestSkipped($msg."1");
    if(!$this->pwd[ME2]) $this->markTestSkipped($msg."2");
  }

  /**
   * @dataProvider testMainProvider
   */
  public function testMain($config,$from) {
    // set pwd
    $config["password"] = $this->pwd[$config["username"]];
    if(array_key_exists("backup",$config)) $config["backup"]["password"]=$this->pwd[$config["backup"]["username"]];

    // send email
    $out = Utils::mail_attachment(
      // attachments
      array("Attachment.txt"=>__DIR__."/attach.txt"),

      // to, from, reply emails
      ME1,
      $from,
      "Shadi Akiki",
      $from,

      // subject, body
      "swiftmailer-wrapper unit test email",
      "This is a message. It <i>supports</i> html."
      ." This is a unit test from <a href='https://github.com/shadiakiki1986/swiftmailer-wrapper'>swiftmailer-wrapper</a> from "
      .php_uname(),

      // config
      $config
    );
    $this->assertNotNull($out);
  }

  public function testMainProvider() {
    $c1 = [
      "host"=>"smtp.gmail.com",
      "port"=>"465",
      "username"=>ME1,
//      "password"=>$this->pwd[ME1],
      "security"=>"ssl" // set SSL
    ];
    $c2 = array_merge(
      $c1,
      [ "backup"=>[
          "host"=>"smtp.gmail.com",
          "port"=>"465",
          "username"=>ME2,
//          "password"=>$this->pwd[ME2],
          "security"=>"ssl" // set SSL
        ]
      ]
    );
    // ruin the hostname of the main entry in c1 to force a fall-back on the backup
    $c2["host"]="wrong.smtp.server";

    return [[$c1,ME1],[$c2,ME2]];
  }

  public function testHtmlInBody() {
    $this->assertTrue(Utils::mail_attachment(array(),
      "s.akiki@ffaprivatebank.com",
      "s.akiki@ffaprivatebank.com",
      "Shadi Akiki",
      "s.akiki@ffaprivatebank.com",
      "Some subject",
      "This is <b>bold</b>."
    ));
  }

  public function testMailAttachmentAttachment() {
    $fn=sprintf("%s.txt",tempnam(sys_get_temp_dir(), 'Tux'));
    file_put_contents($fn,"some text");

    $this->assertTrue(Utils::mail_attachment(array($fn),
      "s.akiki@ffaprivatebank.com",
      "s.akiki@ffaprivatebank.com",
      "Shadi Akiki",
      "s.akiki@ffaprivatebank.com",
      "Some subject",
      "Attached file with random name."
    ));
  }

  public function testMailAttachmentRenameAttachment() {
    $fn=sprintf("%s.txt",tempnam(sys_get_temp_dir(), 'Tux'));
    file_put_contents($fn,"some text");

    $this->assertTrue(Utils::mail_attachment(array("newname.txt"=>$fn),
      "s.akiki@ffaprivatebank.com",
      "s.akiki@ffaprivatebank.com",
      "Shadi Akiki",
      "s.akiki@ffaprivatebank.com",
      "Some subject",
      "Attached file named 'newname.txt'."
    ));
  }

}
