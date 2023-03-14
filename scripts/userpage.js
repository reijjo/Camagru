let	streaming = false;
let uploading = false;
/* Stickers */

	function addSticker1(sticker) {
		if (uploading == true)
			document.getElementById('startCam').disabled = true;
		else
			document.getElementById('startCam').disabled = false;
			document.getElementById('uploadPic').disabled = false;
			document.getElementById('fileinput').disabled = false;
			document.getElementById('del1').disabled = false;
			document.getElementById('add1').disabled = true;

		var screen = document.getElementById('bigscreen');
		var mysticker = document.getElementById('stick1').src;
		var stickercanv = document.createElement('canvas');
		stickercanv.setAttribute('id', 'stickercanvas');
		stickercanv.setAttribute('src', document.getElementById('stick1').src);

		var image = new Image();
		image.src = mysticker;

		image.onload = function() {

			var help = document.getElementById('stickercanvas');
			stickercanv.width = screen.width / 6;
			stickercanv.height = screen.height / 4;
			var help2 = help.getContext('2d');
			help2.drawImage(image, 0, 0, stickercanv.width, stickercanv.height);
		}
		foto.append(stickercanv);
	}

	function delSticker1(element) {
		document.getElementById('del1').disabled = true;
		document.getElementById('add1').disabled = false;
		var sticker = document.getElementById('stickercanvas');
		if (sticker)
			sticker.remove();
	}

	function addSticker2(sticker) {
		if (uploading == true)
			document.getElementById('startCam').disabled = true;
		else
			document.getElementById('startCam').disabled = false;
		document.getElementById('uploadPic').disabled = false;
		document.getElementById('fileinput').disabled = false;
		document.getElementById('del2').disabled = false;
		document.getElementById('add2').disabled = true;

		var screen = document.getElementById('bigscreen');
		var mysticker = document.getElementById('stick2').src;
		var stickercanv2 = document.createElement('canvas');
		stickercanv2.setAttribute('id', 'stickercanvas2');
		stickercanv2.setAttribute('src', document.getElementById('stick2').src);

		var image = new Image();
		image.src = mysticker;

		image.onload = function() {

			var help = document.getElementById('stickercanvas2');
			stickercanv2.width = screen.width / 6;
			stickercanv2.height = screen.height / 4;
			var help2 = help.getContext('2d');
			help2.drawImage(image, 0, 0, stickercanv2.width, stickercanv2.height);
		}
		foto.append(stickercanv2);
	}

	function delSticker2(element) {
		document.getElementById('del2').disabled = true;
		document.getElementById('add2').disabled = false;
		var sticker = document.getElementById('stickercanvas2');
		if (sticker)
			sticker.remove();
	}
/* END Stickers */
/* CAMERA open close stuff */
	function openCam() {
		// Init video
		const video = document.getElementById('videoCam');
		// Validate video element
		if (navigator.mediaDevices.getUserMedia) {
			navigator.mediaDevices
				.getUserMedia({ video: true })
				.then((stream) => {
					video.srcObject = stream;
				})
				.catch(function(error) {
				});
			streaming = true;
		}
		document.getElementById('uploadPic').disabled = true;
		document.getElementById('fileinput').disabled = true;
		document.getElementById('closeCam').disabled = false;
		document.getElementById('takeFoto').disabled = false;
	}
	function closeCam() {
		const video = document.getElementById('videoCam');
		if (streaming == true) {
			let stream = video.srcObject;
			if (stream) {
			let tracks = stream.getTracks();
				tracks.forEach((track) => track.stop());
				video.srcObject = null;
			}
				streaming = false;
		}
		document.getElementById('takeFoto').disabled = true;
		document.getElementById('closeCam').disabled = true;
		document.getElementById('uploadPic').disabled = false;
		document.getElementById('fileinput').disabled = false;
	}
