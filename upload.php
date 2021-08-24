  <?php
    //AUMENTAR VARIABLE CONTADOR
	include("config.php");
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);} 
	$sql = "UPDATE cont SET cont=cont+1 WHERE id=1";
	if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
	} else {
    echo "Error updating record: " . $conn->error;
	}
	$conn->close();
	//AUMENTAR VARIABLE CONTADOR
  ?>