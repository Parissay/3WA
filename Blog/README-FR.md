# Blog de John Doe

<p align="center">
  <img src="https://raw.githubusercontent.com/Parissay/3WA/master/Blog/assets/github-view.jpg" alt="Blog project">
</p>

*An **[English version](https://github.com/Parissay/3WA/blob/master/Blog/README.md)** of this document is available. Une version de ce document est disponible en anglais.*

Le ***blog de John Doe*** a été développé lors de ma formation à la **[3W Academy](https://3wa.fr/)** (été 2018).  L'exercice était de créer le blog d'un écrivain, avec pour fonctionnalités :

### Côté utilisateur

- affichage des articles et commentaires
- possibilité d'écrire des commentaires

### Côté administrateur

- rédiger des articles
- modifier / supprimer des articles

## Sommaire

1. [Création et configuration de la base de données](#cr%C3%A9ation-et-configuration-de-la-base-de-donn%C3%A9es)
	- table authors
	- table categories
	- table posts
	- table comments
2. [Connexion à la base de données](#connexion-%C3%A0-la-base-de-donn%C3%A9es)
3. [Création des fichiers de base](#cr%C3%A9ation-des-fichiers-de-base)
	- layout.phtml
	- index.php
	- index.phtml
4. [Afficher un article](#afficher-un-article)
	- show_post.php
	- show_post.phtml
5. [Ajouter un commentaire](#ajouter-un-commentaire)
	- add_comment.php
6. [Panneau d'administration](#panneau-dadministration)
	- admin.php
	- admin.phtml
7. [Rédiger un article](#r%C3%A9diger-un-article)
	- add_post.php
	- add_post.phtml
8. [Modifier un article](#modifier-un-article)
	- edit_post.php
	- edit_post.phtml
9. [Supprimer un article](#supprimer-un-article)
	- delete_post.php 

## 1. Création et configuration de la base de données

La première étape est de créer la base de données. Vous trouverez ci-dessous le nom des tables, leurs colonnes, et quelques informations importantes. Pour plus de détails sur les caractéristiques de chacune des colonnes, vous pouvez consulter le fichier [blog.sql](https://github.com/Parissay/3WA/blob/master/Blog/sql/blog.sql)

### 1.1. Table *authors*

La table *authors* contient l'ensemble des auteurs d'articles du blog.

|Nom de la colonne  |Type  | Valeur |Commentaires
|--|--|--|--|
|a_id  |tinyint  |3  |clef primaire en auto-incrémentation  |
|a_name  |varchar  |50  |*prénom*  |
|a_surname  |varchar  |50  |*nom de famille*  |

### 1.2. Table *categories*

La table *categories* contient l'ensemble des catégories auxquelles appartiennes les articles (*posts*).

|Nom de la colonne  |Type  | Valeur |Commentaires
|--|--|--|--|
|cat_id  |tinyint  |3  |clef primaire en auto-incrémentation  |
|cat_name  |varchar  |40  |nom de la catégorie  |

### 1.3. Table *posts*

La table *posts* contient l'ensemble des articles.

|Nom de la colonne  |Type  | Valeur |Commentaires
|--|--|--|--|
|p_id  |smallint  |5  |clef primaire en auto-incrémentation  |
|p_title  |varchar  |100  |titre de l'article  |
|p_content  |text  |  |contenu de l'article |
|p_creation_date  |datetime  |  |date et heure de création de l'article |
|p_author_id  |tinyint  |3  |clef étrangère de la table *authors*  |
|p_category_id  |tinyint  |3  |clef étrangère de la table *categories*  |

### 1.4. Table *comments*

La table *comments* contient l'ensemble des commentaires se rapportant aux articles.

|Nom de la colonne  |Type  | Valeur |Commentaires
|--|--|--|--|
|com_id  |mediumint  |8  |clef primaire en auto-incrémentation  |
|com_nickname  |varchar  |30  |pseudo de l'utilisateur  |
|com_content  |text  |  |contenu du commentaire  |
|com_creation_date  |datetime  |  |date et heure de création du commentaire  |
|com_post_id  |smallint  |5  |clef étrangère de la table *posts*  |

## 2. Connexion à la base de données

### bdd_connection.php

Pour établir une connexion avec la base de données il faut créer un dossier `application` à la racine de votre projet. À l'intérieur, créez le fichier [`bdd_connection.php`](https://github.com/Parissay/3WA/blob/master/Blog/application/bdd_connection.php)

Dans un premier temps on attribue nos identifiants de connexion aux variables correspondantes. Et dans un second temps on test la connexion à la base de données avec un *[try and catch](https://openclassrooms.com/fr/courses/918836-concevez-votre-site-web-avec-php-et-mysql/914293-lire-des-donnees#/id/r-2175084)*

```PHP
<?php

// On attribue nos identifiants de connexion aux variables correspondantes
$host_name = '<l`identifiant de votre host>';
$database = '<nom de votre base de données>';
$user_name = '<votre nom d`utilisateur>';
$password = '<votre mot de passe>';

$pdo = null;
try {
	// On test la connexion à la base de données
	$pdo = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
} catch (PDOException $e) {
	// Si cela ne fonctionne pas on affiche un message d'erreur
	echo "Erreur!: " . $e->getMessage() . "<br/>";
	die();
}
```

[`getMessage()`](http://php.net/manual/fr/exception.getmessage.php) renverra l'erreur d'origine. Le plus souvent une erreur `SQLSTATE` pour dire que la connexion n'a pas été effectuée. Il est tout à fait possible de personnaliser ce message :

```PHP
die('connexion à la base de données impossible');
```

## 3. Création des fichiers de base

### layout.phtml

Pour une meilleure compréhension du fonctionnement du projet, nous allons dans un premier temps créer le fichier [`layout.phtml`](https://github.com/Parissay/3WA/blob/master/Blog/layout.phtml) à la racine du projet. Celui-ci nous permettra d'avoir le même `header` et `footer` sur l'ensemble du site. Dans la partie `main`, nous allons écrire du code PHP pour inclure automatiquement les *templates* des pages.

```PHP
<main>
	<?php include $template.'.phtml' ?>
</main>
```

### index.php

Notre fichier [`index.php`](https://github.com/Parissay/3WA/blob/master/Blog/index.php) sera appelé en premier. Comme pour l'ensemble des fichiers .php de notre projet, la première étape est de faire appel à notre fichier de connexion à la base de données.

```PHP
include 'application/bdd_connection.php';
```

Nous souhaitons afficher sur la page d'accueil l'ensemble des articles classés en ordre antéchronologique (du plus récent au plus ancien). La requête SQL est la suivante :

```PHP
$query = 
`
	// Sélection de l`id de l'article, son titre, son contenu, sa date de création, sa catégorie
	// ainsi que le prénom et nom de l`auteur
	SELECT 
		p_id,
		p_title,
		p_content,
		p_creation_date,
		p_category_id,
		a_name,
		a_surname
	// Dans la table `posts`
	FROM 
		posts
	// Et la table `authors`
	INNER JOIN 
		authors
	// En utilisant la clef étrangère p_author_id
	ON 
		posts.p_author_id = authors.a_id
	// Et on classe les résultats par ordre antéchronologique
	ORDER BY 
		p_creation_date DESC
`;

// On exécute la requête qu'on stock dans $result
$result = $pdo -> query($query);

// Retourne un tableau de données avec fetchAll(), qu'on stock dans $posts
$posts = $result -> fetchAll();
```

Pour finir, nous devons définir ce fichier comme étant le *template* `index`, et afficher le `layout.phtml` :

```PHP
// On définit ce fichier comme étant le template `index`
$template = 'index';

// On fait appel à notre page `layout.phtml`
include 'layout.phtml';
```

### index.phtml

Maintenant que notre requête SQL est prête, nous devons afficher le résultat sur notre page [`index.phtml`](https://github.com/Parissay/3WA/blob/master/Blog/index.phtml) qui sera automatiquement appelée dans le `layout.phtml` de la manière suivante :

```PHP
<?php include $template.'.phtml' ?>
```
qui revient à :
```PHP
<?php include 'index.phtml' ?>
```

Pour afficher l'ensemble de nos articles, nous devons créer une liste `ul` > `li` en utilisant une boucle `foreach()` pour parcourir le tableau de données, contenu dans la variable `$posts` du fichier `index.php`

Pour rappel, `foreach ($tableau as $valeur)`

```PHP
<ul>
    <?php foreach ($posts as $post): ?>
    <li>
            // Notre code
    </li>
    <?php endforeach; ?>
</ul>
```

À l'intérieur des balises `li` se trouve notre code. On utilise la *valeur* `$post` de notre boucle `foreach()` pour faire appel aux informations de notre article.

```PHP
<article>

    <h2><?= htmlspecialchars($post['p_title']) ?></h2>
    
    <h3>Posté le <?= htmlspecialchars($post['p_creation_date']) ?> 
    par <?= htmlspecialchars($post['p_name']).' '.htmlspecialchars($post['p_surname']) ?></h3>
    
    <p><?= substr(htmlspecialchars($post['p_content']), 0, 150) ?>[...]</p>
    
</article>
```
[`htmlspecialchars()`](http://php.net/manual/fr/function.htmlspecialchars.php) permet de convertir les caractères spéciaux en entités HTML. Par exemple `&` sera remplacé par `&amp;`, `>` par `&gt;`, etc.

[`substr()`](http://php.net/manual/fr/function.substr.php) permet de retourner un segment d'une chaîne de caractères. Dans notre exemple, `substr()` retourne une chaîne de 0 à 150 caractères :

```PHP
substr(htmlspecialchars($post['p_content']), 0, 150)
```
Cela nous permet d'afficher quelque chose comme :
> Titre de l'article
> Posté le *2000-01-01 20:00:00* par *John Doe*
>Mauris quam quam, dictum ac velit id, rhoncus maximus mauris. Nulla vulputate metus id orci rutrum mollis. Sed nisi libero, viverra eget venenatis [...]

## 4. Afficher un article

### show_post.php

Nous souhaitons maintenant afficher l'article en entier, ses commentaires, et pouvoir en écrire. Nous allons créer la page [show_post.php](https://github.com/Parissay/3WA/blob/master/Blog/show_post.php)

Comme pour `index.php`, on commence par faire appel à notre fichier `bdd_connection.php`. 

Lorsque l'utilisateur clic pour afficher un article, l'`id` de ce dernier se retrouvera dans l'url. Par exemple pour l'article ayant l'`id` n°`5` :

`blog/show_post.php?id=5`

Avant d'afficher l'article, on doit vérifier s'il existe. On récupère pour cela l'`id` entré dans l'url à l'aide de `$_GET` :

```PHP
include 'application/bdd_connection.php';

// Si l`id, et donc l'article, n'existe pas
if (!array_key_exists('id' , $_GET) OR !ctype_digit($_GET['id']))
{
	// Alors on renvoi vers la page d`accueil
	header('Location: index.php');
	exit();
}
```
[`array_key_exists()`](http://php.net/manual/fr/function.array-key-exists.php) : vérifie si une clé existe dans un tableau
[`ctype_digit()`](http://php.net/manual/fr/function.ctype-digit.php) : vérifie qu'une chaîne est un entier

Nous ne pouvons pas déterminer à l'avance quel article sera choisi par l'utilisateur, et donc la valeur de l'`id`. On remplace donc cette valeur par un point d'interrogation `?`

```PHP
$query =
`
	// On sélectionne les informations nécessaires
	SELECT
		p_id,
		p_title,
		p_content,
		p_creation_date,
		a_name,
		a_surname
	// Dans la table `posts`
	FROM
		posts
	// Et la table `authors`
	INNER JOIN
		authors
	// En utilisant la clef étrangère p_author_id
	ON
		posts.p_author_id = authors.a_id
	// Et où l'id de l'article est égal à ?
	WHERE
		posts.p_id = ?
`;

// On prépare la requête qu'on stock dans $result
$result = $pdo -> prepare($query);

// On exécute la requête selon l`id de l'article
$result -> execute(array($_GET['id']));

// Puis on retourne un jeu de résultat, avec fetch(), qu'on stock dans $post
$post = $result -> fetch();
```

On utilise ici [`PDOStatement`](https://secure.php.net/manual/fr/class.pdostatement.php) qui représente une requête préparée et, une fois exécutée, le jeu de résultats associé. [`fetch()`](http://php.net/manual/fr/pdostatement.fetch.php) récupère la ligne du jeu de résultat.

On récupère ensuite l'ensemble des commentaires liés à l'article. Comme pour la requête précédente, on ne sait pas encore quel sera l'article et son `id`. Pour rappel, `com_post_id` est la clef étrangère de la table `posts`.

```PHP
$query = 
`
	// On sélectionne les informations nécessaires
	SELECT
		com_nickname,
		com_content,
		com_creation_date
	// Dans la table `comments`
	FROM
		comments
	// Et où l'id du commentaire est égal à ?
	WHERE
		com_post_id = ?
`;

// On prépare la requête qu'on stock dans $result
$result = $pdo -> prepare($query);

// On exécute la requête selon l`id de l'article
$result -> execute(array($_GET['id']));

// Puis on retourne un tableau de données, avec fetchAll(), qu'on stock dans $comments
$comments = $result -> fetchAll();
```

Pour finir :

```PHP
// On définit ce fichier comme étant le template `show_post`
$template = 'show_post';

// On fait appel à notre page `layout.phtml`
include 'layout.phtml';
```

### show_post.phtml

Nous pouvons désormais afficher l'article, ses commentaires, ainsi qu'un formulaire pour en écrire de nouveaux, dans notre page [show_post.phtml](https://github.com/Parissay/3WA/blob/master/Blog/show_post.phtml)

#### Afficher l'article

On utilise ici la variable `$post`, de notre première requête, pour afficher les données.

```PHP
<article>

    <h2><?= htmlspecialchars($post['p_title']) ?></h2>
    
    <h3>Posté le <?= htmlspecialchars($post['p_creation_date']) ?> 
    par <?= htmlspecialchars($post['p_name']).' '.htmlspecialchars($post['p_surname']) ?></h3>
    
    <p><?= htmlspecialchars($post['p_content']) ?></p>
    
</article>
```

#### Afficher les commentaires

On utilise une boucle `foreach()` pour parcourir l'ensemble des commentaires liés à l'article, et les afficher.

```PHP
<h4>Commentaires</h4>

<ul>
    <?php foreach($comments as $comment): ?>
    <li>
	<article>
            <h5>Posté le <?= htmlspecialchars($comment['com_creation_date']) ?> 
            par <?= htmlspecialchars($comment['com_nickname']) ?></h5>
            <p><?= htmlspecialchars($comment['com_content']) ?></p>
        </article>
    </li>
    <?php endforeach; ?>
</ul>
```

#### Rédiger un commentaire

```PHP
<h6>Rédiger un commentaire</h6>

<form action="add_comment.php" method="POST">

    <input type="hidden" name="post" method="GET" value="<?= intval($_GET['id']) ?>">
    
    <label for="nickname">Pseudo :</label>
    <input type="text" name="nickname">
    
    <label for="content">Commentaire :</label>
    <textarea id="content" name="content" type="textarea"></textarea>
    
    <input type="submit" value="poster le commentaire">
    
</form>
```

On utilise ici un champ caché pour spécifier à quel article rattacher le commentaire. La méthode `$_GET` relève l'`id` de l'article dans l'url et la transmet lors de l'envoi du formulaire.

```PHP
<input type="hidden" name="post" method="GET" value="<?= intval($_GET['id']) ?>">
```

Le formulaire renvoi vers le fichier `add_comment.php` que nous allons voir un peu plus bas. 

Avant cela, nous pouvons modifier le fichier `index.phtml` pour lui ajouter des liens vers nos articles. Ainsi, lorsque l'utilisateur cliquera sur le titre d'un article, ce dernier s'affichera grâce à nos deux nouvelles pages `show_post`. Vous pouvez aussi ajouter un bouton "lire la suite" si vous le souhaitez.

```PHP
<h2>
    <!-- Affiche l'article dont l'id est égale à ? -->
    <a href="show_post.php?id=<?= intval($post['p_id']) ?>"><?= htmlspecialchars($post['p_title']) ?></a>
</h2>
```

Ce qui nous donne, par exemple, pour le troisième article :

```PHP
<h2>
    <!-- Affiche l'article dont l'id est égale à 3 -->
    <a href="show_post.php?id=3"><?= Titre du troisième article ?></a>
</h2>
```

[`intval()`](http://php.net/manual/fr/function.intval.php) : retourne la valeur numérique entière équivalente d'une variable.

## 5. Ajouter un commentaire

### add_comment.php

La page [add_comment.php](https://github.com/Parissay/3WA/blob/master/Blog/add_comment.php) permet à un utilisateur d'ajouter un commentaire.

```PHP
// On appel le fichier de connexion
include 'application/bdd_connection.php';

$query =
`
	// Insère dans la table `comments` : l'id de l'article, le pseudo de l'utilisateur,
	// le contenu du commentaire et la date de sa création
	INSERT INTO
		comments (com_post_id, com_nickname, com_content, com_creation_date)
	// ? = car on ne sait aucunes de ces informations pour l'instant
	// NOW() = la date de création du commentaire est maintenant
	VALUES
		(?, ?, ?, NOW())
`;

// On prépare la requête qu'on stock dans $result
$result = $pdo -> prepare($query);

// On exécute la requête et on stock le pseudo, le commentaire et l'article
// y étant rattaché dans un nouveau tableau
$result -> execute(array($_POST['post'], $_POST['nickname'], $_POST['content']));

// Pour finir on retourne à l'article modifié d'un nouveau commentaire
header('Location: show_post.php?id='.$_POST['post']);
exit();
```

## 6. Panneau d'administration

Le panneau d'administration **affiche l'ensemble des articles** dans un tableau, avec un bouton ***éditer*** et ***supprimer***. Il est tout à fait possible d'ajouter un extrait de chaque article, son auteur, sa catégorie, etc. Mais nous resterons simple pour notre exemple. Le code des pages [admin.php](https://github.com/Parissay/3WA/blob/master/Blog/admin.php) et [admin.phtml](https://github.com/Parissay/3WA/blob/master/Blog/admin.phtml) en ligne.

### admin.php

```PHP
// Appel au fichier de connexion à la base de données
include 'application/bdd_connection.php';
	
$query =
`
	// Sélection de l'id de l'article, son titre et sa date de création
	SELECT
		p_id,
		p_title,
		p_creation_date
	// Dans la table `posts`
	FROM
		posts
	// Et on classe les résultats par ordre antéchronologique
	ORDER BY
		p_creation_date DESC
`;

// On exécute la requête qu'on stock dans $result
$result = $pdo -> query($query);

// Puis on retourne un tableau de données avec fetchAll() qu'on stock dans $posts
$posts = $result -> fetchAll();
	
// On définit ce fichier comme étant le template `admin`
$template = 'admin';

// On fait appel à notre page `layout.phtml`
include 'layout.phtml';
```

### admin.phtml

Le fonctionnement est le même que les pages précédentes. On utilise une boucle `foreach()` pour parcourir le tableau de données et afficher l'ensemble des articles.

```PHP
<h2>PANNEAU D'ADMINISTRATION</h2>

<!-- voir plus bas pour add_post.php -->
<a href="add_post.php">Rédiger un article</a>

<h3>Liste des articles publiés</h3>

<table>
    <?php foreach ($posts as $post): ?>
        <tr>
	    <td><?= htmlspecialchars($post['p_title']) ?></td>
	    <!-- voir plus bas pour edit_post.php -->
	    <td><a href="edit_post.php?id=<?= intval($post['id']) ?>">éditer</a></td>
	    <!-- voir plus bas pour delete_post.php -->
	    <td><a href="delete_post.php?id=<?= intval($post['id']) ?>">supprimer</a></td> 
	</tr>
    <?php endforeach; ?>
</table>
```

## 7. Rédiger un article

Sur la page ***panneau d'administration***, l'admin peut cliquer sur un bouton ***rédiger un article*** qui l'envoi vers notre page [`add_post.phtml`](https://github.com/Parissay/3WA/blob/master/Blog/add_post.phtml), qui contient un formulaire avec pour champs :
- titre de l'article
- contenu de l'article
- choix de l'auteur (liste déroulante)
- choix de la catégorie (liste déroulante)

### add_post.php

Le code de la page [add_post.php](https://github.com/Parissay/3WA/blob/master/Blog/add_post.php).

Comme toujours, on commence par appeler notre fichier de connexion à la base de données

```PHP
include 'application/bdd_connection.php';
```

Dans un premier temps on ouvre une condition `if` qui vérifie si le formulaire est vide ou non. Cela nous permet de récupérer les informations nécessaires aux listes déroulante  (auteurs et catégories). Puis on effectue les requêtes.

```PHP
// Si le formulaire est vide
if (empty($_POST)) {

// 1. Requête pour récupérer les auteurs
// =====================================
$query =
`
	// Sélectionde de l'id des auteurs, leurs prénoms et noms
	SELECT
		a_id,
		a_name,
		a_surname
	// Dans la table `authors`
	FROM
		authors
`;

// On exécute la requête qu'on stock dans $result
$result = $pdo -> query($query);

// Puis on retourne un tableau de données, avec fetchAll(), qu'on stock dans $authors
$authors = $result -> fetchAll();

// 2. Requête pour récupérer les catégories
// ========================================
$query =
`
	// Sélection de l'id des catégories et leur noms
	SELECT
		cat_id,
		cat_name
	// Dans la table `categories`
	FROM
		categories
`;

// On exécute la requête qu'on stock dans $result
$result = $pdo -> query($query);

// Puis on retourne un tableau de données, avec fetchAll(), qu'on stock dans $categories
$categories = $result -> fetchAll();

// On définit ce fichier comme étant le template `add_post`
$template = 'add_post';

// On fait appel à notre page `layout.phtml`
include 'layout.phtml';

}
```

La seconde étape est d'ajouter l'article selon les informations du formulaire. Le `if` vérifie si le formulaire est vide, dans le cas contraire (`else`) c'est que l'admin a rempli les champs. On peut donc envoyer les données vers la table `posts`.

```PHP
// Sinon (si le formulaire n'est pas vide)
else {

$query =
`
	// On insère dans la table `posts` : le titre, le contenu, l'id de l'auteur,
	// la catégorie, et la date de création de l'article
	INSERT INTO
		posts (
			p_title,
			p_content,
			p_author_id,
			p_category_id,
			p_creation_date
		)
	// ? = car on ne sait aucunes de ces informations pour l'instant
	// NOW() = la date de création de l'article est maintenant
	VALUES (?, ?, ?, ?, NOW())
`;

// On prépare la requête qu'on stock dans $result
$result = $pdo -> prepare($query);

// On stock le titre, le contenu, l'auteur et la catégorie de l'article
// puis on exécute la requête pour envoyer les données vers notre table
$result -> execute([$_POST['title'], $_POST['content'], $_POST['author'], $_POST['category']]);

// On retourne ensuite au panneau d'administration.
header('Location: index.php');
exit();
	
}
```

### add_post.phtml

Du côté affichage, on utilise une boucle `foreach()` pour les listes déroulantes.

```PHP
<h2>REDIGER UN ARTICLE</h2>

<!-- Envoi du formulaire avec la méthode post -->
<form action="add_post.php" method="POST">

<label for="title">Titre de l'article :</label>
<input type="text" name="title" for="title">

<label for="content">Votre article :</label>
<textarea type="text" name="content" for="content"></textarea>

<label for="author" class="labelStyle">Auteur :</label>
<select name="author" for="author">
    <?php foreach($authors as $author): ?>
        <option value="<?= intval($author['id']) ?>">
            <?= htmlspecialchars($author['a_name']).' '.htmlspecialchars($author['a_surname']) ?>
        </option>
    <?php endforeach; ?>
</select>

<label for="category">Catégorie :</label>
<select name="category" for="category">
    <?php foreach($categories as $categorie): ?>
        <option value="<?= intval($categorie['id']) ?>">
            <?= htmlspecialchars($categorie['cat_name']) ?>
	</option>
    <?php endforeach; ?>
</select>

<input type="submit" value="publier l'article">
<input type="reset" value="effacer le formulaire">

</form>
```


## 8. Modifier un article

### edit_post.php

Le code de la page [edit_post.php](https://github.com/Parissay/3WA/blob/master/Blog/edit_post.php).

Comme pour la rédaction d'un article, on utilise la condition `if` pour vérifier que le formulaire est vide. On ajoute une seconde condition `if` pour vérifier que l'article demandé existe bien. On récupère pour cela l'`id` entré dans l'url à l'aide de `$_GET`.

```PHP
// On appel le fichier de connexion
include 'application/bdd_connection.php';

if (empty($_POST)) {

	// Si l'id, et donc l'article, n'existe pas
	if (!array_key_exists('id' , $_GET) OR !ctype_digit($_GET['id']))
	{
		// Alors on renvoi vers la page d`admin
		header('Location: admin.php');
		exit();
	}

	$query =
	`
		// On sélectionne les informations nécessaires
		SELECT
			p_id,
			p_title,
			p_content
		// Dans la table `posts`
		FROM
			posts
		// Où l'id de l'article est égal à ?
		WHERE
			p_id = ?
	`;

	// On stock la requête dans $result
	$result = $pdo -> prepare($query);

	// On exécute la requête selon l'id de l'article
	$result -> execute(array($_GET['id']));

	// Puis on retourne un jeu de résultat, avec fetch(), qu'on stock dans $post
	$post = $result -> fetch();

	// On définit ce fichier comme étant le template `edit_post`
	$template = 'edit_post';

	// On fait appel à notre page `layout.phtml`
	include 'layout.phtml';

}
```

La seconde étape est d'ajouter l'article modifié selon les informations du formulaire. Le `if` vérifie si le formulaire est vide, dans le cas contraire (`else`) c'est que l'admin a rempli les champs. On peut donc envoyer les données vers la table `posts`. On ne connais pas encore les données ni l'article choisi par l'admin, on remplace donc le tout par des points d'interrogations `?`

```PHP
else {

$query =
`
	// On met à jour la table `posts`
	UPDATE
		posts
	// Avec les nouvelles données saisies
	SET
		p_title = ?,
		p_content = ?
	// Où l'id de l'article est égal à ?
	WHERE
		p_id = ?
`;

// On prépare la requête qu'on stock dans $result
$result = $pdo -> prepare($query);

// On stock le titre, le contenu et l'id de l'article, puis on exécute la
// requête pour envoyer les données vers notre table
$result -> execute(array($_POST['title'], $_POST['content'], $_POST['postId']));

// Et on retourne au panneau d'administration
header('Location: admin.php');
exit();
	
}
```

### edit_post.phtml

Le code de la page [](https://github.com/Parissay/3WA/blob/master/Blog/edit_post.phtml).

Cette page ne diffère pas des autres dans son fonctionnement. À noter que comme la page [show_post.phtml](#show_post.phtml), on utilise ici un champ caché pour spécifier à quel article rattacher la modification. La méthode `$_GET` relève l'`id` de l'article dans l'url et la transmet lors de l'envoi du formulaire.

```PHP
<h2>EDITER UN ARTICLE</h2>

<form action="edit_post.php" method="POST">

    <input type="hidden" name="postId" value="<?= intval($post['id']) ?>">

    <label for="title">Titre de l'article :</label>
    <input type="text" name="title" for="title" value="<?= htmlspecialchars($post['p_title']) ?>">

    <label for="content">Votre article :</label>
    <textarea type="text" name="content" for="content">
        <?= htmlspecialchars($post['p_content']) ?>
    </textarea>

    <input type="submit" class="valider" value="MODIFIER">
    <input type="reset" class="supprimer" value="EFFACER">	

</form>
```

## 9. Supprimer un article

### delete_post.php

Le code de la page [delete_post.php](https://github.com/Parissay/3WA/blob/master/Blog/delete_post.phphp).

Après l'appel de notre fichier de connexion, on ajoute une condition `if` pour vérifier que l'article demandé existe bien. On récupère pour cela l'`id` entré dans l'url à l'aide de `$_GET`.

```PHP
// On appel notre fichier de connexion
include 'application/bdd_connection.php';
	
// Si l'id, et donc l'article, n'existe pas
if (!array_key_exists('id' , $_GET) OR !ctype_digit($_GET['id']))
{
	// Alors on renvoi vers la page d`admin
	header('Location: admin.php');
	exit();
}

$query =
`
	// On supprime de la table `posts`
	DELETE FROM
		posts
	// L'article dont l'id est égal à ?
	WHERE
		p_id = ?
`;

// On prépare la requête qu'on stock dans $result
$result = $pdo -> prepare($query);

// On exécute la requête selon l'id de l'article
$result -> execute(array($_GET['id']));

// Puis on retourne au panneau d'administration
header('Location: admin.php');
exit();
```

## Auteur et licence

Si vous avez des commentaires, des corrections, des plaintes ou des idées d'amélioration, n'hésitez pas à m'envoyer un message.

Le **Blog de John Doe** a été créé par [@Parissay](https://github.com/Parissay). 

Le **Blog de John Doe** est disponible sous licence MIT. Vous pouvez consulter la [LICENCE](https://github.com/Parissay/3WA/blob/master/Blog/LICENSE.md) pour plus d'informations. ![GitHub](https://img.shields.io/github/license/mashape/apistatus.svg)
