function offthat() {
	var fd = new FormData();
	fd.append('offmailnotif', true);

	var xhr = XMLHttpRequest();
	xhr.open('POST', 'settings.php', true);
	xhr.send(fd);
}
function onthat () {
	var fd = new FormData();
	fd.append('onmailnotif', true);

	var xhr = XMLHttpRequest();
	xhr.open('POST', 'settings.php', true);
	xhr.send(fd);
}
