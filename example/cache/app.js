function helloWorld () {
	var target = document.getElementById('target');
	var h1	   = document.createElement('h1');
	h1.innerHTML += 'Hello, World!';
	target.appendChild(h1);
}
console.log('This is a test log for Condense.php!');


window.onload = function () {
	
	// Imported from hello.world
	helloWorld();
	
}