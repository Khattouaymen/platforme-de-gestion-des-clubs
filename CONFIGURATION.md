# Ce fichier détaille comment configurer le serveur web pour l'application

## Configuration avec Apache et PHP

1. Assurez-vous que Apache et PHP sont installés sur votre système
2. Configurez un hôte virtuel dans Apache pour pointer vers le répertoire du projet

### Exemple de configuration Apache (httpd.conf ou .htaccess)

```
<VirtualHost *:80>
    ServerName gestion-clubs.local
    DocumentRoot "C:/Users/Pavilion/sfe"
    
    <Directory "C:/Users/Pavilion/sfe">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/gestion_clubs_error.log
    CustomLog ${APACHE_LOG_DIR}/gestion_clubs_access.log combined
</VirtualHost>
```

3. Ajoutez l'entrée suivante dans votre fichier hosts :
```
127.0.0.1 gestion-clubs.local
```

4. Redémarrez Apache

## Utilisation avec le serveur intégré de PHP (pour le développement)

Vous pouvez également utiliser le serveur web intégré de PHP pour le développement :

```
cd C:/Users/Pavilion/sfe
php -S localhost:8000
```

Ensuite, accédez à http://localhost:8000 dans votre navigateur.

## Configuration de la base de données

1. Importez le fichier SQL dans votre serveur MySQL/MariaDB :
```
mysql -u root -p < gestion_clubs.sql
```

2. Vérifiez que les paramètres de connexion dans config/database.php correspondent à votre environnement.
