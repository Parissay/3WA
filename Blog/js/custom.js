$(document).ready(function(){
	
	// Main function to control data response
	function dataControl(data)
	{
		$(".form-message").addClass('visible');
		$(".form-message").removeClass('hidden');

		switch(true)
		{
			case (data == 'empty'):
				$(".form-message").addClass('bg-warning');
				$(".form-message").removeClass('bg-success');
				$(".form-message").html('Veuillez remplir l\'ensemble des champs');
				break;
			case (data == 'password'):
				$(".form-message").html('Le mot de passe est incorrect');
				break;
			case (data == 'post'):
				$(".form-message").addClass('bg-success');
				$(".form-message").removeClass('bg-warning');
				$(".form-message").html('L\'article a été modifié avec succès');
				break;
			case (data == 'comment'):
				$("#commentLoop").load(document.URL + ' #commentLoop');
				$(".form-message").addClass('bg-success');
				$(".form-message").removeClass('bg-warning');
				$(".form-message").html('Merci pour votre commentaire');
				break;
			default:
				window.location.replace("admin_panel.php");
		}
	};

	// Connection
	$("#connection-submit").click(function(event) {
		event.preventDefault();
		$.post('connection.php',
			{
				password : $("#connection-password").val()
			},
			function(data){dataControl(data);},'text');
	});

	// Add Post
	$("#addPost-submit").click(function(event) {
		event.preventDefault();
		$.post('add_post.php',
			{
				title : $("#addPost-title").val(),
				content : $("#addPost-content").val(),
				author : $("#addPost-author").val(),
				category : $("#addPost-category").val(),
				submit : $("#addPost-submit").val()
			},
			function(data){dataControl(data);},'text');
	});

	// Edit Post
	$("#editPost-submit").click(function(event) {
		event.preventDefault();
		$.post('edit_post.php',
		{
				postId : $("#editPost-postId").val(),
				title : $("#editPost-title").val(),
				content : $("#editPost-content").val(),
				submit : $("#editPost-submit").val()
		},
		function(data){dataControl(data);},'text');
	});

	// Add Comment
	$("#comment-submit").click(function(event) {
		event.preventDefault();
		$.post('add_comment.php',
		{
			postId : $("#comment-postId").val(),
			nickname : $("#comment-nickname").val(),
			content : $("#comment-content").val(),
			submit : $("#comment-submit").val()
		},
		function(data){dataControl(data);},'text');
	});

});