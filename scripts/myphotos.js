function delReal(element) {
	var $foto_id = 0;
	var fd = new FormData();
	fd.append('hidden_real', $foto_id);
	fd.append('deleteForReal', true);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'myphotos.php', true);
	xhr.send(fd);
}

function delComment(element) {
	var $get_id = 0;
	var fd = new FormData();
	fd.append('hidden_comdel', $get_id);
	fd.append('comdel', true);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'myphotos.php', true);
	xhr.send(fd);
}
