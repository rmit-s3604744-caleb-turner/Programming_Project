<?php

	include(resource_path() . '..\lang\database.php');

	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$email = $_POST["email"];
		$password = $_POST["password"];

		

		$id = canLogin($email, $password);
		


		
		
	}

?>


<script type="text/javascript">
	sessionStorage.ID = <?php echo json_encode($id) ?>;
			
</script>
		
		
		
<?php 
	if($id==0){
		header('Location: /'); 
	}else{
		Redirect::to('home'); 
	}
?>