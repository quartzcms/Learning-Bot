navigator.getUserMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
var record = document.querySelector('.record');
var stop = document.querySelector('.stop');
stop.disabled = true;

if (navigator.getUserMedia) {
	var constraints = { audio: true };
	var chunks = [];
	
	var onSuccess = function(stream) {
		var options = {
			audioBitsPerSecond : 512000
		}
		
		var mediaRecorder = new MediaRecorder(stream, options);
	
		record.onclick = function() {
			mediaRecorder.start();
			record.style.background = "red";
			stop.disabled = false;
			record.disabled = true;
		}
	
		stop.onclick = function() {
			mediaRecorder.stop();
			record.style.background = "";
			stop.disabled = true;
			record.disabled = false;
		}
	
		mediaRecorder.onstop = function(e) {
			var blob = new Blob(chunks, { 'type' : 'audio/ogg; codecs=opus' });
			chunks = [];
		}
	
		mediaRecorder.ondataavailable = function(e) {
			chunks.push(e.data);
			var data = new FormData();
			data.append('file', e.data);
	
			if($('#question').val() == '') {
				$.ajax({
					url: '/demo/google/speech/api.php',
					type: 'POST',
					data: data,
					dataType: 'json',
					contentType: false,
					processData: false,
					success: function(json) {
						$('#question').val(json.voice_text);
						$('#ask').submit();
					}
				});
			}
		}
	}
	
	var onError = function(err) {
		console.log('The following error occured: ' + err);
	}
	
	console.log(navigator.getUserMedia);
	navigator.getUserMedia(constraints, onSuccess, onError);
}