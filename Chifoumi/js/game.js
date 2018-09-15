$(document).ready(function(){

	var userChoice, computerChoice;

	// Play the game
	// =============
	function play(){
		// 1. The computer makes a random choice
		var computerPossibility = ["pierre", "feuille", "ciseaux"];
		for (var i = 0; i <= 2; i++) {
			computerChoice = computerPossibility[(Math.floor(Math.random()*computerPossibility.length))];
		}
		// 2. Possibilities
		switch (true) {
			// a. In case of a tie
			case (userChoice == computerChoice) : 
					$('#result').show().html('<p>ÉGALITÉ !</p>') +
					$('#human').attr('src', 'img/humanEquality.png') +
					$('#computer').attr('src', 'img/robotEquality.png');
					break;
			// b. In case the player wins
			case (userChoice == "pierre") && (computerChoice == "ciseaux")
				|| (userChoice == "feuille") && (computerChoice == "pierre")
				|| (userChoice == "ciseaux") && (computerChoice == "feuille") :
					$('#result').show().html('<p>GAGNÉ !</p>') +
					$('#human').attr('src', 'img/humanWin.png') +
					$('#computer').attr('src', 'img/robotLoose.png');
					break;
			// c. In case the player loses
			default : 
					$('#result').show().html('<p>PERDU !</p>') +
					$('#human').attr('src', 'img/humanLoose.png') +
					$('#computer').attr('src', 'img/robotWin.png');
					break;
		}
		// 3. Hide and Show elements
		$('#startConversation, #choices').hide();
		$('#humanResult').show().html('<p>'+userChoice+'<p>');
		$('#computerResult').show().html('<p>'+computerChoice+'<p>');
		$('#replay').css('display', 'block');
	};

	// Replay the game
	// ===============
	$('#replay').click(function(){
		// Hide
		$('#humanResult, #computerResult, #replay, #result').hide();
		// Show
		$('#startConversation, #choices').show();
		// Change images
		$('#human').attr('src', 'img/humanNeutral.png');
		$('#computer').attr('src', 'img/robotNeutral.png');
	});

	// Player choice -> start the game 
	// ===============================
	$('#rock').click(function(){userChoice = 'pierre'; play();});
	$('#paper').click(function(){userChoice = 'feuille'; play();});
	$('#scissors').click(function(){userChoice = 'ciseaux'; play();});

});