<?php

class condense {

	public 	$files 	 = null, 
			$ext	 = 'js',
			$path 	 = 'js',			
			$cache 	 = true,
			$dir     = 'cache', 
			$options = array(),
			$type    = '',
			$root	 = '';

	private $get 	 = array(),
			$data	 = null,
			$times   = array();

	public function __construct ($files = null, $ext = 'js', $path = 'js', $cache = true, $dir = 'cache', $options = array()) {			
		$this->type  	= gettype($files);
		$this->files 	= isset($files)   ? $files   : $this->files;
		$this->ext 		= isset($ext)	  ? $ext	 : $this->ext;
		$this->path  	= isset($path)    ? $path    : $this->path;
		$this->cache 	= isset($cache)   ? $cache   : $this->cache;
		$this->options  = isset($options) ? $options : $this->options;
		$this->dir     	= isset($dir)     ? $dir	 : $this->dir;
		$this->root 	= dirname(__FILE__) . '/';
	}


	public function get () {		
		switch (strtolower($this->type)) {
			case 'string':
			default:				
				if($this->files == '*' || is_null($this->files)) {	
					$path = $this->root.$this->path.'/*.'.$this->ext;					
					foreach (glob($path) as $file) {
						$this->get[] = $file; 
					}				
				}
			break;
			case 'array':
				foreach ($this->files as $file) {
					$this->get[] = $this->root.$this->path.'/'.$file.'.'.$this->ext;
				}
			break;
		}
		return $this->get;
	}

	public function data () {
		if (!empty($this->get)) {
			foreach ($this->get as $file) {
				$this->data[]  = trim(file_get_contents($file));
				$this->times[] = filemtime($file);
			}
			return trim(implode("",$this->data));
		}		
	}


	public function __toString () {
		$this->get();
		if ($data = $this->data()) {
			if (!file_exists($this->root.$this->dir.'/'.$this->filename.'.'.$this->ext)) {

			}
		}
	}
	
}
//examples
/*
$c = new condense(array(
		'jquery',
		'modernizr',
		'application'
	),
	
);
echo $c;

$c = new condense;
$c->files[] = 'jquery';
$c->files[] = 'modernizr';
$c->files[] = 'application';
$c->path    = 'javascripts';
echo $c;

$c = new condense('*', 'js');
*/

$c = new condense;
#echo $c->root.$c->path.'/*.'.$c->ext;
$c->parse();
