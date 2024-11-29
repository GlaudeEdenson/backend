<?php
session_start();

// En-têtes pour gérer les requêtes CORS et JSON
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Inclusion de la configuration pour la connexion à la base de données
include './api.php';

// Lecture et décodage des données JSON reçues
$data = json_decode(file_get_contents("php://input"), true);

// Vérification des données reçues
if (!$data) {
    echo json_encode(["success" => false, "message" => "Aucune donnée reçue ou erreur de décodage JSON."]);
    exit;
}

// Vérification des champs obligatoires
if (empty($data["name"]) || empty($data["email"]) || empty($data["message"])) {
    echo json_encode(["success" => false, "message" => "Veuillez remplir tous les champs !"]);
    exit;
}

// Validation des données (adresse email valide)
if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Adresse e-mail invalide."]);
    exit;
}

// Extraction des données
$name = $data["name"];
$email = $data["email"];
$message = $data["message"];

// Préparation et exécution de la requête SQL
$stmt = $conn->prepare("INSERT INTO contact (`name`, `email`, `message`) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Erreur de préparation de la requête : " . $conn->error]);
    exit;
}

$stmt->bind_param("sss", $name, $email, $message);
if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Erreur d'enregistrement : " . $stmt->error]);
    exit;
}

// Si tout est réussi, renvoyer un message de succès
echo json_encode(["success" => true, "message" => "Données enregistrées avec succès."]);
exit;

