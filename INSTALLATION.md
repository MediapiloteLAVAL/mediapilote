# 📦 Installation du système de mise à jour

## 1. Préparer votre serveur web

Créez un dossier dédié sur votre serveur :
```
https://mediapilote-laval.fr/theme-updates/
├── update-server.php
├── download.php
└── releases/ (dossier vide, permissions 755)
```

## 2. Uploader les fichiers

Via FTP/SFTP, uploadez :
- `update-server.php` 
- `download.php`
- Créez le dossier `releases/` avec permissions d'écriture

## 3. Tester l'installation

Visitez : `https://mediapilote-laval.fr/theme-updates/update-server.php`
Vous devriez voir : `{"error":"Action non valide"}`

## 4. Premier déploiement

Depuis votre environnement de développement :
```bash
cd /path/to/your/theme
./deploy-theme.sh 1.0.1
```

## 5. Vérifier sur un site client

Dans l'admin WordPress :
Apparence → Mises à jour → "Vérifier les mises à jour"

🎉 C'est prêt !
