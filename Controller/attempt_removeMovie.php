<?php

include '../Model/DPH-api.php';

session_start();


//if (!isset($_SESSION['LoggedIn']) || $_SESSION['Admin_Status'] != 1)
//{
//  header("Location: ../View/index.php");
//}
//else
//{
$movieid = $_GET['id'];
RemoveMovieByID($movieid);
header('location: ../View/removeMovie.php');
//}
?>
