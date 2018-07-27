<?php
error_reporting(0);

	$preptime = 55;

	$conn = mysqli_connect("localhost", "dotato", "qwerty1234", "justdel2");

	if(!$conn)
	{
		die("Connection error:" . mysql_connect_error());
	}

	if(isset($_POST['src']))
	{
		$src = mysqli_real_escape_string($conn, $_POST['src']);
		if(isset($_POST['dest']))
		{
				if($_POST['self'] == 'true')
				{
					$preptime = 8;
				}
				$dest = mysqli_real_escape_string($conn, $_POST['dest']);
				$self = mysqli_real_escape_string($conn, $_POST['self']);
				$t = time();

				$query = "INSERT INTO orders (src, dest, self, org_time, order_time, time_left) VALUES ('$src', '$dest', '$self', NOW(), $t, $preptime)";

				if(mysqli_query($conn, $query))
				{
					echo "Updated";
				}
				else
				{
					echo "Error: " . mysqli_error($conn); 
				}
				return;
		}
	}

?>