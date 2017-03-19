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
			<h4 class="modal-title">Apie paslaugą</h4>
		</div>

		<div class="modal-body">
			<?php echo $data['content']; ?>
		</div>

	</div>

</div>