/* END CAMERA open close stuff */
	function makeCanvas(image) {
		var pic = document.createElement('div');
		pic.setAttribute('class', 'pic');

		var thumbimage = document.createElement('img');
		thumbimage.setAttribute('id', 'canvas');
		thumbimage.setAttribute('src', image);
		thumbimage.setAttribute('alt', 'preview');
		thumbimage.setAttribute('name', 'photo');

		var formi = document.createElement('form');
		formi.setAttribute('method', 'post');
		formi.setAttribute('enctype', 'multipart/form-data');

		var hiddendata = document.createElement('input');
		hiddendata.setAttribute('name', 'hidden_data');
		hiddendata.setAttribute('id', 'hidden_data');
		hiddendata.setAttribute('type', 'hidden');

		var use = document.createElement('button');
		use.setAttribute('type', 'file');
		use.setAttribute('class', 'usebutton');
		use.setAttribute('name', 'usebutton');
		use.setAttribute('onclick', 'useThumb(this)');

		var del = document.createElement('button');
		del.setAttribute('type', 'file');
		del.setAttribute('class', 'delbutton');
		del.setAttribute('name', 'delbutton');
		del.setAttribute('onclick', 'delThumb(this)');

		var usetxt = document.createTextNode('Use');
		var deltxt = document.createTextNode('Del');

		pic.appendChild(thumbimage);
		pic.appendChild(use);
		use.appendChild(usetxt);
		pic.appendChild(del);
		del.appendChild(deltxt);

		var putIt = document.getElementById('side');
		putIt.insertBefore(pic, putIt.children[2]);
	}

	async function takePic() {
		await sleep(1);

		let video = document.getElementById('videoCam');
		let screen = document.getElementById('bigscreen');
		let canvas = document.getElementById('canvas');
		let photo = document.getElementById('photo');
		let stickercanv = document.getElementById('stickercanvas');
		let stickercanv2 = document.getElementById('stickercanvas2');

		// Kamerasta
		if (streaming == true) {
			const fromCamera = screen.getContext('2d');
			fromCamera.drawImage(video, 0, 0, screen.width, screen.height);

			var allcanv = document.createElement('canvas');
			var ctxall = allcanv.getContext('2d');
			 if (stickercanv) {
			 	ctxall.drawImage(stickercanv, 0, 0);
			 	fromCamera.drawImage(stickercanv, 0, 0, screen.width / 5, screen.height / 5);
			 }
			var allcanv2 = document.createElement('canvas');
			var ctxall2 = allcanv2.getContext('2d');
			if (stickercanv2) {
				ctxall.drawImage(stickercanv2, 0, 0);
				fromCamera.drawImage(stickercanv2, 0, 0, screen.width, screen.height);
			}
			fromCamera.drawImage(screen, 0, 0, screen.width, screen.height);

			let image_data_url = screen.toDataURL("image/jpeg");

			let put_webcam = document.getElementById('hidden_pic');
			put_webcam.setAttribute("value", image_data_url);
		// image formdata
			let fd = new FormData();
			fd.append("hidden_pic", put_webcam.value);

			let stick1_src1 = document.getElementById('stickercanvas');
			let stick2_src1 = document.getElementById('stickercanvas2');

			if (stick1_src1) {
				let stick1_src2 = stick1_src1.getAttribute('src');
				fd.append("hidden_st1", stick1_src2);
			}
			if (stick2_src1) {
				let stick2_src2 = stick2_src1.getAttribute('src');
				fd.append('hidden_st2', stick2_src2);
			}
			fd.append("takeFoto", true);

			let xhr = new XMLHttpRequest();
			xhr.open('POST', 'userpage.php', true);
			xhr.send(fd);
		// END formdatas
			makeCanvas(image_data_url);
			document.getElementById('takeFoto').disabled = true;
			document.getElementById('closeCam').disabled = true;
			document.getElementById('uploadPic').disabled = true;
			//document.getElementById('fileinput').disabled = true;
			document.getElementById('startCam').disabled = true;
		}
		// Ladattu
		else {
			const fromUpload = screen.getContext('2d');
			fromUpload.drawImage(screen, 0, 0, screen.width, screen.height);

			var allcanv = document.createElement('canvas');
			var ctxall = allcanv.getContext('2d');
			if (stickercanv) {
				ctxall.drawImage(stickercanv, 0, 0);
				fromUpload.drawImage(stickercanv, 0, 0, screen.width / 10, screen.height / 6); //taalla mihin kohtaan piirretetaaan previewiin
			}
			var allcanv2 = document.createElement('canvas');
			var ctxall2 = allcanv2.getContext('2d');
			if (stickercanv2) {
				ctxall.drawImage(stickercanv2, 0, 0);
				fromUpload.drawImage(stickercanv2, 0, 0, screen.width, screen.height);
			}
			fromUpload.drawImage(screen, 0, 0, screen.width, screen.height);

			let upload_data_url = screen.toDataURL('image/jpeg');
			screen.setAttribute("src", upload_data_url);

			let put_upload = document.getElementById('hidden_pic');
			put_upload.setAttribute("value", upload_data_url);
		// Formdata
			let fd = new FormData();
			fd.append("hidden_pic", put_upload.value);

			let stick1_src1 = document.getElementById('stickercanvas');
			let stick2_src1 = document.getElementById('stickercanvas2');

			if (stick1_src1) {
				let stick1_src2 = stick1_src1.getAttribute('src');
				fd.append("hidden_st1", stick1_src2);
			}
			if (stick2_src1) {
				let stick2_src2 = stick2_src1.getAttribute('src');
				fd.append('hidden_st2', stick2_src2);
			}

			fd.append("takeFoto", true);

			let xhr = new XMLHttpRequest();
			xhr.open('POST', 'userpage.php', true);
			xhr.send(fd);

			makeCanvas(upload_data_url);
		}
		// END formdata

		clearScreen();
		closeCam();
		delSticker1();
		delSticker2();
		document.getElementById('takeFoto').disabled = true;
		document.getElementById('closeCam').disabled = true;
		//document.getElementById('uploadPic').disabled = true;
		document.getElementById('fileinput').disabled = true;
		uploading = false;
		//window.location.href = 'http://localhost:8080/Camagru/userpage.php';
		//window.location.reload();

	}
	function sleep(ms) {
		return new Promise(resolve => setTimeout(resolve, ms))
	}

	function clearScreen() {
		let upload = document.getElementById('bigscreen');
		let clear = upload.getContext('2d');
		clear.clearRect(0, 0, upload.width, upload.height);
	}
