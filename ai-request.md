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
- "provider_reference" : La référence de la transaction de l'opérateur en cas de succès. Renvoyé par FlexPay uniquement lorsque le type de transaction est "carte bancaire" (type = 2).
- "message" : Ce message est la description de la réponse (Exemple : Transaction envoyée avec succès. Veuillez valider le push message sur votre téléphone) ;
- "orderNumber" : Le code de la transaction généré par FlexPay. Très utile pour rechercher des paiements dans l'application.

Je t'ai joint un exemple complet (purchase-sample.txt) d'ancienne méthode où j'ai utilisé FlexPay dans une application E-commerce.

=====================================

1️⃣ Apparemment, il y a des icônes qui ne concordent pas. Pour toutes les icônes du site, tu peux utiliser "bootstrap-icons" et/ou "font-awesome". Ce qui te permettra d'avoir des icônes appropriées pour le bouton "toggle theme Dark/Light", le lien du menu de navigation, les blocs des statistiques sur le tableau de bord, ou autres parties du site.
👉 Pour la barre de menu, je t'ai joint une image (menu.png) de la disposition de la dimension des icônes et de la police

2️⃣ En cliquant pour passer à une page, inutile de mettre un "ajax-loader" et de recharger toute la page. Tu dois plutôt utiliser les placeholders sur la partie "contenu" (seul endroit qui recharge de manière asynchone) comme sur les réseaux sociaux.
👉 Je t'ai joint une image ("placeholder-light.png" et "placeholder-dark.png") comme modèle. Car tu devras générer ça entant que code pour que ça respecte le lien, mais aussi le thème "Dark/Light" ; c'est-à-dire :
- les blocs du placeholder du tableau de bord ont leur disposition ;
- les blocs du placeholder du profil (/account) ont leur disposition (presque comme sur l'image que je t'ai jointe) ;
- les blocs du placeholder des notifications ont leur disposition.
Bref, chaque placeholder prépare l'utilisateur à voir du contenu avec la disposition des blocs presque telle qu'il les voit.

3️⃣ Le bouton pour retrecir la barre de menu, lorsqu'on est sur les petits écrans doit servir pour faire apparaitre (icône devient "<i class="bi bi-x"></i>") ou disparaitre (icône redevient "<i class="bi bi-list"></i>" icône par défaut) la barre de menu.

4️⃣ Quand on clique sur le bouton qui retrecit la barre de menu :
- il n'y a plus de "/assets/img/logo/logo-dark.svg", ni de "/assets/img/logo/logo.svg", parce que ce sont des logos avec textes. Ils sont remplacés par un simple logo "/assets/img/logo/logo-icon.svg".
- en posant le curseur sur la barre menu, celui-ci s'agrandit ; et en enlevant le curseur sans cliquer, il se retrecit à nouveau. Ne pas oublier de switcher entre les logos pendant l'agrandissement/le retrecissement.

5️⃣ La barre de recherche doit déjà afficher le bloc autocomplete avec les résultats regroupés (un bon design) :
- Utilisateurs (table "users")
- Clashs (table "clashs")
- Champs (table "fields")

Quand il n'y a encore rien, il doit y avoir le message "Liste vide".
Sur le plus petit écran (téléphone), une icône de recherche simple apparait. Quand on clique sur cette icône, un pop-up avec barre de recherche apparait (comme sur le site de la documentation officiel de Laravel ou sur Freepik/Magnific, ou autre).

6️⃣ J'ai joint l'image de la page de la gestion du compte (profile.png) pour que tu remplisses déjà la page "account" ; en sachant que la partie où il y a :
- "Team Manager", tu mets le rôle principal (colonne `role_user`.`is_selected`) ;
- "Arizona, United States", tu mets la ville (colonne `users`.`city`) et le pays (colonne `users`.`country`) ;
- "Les icônes des réseaux sociaux (voir la table `websites`)". J'ai ajouté la colonne "logo_icon" que tu dois aussi ajouter dans les modèles et autres endroits appropriés.

Les contenus doivent toujours respecter (même à l'avenir) ceci :
- Toujours en 2 langues (Français & English) ;
- Chaque contenu de notification (texte que tu génères selon les données de la table) doit être accompagné d'une image (la photo de l'utilisateur de la colonne `notifications`.`from_user_id` ou une image par défaut si cette colonne est vide ou cet utilisateur n'a pas mis d'avatar). Tu dois d'ailleurs déjà remplir la page des notifications, vu que son contenu est comme sur le Dropdown des notifications depuis l'icône sur la barre en haut ;
- Tout Dropdown, dans le site, doit se fermer quand on clique ailleurs sur la page.

