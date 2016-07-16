<?php

function mail_attachment($files, $mailto, $from_mail, $from_name, $replyto, $subject, $message, $config) {
  if(!class_exists("\Swift_Message")) throw new Exception("Email support not installed on server. Aborting");

  $missingConfigKeys = array_diff(array("host","port","username","password"),array_keys($config));
  if(count($missingConfigKeys)>0) throw new Exception("Missing config keys: ".implode(", ",$missingConfigKeys));

  $message = \Swift_Message::newInstance()
      ->setSubject($subject)
      ->setFrom(array($from_mail=>$from_name))
      ->setReplyTo($replyto)
      ->setTo($mailto)
      ->setBody($message)
  ;
  foreach($files as $fi) {
    # TODO support change filename http://swiftmailer.org/docs/messages.html#setting-the-filename
    $message->attach(\Swift_Attachment::fromPath($fi));
  }

  # References
  # https://www.sitepoint.com/sending-email-with-swift-mailer/
  # http://swiftmailer.org/docs/sending.html
  # http://stackoverflow.com/a/26256177/4126114
  $transport = \Swift_SmtpTransport::newInstance($config["host"],$config["port"])
      ->setUsername($config["username"])
      ->setPassword($config["password"]);
  $mailer = \Swift_Mailer::newInstance($transport);
  return $mailer->send($message);
}

