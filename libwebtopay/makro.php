<?php   

require_once('WebToPay.php');
include('index.php');
include('../includes/connection.php');

function get_self_url() {
    $s = substr(strtolower($_SERVER['SERVER_PROTOCOL']), 0,
                strpos($_SERVER['SERVER_PROTOCOL'], '/'));
 
    if (!empty($_SERVER["HTTPS"])) {
        $s .= ($_SERVER["HTTPS"] == "on") ? "s" : "";
    }
 
    $s .= '://'.$_SERVER['HTTP_HOST'];
 
    if (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80') {
        $s .= ':'.$_SERVER['SERVER_PORT'];
    }
 
    $s .= dirname($_SERVER['SCRIPT_NAME']);
 
    return $s;
}

$stmt = $conn->prepare("SELECT * FROM privileges WHERE id = :id");
$stmt->execute(array(':id' => $_GET['id']));
$data = $stmt->fetch(PDO::FETCH_ASSOC);

try 
{
    $self_url = get_self_url();
    $request = WebToPay::redirectToPayment(array(
        'projectid'     => $paysera['project_id'],
        'sign_password' => $paysera['sign_password'],
        'orderid'       => "Nick: ".$_GET['nick'],
        'pageid'        => $_GET['id'],
        'amount'        => $data['makro_price'],
        'currency'      => 'EUR',
        'country'       => 'LT',
        'accepturl'     => $self_url.'/accept.php',
        'cancelurl'     => $self_url.'/cancel.php',
        'callbackurl'   => $self_url.'/makro_callback.php',
        'test'          => 0,
    ));
}
catch (WebToPayException $e) 
{
    echo $e->getMessage();
}