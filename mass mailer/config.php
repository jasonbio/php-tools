<?php

require_once "Mail/Queue.php";

// options for storing the messages
// type is the container used, currently there are 'creole', 'db', 'mdb' and 'mdb2' available
$db_options['type']       = 'db';
$db_options['dsn']        = 'mysql://DATABASE_USER:DATABASE_PASSWORD@localhost/DATABASE';
$db_options['mail_table'] = 'TABLE';

// here are the options for sending the messages themselves
// these are the options needed for the Mail-Class, especially used for Mail::factory()
// recommended to send mail locally to postfix/sendmail first and not directly to outside mail server
// if sending to outside mailserver, configure postfix/sendmail to do so - better performance
$mail_options['driver']    = 'smtp';
$mail_options['host']      = 'localhost';
$mail_options['port']      = 25;
$mail_options['localhost'] = 'localhost'; //optional Mail_smtp parameter
$mail_options['auth']      = false;
$mail_options['username']  = '';
$mail_options['password']  = '';

?>