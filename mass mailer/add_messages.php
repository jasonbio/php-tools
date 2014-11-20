<?php

// prevents more than one process of the same script at a time.
// useful for cronjobs and if hitting script quickly
if (file_exists('/tmp/script.lock')) {
  exit();
}

file_put_contents('/tmp/script.lock','');
	
// connect to a SQL db with access to emails to pull for job
$host = "";
$username = "";
$password = "";
$db_name = "";
mysql_connect($host, $username, $password);
mysql_select_db($db_name) or die(mysql_error());
	
// find eligible email addresses in the table. notice the 'active = 1' requirement
// you should include some sort of deliminator for users who usubscribe, hard bounce, etc.
// set it to active = 0 to ignore
$result = mysql_query("SELECT * FROM `list` WHERE group_a = 1 AND active = 1");

while($row = mysql_fetch_assoc($result)) {
	
	include 'config.php';
		
	$mail_queue =& new Mail_Queue($db_options, $mail_options);

	// compose the email
	$from = 'noreply@yourdomain.com';
	$to = $row["email"];
	$message = "Here is a sample newsletter email.\r\n";
	$message .= "\r\n";
	$message .=	"There is no news to report.\r\n";
	$message .= "\r\n";

	$hdrs = array( 'From'    			=> $from,
					'To'      			=> $to,
               		'Subject' 			=> "Monthly Newsletter"	);

	// we use Mail_mime() to construct a valid mail 
	$mime =& new Mail_mime();
	$mime->setTXTBody($message);
	$body = $mime->get();
	// the 2nd parameter allows the header to be overwritten
	// @see http://pear.php.net/bugs/18256
	$hdrs = $mime->headers($hdrs, true); 

	$seconds_to_send = 0;

	$delete_after_send = true;

	// Put message to queue
	$mail_queue->put($from, $to, $hdrs, $body, $seconds_to_send, $delete_after_send);
		
}

// remove file lock when finished running
unlink('/tmp/script.lock');

?>
