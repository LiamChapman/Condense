<?php

class condense {

	public 	$files 	  = null, 
			$ext	  = 'js',
			$path 	  = 'js',			
			$cache 	  = true,
			$dir      = 'cache', 
			$options  = array(),
			$type     = '',
			$root	  = '',
			$filename = 'app';

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

	public function file () {
		$this->get();
		if ($data = $this->data()) {
			$name = $this->filename.'.'.$this->ext;
			$path = $this->root.$this->dir.'/'.$name;
			if (!file_exists($path)) {
				file_put_contents($path, $data);				
			} else {
				if (max($this->times) > filemtime($path)) {
					file_put_contents($path, $data);
				}
			}
			switch (strtolower($this->ext)) {				
				case 'js':			
					return '<script type="text/javascript" src="/'.$this->dir.'/'.$name.'"></script>';
				break;
				case 'css':
					return '<link rel="stylesheet" type="text/css" href="/'.$this->dir.'/'.$name.'" />';
				break; 
			}
		}
	}

	public function __toString () {
		return $this->file();
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
echo $c;