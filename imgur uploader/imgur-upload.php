<?php
$img = $_FILES['file-0'];
if ($img['name'] == '') {  
  echo "upload a valid image";
} else {
  $filename = $img['tmp_name'];
  $client_id = ""; // imgur API client id
  $client_secret = ""; // imgur API client secret
  $handle = fopen($filename, "r");
  $data = fread($handle, filesize($filename));
  $pvars = array('image' => base64_encode($data));
  $timeout = 30;
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
  curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
  $out = curl_exec($curl);
  curl_close ($curl);
  $resp = json_decode($out,true);
  $url = $resp['data']['link'];
  if ($url != "") {
    echo "Upload successful at ".$url;
  } else {
    echo "Upload not successful";
  } 
}
?>