<?php

/**
 * Script d'export des commentaires et avis vers Google Sheets
 * 
 * Ce script exporte toutes les données de la table comments vers Google Sheets
 * pour backup avant nettoyage de la base de données.
 * 
 * Installation :
 * composer require google/apiclient:"^2.0"
 * 
 * Configuration :
 * 1. Créer un projet Google Cloud : https://console.cloud.google.com/
 * 2. Activer l'API Google Sheets
 * 3. Créer des credentials (Service Account)
 * 4. Télécharger le fichier JSON des credentials
 * 5. Placer le fichier dans : storage/app/google-credentials.json
 * 6. Créer un Google Sheet et partager avec l'email du service account
 * 7. Copier l'ID du Google Sheet dans la variable $spreadsheetId ci-dessous
 */

require __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Sheets;

// Configuration
$spreadsheetId = 'VOTRE_SPREADSHEET_ID_ICI'; // Remplacer par l'ID de votre Google Sheet
$range = 'Commentaires!A1'; // Nom de la feuille et cellule de départ

// Connexion à la base de données Laravel
$dbConfig = require __DIR__ . '/config/database.php';
$defaultConnection = env('DB_CONNECTION', 'mysql');
$config = $dbConfig['connections'][$defaultConnection];

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
        $config['username'],
        $config['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie\n";
} catch (PDOException $e) {
    die("❌ Erreur de connexion à la base de données : " . $e->getMessage() . "\n");
}

// Récupérer tous les commentaires avec les informations liées
$query = "
    SELECT 
        c.id,
        c.comment,
        c.rating,
        c.created_at,
        c.updated_at,
        p.name as product_name,
        p.id as product_id,
        u.name as user_name,
        u.email as user_email,
        u.id as user_id
    FROM comments c
    LEFT JOIN products p ON c.product_id = p.id
    LEFT JOIN users u ON c.user_id = u.id
    ORDER BY c.created_at DESC
";

$stmt = $pdo->query($query);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "📊 Nombre de commentaires trouvés : " . count($comments) . "\n";

if (count($comments) === 0) {
    echo "ℹ️  Aucun commentaire à exporter\n";
    exit(0);
}

// Configuration Google Sheets API
$credentialsPath = __DIR__ . '/storage/app/google-credentials.json';

if (!file_exists($credentialsPath)) {
    die("❌ Fichier de credentials Google non trouvé : $credentialsPath\n" .
        "   Veuillez télécharger le fichier JSON depuis Google Cloud Console\n");
}

try {
    $client = new Client();
    $client->setApplicationName('Mantouji Export');
    $client->setScopes([Sheets::SPREADSHEETS]);
    $client->setAuthConfig($credentialsPath);
    
    $service = new Sheets($client);
    
    echo "✅ Connexion à Google Sheets API réussie\n";
} catch (Exception $e) {
    die("❌ Erreur de connexion à Google Sheets API : " . $e->getMessage() . "\n");
}

// Préparer les données pour Google Sheets
$values = [
    // En-têtes
    [
        'ID',
        'Commentaire',
        'Note (étoiles)',
        'Nom du Produit',
        'ID Produit',
        'Nom de l\'Utilisateur',
        'Email Utilisateur',
        'ID Utilisateur',
        'Date de Création',
        'Date de Modification'
    ]
];

// Ajouter les données
foreach ($comments as $comment) {
    $values[] = [
        $comment['id'],
        $comment['comment'],
        $comment['rating'],
        $comment['product_name'] ?? 'N/A',
        $comment['product_id'] ?? 'N/A',
        $comment['user_name'] ?? 'N/A',
        $comment['user_email'] ?? 'N/A',
        $comment['user_id'] ?? 'N/A',
        $comment['created_at'],
        $comment['updated_at']
    ];
}

// Envoyer les données à Google Sheets
$body = new \Google\Service\Sheets\ValueRange([
    'values' => $values
]);

$params = [
    'valueInputOption' => 'RAW'
];

try {
    $result = $service->spreadsheets_values->update(
        $spreadsheetId,
        $range,
        $body,
        $params
    );
    
    echo "✅ Export réussi vers Google Sheets !\n";
    echo "   Lignes exportées : " . $result->getUpdatedRows() . "\n";
    echo "   Cellules mises à jour : " . $result->getUpdatedCells() . "\n";
    echo "\n";
    echo "🔗 Lien vers le Google Sheet :\n";
    echo "   https://docs.google.com/spreadsheets/d/$spreadsheetId\n";
    
} catch (Exception $e) {
    die("❌ Erreur lors de l'export vers Google Sheets : " . $e->getMessage() . "\n");
}

// Créer également un backup CSV local
$csvFile = __DIR__ . '/storage/app/backup_comments_' . date('Y-m-d_H-i-s') . '.csv';
$fp = fopen($csvFile, 'w');

// Ajouter le BOM UTF-8 pour Excel
fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

// Écrire les données
foreach ($values as $row) {
    fputcsv($fp, $row);
}

fclose($fp);

echo "✅ Backup CSV local créé : $csvFile\n";
echo "\n";
echo "🎉 Export terminé avec succès !\n";

