<?php
require_once('WebToPay.php');
include('index.php');
include('../includes/connection.php');

try {
    $response = WebToPay::checkResponse($_POST, array(
        'projectid' 	=> $paysera['project_id'],
        'sign_password' => $paysera['sign_password']
    ));

    $stmt = $conn->prepare("SELECT * FROM privileges WHERE id = :pageid AND makro_price = :makro_price");
        $stmt->execute(array(
        ':pageid' => $response['pageid'],
        ':makro_price' => $response['amount']
    ));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

	$nick = str_replace("Nick: ", "", $response['orderid']);

    $stmt = $conn->prepare("SELECT * FROM sms WHERE nick = :nick");
    $stmt->execute(array(':nick' => $nick));
    $data2 = $stmt->fetch(PDO::FETCH_ASSOC);

    $timeleft = $data2['expires'] - time();
	
	if($response['amount'] == $data['makro_price']) {

		if($data['type'] == 1) {

            if($stmt->rowCount() == 1) {

				$stmt = $conn->prepare("UPDATE sms SET nick = :nick, keyword = :keyword, nr = :nr, expires = :expires");
				$stmt->execute(array(
					':nick' 	=> str_replace("Nick: ", "", $nick),
					':keyword' 	=> $data['keyword'],
					':nr'		=> '86',
					':expires'	=> $timeleft + time() + (60*60*24*30)
				));

			} else {

				$stmt = $conn->prepare("INSERT INTO sms (nick, keyword, nr, expires) VALUES (:nick, :keyword, :nr, :expires)");
				$stmt->execute(array(
					':nick' 	=> str_replace("Nick: ", "", $nick),
					':keyword' 	=> $data['keyword'],
					':nr'		=> '86',
					':expires'	=> time() + (60*60*24*30)
				));

			}

		} else {

			$stmt = $conn->prepare("INSERT INTO sms (nick, keyword, nr, expires) VALUES (:nick, :keyword, :nr, :expires)");
			$stmt->execute(array(
				':nick' 	=> str_replace("Nick: ", "", $nick),
				':keyword' 	=> $data['keyword'],
				':nr'		=> '86',
				':expires'	=> 0
			));

		}

        $Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
        $Query->SetRconPassword( SQ_RCON_PASS );
        $data['cmd'] = htmlspecialchars_decode($data['cmd']);
    	$data['cmd'] = str_replace('[nick]', $nick, $data['cmd']);
    	$cmds = explode(", ", $data['cmd']);
    	foreach($cmds as $cmd) {
      		$Query->Rcon( $data['cmd'] );
        }
		
		echo 'OK Paslauga/privilegija sėkmingai užsakyta!';
		
	} else {
		echo 'OK Suma neatitinka.';
	}
} catch (Exception $e) {
    echo get_class($e).': '.$e->getMessage();
}
finally
{
    $Query->Disconnect( );
}