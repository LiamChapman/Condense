Condense
========

PHP Script to condense files such as JS and CSS using a main file with keywords to import file contents into one file.

By condensing files into one this reduces HTTP requests and should improve overall performance.

Make sure that this class gets autoloaded or required into your project.

## Default Example for javascript

	<script src="<?= new Condense ?>"></script>
	
The above example assumes your scripts and cache directory are in your root directory and the file its looking for is named 'app.js'.

Defaults are as follows:
- Directory: '/scripts/'
- Source File : 'app'
- Output Directory and File name: '/cache/app'
- Extension: 'js'
- Keyword to look for: '//@import'

Every project has a different setup and you can pass through custom parameters like so:

	<script src="<?= new Condense('/assets/scripts/', 'main', '/sites/cache/main') ?>"></script>
	
Onload the script checks the files modification time and will only run if a change has been made.

When the class gets echoed on screen it will return the output source URL. By default its the following:

	'/cache/app.js'
	
