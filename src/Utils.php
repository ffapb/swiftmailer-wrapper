<?php

namespace SwiftmailerWrapper;

class Utils {
  public static function mail_attachment($files, $mailto, $from_mail, $from_name, $replyto, $subject, $body, $config) {
    if(!class_exists("\Swift_Message")) throw new Exception("Email support not installed on server. Aborting");

    // required entries
    $missingConfigKeys = array_diff(array("host","port","username","password"),array_keys($config));
    if(count($missingConfigKeys)>0) throw new Exception("Missing config keys: ".implode(", ",$missingConfigKeys));

    // optional entries
    if(!array_key_exists('security',$config)) {
      $config['security']=false;
    } else {
      if(!in_array($config['security'],array('ssl','tls'))) throw new \Exception("Invalid security option: ".$config['security']);
    }

    $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom(array($from_mail=>$from_name))
        ->setReplyTo($replyto)
        ->setTo($mailto)
        ->setBody($body,'text/html')
    ;
    foreach($files as $k=>$fi) {
      $attachment = \Swift_Attachment::fromPath($fi);
      # change filename http://swiftmailer.org/docs/messages.html#setting-the-filename
      if(is_string($k)) $attachment->setFilename($k);
      $message->attach($attachment);
    }

    # References
    # https://www.sitepoint.com/sending-email-with-swift-mailer/
    # http://swiftmailer.org/docs/sending.html
    # http://stackoverflow.com/a/26256177/4126114
    $transport = \Swift_SmtpTransport::newInstance($config["host"],$config["port"])
        ->setUsername($config["username"])
        ->setPassword($config["password"]);

    // http://swiftmailer.org/docs/sending.html#encrypted-smtp
    // https://github.com/swiftmailer/swiftmailer/blob/fffbc0e2a7e376dbb0a4b5f2ff6847330f20ccf9/lib/classes/Swift/SmtpTransport.php#L42
    if(!!$config['security']) $transport->setEncryption($config['security']);

    $mailer = \Swift_Mailer::newInstance($transport);

    try {
      $out = $mailer->send($message);
      return $out;
    } catch(\Swift_TransportException $err) {
      if(array_key_exists("backup",$config)) {
        return self::mail_attachment($files, $mailto, $from_mail, $from_name, $replyto, $subject, $body, $config["backup"]);
      }
    }
  }
} // end class
