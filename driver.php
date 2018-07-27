<?php
error_reporting(0);

	$check = 0;

	$conn = mysqli_connect("localhost", "dotato", "qwerty1234", "justdel2");

	if(!$conn)
	{
		die("Connection error:" . mysql_connect_error());
	}

	$data = json_decode(stripslashes($_POST['data']));
	$loc = json_decode(stripslashes($_POST['locs']));
	//$data = explode("," , $_POST['data']);

	$query = "UPDATE driver SET active=0";
	if(mysqli_query($conn, $query))
	{
	}
	else
	{
		echo "Error: " . mysqli_error($conn); 
	}

	for($i=0; $i<count($data);$i++)
	{

		if($loc[$i] == "")
		{
			$l = "JUST DELIVERY";
		}
		else
		{
			$l = strtoupper($loc[$i]);
		}

		$query = "UPDATE driver SET active=1, location='$l' WHERE id=$data[$i]";
		if(mysqli_query($conn, $query))
		{
			$check = 1;
		}
	}

	if($check==1)
	{
		echo "Done";
	}

	/*foreach($data as $d $locs as $l)
	{
		if($l == "")
		{
			$l = "JUST DELIVERY";
		}
		else
		{
			$l = strtoupper($l);
		}
		$query = "UPDATE driver SET active=1 WHERE id=$d AND location=$l";
		if(mysqli_query($conn, $query));
	}*/

?>