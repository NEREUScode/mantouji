<?php

/**
 * CLEAN DATABASE - Mantouji
 * 
 * Supprime toutes les données de test
 * 
 * Usage: php clean.php
 */

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Connexion
$pdo = new PDO(
    "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'] . ";charset=utf8mb4",
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "\n";
echo "⚠️  NETTOYAGE DE LA BASE DE DONNÉES\n";
echo "═══════════════════════════════════\n\n";

// Compter avant
$countComments = $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
$countProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$countUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

echo "📊 AVANT :\n";
echo "   • Commentaires : $countComments\n";
echo "   • Produits : $countProducts\n";
echo "   • Utilisateurs : $countUsers\n\n";

echo "⚠️  Cette action est IRRÉVERSIBLE !\n";
echo "   Tapez 'OUI' pour confirmer : ";

$handle = fopen("php://stdin", "r");
$confirm = trim(fgets($handle));

if ($confirm !== 'OUI') {
    echo "\n❌ Annulé\n\n";
    exit(0);
}

echo "\n🧹 Nettoyage...\n\n";

// Désactiver contraintes
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

// Supprimer
$pdo->exec("DELETE FROM comments");
echo "   ✅ Commentaires supprimés\n";

$pdo->exec("DELETE FROM products");
echo "   ✅ Produits supprimés\n";

$pdo->exec("DELETE FROM users");
echo "   ✅ Utilisateurs supprimés\n";

// Réinitialiser auto-increment
$pdo->exec("ALTER TABLE comments AUTO_INCREMENT = 1");
$pdo->exec("ALTER TABLE products AUTO_INCREMENT = 1");
$pdo->exec("ALTER TABLE users AUTO_INCREMENT = 1");
echo "   ✅ Compteurs réinitialisés\n";

// Réactiver contraintes
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

echo "\n📊 APRÈS :\n";
echo "   • Commentaires : 0\n";
echo "   • Produits : 0\n";
echo "   • Utilisateurs : 0\n\n";

echo "✅ BASE NETTOYÉE !\n";
echo "🎉 Prête pour les vraies données\n\n";

