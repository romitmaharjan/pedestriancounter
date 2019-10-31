<?php
session_start();
header('Content-Type: text/plain');

if (isset($_POST['id'])) { //Name cannot be empty
        $emp = $_POST['id'];
        echo $emp;
        $_SESSION['id'] = $emp;
        echo $_SESSION['id'];
    }
    
/*elseif(isset($_POST['idFromDropdown'])){
    
    $idForDropdown = $_POST['idFromDropdown'];
    print_r($idForDropdown);
}elseif(isset($_POST['idFromDropdown1'])){
    
    $idForDropdown1 = $_POST['idFromDropdown1'];
    print_r($idForDropdown1);
}else{
	
	echo "NOT from success"; }
    
 //$_SESSION['id'] = $emp;*/

    ?>