=====================================

1️⃣ Divers :
- Le logo doit toujours être à gauche. Quand la barre de menu se retrecit et que le logo simple apparait, le logo-text doit disparaitre et non devenir petit (réapparaitre quand on agrandit la barre de menu ; et le logo simple disparait).
- Dans les petits écrans, quand la barre de menu apparait, il doit avoir une marge supérieur (paddingTop) ayant la taille de la barre du haut, pour que le bouton pour cacher la barre de menu se voie avec l'icône "<i class="bi bi-x"></i>" pour cacher la barre de menu.
- Dans le Dropdown des notifications, le lien de chaque notification doit être cliquable. On ne peut voir la page "/notifications" que lorsqu'on clique sur "Voir toutes les notifications".
- Dans les petits écrans, sur le pop-up de recherche, l'autocomplete doit aussi fonctionner comme sur la barre de recherche dans les grands/moyens écrans.
- A la partie "Contenu" de la page, à la fin tu mets les textes "copyright" comme sur l'écran d'authentification.

2️⃣ Le lien "/account" :
- Lorsqu'on inscrit un utilisateur, l'avatar est envoyé sur "S3" et non "public".
- Le profil n'a pas de barre (photo) de couverture ; juste la photo de profil qui doit bien s'ajuster (cercle ; même sur la barre du haut).
- La localisation de l'utilisateur (city, country) est :
    - à droite dans les grands/moyens écrans ;
    - en dessous du rôle dans les petits écrans.
- Les blocs "Réseaux et sites" et "A propos" doivent avoir un bouton-icône pour faire apparaitre un Modal pour modifier ; et en cliquant sur ce bouton :
    - "Réseaux et sites" ouvre le Modal pour le formulaire d'ajout des sites web de l'utilisateur comme suit :
        - le formulaire dépend de la table "websites" ;
        - il y a toujours un bouton "<i class="bi bi-plus"></i>" pour ajouter un autre groupe des champs pour un site web de plus ; ou bien modifier un site web qui sera aussi dans le modal. Donc le bouton enregistre la liste modifiée ; 
        - dans le formulaire pour chaque site web, la colonne "logo_icon" enregistre juste le nom de l'icône depuis "Bootstrap Icons" (Exemple : facebook) ; donc, présente déjà l'icône à l'utilisateur. Comme ça, sur le bloc où il y a la liste des sites web de l'utilisateur, tu mets juste par exemple "<i class="bi bi-{website.logo_icon}"></i>", sachant que "website" est un foreach de "user.websites".
    - "A propos" ouvre le Modal pour modifier/ajouter les informations de l'utilisateur depuis la table "users" hormis le mot de passe.
- Il faut ajouter :
    - un bloc pour modifier le mot de passe (ancien mot de passe, nouveau mot de passe et confirmer nouveau mot de passe) ;
    - un bloc pour désactiver ou supprimer son compte.

