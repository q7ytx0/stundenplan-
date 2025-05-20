<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Datenbankverbindung herstellen
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zyro";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Benutzer aus der Datenbank abrufen
    $stmt = $conn->prepare("SELECT id, username, fullname, role, school, status, last_active FROM users");
    $stmt->execute();
    
    // Ergebnisse als Array formatieren
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Benutzer mit zusätzlichen Informationen anreichern
    $enhancedUsers = array_map(function($user) {
        return [
            'id' => $user['id'],
            'username' => $user['username'],
            'fullname' => $user['fullname'],
            'role' => $user['role'],
            'school' => $user['school'],
            'status' => $user['status'] ?? 'offline',
            'lastActive' => $user['last_active'] ?? date('Y-m-d H:i:s'),
            'isTeacher' => $user['role'] === 'teacher'
        ];
    }, $users);
    
    // Als JSON ausgeben
    echo json_encode($enhancedUsers);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Datenbankfehler: ' . $e->getMessage()]);
}

$conn = null;
?> 