/* use/del thumbnail buttons */
	function useThumb(element) {
		var img_ch = element.parentNode.querySelector("img");
		var src_ch = img_ch.getAttribute('src');
		if (!src_ch)
			//window.location.href = 'http://localhost:8080/Camagru/userpage.php';
			window.location.href = 'http://localhost:8080/userpage.php';
		else {
			var fd = new FormData();
			fd.append("hidden_data", element.parentNode.querySelector("img").src);
			fd.append("usebutton", true);

			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'userpage.php', true);
			xhr.send(fd);
			setTimeout(function() {
				window.location.href = 'http://localhost:8080/makepost.php';
				//window.location.href = 'http://localhost:8080/Camagru/makepost.php';
			}, 500);
		}
	}

	function delThumb(element) {
		var img_ch = element.parentNode.querySelector("img");
		var src_ch = img_ch.getAttribute('src');
		if (!src_ch)
			window.location.href = 'http://localhost:8080/userpage.php';
			//window.location.href = 'http://localhost:8080/Camagru/userpage.php';
		else {
			var fd = new FormData();
			fd.append("hidden_data", element.parentNode.querySelector("img").src);
			fd.append("delbutton", true);

			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'userpage.php', true);
			xhr.send(fd);

			element.parentNode.remove();
			clearScreen();
		}
	}
/* END use/del thumbnail buttons */
/* UPLOAD PIC start */
	function uploadPic() {
		uploading = true;
		let fileInput = document.getElementById('fileinput');
		fileInput.addEventListener('change', function(ev) {
			if(ev.target.files[0]) {
				let file = ev.target.files[0];
				var tooBig = Math.round(file.size / 1024);
				if (tooBig >= 4096) {
					alert("File too big, max size 4mb");
				}

			else {
				var reader  = new FileReader();
				reader.readAsDataURL(file);

				reader.onloadend = function (e) {
				var image = new Image();
				image.src = e.target.result;
				image.onload = function(ev) {
					var screen = document.getElementById('bigscreen');
					screen.width = image.width;
					screen.height = image.height;
					var ctx = screen.getContext('2d');
					ctx.drawImage(image, 0, 0, screen.width, screen.height);
					}

				}
			}

			}
		})
		document.getElementById('takeFoto').disabled = false;
		document.getElementById('closeCam').disabled = true;
		document.getElementById('startCam').disabled = true;

	}

/* END UPLOAD PIC */
