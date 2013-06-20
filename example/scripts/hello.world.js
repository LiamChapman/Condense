function helloWorld () {
	var target = document.getElementById('target');
	var h1	   = document.createElement('h1');
	h1.innerHTML += 'Hello, World!';
	target.appendChild(h1);
}