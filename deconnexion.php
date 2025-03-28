<?php
session_start();
unset($_SESSION['user_id'], $_SESSION['username']);
header("Location: products.php"); // Rediriger vers la page des produits