<?php

/**
 * Script de nettoyage de la base de données Mantouji
 * 
 * Ce script supprime toutes les données de test de la base de données
 * tout en préservant la structure des tables.
 * 
 * ⚠️  ATTENTION : Cette opération est IRRÉVERSIBLE !
 * Assurez-vous d'avoir fait un backup avant d'exécuter ce script.
 * 
 * Usage :
 * php clean_database.php
 */

require __DIR__ . '/vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Connexion à la base de données
$host = $_ENV['DB_HOST'] ?? 'localhost';
$database = $_ENV['DB_DATABASE'] ?? 'mantouji';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n";
    echo "   Base de données : $database\n";
    echo "\n";
} catch (PDOException $e) {
    die("❌ Erreur de connexion à la base de données : " . $e->getMessage() . "\n");
}

// Fonction pour compter les enregistrements
function countRecords($pdo, $table) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
    return $stmt->fetchColumn();
}

// Afficher les statistiques avant nettoyage
echo "📊 STATISTIQUES AVANT NETTOYAGE\n";
echo "================================\n";

$tables = ['users', 'products', 'comments'];
$counts = [];

foreach ($tables as $table) {
    try {
        $count = countRecords($pdo, $table);
        $counts[$table] = $count;
        echo "   $table : $count enregistrements\n";
    } catch (PDOException $e) {
        echo "   $table : Table non trouvée ou erreur\n";
    }
}

echo "\n";

// Demander confirmation
echo "⚠️  ATTENTION : Vous êtes sur le point de SUPPRIMER toutes les données !\n";
echo "\n";
echo "Cette opération va supprimer :\n";
echo "   • Tous les utilisateurs (sauf l'admin si vous en avez un)\n";
echo "   • Tous les produits\n";
echo "   • Tous les commentaires et avis\n";
echo "\n";
echo "Cette opération est IRRÉVERSIBLE !\n";
echo "\n";
echo "Tapez 'OUI' en majuscules pour confirmer : ";

$handle = fopen("php://stdin", "r");
$confirmation = trim(fgets($handle));

if ($confirmation !== 'OUI') {
    echo "\n❌ Opération annulée par l'utilisateur\n";
    exit(0);
}

echo "\n";
echo "🧹 NETTOYAGE EN COURS...\n";
echo "========================\n";

try {
    // Désactiver les contraintes de clés étrangères temporairement
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Supprimer les commentaires
    echo "   Suppression des commentaires...";
    $stmt = $pdo->exec("DELETE FROM comments");
    echo " ✅ ($stmt supprimés)\n";
    
    // Supprimer les produits
    echo "   Suppression des produits...";
    $stmt = $pdo->exec("DELETE FROM products");
    echo " ✅ ($stmt supprimés)\n";
    
    // Supprimer les utilisateurs (option : garder l'admin)
    echo "   Suppression des utilisateurs...";
    
    // Option 1 : Supprimer TOUS les utilisateurs
    $stmt = $pdo->exec("DELETE FROM users");
    
    // Option 2 : Garder l'admin (décommenter si vous voulez garder un admin)
    // $stmt = $pdo->exec("DELETE FROM users WHERE email != 'admin@mantouji.org'");
    
    echo " ✅ ($stmt supprimés)\n";
    
    // Réinitialiser les auto-increment
    echo "   Réinitialisation des compteurs...";
    $pdo->exec("ALTER TABLE comments AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE products AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE users AUTO_INCREMENT = 1");
    echo " ✅\n";
    
    // Réactiver les contraintes de clés étrangères
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "\n";
    echo "📊 STATISTIQUES APRÈS NETTOYAGE\n";
    echo "================================\n";
    
    foreach ($tables as $table) {
        try {
            $count = countRecords($pdo, $table);
            echo "   $table : $count enregistrements\n";
        } catch (PDOException $e) {
            echo "   $table : Erreur\n";
        }
    }
    
    echo "\n";
    echo "✅ NETTOYAGE TERMINÉ AVEC SUCCÈS !\n";
    echo "\n";
    echo "La base de données est maintenant propre et prête pour la production.\n";
    echo "Vous pouvez maintenant créer vos premiers utilisateurs et produits.\n";
    
} catch (PDOException $e) {
    echo "\n";
    echo "❌ ERREUR lors du nettoyage : " . $e->getMessage() . "\n";
    echo "\n";
    echo "La base de données peut être dans un état incohérent.\n";
    echo "Veuillez restaurer votre backup si disponible.\n";
    exit(1);
}

