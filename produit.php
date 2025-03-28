<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$host = 'localhost'; 
$dbname = 'librairie_elegane'; 
$username = 'root'; 
$password = '';

try {
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

 
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
if (!$products) {
    die("Aucun produit trouvé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Produits</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .product {
            display: inline-block;
            width: 200px;
            margin: 10px;
            padding: 10px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .product h3 {
            font-size: 18px;
            margin: 10px 0;
        }
        .product p {
            font-size: 14px;
            color: #555;
        }
        .product button {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .product button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <h1>Nos Produits</h1>
    <div class="product-container">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <!-- Affichage de l'image, avec le chemin correct -->
                <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p><?= htmlspecialchars($product['price']) ?> €</p>
                <!-- Ajouter au panier (fonction à définir si nécessaire) -->
                <button onclick="addToCart(<?= htmlspecialchars($product['id']) ?>)">Ajouter au panier</button>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
