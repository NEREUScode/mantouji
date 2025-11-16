# Instructions de Nettoyage de la Base de Données Mantouji

Ce guide vous explique comment faire un backup de vos données puis nettoyer la base de données pour la production.

---

## 📋 Table des Matières

1. [Prérequis](#prérequis)
2. [Étape 1 : Backup vers Google Sheets](#étape-1--backup-vers-google-sheets)
3. [Étape 2 : Nettoyage de la base de données](#étape-2--nettoyage-de-la-base-de-données)
4. [Commandes Rapides](#commandes-rapides)
5. [Dépannage](#dépannage)

---

## Prérequis

### Pour l'export Google Sheets

1. **Installer la librairie Google API** :
   ```bash
   composer require google/apiclient:"^2.0"
   ```

2. **Créer un projet Google Cloud** :
   - Aller sur https://console.cloud.google.com/
   - Créer un nouveau projet
   - Activer l'API "Google Sheets API"

3. **Créer un Service Account** :
   - Dans Google Cloud Console, aller dans "APIs & Services" > "Credentials"
   - Cliquer sur "Create Credentials" > "Service Account"
   - Télécharger le fichier JSON des credentials
   - Renommer le fichier en `google-credentials.json`
   - Placer le fichier dans : `storage/app/google-credentials.json`

4. **Créer un Google Sheet** :
   - Créer un nouveau Google Sheet
   - Copier l'ID du Sheet depuis l'URL (entre `/d/` et `/edit`)
   - Partager le Sheet avec l'email du service account (dans le fichier JSON)
   - Donner les droits d'édition

5. **Configurer le script** :
   - Ouvrir `export_to_google_sheets.php`
   - Remplacer `VOTRE_SPREADSHEET_ID_ICI` par l'ID de votre Google Sheet

---

## Étape 1 : Backup vers Google Sheets

### Exécuter l'export

```bash
cd /path/to/mantouji
php export_to_google_sheets.php
```

### Ce que fait le script

- ✅ Exporte tous les commentaires et avis vers Google Sheets
- ✅ Inclut les informations des produits et utilisateurs associés
- ✅ Crée un backup CSV local dans `storage/app/`
- ✅ Affiche le lien vers le Google Sheet

### Résultat attendu

```
✅ Connexion à la base de données réussie
📊 Nombre de commentaires trouvés : 42
✅ Connexion à Google Sheets API réussie
✅ Export réussi vers Google Sheets !
   Lignes exportées : 43
   Cellules mises à jour : 430

🔗 Lien vers le Google Sheet :
   https://docs.google.com/spreadsheets/d/VOTRE_ID

✅ Backup CSV local créé : storage/app/backup_comments_2025-01-17_14-30-00.csv

🎉 Export terminé avec succès !
```

---

## Étape 2 : Nettoyage de la Base de Données

### ⚠️ ATTENTION

**Cette opération est IRRÉVERSIBLE !**

Assurez-vous d'avoir :
- ✅ Fait un backup avec le script d'export
- ✅ Vérifié que le backup est complet
- ✅ Téléchargé le fichier CSV local

### Exécuter le nettoyage

```bash
cd /path/to/mantouji
php clean_database.php
```

### Confirmation requise

Le script vous demandera de taper `OUI` en majuscules pour confirmer.

### Ce que fait le script

- 🗑️ Supprime tous les commentaires
- 🗑️ Supprime tous les produits
- 🗑️ Supprime tous les utilisateurs
- 🔄 Réinitialise les compteurs auto-increment
- 📊 Affiche les statistiques avant/après

### Résultat attendu

```
✅ Connexion à la base de données réussie
   Base de données : mantouji

📊 STATISTIQUES AVANT NETTOYAGE
================================
   users : 15 enregistrements
   products : 42 enregistrements
   comments : 87 enregistrements

⚠️  ATTENTION : Vous êtes sur le point de SUPPRIMER toutes les données !

Cette opération va supprimer :
   • Tous les utilisateurs (sauf l'admin si vous en avez un)
   • Tous les produits
   • Tous les commentaires et avis

Cette opération est IRRÉVERSIBLE !

Tapez 'OUI' en majuscules pour confirmer : OUI

🧹 NETTOYAGE EN COURS...
========================
   Suppression des commentaires... ✅ (87 supprimés)
   Suppression des produits... ✅ (42 supprimés)
   Suppression des utilisateurs... ✅ (15 supprimés)
   Réinitialisation des compteurs... ✅

📊 STATISTIQUES APRÈS NETTOYAGE
================================
   users : 0 enregistrements
   products : 0 enregistrements
   comments : 0 enregistrements

✅ NETTOYAGE TERMINÉ AVEC SUCCÈS !

La base de données est maintenant propre et prête pour la production.
```

---

## Commandes Rapides

### Backup + Nettoyage (séquence complète)

```bash
# 1. Export vers Google Sheets
php export_to_google_sheets.php

# 2. Vérifier que l'export est réussi
# Ouvrir le lien Google Sheets affiché

# 3. Nettoyer la base de données
php clean_database.php
```

### Backup SQL traditionnel (alternative)

```bash
# Backup complet de la base de données
mysqldump -u root -p mantouji > backup_mantouji_$(date +%Y%m%d_%H%M%S).sql

# Restaurer depuis un backup
mysql -u root -p mantouji < backup_mantouji_20250117_143000.sql
```

### Nettoyer uniquement certaines tables

```bash
# Supprimer uniquement les commentaires
mysql -u root -p mantouji -e "DELETE FROM comments;"

# Supprimer uniquement les produits
mysql -u root -p mantouji -e "DELETE FROM products;"

# Supprimer uniquement les utilisateurs (sauf admin)
mysql -u root -p mantouji -e "DELETE FROM users WHERE email != 'admin@mantouji.org';"
```

---

## Dépannage

### Erreur : "Fichier de credentials Google non trouvé"

**Solution** :
1. Vérifier que le fichier `google-credentials.json` est dans `storage/app/`
2. Vérifier les permissions du fichier : `chmod 644 storage/app/google-credentials.json`

### Erreur : "Permission denied to Google Sheets"

**Solution** :
1. Ouvrir le Google Sheet
2. Cliquer sur "Partager"
3. Ajouter l'email du service account (dans le fichier JSON)
4. Donner les droits "Éditeur"

### Erreur : "Connection refused" (base de données)

**Solution** :
1. Vérifier que MySQL est démarré : `sudo systemctl status mysql`
2. Vérifier les credentials dans `.env`
3. Tester la connexion : `mysql -u root -p`

### Erreur : "Class 'Dotenv\Dotenv' not found"

**Solution** :
```bash
composer install
```

### Le script se bloque sur la confirmation

**Solution** :
- Taper exactement `OUI` en majuscules
- Appuyer sur Entrée

---

## Options Avancées

### Garder un utilisateur admin

Modifier `clean_database.php` ligne 95 :

```php
// Au lieu de :
$stmt = $pdo->exec("DELETE FROM users");

// Utiliser :
$stmt = $pdo->exec("DELETE FROM users WHERE email != 'admin@mantouji.org'");
```

### Exporter vers CSV uniquement (sans Google Sheets)

Créer un fichier `export_csv_only.php` :

```php
<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO(
    "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

$stmt = $pdo->query("
    SELECT c.*, p.name as product_name, u.name as user_name, u.email as user_email
    FROM comments c
    LEFT JOIN products p ON c.product_id = p.id
    LEFT JOIN users u ON c.user_id = u.id
");

$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$csvFile = 'backup_comments_' . date('Y-m-d_H-i-s') . '.csv';
$fp = fopen($csvFile, 'w');

fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

fputcsv($fp, array_keys($comments[0])); // Headers

foreach ($comments as $row) {
    fputcsv($fp, $row);
}

fclose($fp);

echo "✅ Export CSV créé : $csvFile\n";
```

---

## Support

Pour toute question ou problème :
- 📧 Contact : Tech-da (https://www.tech-da.com/)
- 🌐 Site web : www.Mantouji.org

---

**Dernière mise à jour** : 17 janvier 2025

