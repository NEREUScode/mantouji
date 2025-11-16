<?php

/**
 * BACKUP SIMPLE - Mantouji
 * 
 * Sauvegarde toutes les données en JSON
 * 
 * Usage: php backup.php
 */

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configuration
$backupDir = __DIR__ . '/backups';
$timestamp = date('Y-m-d_H-i-s');

// Créer le dossier backups
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Connexion base de données
$pdo = new PDO(
    "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'] . ";charset=utf8mb4",
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "✅ Connexion réussie\n\n";

// ============================================
// BACKUP COMMENTAIRES + RATINGS
// ============================================

echo "📊 Backup des commentaires...\n";

$comments = $pdo->query("
    SELECT 
        c.id,
        c.comment,
        c.rating,
        c.product_id,
        c.user_id,
        c.created_at,
        p.name as product_name,
        u.name as user_name,
        u.email as user_email
    FROM comments c
    LEFT JOIN products p ON c.product_id = p.id
    LEFT JOIN users u ON c.user_id = u.id
    ORDER BY c.id
")->fetchAll(PDO::FETCH_ASSOC);

echo "   → " . count($comments) . " commentaires\n";

file_put_contents(
    "$backupDir/comments_$timestamp.json",
    json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// ============================================
// BACKUP PRODUITS
// ============================================

echo "📦 Backup des produits...\n";

$products = $pdo->query("
    SELECT 
        p.id,
        p.name,
        p.image,
        p.reviews,
        p.reviews_number,
        p.user_id,
        p.created_at,
        u.name as owner_name,
        u.email as owner_email
    FROM products p
    LEFT JOIN users u ON p.user_id = u.id
    ORDER BY p.id
")->fetchAll(PDO::FETCH_ASSOC);

echo "   → " . count($products) . " produits\n";

file_put_contents(
    "$backupDir/products_$timestamp.json",
    json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// ============================================
// BACKUP UTILISATEURS
// ============================================

echo "👥 Backup des utilisateurs...\n";

$users = $pdo->query("
    SELECT *
    FROM users
    ORDER BY id
")->fetchAll(PDO::FETCH_ASSOC);

// Supprimer le mot de passe pour sécurité
foreach ($users as &$user) {
    unset($user['password']);
    unset($user['remember_token']);
}

echo "   → " . count($users) . " utilisateurs\n";

file_put_contents(
    "$backupDir/users_$timestamp.json",
    json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

// ============================================
// BACKUP SQL COMPLET
// ============================================

echo "💾 Backup SQL...\n";

$sqlFile = "$backupDir/mantouji_$timestamp.sql";
$cmd = sprintf(
    'mysqldump -h %s -u %s %s %s > %s 2>&1',
    escapeshellarg($_ENV['DB_HOST']),
    escapeshellarg($_ENV['DB_USERNAME']),
    $_ENV['DB_PASSWORD'] ? '-p' . escapeshellarg($_ENV['DB_PASSWORD']) : '',
    escapeshellarg($_ENV['DB_DATABASE']),
    escapeshellarg($sqlFile)
);

exec($cmd, $output, $code);

if ($code === 0 && file_exists($sqlFile)) {
    echo "   → " . round(filesize($sqlFile)/1024, 2) . " KB\n";
} else {
    echo "   → Ignoré (mysqldump non disponible)\n";
}

// ============================================
// RÉSUMÉ
// ============================================

echo "\n✅ BACKUP TERMINÉ !\n\n";
echo "📁 Dossier : backups/\n";
echo "📊 Fichiers :\n";
echo "   • comments_$timestamp.json\n";
echo "   • products_$timestamp.json\n";
echo "   • users_$timestamp.json\n";
if ($code === 0) {
    echo "   • mantouji_$timestamp.sql\n";
}
echo "\n";

