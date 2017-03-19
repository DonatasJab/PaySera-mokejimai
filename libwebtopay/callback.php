<?php

require_once('WebToPay.php');
include('index.php');
include('../includes/connection.php');

$get = removeQuotes($_POST);

try {
    $response = WebToPay::checkResponse($get, array(
        'projectid'     => $paysera['project_id'],
        'sign_password' => $paysera['sign_password']
    ));
    
    $sms = explode(' ', $response['sms']);
    $kaina = $response['amount'];
    $nr = $response['from'];
    $raktazodis = strtolower($sms[0]);
    $nick = $sms[1];

    $stmt = $conn->prepare("SELECT * FROM privileges WHERE keyword = :keyword AND price = :price");
    $stmt->execute(array(
        ':keyword' => $raktazodis,
        ':price'   => $kaina
    ));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("SELECT * FROM sms WHERE nick = :nick");
    $stmt->execute(array(':nick' => $nick));
    $data2 = $stmt->fetch(PDO::FETCH_ASSOC);

    $timeleft = $data2['expires'] - time();


    if ($data['price'] != $kaina) {

        echo 'OK Suma neatitinka';

    } else {

        if ($nick != ""){

            $Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
            $Query->SetRconPassword( SQ_RCON_PASS );
            $data['cmd'] = htmlspecialchars_decode($data['cmd']);
            $data['cmd'] = str_replace('[nick]', $nick, $data['cmd']);
            $cmds = explode(", ", $data['cmd']);
            foreach($cmds as $cmd) {
                $Query->Rcon( $data['cmd'] );
            }

            if($data['type'] == 1) {

            	if($stmt->rowCount() == 1) {

		            $stmt = $conn->prepare("UPDATE sms SET nick = :nick, keyword = :keyword, nr = :nr, expires = :expires WHERE nick = :nick");
		            $stmt->execute(array(
		            	':nick' 	=> $nick,
		            	':keyword' 	=> $raktazodis,
		            	':nr'		=> $nr,
		                ':expires'  => $timeleft + time() + (60*60*24*30)
		            ));

	        	} else {

		            $stmt = $conn->prepare("INSERT INTO sms (nick, keyword, nr, expires) VALUES (:nick, :keyword, :nr, :expires)");
		            $stmt->execute(array(
		            	':nick' 	=> $nick,
		            	':keyword' 	=> $raktazodis,
		            	':nr'		=> $nr,
		                ':expires'  => time() + (60*60*24*30)
		            ));

	        	}

            } else if ($data['type'] == 2) {

            	if($stmt->rowCount() == 0) {

		            $stmt = $conn->prepare("INSERT INTO sms (nick, keyword, nr, expires) VALUES (:nick, :keyword, :nr, :expires)");
		            $stmt->execute(array(
		                ':nick'     => $nick,
		                ':keyword'  => $raktazodis,
		                ':nr'       => $nr,
		                ':expires'  => 0
		            )); 

	        	}

            }
            echo 'OK Paslauga sekmingai uzsakyta!';
        } else {
            echo 'OK Neivedet nicko!';
        }
    }
}
catch (Exception $e) {
    echo get_class($e).': '.$e->getMessage();
}
finally
{
    $Query->Disconnect( );
}

function removeQuotes($post) {
    if (get_magic_quotes_gpc()) {
        foreach ($post as &$var) {
            if (is_array($var)) {
                $var = removeQuotes($var);
            } else {
                $var = stripslashes($var);
            }
        }
    }
    return $post;
}