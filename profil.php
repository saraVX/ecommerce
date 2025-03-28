<?php
session_start();  // Démarre la session pour accéder aux données utilisateur

// Configuration de la base de données
$host = 'localhost';  // Hôte de la base de données
$dbname = 'librairie_elegane';  
$username = 'root';  // Nom d'utilisateur de la base de données
$password = '';  // Mot de passe de la base de données

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
    exit();
}

// Vérifie si l'utilisateur est connecté (par exemple, si un ID utilisateur existe en session)
if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour voir ou modifier votre profil.";
    exit();
}

// ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];

// Récupérer les données actuelles de l'utilisateur
$sql = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Si l'utilisateur n'existe pas
if (!$user) {
    echo "Utilisateur introuvable.";
    exit();
}

// Mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les nouvelles données du formulaire
    $name = $_POST['username'];
    $email = $_POST['email'];
 
    // Requête pour mettre à jour les données utilisateur
    $sql = "UPDATE users SET name = :name, email = :email, dob = :dob, address = :address, phone = :phone WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    
   // Exécuter la requête
   if ($stmt->execute()) {
    // Message de succès si l'exécution est réussie
    echo "Profil mis à jour avec succès.";
    } else {
    // Message d'erreur si l'exécution échoue
    echo "Erreur lors de la mise à jour du profil.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
</head>
<body>
    <h1>Profil de <?php echo htmlspecialchars($user['username']); ?></h1>

    <!-- Affichage du profil -->
    <table border="1">
        <tr>
            <th>Nom</th>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
    
    </table>

    <br>
    <a href="#edit-form" onclick="document.getElementById('edit-form').style.display = 'block';">Modifier mon profil</a>

    <!-- Formulaire pour modifier le profil -->
    <div id="edit-form" style="display: none;">
        <h2>Modifier mon profil</h2>
        <form method="POST">
            <label for="name">Nom :</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

            <label for="email">Email :</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>

</body>
</html>
