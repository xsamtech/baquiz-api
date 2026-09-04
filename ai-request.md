1️⃣ J'ai enlevé les tables "bank_cards", "about_subjects", "about_titles", "about_contents", "about_dashes". Tu peux retirer tout ce qui les concerne (modèles, contrôleurs, etc.). Et j'ai renommé la table "password_resets" en "password_reset_tokens". J'ai enlevé, à la table "users", la colonne "belongs_to" et j'ai ajouté la colonne "about". J'ai ajouté, à la table "clashs", la colonne "currency". J'ai ajouté, à la table "files", les colonnes "mime_type", "file_size", "width", "height" et "duration". J'ai ajouté, à la table "reasons", la colonne "max_reports"

Mais j'ai aussi ajouté les tables "websites" (mettre les modèles, les contrôleurs, etc.) Ensuite, j'ai aussi ajouté les tables pour gérer la technologie IA dans mon application ("ai_conversations", "ai_messages", "ai_message_files", "ai_tool_calls" et "ai_settings"). Tu vas donc commencer à placer leurs contrôleurs, etc. dans un sous-dossier "AI" pour chaque composant.
> Par exemple, pour le composant "Model", les modèles des autres tables sont déjà dans "app/Models", mais pour les tables pour IA j'ai mis leurs modèles dans "app/Models/AI". Fais donc la même chose pour les autres composants (contrôleurs et autres.)

2️⃣ J'ai ajouté la colonne "uuid" pour certaines tables. Il faut donc en tenir compte dans les modèles et autres composants existants.
> Par exemple, l'enregistrement d'un code généré dans la méthode "store" du contrôleur, l'utilisation de cette colonne dans les APIs, etc.

⚠️ BREF, TU DOIS VÉRIFIER LA MISE À JOUR DE LA BASE DES DONNÉES DANS LE FICHIER "database/baquiz.sql" POUR T'ASSURER QUE LES MODÈLES, LES CONTRÔLEURS ET AUTRES COMPOSANTS DU PROJET SE CONFORMENT À LA BASE DES DONNÉES.

3️⃣ Je t'ai joint le fichier "workflow.pdf" qui contient les URLs pour les Routes Laravel et les descriptions de la navigation ; afin que tu génères l'espace ADMIN, selon le template qui est dans le dossier "public/template" (il y a toutes les pages, même celles de l'authentification et des erreurs) ; donc le projet utilisera la technologie "Laravel+React+Tailwind" et autres selon l'utilité. De ce fait :
- Tout l'espace ADMIN est protégé par une authentification (Si le premier utilisateur crée le compte, le lien pour REGISTER disparait de la page de LOGIN, et cet espace "/register" retourne une page d'erreur 403 Forbidden).

- Les images doivent être stockées dans le dossier "public/assets/img". J'ai déjà créé un dossier "logo" où j'ai mis les SVG de la même dimension que les logos du template. Donc, à chaque fois que tu utiliseras une image, envoie une copie qui se trouve dans le dossier du template (public/template/public/images) vers le dossier du projet (public/assets/img). Parce que le template sera retiré du projet à la fin.

Et pour le favicon (public/assets/img/favicon), 👉 à moins qu'il y ait un autre moyen avec React, tu peux l'appeler avec un code qui ressemble à ce HTML :
<link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
<link rel="manifest" href="/assets/img/favicon/site.webmanifest">

- Dès l'entrée dans l'espace ADMIN, si la table "roles" est vide, on enregistre, en 2 langues (Français et English) pour les colonnes de type JSON (selon Laravel Translatable), les données suivantes :
    - role_nome 1 : Administrateur
      role_description 1 : Gestion des données de fonctionnement de la plateforme.

    - role_nome 2 : Partenaire
      role_description 2 : Personne ou organisation qui finance la plateforme et/ou dont la plateforme met les annonces.

    - role_nome 3 : Membre
      role_description 3 : Personne ou organisation qui utilise les fonctionnalités de la plateforme.

    - role_nome 4 : Quiz master
      role_description 4 : Membre qui a créé au moins une fois un clash sur la plateforme.

- Sur la page d'authentification du template, la partie où il y a "Free and Open-Source Tailwind CSS Admin Dashboard Template", remplace le texte par "Espace de gestion de la plateforme Baquiz" en 2 langues (Français & English). Pour tout le site, tu peux enregistrer les textes en 2 langues.

- Concernant l'inscription du premier utilisateur, il doit être enregistré directement avec le rôle "Administrateur", et ses données doivent aussi être conservées dans la table "password_reset_tokens" (ancien "password_resets").

Pour le reste des pages, tu as le document PDF que j'ai joint pour y voir les informations dont tu as besoin. Essaie de générer les pages ergonomiques ; et mettre un ajax-loader quand on lance une requête.

=====================================

