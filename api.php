<?php 
$hostname = "localhost";
$username = "root";
$password = "";
$database = "test";

$conn = mysqli_connect($hostname, $username, $password, $database);
if (!$conn) {
    die("Erreur de connexion à la base de donnée");
}

