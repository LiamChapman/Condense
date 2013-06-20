<?php
/**
 * @name Condense
 * @version 1.0
 * @author Liam Chapman 
 *
 * Condenses files on load and imports files using keywords e.g. '//@import myfile'
 * This is used for scripts such as JavaScript where there isn't  native include functionality.
 * By combining scripts and other files it reduces HTTP requests and overall performance
 * (This could also be used for CSS!).
 *
 * I recommend using the defaults however, you could change the setup like so:
 * @example
 *
 * new Condense('/styles/', 'main', '/cache/app', 'css', '//@require');
 *
 * You could either echo out the class as a string.
 * Or just hardcode the path in your HTML
 */
class Condense {
	
	/**
	 * Variables below are private and used internally within the class.
	 * They help transfer information between internal methods.
	 */
	private $contents	= '';
	private $source		= '';
	private $file 		= '';
	private $path		= '';
	private $matches	= array();
	private $files		= array();
	private $data		= array();
	
	
	/**
	 * @name __construct
	 * @param Directory String 	- Directory to look for files to import and run
	 * @param File String  		- file that contains list of imports etc
	 * @param Output String 	- directory and file name for output
	 * @param Ext String 		- Extension / File type to import.
	 * @param Keyword String	- Keyword to look for when importing contents into output
	 * 
	 * On initialisation execute script and set path and also set file name to look for.
	 */
	public function __construct($directory = '/scripts/', $file = 'app', $output = '/cache/app', $ext = 'js', $keyword = '//@import') {
		$this->directory = $directory;
		$this->output	 = $output;
		$this->ext		 = $ext;
		$this->keyword	 = $keyword;
		$this->path 	 = dirname(__FILE__) . $this->directory;
		$this->exec($file);
	}
	
	/**
	 * @name exec
	 * @param File String
	 *
	 * When called it checks to see if we have a file and if it needs updating.
	 * Then it will open up our file and then look for keywords
	 */
	function exec ($file) {
		$this->source	= $this->path . $file . '.' . $this->ext;
		$this->file		= dirname(__FILE__) . $this->output . '.' . $this->ext;
		if (!file_exists($this->file) || filemtime($this->source) > filemtime($this->file) ) {				
			$this->contents = file_get_contents($this->source);
			$this->match( $this->contents );
		}
	}
	
	/**
	 * @name match
	 * @param Contents String
	 *
	 * Looks for our keyword in the contents and stores them in the $files array. 
	 * When complete it calls the fetch method
	 */
	function match ($contents) {
		if (preg_match_all('~'.$this->keyword.'(.+)~', $contents, $this->matches)) {
			foreach ($this->matches[1] as $key => $match) {
				$this->files[$key] = trim($match);
			}
		}
		$this->fetch();
		return $this;
	}
	
	/**
	 * @name fetch
	 * 
	 * Loops through our files and then gets the contents and stores it in the $data array.
	 * If it can't find the file, it will add a comment.
	 */
	function fetch () {
		if (!empty($this->files)) {
			foreach ($this->files as $key => $file) {				
				if ( file_exists($this->path . $file . '.' . $this->ext)) {
					$this->data[$key] = file_get_contents( $this->path . $file . '.' . $this->ext );
				} else {
					$this->data[$key] = '/* Could not find: ' . $file . '.'.$this->ext.' */';
				}
			}
		}
		$this->replace();
		return $this;
	}
	
	/**
	 * @name replace
	 *
	 * Loops through our data and finds and replaces our keywords with the relevant content from each file.
	 * When complete it calls the save function.
	 */
	function replace () {
		if (!empty($this->data)) {
			foreach ($this->matches[0] as $key => $match) {
				$this->contents = preg_replace('~'.$match.'~', $this->data[$key], $this->contents, 1);
			}
		}
		$this->save();
		return $this;
	}
	
	/**
	 * @name save
	 *
	 * When called it saves the contents to a new file
	 */
	public function save () {
		file_put_contents($this->file, $this->contents);		
	}
	
	/**
	 * @name __toString
	 *
	 * Optional funciton to echo out the class as a string when ran
	 * @example echo new Condense();
	 * @returns ../cache/app.js
	 */
	public function __toString () {
		return $this->output . '.' . $this->ext;
	}
	
}