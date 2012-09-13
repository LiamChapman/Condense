<?php

/*
 * e.g.
 $c = condense('*', 'js', 'js');
 echo $c;
*/

class condense {

	public 	$files 	  = null, 
			$type	  = 'js',
			$path 	  = 'js',			
			$cache 	  = true,
			$newline  = false,
			$dir      = 'cache',			
			$root	  = '',
			$filename = 'app';

	private $get 	  = array(),			
			$times    = array(),
			$contents = null;

	public function __construct ($files = null, $type = 'js', $path = 'js') {		
		$this->var  = gettype($files);
		$this->root = dirname(__FILE__) . '/'; #$_SERVER['DOCUMENT_ROOT'];	
		$this->type = isset($type) ? $type : $this->type;
		$this->path = isset($path) ? $path : $this->path;
	}

	public function get () {		
		switch (strtolower($this->var)) {
			case 'string':
			default:				
				if($this->files == '*' || is_null($this->files)) {	
					$path = $this->root.$this->path.'/*.'.$this->type;					
					foreach (glob($path) as $file) {
						$this->get[] = $file; 
					}				
				}
			break;
			case 'array':
				foreach ($this->files as $file) {
					$this->get[] = $this->root.$this->path.'/'.$file.'.'.$this->type;
				}
			break;
		}
		return $this->get;
	}

	public function contents () {
		if (!empty($this->get)) {
			foreach ($this->get as $file) {
				$this->contents[] = trim(file_get_contents($file)) . $this->newline ? '\n':'';
				$this->times[] 	  = filemtime($file);
			}
			return trim(implode("",$this->contents));
		}		
	}

	public function file () {
		$this->get();
		if ($contents = $this->contents()) {
			$name = $this->filename.'.'.$this->type;
			$path = $this->root.$this->dir.'/'.$name;
			if (!file_exists($path)) {
				file_put_contents($path, $contents);				
			} else {
				if (max($this->times) > filemtime($path)) {
					file_put_contents($path, $contents);
				}
			}
			switch (strtolower($this->type)) {				
				case 'js':			
					return '<script type="type/javascript" src="/'.$this->dir.'/'.$name.'"></script>';
				break;
				case 'css':
					return '<link rel="stylesheet" type="type/css" href="/'.$this->dir.'/'.$name.'" />';
				break; 
			}
		}
	}

	public function __toString () {
		return $this->file();
	}
	
}