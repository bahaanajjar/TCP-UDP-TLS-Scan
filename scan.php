<?php
error_reporting(0);
echo "
	###############################################################
	# * TCP UDP TLS SCAN                                          #
	# * By    : Bahaa Al-Najjar                                   #
	# * Usage : php scan.php                                      #
	# * Priv8 Script Plz Don't Share! Enjoy ;)                    #
	###############################################################\r\n\n";
	
echo "\n[+] Welcome to  TCP UDP TLS scan :)\n[+] GOODLUCK\n\n";
sleep(3);
$file = fopen("ips.txt", "r") or die("Unable to open file!");

$i=0;
while (!feof($file)) {
	$i+=1;
  $line = fgets($file);
  list($ip, $port) = explode(":", $line);

  $connection = @fsockopen($ip, $port, $errno, $errstr, 1);
  if ($connection) {
    $result = "$ip";
	echo " [$i]\033[32m $ip \033[0m ==> \033[32m TCP Protocol \033[0m \r\n";
	file_put_contents("TCP.txt", "$result\n",FILE_APPEND);
	
  } else {
    $connection = @fsockopen("udp://$ip", $port, $errno, $errstr, 1);
    if ($connection) {
      $result = "$ip";
	  echo " [$i]\033[32m $ip \033[0m ==> \033[32m UDP Protocol \033[0m \r\n";
	  file_put_contents("UDP.txt", "$result\n",FILE_APPEND); 
	  
    } else {
      $context = stream_context_create(array("ssl" => array("capture_peer_cert" => true)));
      $connection = @stream_socket_client("tls://$ip:$port", $errno, $errstr, 1, STREAM_CLIENT_CONNECT, $context);
      if ($connection) {
        $result = "$ip";
		echo " [$i]\033[32m $ip \033[0m ==> \033[32m TLS Protocol \033[0m \r\n";
		file_put_contents("TLS.txt", "$result\n",FILE_APPEND); 
		
      } else {
        $result = "$ip Protocol not supported\n";
		echo " [$i]\e[31m $ip  ==> Protocol not supported \e[0m\n";
      }
    }
  }
  fclose($connection);
}

fclose($file);

