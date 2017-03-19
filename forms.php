<?php

	if(isset($_POST['submit'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$data = $conn->prepare("SELECT * FROM users WHERE nick = :username");
		$data->execute(array("username" => $username));
		$user = $data->fetch(PDO::FETCH_ASSOC);

		if($data->rowCount() == 1) {

			if(($user['nick'] == $username) && password_verify($password, $user['password'])) {
				$_SESSION['username'] = $username;
			} else {
				$err = "Slaptažodis neteisingas!";
			}

		} else {
			$err = "Slapyvardis neegzistuoja!";
		}

	}

	if(isset($_POST['save'])) {
		$title = $_POST['title'];
		$body = $_POST['body'];
		if(empty($title)) {
			$err = "Naujienos pavadinimas negali būti tuščias!";
		} else if(empty($body)) {
			$err = "Naujienos turinys negali būti tuščias!";
		} else {
			$stmt = $conn->prepare("UPDATE news SET title = :title, body = :body WHERE id = :id");
			$stmt->execute(array(
				':title'    => $title,
				':body'     => $body,
				':id'       => $_GET['id']
			));
			$msg = "Naujiena atnaujinta!";
		}
	}

	if(isset($_POST['cnew'])) {
		$ctitle = $_POST['ctitle'];
		$cbody = $_POST['cbody'];
		if(empty($ctitle)) {
			$err = "Naujienos pavadinimas tuščias!";
		} else if(empty($cbody)) {
			$err = "Naujienos turinys tuščias!";
		} else {
			$stmt = $conn->prepare("INSERT INTO news (title, body, date) VALUES (:ctitle, :cbody, :date)");
			$stmt->execute(array(
				':ctitle'   => $ctitle,
				':cbody'    => $cbody,
				':date'     => time()
			));
			$msg = "Naujiena sėkmingai pridėta!";
		}
	}

	if(isset($_GET['delete']) && $_GET['delete'] == "news" && isset($_GET['id']) && isset($_SESSION['username'])) {
		$stmt = $conn->prepare("DELETE FROM news where id = :id");
		$stmt->execute(array(':id' => $_GET['id']));
		header('Location: index.php?edit=news');
	}

    if(isset($_POST['update'])) {
        $stitle = $_POST['stitle'];
        $sip = $_POST['sip'];
        $sport = $_POST['sport'];
        if(empty($stitle)) {
            $err = "Serverio pavadinimas yra tuščias!";
        } else if(empty($sip)) {
            $err = "Serverio IP neužpildytas!";
        } else if(empty($sport)) {
            $err = "Serverio port laukelis tuščias!";
        } else {
            $stmt = $conn->prepare("UPDATE servers SET title = :stitle, ip = :sip, port = :sport WHERE id = :id");
            $stmt->execute(array(
                ':stitle'   => $stitle,
                ':sip'      => $sip,
                ':sport'    => $sport,
                ':id'       => $_GET['id']
            ));
            $msg = "Serveris atnaujintas!";
        }
    }

    if(isset($_POST['addserver'])) {
        $stitle = $_POST['stitle'];
        $sip = $_POST['sip'];
        $sport = $_POST['sport'];
        if(empty($stitle)) {
            $err = "Serverio pavadinimas yra tuščias!";
        } else if(empty($sip)) {
            $err = "Serverio IP neužpildytas!";
        } else if(empty($sport)) {
            $err = "Serverio port laukelis tuščias!";
        } else {
            $stmt = $conn->prepare("INSERT INTO servers (title, ip, port) VALUES (:stitle, :sip, :sport)");
            $stmt->execute(array(
                ':stitle'    => $stitle,
                ':sip'      => $sip,
                ':sport'    => $sport
            ));
            $msg = "Serveris sėkmingai pridėtas!";
        }
    }

    if(isset($_GET['delete']) && $_GET['delete'] == "server" && isset($_GET['id']) && isset($_SESSION['username'])) {
        $stmt = $conn->prepare("DELETE FROM servers where id = :id");
        $stmt->execute(array(':id' => $_GET['id']));
        header('Location: index.php?edit=servers');
    }

	if(isset($_POST['padd'])) {
		$ptitle = $_POST['ptitle'];
		$type = $_POST['type'];
		$keyword = $_POST['keyword'];
		$pgroup = $_POST['pgroup'];
		$pprice = str_replace('0.', "", $_POST['pprice'] * 100);
		$makroprice = str_replace('0.', "", $_POST['makroprice'] * 100);
		$pnum = $_POST['pnum'];
		$cmd = htmlentities($_POST['cmd']);
		$pcontent = $_POST['pcontent'];
		if(empty($ptitle)) {
			$err = "Nenurodei privilegijos pavadinimo!";
		} else if($type == 0) {
			$err = "Nenurodei tipo!";
		} else if(empty($keyword)) {
			$err = "Nenurodei privilegijos raktažodžio!";
		} else if($pgroup == 0) {
			$err = "Nenurodei privilegijos grupės!";
		} else if(empty($pprice)) {
			$err = "Nenurodei privilegijos mikro kainos!";
		} else if(empty($makroprice)) {
			$err = "Nenurodei privilegijos makro kainos!";
		} else if(empty($pnum)) {
			$err = "Nenurodei kokiu numeriu paslaugą reikės užsisakyti!";
		} else if(empty($cmd)) {
			$err = "Nenurodei kokia komanda bus įvykdoma išsiuntus SMS!";
		} else {
			$stmt = $conn->prepare("INSERT INTO privileges (groupid, title, type, keyword, price, makro_price, number, cmd, content) VALUES (:pgroup, :ptitle, :type, :keyword, :pprice, :makroprice, :pnum, :cmd, :pcontent)");
			$stmt->execute(array(
				':pgroup' 		=> $pgroup,
				':ptitle'   	=> $ptitle,
				':type' 		=> $type,
				':keyword' 		=> $keyword,
				':pprice'   	=> $pprice,
				':makroprice' 	=> $makroprice,
				':pnum' 		=> $pnum,
				':cmd' 			=> $cmd,
				':pcontent' 	=> $pcontent
			));
			$msg = "Privilegija sėkmingai pridėta!";
		}
	}

	if(isset($_POST['gadd'])) {
		$gtitle = $_POST['gtitle'];
		if(empty($gtitle)) {
			$err = "Nenurodei grupės pavadinimo!";
		} else {
			$stmt = $conn->prepare("INSERT INTO privgroups (title) VALUES (:gtitle)");
			$stmt->execute(array(
				':gtitle'   => $gtitle
			));
			$msg = "Grupė sėkmingai pridėta!";
		}
	}

	if(isset($_POST['psave'])) {
		$ptitle = $_POST['ptitle'];
		$type = $_POST['type'];
		$keyword = $_POST['keyword'];
		$pgroup = $_POST['pgroup'];
		$pprice = str_replace('0.', "", $_POST['pprice'] * 100);
		$makroprice = str_replace('0.', "", $_POST['makroprice'] * 100);
		$pnum = $_POST['pnum'];
		$cmd = htmlentities($_POST['cmd']);
		$pcontent = $_POST['pcontent'];
		if(empty($ptitle)) {
			$err = "Nenurodei privilegijos pavadinimo!";
		} else if($type == 0) {
			$err = "Nenurodei tipo!";
		} else if(empty($keyword)) {
			$err = "Nenurodei privilegijos raktažodžio!";
		} else if($pgroup == 0) {
			$err = "Nenurodei privilegijos grupės!";
		} else if(empty($pprice)) {
			$err = "Nenurodei privilegijos kainos!";
		} else if(empty($pnum)) {
			$err = "Nenurodei kokiu numeriu paslaugą reikės užsisakyti!";
		} else if(empty($cmd)) {
			$err = "Nenurodei kokia komanda bus įvykdoma išsiuntus SMS!";
		} else {
			$stmt = $conn->prepare("UPDATE privileges SET groupid = :pgroup, title = :ptitle, type = :type, keyword = :keyword, price = :pprice, makro_price = :makroprice, number = :pnum, cmd = :cmd, content = :pcontent WHERE id = :id");
			$stmt->execute(array(
				':id'			=> $_GET['id'],
				':pgroup' 		=> $pgroup,
				':ptitle'   	=> $ptitle,
				':type'			=> $type,
				':keyword' 		=> $keyword,
				':pprice'   	=> $pprice,
				':makroprice' 	=> $makroprice,
				':pnum' 		=> $pnum,
				':cmd' 			=> $cmd,
				':pcontent' 	=> $pcontent
			));
			$msg = "Privilegija sėkmingai atnaujinta!";
		}
	}

	if(isset($_GET['delete']) && $_GET['delete'] == "privileges" && isset($_GET['id']) && isset($_SESSION['username'])) {
		$stmt = $conn->prepare("DELETE FROM privileges where id = :id");
		$stmt->execute(array(':id' => $_GET['id']));
		header('Location: index.php?page=editpriv');
	}

	if(isset($_GET['delete']) && $_GET['delete'] == "groups" && isset($_GET['id']) && isset($_SESSION['username'])) {
		$stmt = $conn->prepare("DELETE FROM privgroups where id = :id");
		$stmt->execute(array(':id' => $_GET['id']));
		header('Location: index.php?page=editpgroup');
	}

	if(isset($_POST['gsave'])) {
		$gtitle = $_POST['gtitle'];
		if(empty($gtitle)) {
			$err = "Nenurodei grupės pavadinimo!";
		} else {
			$stmt = $conn->prepare("UPDATE privgroups SET title = :gtitle WHERE id = :id");
			$stmt->execute(array(
				':gtitle'   => $gtitle,
				':id'		=> $_GET['id']
			));
			$msg = "Grupė sėkmingai atnaujinta!";
		}
	}

	if(isset($_POST['register'])) {
		$rusername = $_POST['rusername'];
		$rpassword = $_POST['rpassword'];
		$rpassword2 = $_POST['rpassword2'];
		if (strlen($rusername) < 6) {
			$err = "Slapyvardis per trumpas!";
		} else if (strlen($rpassword) < 6) {
			$err = "Slaptažodis per trumpas!";
		} else if ($rpassword != $rpassword2) {
			$err = "Slaptažodžiai nesutampa!";
		} else {
			$stmt = $conn->prepare("INSERT INTO users (nick, password) VALUES (:rusername, :rpassword)");
			$stmt->execute(array(
				':rusername' => $rusername,
				':rpassword' => password_hash($rpassword, PASSWORD_DEFAULT)
			));
			$msg = "Sėkmingai užsiregistravot!";
		}
	}

	if(isset($_POST['paysera'])) {
		$projectid = $_POST['projectid'];
		$signpass = $_POST['signpassword'];

		$stmt = $conn->prepare("SELECT * FROM settings");
		$stmt->execute();

		if($stmt->rowCount() == 0) {
			$stmt = $conn->prepare("INSERT INTO settings (project_id, sign_password) VALUES (:projectid, :signpass)");
			$stmt->execute(array(
				':projectid' => $projectid,
				':signpass' => $signpass,
			));
		} else {
			$stmt = $conn->prepare("UPDATE settings SET project_id = :projectid, sign_password = :signpass");
			$stmt->execute(array(
				':projectid' => $projectid,
				':signpass' => $signpass,
			));			
		}

		$msg = "Nustatymai sėkmingai atnaujinti!";

	}