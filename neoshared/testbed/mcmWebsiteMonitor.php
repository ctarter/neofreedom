<?php

/*
This program is free software; you can redistribute it and/or modify     
it under the terms of the GNU General Public License as published by     
the Free Software Foundation; either version 2 of the License, or        
(at your option) any later version.                                  
        
Joseph McMurry
support@mcmwebsite.com
http://www.mcmwebsite.com
Version 0.1
2/19/2009        
                                                                           
This program is distributed in the hope that it will be useful,          
but WITHOUT ANY WARRANTY; without even the implied warranty of           
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            
GNU General Public License for more details.     
*/


// call on cron e.g. every hour


// BEGIN CONFIG AREA
$urlToMonitor = 'http://neoxenos.org'; // the URL of the website you want to monitor e.g. http://www.example.com
$timeout = 30; // the number of seconds to wait on the website
$email = 'alerts@neoxenos.info'; // the email address to send notices to e.g. if a website is not responding
// END CONFIG AREA


// requires cURL
$curl_handle = curl_init($urlToMonitor);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 0); // do not return data, just TRUE or FALSE
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, $timeout);
ob_start(); 
$result = curl_exec($curl_handle); 
ob_end_clean(); 
$info = curl_getinfo($curl_handle);
curl_close($curl_handle);

if ( !$result || $info['http_code'] != 200 ) {
   // send email notice of website non-respone to $email
   
   $output = "$urlToMonitor may not be working.  HTTP Code: [". $info['http_code']. "]";
   if ( curl_error($curl_handle) )
      $output .= "\n". curl_error($curl_handle); // debug  
  
   $headers = "";
	 $headers .= "From: mcmWebsiteMonitor\n \t <" . $email . ">\n";
	 $headers .= "Reply-To: ".$from."\n";
	 $headers .= "X-Sender: ".$from."\n";
	 $headers .= "X-Mailer: PHP5\n";
	 $headers .= "X-Priority: 3\n";
	 $headers .= "MIME-Version: 1.0\n";
	 $headers .= "Content-type: text/plain\n";
	 $headers .= "Return-Path: " . $from . "\r\n\r\n";

	// send email to site owner
	
  $subject = "mcmWebsiteMonitor - $urlToMonitor may not be working";

	mail( $email, $subject, $output, $headers ); 
      
}

?>
