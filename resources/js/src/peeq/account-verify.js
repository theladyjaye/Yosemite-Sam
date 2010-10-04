window.onload = function() {
	var counter = 15; // redirect to sign up page in $counter seconds

	var timer = setInterval(function() {
		counter--;

		if(counter == 0)
		{
			clearInterval(counter);
			document.location.href = "http://yss.com/sign-up#login";			
		}
	}, 1000);
};