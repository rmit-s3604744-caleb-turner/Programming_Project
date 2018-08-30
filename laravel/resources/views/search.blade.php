
@extends('layouts.app')

@section('content')
	<h1> Match <h1>



		<img src="Bean.jpg">
	
<?php

include(resource_path() . '..\lang\readFile.php');

$array = readIntoArray();

foreach($array AS $row){
	echo $row[0] . "  |  " . $row[2] . "<br>";
}



echo areDetailsSet(1);

?>
@endsection

<img src="Bean.jpg">
<script type="text/javascript">
var array = <?php json_encode($array)?>;
/*
array[0][0] == 1st match's ID
array[1][1] == 2nd match's similarity score as a decimal
array[0][2] == 1st match's similarity score as a %

*/
</script>