1️⃣ J'ai ajouté la colonne "is_competition" et la clé étrangère comme colonne de la table pivot "medal_user". De ce fait, tu peux compléter au niveau des modèles, contrôleurs et autres.

2️⃣ Concernant l'inscription, j'ai oublié la table "notifications" qui doit aussi être remplie avec les valeurs :
- uuid = Géré automatiquement ;
- type = "welcome_new_user" ;
- is_read = "0" (par défaut) ;
- to_user_id = ID du nouvel utilisateur
N'oublie pas de gérer ça aussi au niveau de l'API.

3️⃣ Après avoir créé le compte, j'ai remarqué que l'interface ne respecte pas les dispositions du template.

En dehors des pages d'authentification qu'on a déjà modélées, pour le reste des pages, tu n'as pas besoin de créer tes propres styles, le template à presque tout ce qu'il te faut (classes CSS, images, icônes, boutons, ...), en plus en mode responsive.👌

Je t'ai joint les captures d'écrans du template pour que tu comprennes.

Pour la disposition générale, il faut savoir que :
- La barre à gauche qui contient le logo et le menu principal a déjà une taille qui convient ;
- Chaque lien du menu principal (qui doit être scrollable) est accompagné d'une icône. Et le lien devient bleu quand il est en mode "active" ou "hover" ;
- Dans la navigation, le lien "account" ne se trouve pas sur la barre à gauche, mais la barre en haut à droite (Avatar+Prénom) ; et c'est un Dropdown qui contient les liens : Paramètres du compte et Connexion (Voir la capture qui va dans ce sens) ;
- En haut à gauche, il y a :
    - le bouton toggle qui cache/affiche la barre de menu ;
    - la barre de recherche. Utilise l'autocompletion (un joli bloc), pendant que l'utilisateur tape, pour afficher (en groupe) les utilisateurs, les matières (subjects), les domaines, les champs et les compétences.
