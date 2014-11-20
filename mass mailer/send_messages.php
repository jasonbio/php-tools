<?php

if (file_exists('/tmp/script2.lock')) {

  	exit();
	
} else {

	// connect to SQL table with requirements of Pear Mail Queue
	// get SQL formatting here: http://pear.php.net/manual/en/package.mail.mail-queue.mail-queue.tutorial.php
	$host = "";
	$username = "";
	$password = "";
	$db_name = "";
	mysql_connect($host, $username, $password);
	mysql_select_db($db_name) or die(mysql_error());
	$result = mysql_query("SELECT count(id) from mail_queue limit 1");
	
	if ($result > 0) {
	
		// we don't lock the script until the actual process of putting mails in the queue happens
		file_put_contents('/tmp/script3.lock','');
		
		include 'config.php';
		// number of emails to put in the queue before stopping
		$max_amount_mails = 500;
		$mail_queue =& new Mail_Queue($db_options, $mail_options);
		
		// sending the mail
		$mail_queue->sendMailsInQueue($max_amount_mails);
		
	} else {
	
		exit();
		
	}
}

// remove file lock when finished running
unlink('/tmp/script2.lock');

?>