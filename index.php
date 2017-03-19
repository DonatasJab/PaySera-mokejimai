<?php
include_once 'includes/config.php';
include_once 'includes/connection.php';
include_once 'forms.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MineCube.LT paslaugų meniu</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/style.css" />
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>

<div class="container headLoc">
	<img src="images/minecube.png" alt="MineCube.LT" height="200px" />
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
				<li><a href="../index.php"><span class="glyphicon glyphicon-comment"></span> Forumas</a></li>
				<li class="active"><a href="/paslaugos"><span class="glyphicon glyphicon-shopping-cart"></span> Paslaugos</a></li>
			</ul>
		</div>
	</nav>

	<div class="row">

		<div class="col-md-9">

			<div class="menu1">

				<div class="panel panel-info">

					<div class="panel-heading">
						<h3 class="panel-title">Paslaugų užsakymas</h3>
					</div>

					<div class="panel-body">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Įveskite žaidimo nick'ą" id="nick">
							<span class="input-group-btn"><button class="btn btn-info" id="next">Tęsti</button></span>
						</div>
					</div>

				</div>

			</div>


			<div class="menu2" style="display: none;">

				<div class="panel panel-info">

					<div class="panel-heading">
						<h3 class="panel-title">Paslaugų užsakymas serveryje</h3>
					</div>

					<div class="panel-body">

						<div class="panel panel-default">

							<div class="panel-heading">
								<h3 class="panel-title">Vartotojo informacija</h3>
							</div>

							<div class="panel-body">

								<div class="row">

									<div class="col-md-3" style="text-align: center;">
										<img id="user-img" src="https://minotar.net/helm/Sprunkas/100.png" alt="">
										<p class="text-success" id="nickas">Sprunkas</p>
									</div>

									<div class="col-md-9"><h3 class="nick text-info">

										<span class="user-info">Šis žaidėjas neturi užsakytų paslaugų!</span>

									</h3></div>

								</div>

							</div>

						</div>

						<div class="panel-group" id="accordion">

								<?php
								$stmt = $conn->prepare("SELECT * FROM privgroups");
								$stmt->execute();
								$data = $stmt->fetchAll();
								$data2 = $data;

								foreach($data as $groups) { ?>
								<div class="panel panel-default">

									<div class="panel-heading"><a data-toggle="collapse" data-parent="#accordion" href="#accordion<?php echo $groups['id']; ?>"><?php echo $groups['title']; ?></a></div>

										<div id="accordion<?php echo $groups['id']; ?>" class="<?php if($data2[0]['id'] == $groups['id']) { echo "panel-collapse collapse in"; } else { echo "panel-collapse collapse"; } ?>">

											<table class="table table-hover table-striped">
												<tbody>
												<?php
												$stmt = $conn->query("SELECT * FROM privileges");
												$data = $stmt->fetchAll();

												foreach($data as $privileges) { ?>

													<?php if($groups['id'] == $privileges['groupid']) { ?>

													<tr>
														<td><?php echo $privileges['title']; ?></td>
														<td style="width: 200px;"><button type="button" data-pid="<?php echo $privileges['id']; ?>" class="btn btn-warning btn-sm info" data-toggle="modal" data-target="#modalinfo"><span class="glyphicon glyphicon-info-sign"></span> Detaliau apie paslaugą</button></td>
														<td style="width: 150px;"><button type="button" data-pid="<?php echo $privileges['id']; ?>" class="btn btn-success btn-sm buy" data-toggle="modal" data-target="#modalbuy"><span class="glyphicon glyphicon-shopping-cart"></span> Pirkti paslaugą</button></td>
													</tr>

												<?php }
												} ?>
												</tbody>
											</table>
										</div>
								</div>
									<?php } ?>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="col-md-3">

			<?php
			$stmt = $conn->prepare("SELECT * FROM users");
			$stmt->execute();

			if($stmt->rowCount() > 0) {

				if(!isset($_SESSION['username'])) { ?>
					<div class="panel panel-success">
						<div class="panel-heading">Administracijos panelė</div>
						<div class="panel-body">
							<?php
							if(isset($msg)) { echo "<div class='alert alert-success'>" . $msg . "</div>"; }
                    		if(isset($err)) { echo "<div class='alert alert-danger'>" . $err . "</div>"; } 
							?>
							<form action="" method="POST">
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
							<a href="admin/index.php?page=editpriv">Privilegijų redagavimas</a><br>
							<a href="admin/index.php?page=editpgroup">Grupių redagavimas</a><br>
							<a href="admin/index.php?page=settings">Nustatymai</a><br>
							<a href="logout.php">Atsijungti</a>
						</div>
					</div>
				<?php }
				} else if($stmt->rowCount() == 0) { ?>
				<div class="panel panel-success">
					<div class="panel-heading">Registracija</div>
					<div class="panel-body">
						<?php
						if(isset($msg)) { echo "<div class='alert alert-success'>" . $msg . "</div>"; }
                    	if(isset($err)) { echo "<div class='alert alert-danger'>" . $err . "</div>"; } 
						?>
						<form action="" method="POST">
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

		<div class="modal fade" id="modalinfo">
			<div class="modal-dialog">
				<div class="modal-content">

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
						<h4 class="modal-title">Apie paslaugą</h4>
					</div>

					<div class="modal-body">
						<?php echo $data['content']; ?>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
					</div>

				</div>

			</div>

		</div>

	<div class="modal fade" id="modalbuy">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
					<h4 class="modal-title">Paslaugos užsakymas</h4>
				</div>

				<div class="modal-body">

					<div class="well">

					<h3>Paslaugos užsakymas SMS žinute</h3>

					<div class="alert alert-warning">

						<p><b>Žinutės tekstas:</b> <?php echo $data['keyword']; ?> <span class="nickname"></span></p>
						<p><b>Numeris:</b> <?php echo $data['number']; ?></p>
						<p><b>Žinutės kaina:</b> <?php echo $data['price'] / 100; ?> €</p>

					</div>

					<h3>Pirkti banku arba grynais</h3>

					<div class="alert alert-success">
						<h4>Atsiskaitant banku arba grynais taikoma <b>50%</b> nuolaida!</h4>
					</div>

					<a href="/libwebtopay/makro.php?nick=nickhere&id=<?php echo $data['id']; ?>" id="makro" class="btn btn-info">Pirki banku/grynais</a>

					</div>

				</div>

			</div>

		</div>
	</div>

	<script type="text/javascript">
		$( "#next" ).click(function() {
			if($("#nick").val() == "")
			{
				alert("Įrašykite savo NICK");
			} else {
				$(".menu1").fadeOut(1000);
				$("#nickas").html($("#nick").val());
				$(".menu2").delay(1000).slideDown(1000);
				$(".nickname").html($("#nick").val());
				$("#user-img").attr("src", "https://minotar.net/helm/" + $("#nick").val() + "/100.png");
				$.post('ajax/user-info.php', { gamenick: $("#nick").val() }, function(data) {
					$('.user-info').html(data);
				});
				$( ".buy" ).click(function() {
					$.post('ajax/buy.php', { gamenick: $("#nick").val(), id: $(this).attr('data-pid') }, function(data) {
						$('#modalbuy').html(data);
					});
				});
				$( ".info" ).click(function() {
					$.post('ajax/info.php', { id: $(this).attr('data-pid') }, function(data) {
						$('#modalinfo').html(data);
					});
				});
			}
		});
	</script>

</html>