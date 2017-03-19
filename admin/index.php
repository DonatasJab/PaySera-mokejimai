<?php
include('../includes/config.php');
include('../includes/connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Miners.LT paslaugų administravimas</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <script src="../js/jquery-2.1.4.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../tinymce/tinymce.min.js"></script>
    <script>tinymce.init({
            selector:'textarea',
            plugins: "advlist, image, autolink, link, textcolor colorpicker",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor"
        });</script>
</head>
<?php include('../forms.php'); ?>
<body>

<div class="container headLoc">
    <img src="../images/minecube.png" alt="MineCube.LT" height="200px" />
</div>

<div class="container" id="shop">

    <nav class="navbar navbar-default navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../index.php">MineCube.LT</a>
        </div>
        <div class="collapse navbar-collapse" id="toggle">
            <ul class="nav navbar-nav">
                <li><a href="../../index.php"><span class="glyphicon glyphicon-comment"></span> Forumas</a></li>
                <li class="active"><a href="../index.php"><span class="glyphicon glyphicon-shopping-cart"></span> Paslaugos</a></li>
            </ul>
        </div>
    </nav>

    <div class="row">
        <div class="col-md-9">

            <?php if(isset($_GET['page']) && $_GET['page'] == 'editpriv' && isset($_SESSION['username'])) { ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Privilegijos</h3>
                    </div>
                    <div class="panel-body">
                        <?php

                        $stmt = $conn->query("SELECT * FROM privileges");
                        $data = $stmt->fetchAll();

                        ?>

                        <label for="privileges">Privilegijos:</label>
                        <select class="form-control" id="privileges">
                            <option value="0">--- Pasirinkit privilegiją ---</option>
                            <?php foreach($data as $privileges) { ?>
                                <option value="<?php echo $privileges['id']; ?>"><?php echo $privileges['title'] ?></option>
                            <?php } ?>
                        </select>
                        <br><a class="btn btn-success" id="edit" href="#">Redaguoti privilegiją</a>
                        <a class="btn btn-danger" id="delete" href="#">Trinti privilegiją</a>
                        <a class="btn btn-info pull-right" href="index.php?page=addpriv">Pridėti naują privilegiją</a>

                    </div>
                </div>

                <?php 
                if(isset($_GET['id'])) {
                    if(isset($msg)) { echo "<div class='alert alert-success'>" . $msg . "</div>"; }
                    if(isset($err)) { echo "<div class='alert alert-danger'>" . $err . "</div>"; } 
                ?>
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Privilegijos redagavimas</h3>
                        </div>
                        <div class="panel-body">

                            <?php

                            $stmt = $conn->prepare("SELECT * FROM privileges WHERE id = :id");
                            $stmt->execute(array('id' => $_GET['id']));
                            $data = $stmt->fetch(PDO::FETCH_ASSOC);
                            $stmt = $conn->query("SELECT * FROM privgroups");
                            $data2 = $stmt->fetchAll();

                            ?>

                            <form action="" method="POST">
                                <div class="form-group">
                                    <label for="ptitle">Pavadinimas</label>
                                    <input type="text" class="form-control" id="ptitle" name="ptitle" value="<?php echo $data['title']; ?>">
                                </div>
                            <div class="form-group">
                                <label for="type">Tipas</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="0">--- Pasirinkit tipą ---</option>
                                    <option value="1" <?php echo ($data['type'] == 1 ? " selected" : ""); ?>>Privilegija</option>
                                    <option value="2" <?php echo ($data['type'] == 2 ? " selected" : ""); ?>>Paslauga</option>
                                </select>
                            </div>
                                <div class="form-group">
                                    <label for="keyword">Raktažodis</label>
                                    <input type="text" class="form-control" id="keyword" name="keyword" value="<?php echo $data['keyword']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="pgroup">Pasirinkit grupę</label>
                                    <select class="form-control" id="pgroup" name="pgroup">
                                        <option value="0">--- Pasirinkit grupę ---</option>
                                        <?php foreach($data2 as $groups) { ?>
                                            <option value="<?php echo $groups['id']; ?>" <?php echo ($data['groupid'] == $groups['id'] ? " selected" : ""); ?>><?php echo $groups['title'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pprice">Mikro kaina</label>
                                    <input type="text" class="form-control" id="pprice" name="pprice" value="<?php echo $data['price'] / 100; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="makroprice">Makro kaina</label>
                                    <input type="text" class="form-control" id="makroprice" name="makroprice" value="<?php echo $data['makro_price'] / 100; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="pnum">Numeris, kurio reikės siųsti</label>
                                    <input type="text" class="form-control" id="pnum" name="pnum" value="<?php echo $data['number']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="cmd">Įvykdoma komanda išsiuntus SMS (Jeigu norit, kad įvykdytų daugiau komandų pridėkit kablelį)</label>
                                    <input type="text" class="form-control" id="cmd" name="cmd" value="<?php echo $data['cmd']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="pcontent">Apie privilegiją/paslaugą</label>
                                    <textarea class="form-control" id="pcontent" name="pcontent" rows="3"><?php echo $data['content']; ?></textarea>
                                </div>
                                <br><button type="submit" class="btn btn-success pull-right" name="psave">Atnaujinti privilegiją</button>
                            </form>

                        </div>
                    </div>
                <?php }

                } else if(isset($_GET['page']) && $_GET['page'] == 'addpriv' && isset($_SESSION['username'])) {
                    if(isset($msg)) { echo "<div class='alert alert-success'>" . $msg . "</div>"; }
                    if(isset($err)) { echo "<div class='alert alert-danger'>" . $err . "</div>"; }

                $stmt = $conn->query("SELECT * FROM privgroups");
                $data = $stmt->fetchAll();

                ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Privilegijos pridėjimas</h3>
                    </div>
                    <div class="panel-body">

                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="ptitle">Pavadinimas</label>
                                <input type="text" class="form-control" id="ptitle" name="ptitle" placeholder="VIP">
                            </div>
                            <div class="form-group">
                                <label for="type">Tipas</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="0">--- Pasirinkit tipą ---</option>
                                    <option value="1">Privilegija</option>
                                    <option value="2">Paslauga</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keyword">Raktažodis</label>
                                <input type="text" class="form-control" id="keyword" name="keyword" placeholder="VIP">
                            </div>
                            <div class="form-group">
                                <label for="pgroup">Pasirinkit grupę</label>
                                <select class="form-control" id="pgroup" name="pgroup">
                                    <option value="0">--- Pasirinkit grupę ---</option>
                                    <?php foreach($data as $groups) { ?>
                                        <option value="<?php echo $groups['id']; ?>"><?php echo $groups['title'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pprice">Kaina</label>
                                <input type="text" class="form-control" id="pprice" name="pprice" placeholder="1.00">
                            </div>
                            <div class="form-group">
                                <label for="makroprice">Makro kaina</label>
                                <input type="text" class="form-control" id="makroprice" name="makroprice" placeholder="0.50">
                            </div>
                            <div class="form-group">
                                <label for="pnum">Numeris, kurio reikės siųsti</label>
                                <input type="text" class="form-control" id="pnum" name="pnum" placeholder="1398">
                            </div>
                            <div class="form-group">
                                <label for="cmd">Įvykdoma komanda išsiuntus SMS (Jeigu norit, kad įvykdytų daugiau komandų pridėkit kablelį)</label>
                                <input type="text" class="form-control" id="cmd" name="cmd" placeholder="pex user [nick] group add VIP "" 2592000">
                            </div>
                            <div class="form-group">
                                <label for="pcontent">Apie privilegiją/paslaugą</label>
                                <textarea class="form-control" id="pcontent" name="pcontent" rows="10"></textarea>
                            </div>
                            <br><button type="submit" class="btn btn-success pull-right" name="padd">Pridėti privilegiją</button>
                        </form>

                    </div>
                </div>
            <?php } else if(isset($_GET['page']) && $_GET['page'] == 'editpgroup' && isset($_SESSION['username'])) { ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Grupės</h3>
                    </div>
                    <div class="panel-body">
                        <?php

                        $stmt = $conn->query("SELECT * FROM privgroups");
                        $data = $stmt->fetchAll();

                        ?>

                        <label for="groups">Grupės:</label>
                        <select class="form-control" id="groups">
                            <option value="0">--- Pasirinkit grupę ---</option>
                            <?php foreach($data as $group) { ?>
                                <option value="<?php echo $group['id']; ?>"><?php echo $group['title'] ?></option>
                            <?php } ?>
                        </select>
                        <br><a class="btn btn-success" id="edit" href="#">Redaguoti grupę</a>
                        <a class="btn btn-danger" id="delete" href="#">Trinti grupę</a>
                        <a class="btn btn-info pull-right" href="index.php?page=addpgroup">Pridėti naują grupę</a>

                    </div>
                </div>

                <?php 
                if(isset($_GET['id'])) {
                    if(isset($msg)) { echo "<div class='alert alert-success'>" . $msg . "</div>"; }
                    if(isset($err)) { echo "<div class='alert alert-danger'>" . $err . "</div>"; } 
                ?>
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Privilegijos grupių redagavimas</h3>
                        </div>
                        <div class="panel-body">

                            <?php

                            $stmt = $conn->prepare("SELECT * FROM privgroups WHERE id = :id");
                            $stmt->execute(array('id' => $_GET['id']));
                            $data = $stmt->fetch(PDO::FETCH_ASSOC);

                            ?>

                            <form action="" method="POST">
                                <div class="form-group">
                                    <label for="gtitle">Grupės pavadinimas</label>
                                    <input type="text" class="form-control" id="gtitle" name="gtitle" value="<?php echo $data['title']; ?>">
                                </div>
                                <br><button type="submit" class="btn btn-success pull-right" name="gsave">Atnaujinti grupę</button>
                            </form>

                        </div>
                    </div>
                <?php }

                } else if(isset($_GET['page']) && $_GET['page'] == 'addpgroup' && isset($_SESSION['username'])) {
                    if(isset($msg)) { echo "<div class='alert alert-success'>" . $msg . "</div>"; }
                    if(isset($err)) { echo "<div class='alert alert-danger'>" . $err . "</div>"; } ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Grupės pridėjimas</h3>
                    </div>
                    <div class="panel-body">

                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="gtitle">Grupės pavadinimas</label>
                                <input type="text" class="form-control" id="gtitle" name="gtitle" placeholder="Daiktų užsakymas, privilegijos ir pnš..">
                            </div>
                            <br><button type="submit" class="btn btn-success pull-right" name="gadd">Pridėti grupę</button>
                        </form>

                    </div>
                </div>
            <?php } else if (isset($_GET['page']) && $_GET['page'] == 'settings' && isset($_SESSION['username'])) {
                if(isset($msg)) { echo "<div class='alert alert-success'>" . $msg . "</div>"; }
                if(isset($err)) { echo "<div class='alert alert-danger'>" . $err . "</div>"; } ?>
            <div class="panel panel-warning">

                <div class="panel-heading">
                    <h3 class="panel-title">Nustatymai</h3>
                </div>

                <?php $stmt = $conn->prepare("SELECT * FROM settings");
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_ASSOC); ?>

                <div class="panel-body">

                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="projectid">PaySera projekto id (project_id)</label>
                            <input type="text" class="form-control" name="projectid" id="projectid" placeholder="Įrašykit projekto id" value="<?php echo $data['project_id']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="signpassword">PaySera projekto slaptažodis (sign_password)</label>
                            <input type="text" class="form-control" name="signpassword" id="signpassword" placeholder="Įrašykit projekto slaptažodį" value="<?php echo $data['sign_password']; ?>">
                        </div>
                        <br><button type="submit" class="btn btn-success pull-right" name="paysera">Saugoti</button>
                    </form> 

                </div>

            </div>


            <?php } ?>

        </div>
        <div class="col-md-3">
            <?php

            $stmt = $conn->query("SELECT * FROM users");

            if($stmt->rowCount() > 0) {

                if(!isset($_SESSION['username'])) { ?>
                    <div class="panel panel-success">
                        <div class="panel-heading">Administracijos panelė</div>
                        <div class="panel-body">
                        <?php if(isset($msg)) { echo "<div class='alert alert-success'>" . $msg . "</div>"; }
                        if(isset($err)) { echo "<div class='alert alert-danger'>" . $err . "</div>"; } ?>
                            <form action="index.php" method="POST">
                                <div class="form-group">
                                    <label for="username">Slapyvardis</label>
                                    <input type="text" class="form-control" name="username" placeholder="Slapyvardis">
                                </div>
                                <div class="form-group">
                                    <label for="password">Slaptažodis</label>
                                    <input type="password" class="form-control" name="password" placeholder="Slaptažodis">
                                </div>
                                <button type="submit" name="submit" class="btn btn-default pull-right">Prisijungti</button>
                            </form>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="panel panel-success">
                        <div class="panel-heading">Administracijos panelė</div>
                        <div class="panel-body">
                            <a href="index.php?page=editpriv">Privilegijų redagavimas</a><br>
                            <a href="index.php?page=editpgroup">Grupių redagavimas</a><br>
                            <a href="index.php?page=settings">Nustatymai</a><br>
                            <a href="../logout.php">Atsijungti</a>
                        </div>
                    </div>
                <?php }
            } else if($stmt->rowCount() == 0) {?>
                <div class="panel panel-success">
                    <div class="panel-heading">Registracija</div>
                    <div class="panel-body">
                        <?php if(isset($msg)) { echo "<div class='alert alert-success'>" . $msg . "</div>"; }
                        if(isset($err)) { echo "<div class='alert alert-danger'>" . $err . "</div>"; } ?>
                        <form action="index.php" method="POST">
                            <div class="form-group">
                                <label for="rusername">Slapyvardis</label>
                                <input type="text" class="form-control" name="rusername" placeholder="Slapyvardis">
                            </div>
                            <div class="form-group">
                                <label for="rpassword">Slaptažodis</label>
                                <input type="password" class="form-control" name="rpassword" placeholder="Slaptažodis">
                            </div>
                            <div class="form-group">
                                <label for="rpassword2">Pakartokit slaptažodį</label>
                                <input type="password" class="form-control" name="rpassword2" placeholder="Pakartokit slaptažodį">
                            </div>
                            <button type="submit" name="register" class="btn btn-default pull-right">Registruotis</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</body>
<script>
    $('#privileges').change(function() {
        var id = $(this).val();
        $('#edit').attr('href', 'index.php?page=editpriv&id=' + id);
        $('#delete').attr('href', 'index.php?delete=privileges&id=' + id);
    });
    $('#groups').change(function() {
        var id = $(this).val();
        $('#edit').attr('href', 'index.php?page=editpgroup&id=' + id);
        $('#delete').attr('href', 'index.php?delete=groups&id=' + id);
    });
</script>
</html>