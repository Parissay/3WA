# John Doe's Blog

<p align="center">
  <img src="https://raw.githubusercontent.com/Parissay/3WA/master/Blog/assets/github-view.jpg" alt="Blog project">
</p>

*Une [version française](https://github.com/Parissay/3WA/blob/master/Blog/README-FR.md) de ce document est aussi disponible. A French version of this document is also available.*

The ***John Doe's blog*** was developed during my training at the [3W Academy](https://3wa.fr/) (summer 2018). The exercise was to create a writer's blog, with the following features:

### User's side

- display articles and comments
- possibility to write comments

### Admin's side

- write articles
- edit / delete articles

## Table of contents

1. [Creation and configuration of the database](#creation-and-configuration-of-the-database)
	- table authors
	- table categories
	- table posts
	- table comments
2. [Connection to the database](#connection-to-the-database)
3. [Creating basic files](#creating-basic-files)
	- layout.phtml
	- index.php
	- index.phtml
4. [Display an article](#display-an-article)
	- show_post.php
	- show_post.phtml
5. [Add a comment](#add-a-comment)
	- add_comment.php
6. [Admin Panel](#admin-panel)
	- admin.php
	- admin.phtml
7. [Write an article](#write-an-article)
	- add_post.php
	- add_post.phtml
8. [Edit an article](#edit-an-article)
	- edit_post.php
	- edit_post.phtml
9. [Delete an article](#delete-an-article)
	- delete_post.php 

## 1. Creation and configuration of the database

The first step is to create the database. You will find below the names of the tables, their columns, and some important informations. For more details on the characteristics of each column, you can consult the file [blog.sql](https://github.com/Parissay/3WA/blob/master/Blog/sql/blog.sql)

### 1.1. Table *authors*

The *authors* table contains all authors of blog articles.

|Name of the column  |Type  | Value |Comments
|--|--|--|--|
|a_id  |tinyint  |3  |primary key in auto-increment  |
|a_name  |varchar  |50  |name  |
|a_surname  |varchar  |50  |surname  |

### 1.2. Table *categories*

The *categories* table contains all the categories to which the articles belong.

|Name of the column  |Type  | Value |Comments
|--|--|--|--|
|cat_id  |tinyint  |3  |primary key in auto-increment  |
|cat_name  |varchar  |40  |category name  |

### 1.3. Table *posts*

The *posts* table contains all articles

|Name of the column  |Type  | Value |Comments
|--|--|--|--|
|p_id  |smallint  |5  |primary key in auto-increment  |
|p_title  |varchar  |100  |title of the post  |
|p_content  |text  |  |content of the post |
|p_creation_date  |datetime  |  |date and time of creation |
|p_author_id  |tinyint  |3  |foreign key of the *authors* table  |
|p_category_id  |tinyint  |3  |foreign key of the *categories* table |

### 1.4. Table *comments*

The *comments* table contains all comments.

|Name of the column  |Type  | Value |Comments
|--|--|--|--|
|com_id  |mediumint  |8  |primary key in auto-increment  |
|com_nickname  |varchar  |30  |nickname of user  |
|com_content  |text  |  |content of comments  |
|com_creation_date  |datetime  |  |date and time of creation  |
|com_post_id  |smallint  |5  |foreign key of the *posts* table |

## 2. Connection to the database

### bdd_connection.php

To establish a connection with the database you have to create an `application` folder at the root of your project. Inside, create the file [`bdd_connection.php`](https://github.com/Parissay/3WA/blob/master/Blog/application/bdd_connection.php)

First we assign our logins to the corresponding variables. In a second time we test the connection to the database with a *[try and catch](https://www.w3schools.com/php/php_exception.asp)*.

```PHP
<?php

// We attribute our logins to the corresponding variables
$host_name = '<host name>';
$database = '<your database name>';
$user_name = '<your user name>';
$password = '<your password>';

$pdo = null;
try {
	// We test the connection to the database
	$pdo = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
} catch (PDOException $e) {
	// If it does not work, an error message is displayed
	echo "Erreur!: " . $e->getMessage() . "<br/>";
	die();
}
```

[`getMessage()`](http://php.net/manual/en/exception.getmessage.php) will return the original error. Most often a `SQLSTATE` error to say that the connection has not been made. It is possible to personalize this message:

```PHP
die('unable to connect to the database');
```

## 3. Creating basic files

### layout.phtml

For a better understanding of how the project works, we will first create the file [`layout.phtml`](https://github.com/Parissay/3WA/blob/master/Blog/layout.phtml) to the root of the project. This one will allow us to have the same `header` and` footer` on the whole site. In the `main` part, we will write PHP code to automatically include the *templates* of the pages.

```PHP
<main>
	<?php include $template.'.phtml' ?>
</main>
```

### index.php

Our file [`index.php`](https://github.com/Parissay/3WA/blob/master/Blog/index.php) will be called first. As with all .php files in our project, the first step is to call our database connection file.

```PHP
include 'application/bdd_connection.php';
```

We want to display on the home page all articles classified in antechronological order (from most recent to oldest). The SQL request is :

```PHP
$query = 
`
	// Selection of the article's id, title, content, creation date, category  
	// as well as the first and last name of the author
	SELECT 
		p_id,
		p_title,
		p_content,
		p_creation_date,
		p_category_id,
		a_name,
		a_surname
	// In the `posts` table
	FROM 
		posts
	// And `authors` table
	INNER JOIN 
		authors
	// Using the foreign key p_author_id
	ON 
		posts.p_author_id = authors.a_id
	// And we classify the results in antechronological order
	ORDER BY 
		p_creation_date DESC
`;

// We execute the request which we store in $result
$result = $pdo -> query($query);

// We return an array of data with fetchAll(), which we store in $posts
$posts = $result -> fetchAll();
```

Finally, we need to define this file as the `index` *template* , and display the` layout.phtml`:

```PHP
// We define this file as the `index` template
$template = 'index';

// We call our `layout.phtml` page
include 'layout.phtml';
```

### index.phtml

Now that our SQL request is ready, we need to display the result on our [`index.phtml`](https://github.com/Parissay/3WA/blob/master/Blog/index.phtml) page which will automatically be called in the `layout.phtml` page :

```PHP
<?php include $template.'.phtml' ?>
```
which comes back to:
```PHP
<?php include 'index.phtml' ?>
```

To display all of our articles, we need to create an `ul`>` li` list using a `foreach()` loop to browse the array of data contained in the `$posts` variable in the `index.php` file.

As a reminder, `foreach($array as $value)`

```PHP
<ul>
    <?php foreach ($posts as $post): ?>
    <li>
            // Our code
    </li>
    <?php endforeach; ?>
</ul>
```

Inside the tags `li` is our code. We use the *value* `$post` of our `foreach()` loop to call the information in our post.

```PHP
<article>

    <h2><?= htmlspecialchars($post['p_title']) ?></h2>
    
    <h3>Posté le <?= htmlspecialchars($post['p_creation_date']) ?> 
    par <?= htmlspecialchars($post['p_name']).' '.htmlspecialchars($post['p_surname']) ?></h3>
    
    <p><?= substr(htmlspecialchars($post['p_content']), 0, 150) ?>[...]</p>
    
</article>
```
[`htmlspecialchars()`](http://php.net/manual/en/function.htmlspecialchars.php) converts special characters into HTML entities. For example `&` will be replaced with `&amp;`, `>` by `&gt;`, etc.

[`substr()`](http://php.net/manual/en/function.substr.php) returns a segment of a string. In our example, `substr()` returns a string from 0 to 150 characters:

```PHP
substr(htmlspecialchars($post['p_content']), 0, 150)
```
This allows us to display something like:
> Post title
> Posted on *2000-01-01 20:00:00* by *John Doe*
>Mauris quam quam, dictum ac velit id, rhoncus maximus mauris. Nulla vulputate metus id orci rutrum mollis. Sed nisi libero, viverra eget venenatis [...]

## 4. Display an article

### show_post.php

Now we have to post the whole article, its comments, and write it. We will create the page [show_post.php](https://github.com/Parissay/3WA/blob/master/Blog/show_post.php)

Like `index.php`, we start with our` bdd_connection.php` file.

When the user clicks to post an article, his `id` will end up in the url. For example, the article with `id` #`5`:

`blog/show_post.php?id=5`

Before displaying the post, we must check if it exists. To do this, we get the `id` entered in the url using` $ _GET`:

```PHP
include 'application/bdd_connection.php';

// If the id, and therefore the article, does not exist
if (!array_key_exists('id' , $_GET) OR !ctype_digit($_GET['id']))
{
	// Then we return to the home page
	header('Location: index.php');
	exit();
}
```
[`array_key_exists()`](http://php.net/manual/en/function.array-key-exists.php) : Checks if the given key or index exists in the array
[`ctype_digit()`](http://php.net/manual/en/function.ctype-digit.php) : Check for numeric character(s)

We can not determine in advance which article will be chosen by the user, and therefore the value of `id`. So we replace this value with a question mark `?`

```PHP
$query =
`
	// We select the necessary information
	SELECT
		p_id,
		p_title,
		p_content,
		p_creation_date,
		a_name,
		a_surname
	// In the `posts` table
	FROM
		posts
	// And the `authors` table
	INNER JOIN
		authors
	// Using the foreign key p_author_id
	ON
		posts.p_author_id = authors.a_id
	// And where the id of the article is equal to ?
	WHERE
		posts.p_id = ?
`;

// We prepare the request which we store in $result
$result = $pdo -> prepare($query);

// We execute the request according to the id of the article
$result -> execute(array($_GET['id']));

// Then we return a result set, with fetch(), which we store in $post
$post = $result -> fetch();
```

We use here [`PDOStatement`](https://secure.php.net/manual/en/class.pdostatement.php) which represents a prepared query and, once executed, the associated result set. [`fetch()`](http://php.net/manual/en/pdostatement.fetch.php) gets the line of the result set.

We then retrieve all comments related to the article. As for the previous request, we still do not know what the article and its `id` will be. As a reminder, `com_post_id` is the foreign key of the` posts` table.

```PHP
$query = 
`
	// We select the necessary information
	SELECT
		com_nickname,
		com_content,
		com_creation_date
	// In the `comments` table
	FROM
		comments
	// And where the id of the comment is equal to?
	WHERE
		com_post_id = ?
`;

// We prepare the request which we store in $result
$result = $pdo -> prepare($query);

// We execute the request according to the id of the article
$result -> execute(array($_GET['id']));

// Then we return a result set, with fetchAll(), which we store in $comments
$comments = $result -> fetchAll();
```

Finally :

```PHP
// We define this file as the `show_post` template
$template = 'show_post';

// We call our `layout.phtml` page
include 'layout.phtml';
```

### show_post.phtml

We can now display the article, its comments, and a form to write new ones, in our page [show_post.phtml](https://github.com/Parissay/3WA/blob/master/Blog/show_post.phtml)

#### Show article

We use here the `$post` variable from our first query to display the date.

```PHP
<article>

    <h2><?= htmlspecialchars($post['p_title']) ?></h2>
    
    <h3>Posté le <?= htmlspecialchars($post['p_creation_date']) ?> 
    par <?= htmlspecialchars($post['p_name']).' '.htmlspecialchars($post['p_surname']) ?></h3>
    
    <p><?= htmlspecialchars($post['p_content']) ?></p>
    
</article>
```

#### Display comments

We use a `foreach()` loop to browse all the comments related to the article, and display them.

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

#### Write a comment

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

A hidden field is used here to specify which item to attach the comment to. The `$_GET` method records the id of the article in the URL and passes it on when the form is sent.

```PHP
<input type="hidden" name="post" method="GET" value="<?= intval($_GET['id']) ?>">
```

The form returns to the `add_comment.php` file which we will see a little further down.

Before that, we can modify the `index.phtml` file to add links to our articles. When the user clicks on the title of an article, it will be displayed through our new page `show_post.phtml`. You can also add a "read more" button if you wish.

```PHP
<h2>
    <!-- Display the article whose id is equal to? -->
    <a href="show_post.php?id=<?= intval($post['p_id']) ?>"><?= htmlspecialchars($post['p_title']) ?></a>
</h2>
```

Which gives us, for example, for the third article :

```PHP
<h2>
    <!-- Display the article whose id is 3 -->
    <a href="show_post.php?id=3"><?= Title of the third article ?></a>
</h2>
```

[`intval()`](http://php.net/manual/en/function.intval.php) : returns the equivalent integer numeric value of a variable.

## 5. Add a comment

### add_comment.php

The [add_comment.php](https://github.com/Parissay/3WA/blob/master/Blog/add_comment.php) page allows a user to add a comment.

```PHP
// We call the connection file
include 'application/bdd_connection.php';

$query =
`
	// Insert in the `comments` table: the article id, the user's nickname,
	// the content of the comment and the date of its creation
	INSERT INTO
		comments (com_post_id, com_nickname, com_content, com_creation_date)
	// ? = because we do not know any of this information for now
	// NOW() = the creation date of the comment is now
	VALUES
		(?, ?, ?, NOW())
`;

// We prepare the request which we store in $result
$result = $pdo -> prepare($query);

// We execute the request and store the nickname, the comment and the article
// attached to it in a new table
$result -> execute(array($_POST['post'], $_POST['nickname'], $_POST['content']));

// Finally we go back to the modified article of a new comment
header('Location: show_post.php?id='.$_POST['post']);
exit();
```

## 6. Admin Panel

The admin panel **displays all articles** in a table, with an **edit** and **delete** button. It is quite possible to add an extract of each article, its author, category, etc. But we will remain simple for our example. The code of the pages : [admin.php](https://github.com/Parissay/3WA/blob/master/Blog/admin.php) and [admin.phtml](https://github.com/Parissay/3WA/blob/master/Blog/admin.phtml).

### admin.php

```PHP
// Calling the database connection file
include 'application/bdd_connection.php';
	
$query =
`
	// Selection of the article's id, title and date of creation
	SELECT
		p_id,
		p_title,
		p_creation_date
	// In the `posts` table
	FROM
		posts
	// And we classify the results in antechronological order
	ORDER BY
		p_creation_date DESC
`;

// We execute the request which we store in $result
$result = $pdo -> query($query);

// Then we return an array of data with fetchAll() which we store in $posts
$posts = $result -> fetchAll();
	
// We define this file as the `admin` template
$template = 'admin';

// We call our `layout.phtml` page
include 'layout.phtml';
```

### admin.phtml

The operation is the same as the previous pages. A `foreach()` loop is used to browse the data table and display all articles.

```PHP
<h2>PANNEAU D'ADMINISTRATION</h2>

<!-- see below for add_post.php -->
<a href="add_post.php">Rédiger un article</a>

<h3>Liste des articles publiés</h3>

<table>
    <?php foreach ($posts as $post): ?>
        <tr>
	    <td><?= htmlspecialchars($post['p_title']) ?></td>
	    <!-- see below for edit_post.php -->
	    <td><a href="edit_post.php?id=<?= intval($post['id']) ?>">éditer</a></td>
	    <!-- see below for delete_post.php -->
	    <td><a href="delete_post.php?id=<?= intval($post['id']) ?>">supprimer</a></td> 
	</tr>
    <?php endforeach; ?>
</table>
```

## 7. Write an article

On the ***admin panel*** page, the admin can click a button ***write an article*** that will send him to our [`add_post.phtml`](https://github.com/Parissay/3WA/blob/master/Blog/add_post.phtml) page, which contains a form with fields as :
- title of the article
- content of the article
- author's choice (drop-down list)
- choice of category (drop-down list)

### add_post.php

The code of the page [add_post.php](https://github.com/Parissay/3WA/blob/master/Blog/add_post.php).

As always, we start by calling our connection file to the database

```PHP
include 'application/bdd_connection.php';
```

First we open an `if` condition that checks whether the form is empty or not. This allows us to retrieve the necessary information from the drop-down lists (authors and categories). Then we make the requests.

```PHP
// If the form is empty
if (empty($_POST)) {

// 1. Request to retrieve the authors
// =====================================
$query =
`
	// Selection of authors' id, first names and last names
	SELECT
		a_id,
		a_name,
		a_surname
	// In the `authors` table
	FROM
		authors
`;

// We execute the request which we store in $result
$result = $pdo -> query($query);

// Then we return an array of data, with fetchAll(), which we store in $authors
$authors = $result -> fetchAll();

// 2. Request to retrieve categories
// ========================================
$query =
`
	// Select category id and their names
	SELECT
		cat_id,
		cat_name
	// In the `categories` table
	FROM
		categories
`;

// We execute the request which we store in $result
$result = $pdo -> query($query);

// Then we return an array of data, with fetchAll(), which we store in $categories
$categories = $result -> fetchAll();

// This file is defined as the `add_post` template
$template = 'add_post';

// We call our `layout.phtml` page
include 'layout.phtml';

}
```

The second step is to add the article according to the information on the form. The `if` checks if the form is empty, otherwise (`else`) is that the admin has filled in the fields. So we can send the data to the `posts` table.

```PHP
// Otherwise (if the form is not empty)
else {

$query =
`
	// Insert in the `posts` table: the title, the content, the id of the author,
	// the category, and the date of creation of the article
	INSERT INTO
		posts (
			p_title,
			p_content,
			p_author_id,
			p_category_id,
			p_creation_date
		)
	// ? = because we do not know any of this information for now
	// NOW() = the creation date of the article is now
	VALUES (?, ?, ?, ?, NOW())
`;

// We prepare the request which we store in $result
$result = $pdo -> prepare($query);

// We store the title, the content, the author and the category of the article
// then we run the request to send the data to our table
$result -> execute([$_POST['title'], $_POST['content'], $_POST['author'], $_POST['category']]);

// Then we return to the administration panel.
header('Location: index.php');
exit();
	
}
```

### add_post.phtml

On the display side, a `foreach()` loop is used for drop-down lists.

```PHP
<h2>REDIGER UN ARTICLE</h2>

<!-- Sending the form with the post method -->
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


## 8. Edit an article

### edit_post.php

The code of the page [edit_post.php](https://github.com/Parissay/3WA/blob/master/Blog/edit_post.php).

Like writing an article, we use the `if` condition to check that the form is empty. We add a second `if` condition to verify that the requested article exists. To do this, we get the `id` entered in the url using` $ _GET`.

```PHP
// We call the connection file
include 'application/bdd_connection.php';

if (empty($_POST)) {

	// If the id, and therefore the article, does not exist
	if (!array_key_exists('id' , $_GET) OR !ctype_digit($_GET['id']))
	{
		// Then we return to the admin page
		header('Location: admin.php');
		exit();
	}

	$query =
	`
		// We select the necessary information
		SELECT
			p_id,
			p_title,
			p_content
		// In the `posts` table
		FROM
			posts
		// Where is the id of the article equal to?
		WHERE
			p_id = ?
	`;

	// We store the request in $result
	$result = $pdo -> prepare($query);

	// We execute the request according to the id of the article
	$result -> execute(array($_GET['id']));

	// Then we return a result set, with fetch(), which we store in $post
	$post = $result -> fetch();

	// This file is defined as the `edit_post` template
	$template = 'edit_post';

	// We call our `layout.phtml` page
	include 'layout.phtml';

}
```

The second step is to add the modified article according to the information on the form. The `if` checks if the form is empty, otherwise (`else`) is that the admin has filled in the fields. So we can send the data to the `posts` table. We do not yet know the data or the article chosen by the admin, so we replace all with question marks `?

```PHP
else {

$query =
`
	// We update the `posts` table
	UPDATE
		posts
	// With the new data entered
	SET
		p_title = ?,
		p_content = ?
	// Where is the id of the article equal to?
	WHERE
		p_id = ?
`;

// We prepare the request which we store in $ result
$result = $pdo -> prepare($query);

// We store the title, the content and the id of the article, then we execute the
// request to send the data to our table
$result -> execute(array($_POST['title'], $_POST['content'], $_POST['postId']));

// And we go back to the admin panel
header('Location: admin.php');
exit();
	
}
```

### edit_post.phtml

The code of the page [edit_post.phtml](https://github.com/Parissay/3WA/blob/master/Blog/edit_post.phtml).

This page does not differ from others in its operation. Note that like the [show_post.phtml](#show_post.phtml) page, a hidden field is used here to specify which article to attach the change to. The `$ _GET` method records the id of the article in the URL and passes it on when the form is sent.

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

## 9. Delete an article

### delete_post.php

The code of the [delete_post.php](https://github.com/Parissay/3WA/blob/master/Blog/delete_post.phphp) page.

After calling our login file, we add an `if` condition to verify that the requested article exists. To do this, we get the `id` entered in the url using` $ _GET`.

```PHP
// We call our connection file
include 'application/bdd_connection.php';
	
// If the id, and therefore the article, does not exist
if (!array_key_exists('id' , $_GET) OR !ctype_digit($_GET['id']))
{
	// Then we return to the admin page
	header('Location: admin.php');
	exit();
}

$query =
`
	// We remove from the `posts` table
	DELETE FROM
		posts
	// The article whose id is equal to?
	WHERE
		p_id = ?
`;

// We prepare the request which we store in $ result
$result = $pdo -> prepare($query);

// We execute the request according to the id of the article
$result -> execute(array($_GET['id']));

// Then we go back to the administration panel
header('Location: admin.php');
exit();
```

## Author and license

If you have comments, corrections, complaints, or ideas for improvements, feel free to send me a message.

The **John Doe's Blog** was created by [@Parissay](https://github.com/Parissay). 

Le **John Doe's Blog** is available under the MIT license. See the [LICENCE](https://github.com/Parissay/3WA/blob/master/Blog/LICENSE.md) for more info. ![GitHub](https://img.shields.io/github/license/mashape/apistatus.svg)
