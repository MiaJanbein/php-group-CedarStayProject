<?php 
	function connect(){
		$server="localhost";
		$username="root";
		$pass="your_new_password";
		$db='hotelmanagement';
		$mysqli_connect=new mysqli($server,$username,$pass,$db);
		if($mysqli_connect->connect_error){
			 exit('Error in connection: ' . $mysqli_connect->connect_error);
		}
		return $mysqli_connect;
	}
	

?>