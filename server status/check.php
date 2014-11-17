<?php
// PHP script that can ping an arbitrary number and types of servers for a result
// Generates a nice looking table with color coded statuses and logic for determining level of impact based on outages

function serverStatus() {
	$count = 1;
	function ping($host, $port, $timeout) { 
		global $count;
		$fP = fSockOpen($host, $port, $errno, $errstr, $timeout); 
		if (!$fP) {
			return '<div class="progress">
						<div class="bar bar-danger" style="width: 100%;text-shadow: 1px 1px 1px #000;"><small>OFFLINE</small></div>
					</div>'; 
		} else {
			$count++;
			return '<div class="progress">
						<div class="bar bar-success" style="width: 100%;text-shadow: 1px 1px 1px #000;"><small>ONLINE</small></div>
					</div>';
		}
	}

	function checkServer($url) {
		global $count;
		$opts = array(
  			'http' => array(
      			'method' => "GET",
      			'header' => "Accept-language: en\r\n",
      			'ignore_errors' => true
  			)
		);
		$context = stream_context_create($opts);
		$old_eh = set_error_handler ( function () {});
		$handle = fopen($url, 'r', false, $context);
		if (strpos($http_response_header[0], '404') !== false) {
    		return '<div class="progress">
						<div class="bar bar-danger" style="width: 100%;text-shadow: 1px 1px 1px #000;"><small>OFFLINE</small></div>
					</div>';
		} else {
			$count++;
			return '<div class="progress">
						<div class="bar bar-success" style="width: 100%;text-shadow: 1px 1px 1px #000;"><small>ONLINE</small></div>
					</div>';
		}
	}

	function checkNumber() {
		global $count;
		$currentdate = '<script type="text/javascript">
							var date = new Date();
							var n = date.toDateString();
							var time = date.toLocaleTimeString();
							document.write(n+" "+time);
						</script>';
		if ($count == 7) { // Total # of servers that we're checking
			return '<span id="statusgood">
						<strong style="font-size: 16px;color: #FFF;margin-right: 21px;text-shadow: 1px 1px 1px #000000;">status: all servers are online</strong> as of '.$currentdate.'
					</span>';
		}
		else if ($count == 6) {
			return '<span id="statuswarn">
						<strong style="font-size: 16px;color: #FFF;margin-right: 21px;text-shadow: 1px 1px 1px #000000;">status: one server offline; may effect some users</strong> as of '.$currentdate.'
					</span>';
		}
		else if ($count == 5) {
			return '<span id="statuswarn">
						<strong style="font-size: 16px;color: #FFF;margin-right: 21px;text-shadow: 1px 1px 1px #000000;">status: two servers offline; will effect some users</strong> as of '.$currentdate.'
					</span>';
		}
		else if ($count == 4) {
			return '<span id="statusbad">
						<strong style="font-size: 16px;color: #FFF;margin-right: 21px;text-shadow: 1px 1px 1px #000000;">status: three servers offline; will effect most users</strong> as of '.$currentdate.'
					</span>';
		}
		else if ($count == 3) {
			return '<span id="statusbad">
						<strong style="font-size: 16px;color: #FFF;margin-right: 21px;text-shadow: 1px 1px 1px #000000;">status: four servers offline; will effect most users</strong> as of '.$currentdate.'
					</span>';
		}
		else if ($count == 2) {
			return '<span id="statusbad">
						<strong style="font-size: 16px;color: #FFF;margin-right: 21px;text-shadow: 1px 1px 1px #000000;">status: five servers offline; global outage</strong> as of '.$currentdate.'
					</span>';
		}
		else if ($count == 1) {
			return '<span id="statusbad">
						<strong style="font-size: 16px;color: #FFF;margin-right: 21px;text-shadow: 1px 1px 1px #000000;">status: six servers offline; global outage</strong> as of '.$currentdate.'
					</span>';
		}
		else {
			return '<span id="statusbad">
						<strong style="font-size: 16px;color: #FFF;margin-right: 21px;text-shadow: 1px 1px 1px #000000;">status: all servers offline; global outage</strong> as of '.$currentdate.'
					</span>';
		}
	}

	// checkServer("URL"); 		= check an HTTP accessible endpoint (can also serve as a web cron)
	// ping("IP", PORT, 10); 	= check a specific IP and PORT

	$server1 = checkServer("http://google.com/");
	$server2 = checkServer("http://reddit.com/");
	$server3 = checkServer("http://facebook.com/");
	$server4 = ping("174.143.119.91", 6667, 10);
	$server5 = ping("38.229.70.22", 6667, 10);
	$server6 = ping("74.125.224.98", 80, 10);
	$server7 = ping("198.41.212.157", 80, 10);
	$initial = checkNumber();

	$myFile = "results.php";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = $initial.'<div class="server" style="margin-right: 10px;border: none!important;margin-top: 45px;z-index: 1;width: 740px;">
			<table class="table table-striped table-condensed" style="border: 1px #2C2C2C solid !important;">
				<thead style="color: #7A7A7A!important;background: #000000!important;">
					<tr>
						<th id="status" style="text-align: center;">Server Status</th>
						<th id="name" style="text-align: center;">Server Name</th>
						<th id="type" style="text-align: center;">Server Type</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td id="online">
							'.$server1.'
						</td>
						<td>google.com</td>
						<td>Web Server</td>
					</tr>
					<tr>
						<td id="online">
							'.$server2.'
						</td>
						<td>reddit.com</td>
						<td>Web Server</td>
					</tr>
					<tr>
						<td id="online">
							'.$server3.'
						</td>
						<td>facebook.com</td>
						<td>Web Server</td>
					</tr>
					<tr>
						<td id="online">
							'.$server4.'
						</td>
						<td>Freenode IRC (midwest)</td>
						<td>Chat Server</td>
					</tr>
					<tr>
						<td id="online">
							'.$server5.'
						</td>
						<td>Freenode IRC (east)</td>
						<td>Chat Server</td>
					</tr>
					<tr>
						<td id="online">
							'.$server6.'
						</td>
						<td>google</td>
						<td>Web Server</td>
					</tr>
					<tr>
						<td id="online">
							'.$server7.'
						</td>
						<td>CloudFlare</td>
						<td>Web Server</td>
					</tr>
				</tbody>
			</table>
		</div>';
	fwrite($fh, $stringData);  	
	fclose($fh);
}
serverStatus();
?>