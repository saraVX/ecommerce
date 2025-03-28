<?php

session_start();

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


$stmt = $pdo->query("SELECT * FROM livres");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librairie Élégante</title>
    <style>
        /* Styles de base */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a1a;
            color: white;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            position: relative;
        }

        /* Fond animé coloré */
        .animated-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #3498db, #9b59b6, #e74c3c, #f1c40f, #2ecc71);
            background-size: 400% 400%;
            animation: moveBackground 15s ease infinite;
            z-index: -1;
        }

        @keyframes moveBackground {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Effet de défilement fluide */
        html {
            scroll-behavior: smooth;
        }

        /* Header */
        header {
            background-color: rgba(44, 62, 80, 0.9);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: center;
            background-color: rgba(52, 73, 94, 0.9);
            padding: 15px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 80px;
            z-index: 10;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 15px;
            font-size: 18px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        nav a:hover {
            background-color: #2980b9;
            border-radius: 5px;
            transform: translateY(-3px);
        }

        /* Section Accueil */
        .home-section {
            padding: 100px 20px;
            text-align: center;
            background: rgba(52, 152, 219, 0.8);
            color: white;
            margin-bottom: 40px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .home-section h2 {
            font-size: 36px;
            margin: 20px 0;
        }

        .home-section p {
            font-size: 20px;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Section Produits */
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            padding: 20px;
        }

        .product {
            background-color: rgba(255, 255, 255, 0.9);
            border: 2px solid #ddd;
            border-radius: 8px;
            width: 220px;
            padding: 15px;
            margin: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .product:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product h3 {
            font-size: 22px;
            color: #2980b9;
            margin: 15px 0;
        }

        .product p {
            color: #555;
            font-size: 16px;
            line-height: 1.4;
        }

        .product button {
            background-color: #2980b9;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .product button:hover {
            background-color: #1abc9c;
        }

        /* Panier */
        .cart {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: rgba(41, 128, 185, 0.9);
            padding: 15px 20px;
            border-radius: 10px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            z-index: 10;
        }

        .cart:hover {
            background-color: #1abc9c;
            transform: scale(1.05);
        }

        #cartDetails {
            display: none;
            position: fixed;
            top: 80px;
            right: 20px;
            background-color: rgba(255, 255, 255, 0.95);
            color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            backdrop-filter: blur(10px);
            z-index: 10;
        }

        .cartItem {
            margin: 10px 0;
        }

        .cartItem span {
            font-weight: bold;
        }

        .btn {
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #1abc9c;
        }

        /* Barre de recherche */
        .search-bar {
            margin: 20px auto;
            text-align: center;
        }

        .search-bar input {
            padding: 10px;
            width: 300px;
            border: 2px solid #2980b9;
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .search-bar input:focus {
            border-color: #1abc9c;
        }

        /* Filtres */
        .filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 20px;
            gap: 10px;
        }

        .filters button {
            padding: 10px 20px;
            background-color: rgba(41, 128, 185, 0.9);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .filters button:hover {
            background-color: #1abc9c;
            transform: scale(1.05);
        }

        .filters button.active {
            background-color: #1abc9c;
        }

        /* Modal pour la description */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .modal-content h3 {
            color: #2980b9;
            margin-bottom: 15px;
        }

        .modal-content p {
            color: #555;
            line-height: 1.6;
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .close-modal:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <!-- Fond animé -->
    <div class="animated-background"></div>

    <!-- Header et Navigation -->
    <header>
        Librairie Élégante
    </header>

    <nav>
        <a href="#homePage">Accueil</a>
        <a href="#productsPage">Nos Livres</a>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="connexion.html">Connexion</a>
        <?php else:?> 
            <a href="profil.php">Profil</a> 
            <a href="deconnexion.php">Se deconnecter</a>
        <?php endif; ?>
    </nav>

    <!-- Barre de recherche -->
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Rechercher un livre..." oninput="filterProducts()">
    </div>

    <!-- Filtres -->
    <div class="filters">
        <button onclick="filterByGenre('all')" class="active">Tous</button>
        <button onclick="filterByGenre('romance')">Romance</button>
        <button onclick="filterByGenre('action')">Action</button>
        <button onclick="filterByGenre('sci-fi')">Science-Fiction</button>
        <button onclick="filterByGenre('thriller')">Thriller</button>
        <button onclick="filterByGenre('poesie')">Poésie</button>
    </div>

    <!-- Section Accueil -->
    <div id="homePage" class="home-section">
        <h2>Bienvenue dans notre Librairie Élégante</h2>
        <p>Découvrez une sélection raffinée de livres allant de la fiction classique aux dernières nouveautés, soigneusement choisies pour satisfaire votre soif de lecture. Explorez, lisez et laissez-vous séduire par nos titres incontournables.</p>
    </div>

    <!-- Section Produits -->
    <div id="productsPage" class="container">
        <?php foreach ($products as $product): ?>
            <div class="product" data-genre="<?= htmlspecialchars($product['genre']) ?>">
                <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['titre']) ?>">
                <h3><?= htmlspecialchars($product['titre']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p><?= htmlspecialchars($product['prix']) ?> €</p>
                <button class="btn" onclick="addToCart('<?= htmlspecialchars($product['titre']) ?>', <?= htmlspecialchars($product['prix']) ?>)">Ajouter au panier</button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Panier -->
    <div class="cart" onclick="toggleCartDetails()">
        Panier (<span id="cartCount">0</span> articles) - Total: <span id="cartTotal">0.00 €</span>
    </div>

    <div id="cartDetails">
        <h3>Panier</h3>
        <div id="cartItems"></div>
        <button class="btn" onclick="clearCart()">Vider le panier</button>
    </div>

    <!-- Modal pour la description -->
    <div id="descriptionModal" class="modal">
        <div class="modal-content">
            <h3 id="modalTitle"></h3>
            <p id="modalDescription"></p>
            <button class="close-modal" onclick="closeDescription()">Fermer</button>
        </div>
    </div>

    <script>
        let cart = [];
        let cartTotal = 0;

        function addToCart(name, price) {
            cart.push({ name, price });
            cartTotal += price;
            updateCart();
        }

        function updateCart() {
            document.getElementById('cartCount').innerText = cart.length;
            document.getElementById('cartTotal').innerText = cartTotal.toFixed(2) + ' €';
            let cartItems = document.getElementById('cartItems');
            cartItems.innerHTML = '';
            cart.forEach(item => {
                let cartItem = document.createElement('div');
                cartItem.className = 'cartItem';
                cartItem.innerHTML = `
                <span>${item.name}</span> - ${item.price.toFixed(2)} € \n
                <button class="btn" onclick="clearOneCart('${item.name}')">-</button>

                `;
                cartItems.appendChild(cartItem);
            });
        }

        function toggleCartDetails() {
            let cartDetails = document.getElementById('cartDetails');
            cartDetails.style.display = cartDetails.style.display === 'none' ? 'block' : 'none';
        }

         // Fonction pour supprimer un produit du panier
        function clearOneCart(name) {
            // Si name est passé, on supprime le produit correspondant
            let itemIndex = cart.findIndex(item => item.name === name);
            
            if (itemIndex !== -1) {
                // Supprimer l'élément du panier
                cartTotal -= cart[itemIndex].price;  // Réduire le total du prix de l'article
                cart.splice(itemIndex, 1);           // Supprimer l'élément à cet index
            }

            updateCart(); // Mettre à jour l'affichage du panier
        }

        // Fonction pour vider le panier entier
        function clearCart() {
            cart = [];
            cartTotal = 0;
            updateCart();
        }

        function filterProducts() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let products = document.querySelectorAll('.product');
            products.forEach(product => {
                let name = product.querySelector('h3').innerText.toLowerCase();
                if (name.includes(input)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }

        function filterByGenre(genre) {
            let products = document.querySelectorAll('.product');
            products.forEach(product => {
                if (genre === 'all' || product.getAttribute('data-genre') === genre) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
            let buttons = document.querySelectorAll('.filters button');
            buttons.forEach(button => {
                button.classList.remove('active');
            });
            document.querySelector(`.filters button[onclick="filterByGenre('${genre}')"]`).classList.add('active');
        }

        function showDescription(title, description) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalDescription').innerText = description;
            document.getElementById('descriptionModal').style.display = 'flex';
        }

        function closeDescription() {
            document.getElementById('descriptionModal').style.display = 'none';
        }
    </script>
</body>
</html>