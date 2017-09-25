<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syter{
	var $curr_page = null;
    var $access = null;
    function __construct($config=array()){
        if (count($config) > 0){
			$this->initialize($config);
		}
    }
    function initialize($config = array()){
		foreach ($config as $key => $val){
			if (isset($this->$key)){
				$this->$key = $val;
			}
		}
	}
	function spawn($curr_page=null,$check_login=true,$check_load=true){
		$CI =& get_instance();
		$setup = array();
		$log = array();

		if($check_login){
			$log = $this->checkLogin();
			if($log['access'] == "all")
				$access = 'all';
			else
				$access = explode(",",$log['access']);
			$this->access = $access;

			$setup['user'] = $log;
		}

		$img = base_url().'img/avatar.jpg';
		$setup['css'] = $this->initialize_includes($CI->config->item('incCss'),'css');
		$setup['js'] = $this->initialize_includes($CI->config->item('incJs'),'js');
		$menu = $CI->config->item('sideNav');
		$rmenu = $CI->config->item('rightSideNav');
		$setup['sideNav'] = $this->initialize_side_nav($menu);
		$setup['rSideNav'] = $this->initialize_side_nav($rmenu);
		$page_title = "";
		$crumb = "";
		if($curr_page != null){
			$page = $this->get_current_page($curr_page,$menu,$rmenu);
			$page_title = isset($page['title'])?$page['title']:'';
		}
		$setup['crumb'] = $crumb;
		$setup['page_title'] = $page_title;

		$company = sess('company');
		if(count($company) != ""){
			$setup['comp_name'] = $company['comp_name'];
			$setup['comp_logo'] = $company['comp_logo'];
		}

		return $setup;
	}
	function get_navs(){
		$CI =& get_instance();
		$menu = $CI->config->item('sideNav');
		$rmenu = $CI->config->item('rightSideNav');
		$navs = array_merge($menu,$rmenu);
		return $navs;
	}
	function initialize_includes($incs,$type){
		$includes = "";
		if($type=='css'){
			foreach ($incs as $val) {
				$txt = '<link href="'.base_url().$val.'" rel="stylesheet">';
				$includes .= $txt;
			}
		}
		else{
			foreach ($incs as $val) {
				$txt = '<script src="'.base_url().$val.'" type="text/javascript"  language="JavaScript"></script>';
				$includes .= $txt;
			}
		}
		return $includes;
	}
	function initialize_side_nav($navs){
		$sidemenu = $this->build_menu($navs);
		return $sidemenu;
	}
	function build_crumb($navs,$sub=false){
		$crumb = "";
		foreach ($navs as $page_key => $nav) {
			
			$crumb .= "<li>";
				$crumb .= $this->linkitize($nav,$sub);
			$crumb .= "</li>";

			if(is_array($nav['path'])){
				$crumb .= $this->build_crumb($nav['path'],true);
			}
		}
		return $crumb;
	}
	function build_menu($navs,$sub=false){
		$menu = "";
		foreach ($navs as $page_key => $nav) {
			if($nav['path'] == null){
				$menu .= "<li class='separator'>";
					$menu .= $nav['title'];
				$menu .= "</li>";
				// $menu .= "<li class='divider'>";
				// $menu .= "</li>";
			}
			else{
				if(!is_array($nav['path'])){
					if($this->checkAccess($page_key,$nav)){
						$menu .= "<li>";
							$menu .= $this->linkitize($nav,$sub);
						$menu .= "</li>";
					}
				}
				else{
					if($this->checkAccess($page_key,$nav)){
						
						$menu .= '<li class="dropdown">';
								$menu .= $this->linkitize($nav,$sub);
								$menu .= "<ul class='dropdown-menu' role='menu'>";
									$menu .= $this->build_menu($nav['path'],true);
								$menu .= "</ul>";
						$menu .= "</li>";
						// $menu .= "<li class='treeview'>";
						// 		$menu .= $this->linkitize($nav,$sub);
						// 		$menu .= "<ul class='treeview-menu'>";
						// 			$menu .= $this->build_menu($nav['path'],true);
						// 		$menu .= "</ul>";
						// $menu .= "</li>";
					}
				}
			}	
		}
		return $menu;
	}
	function get_current_page($curr_page,$navs,$navs2=array()){
		$page = array();
		foreach ($navs as $key => $nav) {
			if($key != $curr_page){
				if(is_array($nav['path']))
					$page = $this->get_current_page($curr_page,$nav['path']);
			}
			else{
				$page = $nav;
				break;
			}
		}
		if(count($page) == 0){
			foreach ($navs2 as $key => $nav) {
				if($key != $curr_page){
					if(is_array($nav['path']))
						$page = $this->get_current_page($curr_page,$nav['path']);
				}
				else{
					$page = $nav;
					break;
				}
			}
		}
		return $page;
	}
	function linkitize($link,$sub=false){
		$text = "";
		$url = "#";
		if(!is_array($link['path']))
			$url = base_url().$link['path'];
		$drop = "";
		if(is_array($link['path'])){
			$drop = "class='dropdown-toggle' data-toggle='dropdown'";
		}
		$text .= "<a ".$drop."  href='".$url."'>";
			// if($sub==true)
			// 	$text .= "<i class='fa fa-circle-o'></i>";
			$text .= $link['title'];
			// if(is_array($link['path']))
			// 	$text .= " <span class='caret'></span>";
		$text .= "</a>";
		return $text;
	}
	function checkLogin(){
		$CI =& get_instance();
		if($CI->session->userdata('user')){
			return $CI->session->userdata('user');
		}
		else{
			redirect('login','refresh');
		}
	}
	function checkAccess($pageKey=null,$nav){
		$ret = false;
		$access = $this->access;
		if(is_array($access)){
			
			if(isset($nav['exclude']) && $nav['exclude'] == 0){
				if(in_array($pageKey,$access)){
					$ret = true;
				}
			}
			else{
				$ret = true;
			}
		}
		else{
			$ret = true;
		}
		return $ret;
	}
}

/* End of file Access.php */
/* Location: ./application/libraries/Access.php */