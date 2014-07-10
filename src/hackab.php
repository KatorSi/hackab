<?hh

	class hackab {
		private $_DIR;
	public function ab(string $_DIR): string {
		public $filename_autoloader = 'hackab_autoloader.php'; 
		public $filename_header_autoloader = 'header_autoload.php';
		public $filename_footer_autoloader = 'footer_autoloader.php';
		public $dir = $_DIR;
		public $Map = Map();
		public $namespace;
		//put header_autoloader.php into hackab_autoloader.php 
		if(file_exists($filename_header_autoloader)) {
			$file_header = file_get_contents($filename_header_autoloader);
			file_put_contents($filename_autoloader, $file_header);
		} 
		//iterator filesystem
		$iterator = new FileSystemIterator($dir);
		foreach ($iterator as $fileinfo) {
			$file = new SplFileObject($fileinfo);
			while($file != feof()) {
				$namespace = "";
				$content = "";
				$string = $file->fgets();
				$sub_string_namespace = strstr($string, "namespace");
				//looking namespace
				if(!empty($sub_string)) {
					$sub_string_namespace = trim(substr($sub_string_namespace, 9));
					$sub_string_namespace = substr($sub_string_namespace, 0, -1);
					$namespace = '\''.$sub_string_namespace.'\\\\';
				}
				$sub_string_classname = strstr($string, "class");
				//looking classname
				if(!empty($sub_string_classname)) {
					$sub_string_classname = trim(substr(str_replace("}", " ", $sub_string_classname) , 5));
					$sub_string_classname = strstr($sub_string_classname, " ");
					$Map[$sub_string_classname => $file];
					//put namespace and classname into hackab_autoloader.php
					if(!empty($namespace)) {
						$content = $namespace.$sub_string_classname.'\''.'=>'.'\'/'.$sub_string_classname.'.php\',';
						file_put_contents($filename_autoloader, $content, FILE_APPEND);
					}
					//put classname into hackab_autoloader.php
					else {
						$content =	$sub_string_classname.'\''.'=>'.'\'/'.$sub_string_classname.'.php\',';
						file_put_contents($filename_autoloader, $content, FILE_APPEND);
					}
				}
			}
		}
		//put footer_autoloader.php into hackab_autoloader.php
		if(file_exists($filename_footer_autoloader)) {
			$file_footer = file_get_contents($filename_footer_autoloader);
			file_put_contents($filename_autoloader, $file_footer, FILE_APPEND);
		}
	}
}