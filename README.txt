SITE WEB — Stéphanie ALIX, Psychologue clinicienne (Taden / Dinan)
==================================================================

CONTENU DE L'ARCHIVE
--------------------
- index.html ................ Page d'accueil
- le-psychologue.html ....... Le métier de psychologue
- prises-en-charge.html ..... Prises en charge
- honoraires.html ........... Honoraires et horaires
- localisation.html ......... Coordonnées et plan d'accès
- blog.html ................. Blog / témoignages (chargé depuis articles.json)
- liens-utiles.html ......... Liens utiles
- contact.html .............. Contact
- logo.png .................. Logo (aussi utilisé comme favicon)
- images/lavande.jpg ........ Image de fond de la page d'accueil
- articles.json ............. Contenu du blog (géré par l'espace admin)
- admin/index.php ........... Espace administrateur du blog

GOOGLE ANALYTICS (ACTIF)
------------------------
Identifiant de mesure : G-G7EKBFZ3NX
Le script est placé en haut du <head> de chacune des 8 pages.

ESPACE ADMINISTRATEUR DU BLOG (NOUVEAU)
----------------------------------------
Accès : https://votre-site.fr/admin/
Mot de passe : Zack2026

L'espace admin permet d'AJOUTER et de SUPPRIMER des articles du blog,
sans toucher au code. Chaque article est enregistré dans le fichier
articles.json, que la page blog.html lit automatiquement.

POUR CHANGER LE MOT DE PASSE :
1. Ouvrez le fichier admin/index.php dans un éditeur de texte.
2. Modifiez la ligne :
       $ADMIN_PASSWORD = 'Zack2026';
   par votre propre mot de passe.
3. Enregistrez et renvoyez le fichier sur votre hébergement.

IMPORTANT :
- Votre hébergement doit supporter PHP (c'est le cas sur OVH, Hostinger,
  o2switch, Ionos, LWS, etc.). Sur un hébergement 100 % statique
  (GitHub Pages, Netlify sans fonctions serveur), l'espace admin ne
  fonctionnera pas.
- Le dossier du site doit être accessible en écriture par PHP pour que
  articles.json puisse être mis à jour (c'est le cas par défaut sur les
  hébergements mutualisés).

COMMENT PUBLIER LE SITE
-----------------------
1. Décompressez entièrement cette archive (clic droit > "Extraire tout").
2. Envoyez TOUT le contenu (les 8 fichiers .html + logo.png + le dossier
   images + articles.json + le dossier admin) à la racine de votre
   hébergement web (via FTP, ou l'interface de votre hébergeur).
3. La page d'accueil doit s'appeler "index.html" : elle s'ouvrira
   automatiquement quand on visite votre nom de domaine.
