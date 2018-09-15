# Chifoumi - The Game 

<p align="center">
  <img src="https://raw.githubusercontent.com/Parissay/3WA/master/Chifoumi/assets/github-view.jpg" alt="Chifoumi - The Game">
  <i>" Try to beat me !" </i>
</p>

*Une **version française** de ce document est disponible plus bas. A French version of this document is available below.*

## Table of contents

* [English version](#english-version)
* [French version](#version-française---french-version)
* [Author and license](#author-and-license)

<hr>

## English version

***[Chifoumi - The Game](http://parissay.com/projects/chifoumi/index.html)*** was developed during my training at **[3W Academy](https://3wa.fr/)** (summer 2018).

> The user enters the word *rock*, *paper* or *scissors*, the computer
> randomly chooses one of three possibilities, and the game begins !

The exercise was to create a JavaScript program using "simple" `window.prompt` and` document.write`.

I executed a more designed version with illustrations made on **Photoshop**. The code was developed with **jQuery**. You will find below details about the code, but first, some French-English vocabulary to understand everything :

- ***Pierre*** : rock, stone
- ***Feuille*** : paper, sheet
- ***Ciseaux*** : scissors
- ***Gagné*** : win
- ***Perdu*** : loose
- ***Égalité*** : equality, "a tie"

### 1. *User* choice

When the user clicks on one of the possibilities (*rock*, *paper*, or *scissors*), the main function `play()` starts.

```JAVASCRIPT
$('#rock').click(function(){userChoice = 'pierre'; play();});
$('#paper').click(function(){userChoice = 'feuille'; play();});
$('#scissors').click(function(){userChoice = 'ciseaux'; play();});
```

### 2. *Computer* choice

When the `play()` function is started, the computer randomly chooses in the `computerPossibility` array with [`Math.random`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/random).

```JAVASCRIPT
var computerPossibility = ["pierre", "feuille", "ciseaux"];

for (var i = 0; i <= 2; i++) {
	computerChoice = computerPossibility[(Math.floor(Math.random()*computerPossibility.length))];
}
```

### 3. Possibilities

The choices made by the player and the computer are then evaluated using the [`switch()`](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/switch) instruction, which, the associated case, will display the result and the corresponding illustrations.

```JAVASCRIPT
switch (true) {

	// 1. In case of a tie :
	case (userChoice == computerChoice) :
		// We display "ÉGALITÉ !" and the corresponding illustrations
		$('#result').show().html('<p>ÉGALITÉ !</p>') +
		$('#human').attr('src', 'img/humanEquality.png') +
		$('#computer').attr('src', 'img/robotEquality.png');
		break;
		
	// 2. In case the player wins :
	case (userChoice == "pierre") && (computerChoice == "ciseaux")
		|| (userChoice == "feuille") && (computerChoice == "pierre")
		|| (userChoice == "ciseaux") && (computerChoice == "feuille") :
		// We display "GAGNÉ !" and the corresponding illustrations
		$('#result').show().html('<p>GAGNÉ !</p>') +
		$('#human').attr('src', 'img/humanWin.png') +
		$('#computer').attr('src', 'img/robotLoose.png');
		break;
		
	// 3. In case the computer wins (by default):
	default :
		// We display "PERDU !" and the corresponding illustrations
		$('#result').show().html('<p>PERDU !</p>') +
		$('#human').attr('src', 'img/humanLoose.png') +
		$('#computer').attr('src', 'img/robotWin.png');
		break;
				
}
```

The original conversation, as well as the user choice icons, will then be hidden. Two bubbles containing the choices of the user and the computer will be displayed, as well as a * replay * button (wich reset all speech bubbles and illustrations).

## Version française - French version

Le jeu du ***[Chifoumi](http://parissay.com/projects/chifoumi/index.html)*** a été développé lors de ma formation à la **[3W Academy](https://3wa.fr/)** (été 2018). L'énoncé était :

> L'utilisateur saisit le mot *pierre*, *feuille* ou *ciseaux*, l'ordinateur choisit 
> aléatoirement l'une des trois possibilités, et la partie commence !

L'exercice était donc de créé un programme JavaScript en utilisant de "simple" `window.prompt` et `document.write`.

J'ai ensuite élaboré une version plus graphique avec des illustrations réalisées sur **Photoshop**. Quant au code, il est développé en **jQuery**. Vous trouverez ci-dessous des précisions sur le code :

### 1. Choix de l'*utilisateur*

Lors du clique de l'utilisateur sur l'une des possibilités (*pierre*, *feuille*, ou *ciseaux*), la fonction principale `play()` se lance.

```JAVASCRIPT
$('#rock').click(function(){userChoice = 'pierre'; play();});
$('#paper').click(function(){userChoice = 'feuille'; play();});
$('#scissors').click(function(){userChoice = 'ciseaux'; play();});
```

### 2. Choix de l'*ordinateur*

La fonction  `play()` lancée, l'ordinateur parcours le tableau `computerPossibility` et choisi aléatoirement entre *pierre*, *feuille* ou *ciseaux* à l'aide de la fonction [`Math.random`](https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Objets_globaux/Math/random).

```JAVASCRIPT
var computerPossibility = ["pierre", "feuille", "ciseaux"];

for (var i = 0; i <= 2; i++) {
	computerChoice = computerPossibility[(Math.floor(Math.random()*computerPossibility.length))];
}
```

### 3. Possibilités

On évalue ensuite les choix effectués par le joueur et l'ordinateur à l'aide de l'instruction [`switch()`](https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Instructions/switch), qui, le cas associé affichera le résultat et les illustrations correspondantes.

```JAVASCRIPT
switch (true) {

	// 1. En cas d'égalité :
	case (userChoice == computerChoice) :
		// On affiche "ÉGALITÉ !" et les illustrations correspondantes
		$('#result').show().html('<p>ÉGALITÉ !</p>') +
		$('#human').attr('src', 'img/humanEquality.png') +
		$('#computer').attr('src', 'img/robotEquality.png');
		break;
		
	// 2. Dans le cas où le joueur gagne :
	case (userChoice == "pierre") && (computerChoice == "ciseaux")
		|| (userChoice == "feuille") && (computerChoice == "pierre")
		|| (userChoice == "ciseaux") && (computerChoice == "feuille") :
		// On affiche "GAGNÉ !" et les illustrations correspondantes
		$('#result').show().html('<p>GAGNÉ !</p>') +
		$('#human').attr('src', 'img/humanWin.png') +
		$('#computer').attr('src', 'img/robotLoose.png');
		break;
		
	// 3. Dans le cas où l'ordinateur gagne (par défaut):
	default :
		// On affiche "PERDU !" et les illustrations correspondantes
		$('#result').show().html('<p>PERDU !</p>') +
		$('#human').attr('src', 'img/humanLoose.png') +
		$('#computer').attr('src', 'img/robotWin.png');
		break;
		
}
```

La conversation de départ, ainsi que les icônes de choix utilisateur, seront ensuite cachés. Deux bulles contenant les choix du joueur et de l'ordinateur seront affichées, ainsi qu'un bouton *replay* (rejouer). Ce dernier réinitialisera l'ensemble des bulles de conversations et illustrations.

## Author and license ![GitHub](https://img.shields.io/github/license/mashape/apistatus.svg)

If you have comments, corrections, complaints, or ideas for improvements, feel free to send me a message.

*Si vous avez des commentaires, des corrections, des plaintes ou des idées d'amélioration, n'hésitez pas à m'envoyer un message.*

**Chifoumi - The Game** was created by [@Parissay](https://github.com/Parissay). 

**Chifoumi - The Game** is available under the MIT license. See the [LICENSE file](https://github.com/Parissay/3WA/blob/master/Chifoumi/LICENSE.md) for more info.
