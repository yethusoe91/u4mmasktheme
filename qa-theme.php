<?php	

class qa_html_theme extends qa_html_theme_base
{

	function head_css()
	{
		qa_html_theme_base::head_css();
		$this->output('<link rel="shortcut icon" href="'.$this->rooturl.'logo.jpg" />');
		$this->output('<link href="http://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet" type="text/css">');
	}
}