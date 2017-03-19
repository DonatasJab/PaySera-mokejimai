<?php

include("../includes/connection.php");

$stmt = $conn->prepare("SELECT * FROM privileges WHERE id = :id");
$stmt->execute(array(':id' => $_POST['id']));
$data = $stmt->fetch(PDO::FETCH_ASSOC);

?>
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

				<p><b>Žinutės tekstas:</b> <?php echo $data['keyword']." ".$_POST['gamenick']; ?> </span></p>
				<p><b>Numeris:</b> <?php echo $data['number']; ?></p>
				<p><b>Žinutės kaina:</b> <?php echo $data['price'] / 100; ?> €</p>

			</div>

			<h3>Pirkti banku arba grynais</h3>

			<div class="alert alert-success">
				<h4>Atsiskaitant banku arba grynais taikoma <b>50%</b> nuolaida!</h4>
			</div>

			<a href="libwebtopay/makro.php?nick=<?php echo $_POST['gamenick']; ?>&id=<?php echo $data['id']; ?>" id="makro" class="btn btn-info">Pirki banku/grynais</a>

			</div>

		</div>

	</div>

</div>