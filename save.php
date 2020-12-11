<?php

session_start();

require '../db_connection.php';


if($_REQUEST['action'] == 'delete') {
	// delete save
	$query = "DELETE FROM saves WHERE user_email = '{$_SESSION['username']}' AND item_id = '{$_REQUEST['item_id']}'"; 
		
	// Execute the query and store the result set 
	$result = mysqli_query($db, $query); 

	if ($result) {
		// it return number of rows in the table. 
		 
	 	printf("Item removed from favourites."); 
	 	die();
		
	} else {
		echo "Error: " . $query . "<br>" . mysqli_error($db);
	}
}


// check if already saved
$query = "SELECT * FROM saves WHERE user_email = '{$_SESSION['username']}' AND item_id = '{$_REQUEST['item_id']}'"; 
	
// Execute the query and store the result set 
$result = mysqli_query($db, $query); 

if ($result) {
	// it return number of rows in the table. 
	$row = mysqli_num_rows($result); 
	if ($row) { 
	 	printf("Item already saved to favourites."); 
	 	die();
	}
} else {
	echo "Error: " . $query . "<br>" . mysqli_error($db);
}

// var_dump($_REQUEST);
// die();
// $source = serialize($_REQUEST['value']);
$q = "INSERT INTO saves (user_email, item_id) VALUES ('{$_SESSION['username']}', '{$_REQUEST['item_id']}')";
// $sql = mysqli_query($db, $q);
if(mysqli_query($db, $q))
	echo '👍 Item saved to profile';
else
	echo "Error: " . $q . "<br>" . mysqli_error($db);
?>