<?php
error_reporting(0);


	$conn = mysqli_connect("localhost", "dotato", "qwerty1234", "justdel2");
	if(!$conn)
	{
		die("Connection error:" . mysql_connect_error());
	}

	if(isset($_POST['p']))
	{
		$p=$_POST['p'];

		$res="SELECT * FROM orders where id=$p";
		$res = mysqli_query($conn, $res);
		if($res->num_rows == 0) {
			echo "Order doesn't exist";
     	}
     	else
     	{
		
			if(isset($_POST['q']))
			{
				if($_POST['q']=="deli")
				{
					$temp = "SELECT time_left FROM orders WHERE id=$p";
					$res = mysqli_query($conn, $temp);
					$row = mysqli_fetch_row($res);
					if(intval((time()-$row[0])/60) < 0)
					{
						$query = "UPDATE orders SET status='late delivered', assigned_to='-' where id=$p";	
					}
					else
					{
						$query = "UPDATE orders SET status='delivered', assigned_to='-' where id=$p";
					}
				}
				elseif($_POST['q']=="cancel")
				{
					$query = "UPDATE orders SET status='cancelled', assigned_to='-' where id=$p";
				}
				elseif($_POST['q']=="delete")
				{
					$query = "DELETE FROM orders where id=$p";
				}

				if(mysqli_query($conn, $query))
				{
					echo "Updated";
				}
				else
				{
					echo "Error: " . mysqli_error($conn); 
				}
			}
			if(isset($_POST['dri']))
			{
				$dri = $_POST['dri'];

				//Check if driver is active or not.
				$sql = mysqli_query($conn, "SELECT * FROM driver where name='$dri' AND active=1");
				if(mysqli_num_rows($sql) == 0) {
					echo "Driver inactive";
	     		}
				else {
					$query = "UPDATE orders SET assigned_to='$dri' where id=$p";
					if(mysqli_query($conn, $query))
					{
						echo "Assigned";
					}
					else
					{
						echo "Error: " . mysqli_error($conn); 
					}

				}
					
			}
		}
	}

?>