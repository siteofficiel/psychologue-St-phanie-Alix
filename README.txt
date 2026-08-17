SITE WEB — Stéphanie ALIX, Psychologue clinicienne (Taden / Dinan)
==================================================================

CONTENU DE L'ARCHIVE
--------------------
- index.html ................ Page d'accueil
- le-psychologue.html ....... Le métier de psychologue
- prises-en-charge.html ..... Prises en charge
- honoraires.html ........... Honoraires et horaires
- localisation.html ......... Coordonnées et plan d'accès
- blog.html ................. Blog / témoignages (+ espace admin intégré)
- liens-utiles.html ......... Liens utiles
- contact.html .............. Contact
- mentions-legales.html ..... Mentions légales
- politique-de-confidentialite.html .. Politique de confidentialité (RGPD)
- cookies.html .............. Gestion des cookies
- mon-soutien-psy.html ...... Informations réglementaires « Mon soutien psy »
- logo.png .................. Logo (aussi utilisé comme favicon)
- images/lavande.jpg ........ Image de fond de la page d'accueil
- articles.json ............. Articles du blog (mis à jour AUTOMATIQUEMENT
                             par l'espace admin)
- admin-api.php ............. Module serveur de l'espace admin (PHP)

GOOGLE ANALYTICS (ACTIF — avec consentement RGPD)
-------------------------------------------------
Identifiant de mesure : G-G7EKBFZ3NX
Le script est placé en haut du <head> de chacune des pages, mais le dépôt
des cookies de mesure d'audience est soumis à votre consentement : un
bandeau cookies s'affiche sur toutes les pages (« Tout accepter » /
« Refuser »). Sans consentement, aucune donnée de mesure n'est enregistrée.
Les détails figurent sur la page cookies.html.

ESPACE ADMINISTRATEUR DU BLOG (MISE À JOUR AUTOMATIQUE)
--------------------------------------------------------
- Ouvrez la page blog.html.
- Tout en bas, cliquez sur le lien discret « Espace admin ».
- Mot de passe : Zack2026

L'espace admin s'ouvre DANS la page blog.html. Les articles ajoutés ou
supprimés sont enregistrés SUR LE SERVEUR (fichier articles.json) : ils
sont visibles immédiatement par tous les visiteurs, sans rien renvoyer.

POUR CHANGER LE MOT DE PASSE :
1. Ouvrez le fichier admin-api.php dans un éditeur de texte.
2. Modifiez la ligne :
       $ADMIN_PASSWORD = 'Zack2026';
   par votre propre mot de passe.

IMPORTANT :
- Votre hébergement doit supporter PHP (OVH, Hostinger, o2switch, Ionos,
  LWS, Amen, etc.).
- Le fichier articles.json doit être accessible en écriture par PHP.
- L'espace admin ne fonctionne pas si vous ouvrez blog.html en
  double-cliquant sans serveur : testez sur votre hébergement en ligne.

COMMENT PUBLIER LE SITE
-----------------------
1. Décompressez entièrement cette archive (clic droit > "Extraire tout").
2. Envoyez TOUT le contenu (les 12 fichiers .html + logo.png + le dossier
   images + articles.json + admin-api.php) à la racine de votre
   hébergement web (via FTP ou l'interface de votre hébergeur).
3. La page d'accueil doit s'appeler "index.html" : elle s'ouvrira
   automatiquement quand on visite votre nom de domaine.
