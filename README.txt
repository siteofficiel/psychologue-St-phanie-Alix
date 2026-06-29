SITE WEB — Stéphanie ALIX, Psychologue clinicienne (Taden / Dinan)
==================================================================

CONTENU DE L'ARCHIVE
--------------------
- index.html ................ Page d'accueil
- le-psychologue.html ....... Le métier de psychologue
- prises-en-charge.html ..... Prises en charge
- honoraires.html ........... Honoraires et horaires
- localisation.html ......... Coordonnées et plan d'accès
- blog.html ................. Blog / témoignages
- liens-utiles.html ......... Liens utiles
- contact.html .............. Contact
- logo.png .................. Logo (aussi utilisé comme favicon)
- images/lavande.jpg ........ Image de fond de la page d'accueil

IMPORTANT : conservez le dossier "images" à côté des fichiers .html,
sinon le fond de la page d'accueil ne s'affichera pas.


COMMENT PUBLIER LE SITE
-----------------------
1. Décompressez entièrement cette archive (clic droit > "Extraire tout").
2. Envoyez TOUT le contenu (les 8 fichiers .html + logo.png + le dossier
   images) à la racine de votre hébergement web (via FTP, ou l'interface
   de votre hébergeur : OVH, Hostinger, Netlify, GitHub Pages, etc.).
3. La page d'accueil doit s'appeler "index.html" : elle s'ouvrira
   automatiquement quand on visite votre nom de domaine.


GOOGLE ANALYTICS (à finaliser)
------------------------------
Le suivi Google Analytics (GA4) est déjà intégré, mais avec un
identifiant fictif. Pour l'activer :

1. Créez une propriété sur https://analytics.google.com
2. Récupérez votre identifiant de mesure au format : G-XXXXXXXXXX
3. Dans CHACUN des 8 fichiers .html, remplacez la ligne :
       var GA_ID = 'G-XXXXXXXXXX';
   par votre véritable identifiant, par exemple :
       var GA_ID = 'G-ABC1234567';

Note : conforme RGPD — Google Analytics ne se charge QUE si le visiteur
clique sur "Tout accepter" dans le bandeau cookies.


NOTES TECHNIQUES
----------------
- Le site est responsive (ordinateur, tablette, mobile).
- Tailwind CSS, les polices Google et les icônes Font Awesome sont
  chargés depuis Internet (CDN) : une connexion est nécessaire pour
  un affichage complet. Le site fonctionne sans installation.
- Bouton "Prendre RDV" : pointe vers la page Maiia du cabinet.
