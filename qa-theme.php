<?php	

class qa_html_theme extends qa_html_theme_base
{

	function donut_get_glyph_icon($icon)
	{
		if (!empty($icon)) {
			return '<span class="glyphicon glyphicon-'.$icon.'"></span> ';
		}else {
			return '' ;
		}
	}

	function head_css()
	{
		$this->output('<link rel="stylesheet" href="'. $this->rooturl.'css/bootstrap.css" type="text/css"/>');
		$this->output('<link rel="stylesheet" href="'. $this->rooturl.'custom.css" type="text/css"/>');

	}

	public function head_script()
	{
		$this->output('<script src="' . $this->rooturl .'js/jquery-1.11.0.min.js"></script>');
		$this->output('<script src="' . $this->rooturl .'js/bootstrap.js"></script>');
	}
	// removes user navigation and search from header and replaces with custom header content. Also opens new <div>s
	public function header()
	{
		
	}

	public function logged_in()
	{
		if (qa_is_logged_in()) // output user avatar to login bar
		$this->output(
			'<div class="qa-logged-in-avatar">',
			QA_FINAL_EXTERNAL_USERS
			? qa_get_external_avatar_html(qa_get_logged_in_userid(), 24, true)
			: qa_get_user_avatar_html(qa_get_logged_in_flags(), qa_get_logged_in_email(), qa_get_logged_in_handle(),
				qa_get_logged_in_user_field('avatarblobid'), qa_get_logged_in_user_field('avatarwidth'), qa_get_logged_in_user_field('avatarheight'),
				24, true),
			'</div>'
			);

		qa_html_theme_base::logged_in();

		if (qa_is_logged_in()) { // adds points count after logged in username
			$userpoints=qa_get_logged_in_points();

			$pointshtml=($userpoints==1)
			? qa_lang_html_sub('main/1_point', '1', '1')
			: qa_lang_html_sub('main/x_points', qa_html(number_format($userpoints)));

			$this->output(
				'<span class="qa-logged-in-points">',
				'('.$pointshtml.')',
				'</span>'
				);
		}
	}

	// adds login bar, user navigation and search at top of page in place of custom header content
	public function body_header()
	{
		$this->output('<div class="row"><div class="container" style="margin-top="10px">');
		$nav_html = '<ul class="creditial-bar pull-right">';
		foreach ($this->content['navigation']['user'] as $unav) {
			$nav_html.='<li><a href="'.$unav['url'].'">'.$this->donut_get_glyph_icon($unav['label']).$unav['label'].'</a></li>';
		}
		$nav_html.='</ul>';
		$this->output($nav_html);
		$this->logo();
		$nav = '<div class="col-md-12"><nav class="navbar navbar-default navbar-static-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="'.qa_path_html('').'">'.qa_opt('site_title').'</a>
			</div>
			<div class="nav navbar-nav navbar-left" id="">';
				$this->output($nav);
				$this->nav('main');

				$this->nav('sub');
				$nav ='</div>';
				$this->output($nav);
				$nav = $this->search();
				$this->output($nav);
				$nav = '
			</div>	
		</nav></div></div></div>';

		$this->output($nav);
	}

	public function nav_list($navigation, $class, $level=null)
	{
		$this->output('<ul class="nav navbar-nav">');

		$index = 0;

		foreach ($navigation as $key => $navlink) {
			$this->set_context('nav_key', $key);
			$this->set_context('nav_index', $index++);
			$this->nav_item($key, $navlink, $class, $level);
		}

		$this->clear_context('nav_key');
		$this->clear_context('nav_index');

		$this->output('</ul>');
	}

	public function search()
	{
		$search = $this->content['search'];
		$this->output(
			'<form '.$search['form_tags'].' class="navbar-form navbar-right"><div class="form-group">',
			@$search['form_extra']
			);
		$this->search_field($search);
		$this->search_button($search);
		$this->output(
			'</div></form>'
		);
	}

	public function search_field($search)
	{
		$this->output('<input type="text" '.$search['field_tags'].' class="form-control" plceholder="Search"/>');
	}

	public function search_button($search)
	{
		$this->output('<input type="submit" value="'.$search['button_label'].'" class="btn btn-warning"/>');
	}


	function body_content() {
		$this->output('<div class="row"><div class="container body"><div class="col-md-9">');
		$this->main();
		$this->output('</div>');
		$this->output($this->sidepanel());
		$this->output('</div>');
	}


	function sidepanel()
	{
		$this->output('<div div class="col-md-3">');
		$this->widgets('side', 'top');
		$this->sidebar();
		$this->widgets('side', 'high');
		$this->nav('cat', 1);
		$this->widgets('side', 'low');
		$this->output_raw(@$this->content['sidepanel']);
		$this->feed();
		$this->widgets('side', 'bottom');
		$this->output('</div>', '');
	}
}