3️⃣ Le lien "/" :
- Dans tous les statistiques sur le tableau de bord (et partout ailleurs d'ailleurs), les nombres doivent être affichés en gérant les mille (K), les millions (M), les milliards (B), etc.
- Je ne vois aucun graphique (même si c'est vide). Utilise même "ChartJS" ou son équivalent si ça existe sur React.
- Le bloc "Derniers paiements" doit avoir le tableau ; même s'il n'y a rien, il doit y avoir une ligne avec "colspan" égal au nombre des colonnes et possédant le texte "La liste est encore vide" (ne jamais oublier les 2 langues gérées).

4️⃣ Pour les autres liens (hormis ceux cités ci-haut), tu dois mettre un gros bloc à gauche qui contient le tableau des données ; et un petit bloc à droite pour le formulaire pour ajouter/modifier une donnée (le formulaire se transforme en formulaire pour modifier quand on clique sur l'icône "<i class="bi bi-pencil-fill"></i>" sur une donnée du tableau ; sinon le formulaire sert juste à ajouter une nouvelle donnée). Après à la colonne "Action", il doit y avoir "Voir (<i class="bi bi-eye"></i>)", "Editer (<i class="bi bi-pencil-fill"></i>)" et "Supprimer (<i class="bi bi-trash3"></i>) avec modal pour confirmer la suppression". Et enfin il faut tenir des champs en 2 langues pour les données de type JSON, afin de respecter la technologie "Laravel Translatable". Ne pas oublier les colonnes "created_by", "updated_by" et "deleted_by" de certaines tables, pour enregistrer l'ID de l'utilisateur qui fait une action. Les particularités sont :
- Le lien "/user" :
    - le formulaire ressemble a celui de la page "/register" surtout pour ajouter l'avatar. Et, pour choisir le rôle, il y aura une liste déroulante qui affiche la liste des rôles selon la langue de la page. Ensuite, il faut aussi associer le groupe des champs pour les sites web (comme décrit pour le profil concernant la table "websites") de l'utilisateur qu'on veut afficher ;
    - au-dessus du tableau des données, il y a une liste déroulante pour filtrer l'affichage selon le rôle ou afficher tous les utilisateurs ;
    - pour chaque donnée sur le tableau, hormis la colonne pour les icônes d'action (voir, modifier, supprimer), il y a une colonne pour modifier le rôle (simple balise SELECT dynamique Ajax avec modal de confirmation) et une autre colonne pour modifier l'état "status" (Dropdown avec modal de confirmation).
- Le lien "/pricing" :
    - Chaque ligne des données sur le tableau n'affiche que le contenu du tableau "pricings" :
        - "pricing_name" : colonne "Designation",
        - "pricing_type" : colonne "Type" (mettre un nom bilingue selon le type dans la table de la base de données. Exemple : "money" = "Argent" en français) ; et comme c'est un type ENUM, on a une liste déroulante,
        - "reason" : colonne "Motif" (mettre un nom bilingue selon le motif dans la table de la base de données. Exemple : "clash_create" = "Création clash" en français) ; et comme c'est un type ENUM, on a une liste déroulante,
        - "pricing_cost" : colonne "Coût en USD".
    - Dans le formulaire, hormis les champs de la table "pricings" comme les colonnes citées du tableau sur la page, il y a aussi un groupe des champs (bouton "<i class="bi bi-plus"></i>" pour ajouter un autre groupe des champs) qui ajoute une description, pour chaque tarif (table "pricing_descriptions"), avant de tout enregistrer (Tarif et toutes ses descriptions).
- Le lien "/payment" :
    - pas de bloc de formulaire ;
    - au-dessus, les statistiques (En cours, Réussis et Échoués) comme sur le taleau de bord.
    - au milieu, le tableau de tous les paiements, depuis le plus récent et paginés par 10. Quand on clique pour voir (La seule action possible), on a :
        - Quelques informations de l'utilisateur qui a lancé la transaction (avatar, noms, a propos, email et téléphone) ;
        - le montant payé ;
        - le canal (colonne "channel") utilisé pour payer ;
        - le type de paiement ("1" pour Mobile money et "2" pour Carte bancaire) ;
        - le numéro de téléphone utilisé, si le type est "1" ;
        - l'état (dans la table c'est un chiffre, mais sur la page c'est "1" En cours, "0" Réussi ou "2" Échoués) ;
        - si la colonne "entity" est "clash", on récupère quelques données du clash par son ID via la colonne "entity_id" ;
        - le motif de paiement (mettre un nom bilingue selon le motif dans la table de la base de données. Exemple : "clash_create" = "Création clash" en français)
    - en bas, le tableau de tous les transferts d'argent (table "money_transfers") envoyé aux utilisateurs, depuis le plus récent et paginés par 10. Quand on clique pour voir (La seule action possible), ce tableau s'affiche :
        - la colonne « Clash » contient les noms des clashs payants (Ce sont les clashs payants qui contiennent les paiements (colonne `money_transfers`.`payment_id`) dont le type de tarif (colonne `pricings`.`type`) est "percentage" ; c'est-à-dire le motif (`payments`.`reason` et `pricings`.`reason`) est "clash_participate") ;
        - la colonne « Quiz master » contient "avatar + nom complet" du Quiz master propriétaire du clash payant sur chaque ligne du tableau ;
        - la colonne « Total récolté » contient le montant total des participations dans le clash payant sur chaque ligne du tableau (PRIX_CLASH x PARTICIPATION) ;
        - la colonne « Montant commission » sur chaque ligne du tableau :
            - si la colonne `money_transfers`.`has_commission` a la valeur "1", contient les 5% du montant total (voir la colonne « Total récolté ») ;
            - si la colonne `money_transfers`.`has_commission` a la valeur "0", alors cette colonne sur la page sera vide.
        - la colonne « Transféré » permet de savoir si l'argent a déjà été transféré au Quiz master. Si la colonne `money_transfers`.`status` a la valeur :
            - NULL, alors la colonne du tableau sur la page sera « Non »,
            - "done", la valeur sera « Oui »,
            - "failed", la valeur sera « Echoué » ;
        - la colonne « Action » aura juste le bouton pour transférer l'argent au Quiz master, pour les clashs dont l'argent n'a pas encore été transféré (`money_transfers`.`status` est égal à "NULL" ou "failed")
- Le lien "/clash" :
    - Pas de bloc de formulaire. Juste un gros bloc pour le tableau des données ;
    - Un filtre au-dessus pour afficher selon le champ (comme pour les utilisateurs qui affiche selon le rôle), ou voir tous les clashs ;
    - En cliquant sur une donnée, on peut voir : 
        - Quiz master (Avatar + nom complet. Depuis colonne  `clashs`.`user_id`),
        - Photo de couverture (depuis colonne `files`.`clash_id`),
        - Code,
        - Descritpion,
        - Champ (depuis colonne `clashs`.`field_id`),
        - Commence à / Termine à (des icônes calendrier pour faire plus design),
        - Prix + Devise,
        - Est un concours ("0" Non, "1" Oui),
        - Type (mettre un nom bilingue selon le type dans la table de la base de données. Exemple : "private" = "Privé" en français),
        - Matière(s) du clash (depuis colonne `subjects`.`clash_id`) : Chaque nom de matière est affichée sous forme de badge 
        - Nombre de (statistiques affichés avec icônes) :
            - participants (depuis table pivot "clash_user"),
            - réactions (depuis table pivot "clash_user"),
            - médaillés (depuis table "medal_user"),
            - commentaires (depuis table "comments"),
        - Liste des 10 récents commentaires ;
        - Graphique des données (ChartJS ou autre similaire pour React) filtré selon la période (Quotidienne, Hebdomadaire, Mensuelle ou Annuelle) :
            - participants (depuis table pivot "clash_user"),
            - réactions (depuis table pivot "clash_user"),
            - médaillés (depuis table "medal_user"),
            - commentaires (depuis table "comments"),

- Le lien "/report" :
    - Pas de bloc de formulaire. Juste un gros bloc pour le tableau des données (depuis la table "reports") ;
    - les colonnes de la tables sont :
        - « Signalé par » : "Avatar + Nom complet" (depuis colonne `reports`.`user_id`) :
        - « Designation » : Les colonnes `reports`.`entity` et `reports`.`entity_id` déterminent ce qui sera affiché sur cette colonne du tableau :
            - pour "clash", on affiche "Clash : " avec le code du clash (colonne `clashs`.`clash_code`),
            - pour "subject", on affiche "Matière : " (toujours bilingue) avec le nom de la matière (colonne `subjects`.`subject_name`),
            - pour "question", on affiche "Question : " (toujours bilingue) avec le contenu de la question (colonne `questions`.`question_content`),
            - pour "user", on affiche "Membre : " (toujours bilingue) avec "Avatar + nom complet" de l'utilisateur ;
        - « Motif » (contenu extrait depuis colonne `reports`.`reason_id`.`reason_content`) ;
        - « Commentaires » (colonne `reports`.`report_content`) ;
        - « Action » :
            - Un bouton pour débloquer (<i class="bi bi-unlock"></i>), visible uniquement quand l'auteur du clash ("`reports`.`entity` = 'clash'" ou "`reports`.`entity` = 'subject'" ou encore "`reports`.`entity` = 'question'"), ou bien un utilisateur (`reports`.`entity` = 'user') est bloqué ("`blocked_users`.`user_id` = 'auteur ou utilisateur'" et "`blocked_users`.`is_unlocked` = 0"). Quand on débloque l'utilisateur, `blocked_users`.`is_unlocked` devient 1 ;
            - Supprimer (<i class="bi bi-trash3"></i>) ;
    - En cliquant sur une donnée, on peut voir (en plus des données citées pour les colonnes du tableau) :
        - « Mise en sourdine » (colonne `reports`.`muted`) : "0" Non, "1" Oui ;
        - « Uniquement pour » (colonne `reports`.`for_user_id`) : "Avatar + nom complet" de l'utilisateur récupéré depuis l'ID dans cette colonne ;

5️⃣ 



