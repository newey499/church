<?php
/*******************************
Sets include path

to include parent directory
*************************************/
ini_set('include_path',
		ini_get('include_path') . // Existing Path
		':.' .										// Current Directory
		':..'										  // Parent Directory
	   );

?>