- Toujours en haut à droite, tu peux ajouter l'icône des notifications qui est aussi un Dropdown pour les 10 notifications non lues les plus récentes ; avec le lien vers la page des notification. Ajoute donc la route "notifications"
En fait, quand tu appelles les notifications depuis la table des notifications, tu les pagines par 10 (toutes les autres données aussi d'ailleurs). Ensuite, pour les appeler sur la page, tu affiches les textes en 2 langues (Français & English) selon le "type" comme suit :
    - welcome_new_user : Bienvenue "to_user_id.firstname" "to_user_id.lastname" sur l'espace d'administration de Baquiz. (URL vers "/account").
    - user_mention ("to_user_id" est l'utilisateur en cours) : 
        > "clash_id" non nulle : "from_user_id.firstname" "from_user_id.lastname" vous a mentionné dans son clash. (URL vers "/clash/{clash_uuid}") ;
        > "comment_id" non nulle : "from_user_id.firstname" "from_user_id.lastname" vous a mentionné dans son commentaire. (URL vers "/") ;
        > "message_id" non nulle : "from_user_id.firstname" "from_user_id.lastname" vous a mentionné dans son message. (URL vers "/") ;
        > "question_id" non nulle : "from_user_id.firstname" "from_user_id.lastname" vous a mentionné dans une question de son clash. (URL vers "/clash/{question_id.subject.clash_uuid}") ;
        > "assertion_id" non nulle : "from_user_id.firstname" "from_user_id.lastname" vous a mentionné dans une assertion d'une question de son clash. (URL vers "/clash/{assertion_id.question.subject.clash.uuid}") ;
        > "answer_id" non nulle : "from_user_id.firstname" "from_user_id.lastname" vous a mentionné dans une de ses réponses. (URL vers "/user/{from_user_id.uuid}").
    - user_birthday ("to_user_id" est l'utilisateur en cours) : C'est l'anniversaire de "from_user_id.firstname" "from_user_id.lastname".
    - new_clash_attendee ("to_user_id" est l'utilisateur en cours) : "from_user_id.firstname" "from_user_id.lastname" participe à votre clash. (URL vers "/clash/{clash_id.uuid}").
    - clash_created ("from_user_id" n'est pas l'utilisateur en cours) : "from_user_id.firstname" "from_user_id.lastname" a créé un nouveau clash. (URL vers "/clash/{clash_id.uuid}").
    - clash_started : Le clash "clash_id.clash_code" a commencé. (URL vers "/clash/{clash_id.uuid}").
    - clash_ended : Le clash "clash_id.clash_code" est terminé. (URL vers "/clash/{clash_id.uuid}").
    - clash_liked : ("from_user_id" n'est pas l'utilisateur en cours et "to_user_id" est l'utilisateur en cours) : "from_user_id.firstname" "from_user_id.lastname" a réagi à votre clash. (URL vers "/clash/{clash_id.uuid}").
    - medal_awarded ("to_user_id" est l'utilisateur en cours) : Vous avez reçu une médaille dans un clash. (URL vers "/clash/{clash_id.uuid}").
    - new_followerd ("to_user_id" est l'utilisateur en cours) : "from_user_id.firstname" "from_user_id.lastname" s'est abonné à votre compte. (URL vers "/account").
    - payment_pending : La transaction de votre paiement est en cours. (URL vers "/payment").
    - payment_successful : La transaction de votre paiement a réussi. (URL vers "/payment").
    - payment_failed : La transaction de votre paiement a échoué. (URL vers "/payment").
- Pour le tableau de bord, tu peux remplacer le contenu des blocs depuis les templates :
    - Le genre des blocs où il y a "Customers 3,782", tu mets les statistiques pour le nombre : des membres, des membres médaillés (voir table pivot "medal_user"), des clashs, des quiz masters et des partenaires.
    - Le graphique où il y a "Target you've set for each month" peut servir pour faire un graphique pour les paiements, avec 3 références selon la colonne "status" : En cours (2), Réussi (0), Échoué (1)
    - Juste en dessous de ce graphique, tu mets un tableau des paiements (tenir compte du template pour le design ; et de la table "payments").
- Pour les autres pages, tu mets le tableau des données et leurs formulaires selon le document "admin-workflow.docx" que je t'avais joint au début.

=====================================

Tu peux générer une méthode principale qui sera utilisée par toutes les APIs qui demandent d'effectuer un paiement. La prochaine fois que je te dirai de créer une méthode dans le projet qui va lancer une transaction de paiement, tu vas simplement appelé cette méthode principale dans la méthode que tu crées, avec les paramètrres qu'il faut.
Et cette méthode principale, qui sera dans le contrôleur "app/Http/Controllers/Api/ApiController.php" (je te laisse lui donner un nom), dépend de la table "payments" et utilise le service "FlexPay".

=========
Tout d'abord "FlexPay" demande d'envoyer une requête avec :
=========
- Header
    - "authorization" (Le token d'autorisation obligatoire que le service envoie) : "'Bearer ' . config('services.flexpay.api_token')".
- Body
    - "merchant" (Le code Marchand FlexPay) : Appelé via le "config('services.flexpay.merchant')" ;
    - "type" (Le type de la transaction) : "1" si c'est mobile mone ; et "2" si c'est carte bancaire ;
    - "reference" (La référence de la transaction) : Générer un code du genre "REF-{8_digits_random_code}-{user_id}" ;
    - "phone" (Le n° de téléphone du client qui paie) : "Exemple : 243xxxxxxxxx" ; obligatoire quand la transaction est en mobile money (type = 1) ;
    - "amount" (Le montant de la transaction) ;
    - "currency" (La devise de la transaction) : USD ou CDF ;
    - "description" (La description de la transaction) : Surtout utilisé quand la transaction est bancaire (type = 2) ;
    - "callbackUrl" pour mobile money, et "callback_url" pour carte bancaire (L'url de retour ou le résultat de la transaction sera envoyé) : Ce résultat de la transaction est l'ensemble des données à envoyer en temps réel (Par exemple, un endpoint d'une API locale) à la table "payments". Les données importantes sont par exemple l'état de la transaction (colonne `payments`.`status`) ;
    - "approve_url" (L'url de redirection si l'opération est approuvée) : Utilisé pour la transaction par carte bancaire (type = 2). C'est "FlexPay" qui s'occupe de la redirection.
    - "cancel_url" (L'url de redirection si l'opération a été annulée par le client ; en cliquant sur le bouton annuler) : Utilisé pour la transaction par carte bancaire (type = 2). C'est "FlexPay" qui s'occupe de la redirection.
    - "decline_url" (L'url de redirection si l'opération est approuvée) : Utilisé pour la transaction par carte bancaire (type = 2). C'est "FlexPay" qui s'occupe de la redirection.

=========
Ensuite "FlexPay" renvoie une réponse :
=========
- "code" : Ce code donne le statut de la requête envoyée ("0" pour la requête bien envoyée, et "1" en cas de problème).
- "reference" : La référence envoyée lors dans la requête. Renvoyé par FlexPay uniquement lorsque le type de transaction est "carte bancaire" (type = 2).
- "provider_reference" : La référence de la transaction de l’opérateur en cas de succès. Renvoyé par FlexPay uniquement lorsque le type de transaction est "carte bancaire" (type = 2).
- "message" : Ce message est la description de la réponse (Exemple : Transaction envoyée avec succès. Veuillez valider le push message sur votre téléphone) ;
- "orderNumber" : Le code de la transaction généré par FlexPay. Très utile pour rechercher des paiements dans l'application.

Je t'ai joint un exemple complet (purchase-sample.txt) d'ancienne méthode où j'ai utilisé FlexPay dans une application E-commerce.
