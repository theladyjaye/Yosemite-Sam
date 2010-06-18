// Source: https://developer.mozilla.org/en/Using_files_from_web_applications
// Firefox only
$(function() { 
	$("#dropbox").bind("dragenter dragover", function(e) {
		e.stopPropagation();
		e.preventDefault();
	})[0].addEventListener("drop", function(e) {
		e.stopPropagation();
		e.preventDefault();

		handleFiles(e.dataTransfer.files);
	}, false);
});
	
function handleFiles(files) {
  for (var i = 0; i <files.length; i++) {
    var file = files[i];
    var imageType = /image.*/;

    if (!file.type.match(imageType)) {
      continue;
    }

    var img = document.createElement("img");
    img.classList.add("obj");
    img.file = file;
    $("#preview").append(img);

    var reader = new FileReader();
    reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
    reader.readAsDataURL(file);
  }
}