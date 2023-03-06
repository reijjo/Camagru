function likeThis(element) {
	var $foto_id = 0;
	var fd = new FormData();
	fd.append('hidden_like', $foto_id);
	fd.append('like', true);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'feed.php', true);
	xhr.send(fd);
}
function dislikeThis(element) {
	var $foto_id = 0;
	var fd = new FormData();
	fd.append('hidden_dislike', $foto_id);
	fd.append('dislike', true);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'feed.php', true);
	xhr.send(fd);
}

function makeComment(element) {
	var $foto_id = 0;
	var fd = new FormData();
	fd.append('hidden_data', $foto_id);
	fd.append('go', true);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'feed.php', true);
	xhr.send(fd);
}

function delComment(element) {
	var $get_id = 0;
	var fd = new FormData();
	fd.append('hidden_comdel', $get_id);
	fd.append('comdel', true);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'feed.php', true);
	xhr.send(fd);
}
