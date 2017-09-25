<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//////////////////////////////////////////////////
/// SIDE BAR LINKS ///
////////////////////////////////////////////////
$nav = array();
///ADMIN CONTROL////////////////////////////////
$nav['dashboard'] = array('title'=>'<i class="fa fa-dashboard"></i> <span> Dashboard</span>','path'=>'site','exclude'=>0);	
$nav['customers'] = array('title'=>'<i class="fa fa-users"></i> Customers','path'=>'customers','exclude'=>0);
	$inventory['inv-transactions'] = array('title'=>'Transactions','path'=>null,'exclude'=>0);	
		$inventory['purch_orders'] = array('title'=>'Purchase Orders','path'=>'purchase_orders/form','exclude'=>0);
		$inventory['receive_orders'] = array('title'=>'Receive Orders','path'=>'receive_orders/lists','exclude'=>0);
	$inventory['inv-inquiries'] = array('title'=>'Inquiries','path'=>null,'exclude'=>0);	
		$inventory['purch_orders_inq'] = array('title'=>'Purchase Orders List','path'=>'purchase_orders','exclude'=>0);
		$inventory['receive_orders_inq'] = array('title'=>'Receive Orders List','path'=>'receive_orders','exclude'=>0);
		$inventory['qoh'] = array('title'=>'Quantity on Hand','path'=>'locations/qoh','exclude'=>0);
	$inventory['inv-maintenance'] = array('title'=>'Maintenance','path'=>null,'exclude'=>0);	
		$inventory['mats'] = array('title'=>'Materials','path'=>'materials','exclude'=>0);
		$inventory['mat_cat'] = array('title'=>'Materials Categories','path'=>'materials/categories','exclude'=>0);
		$inventory['suppliers'] = array('title'=>'Suppliers','path'=>'suppliers','exclude'=>0);
		$inventory['locs'] = array('title'=>'Locations','path'=>'locations','exclude'=>0);
		$inventory['uom'] = array('title'=>'Unit Of Measures','path'=>'uom','exclude'=>0);
$nav['inventory'] = array('title'=>'<i class="fa fa-cube"></i> Inventory','path'=>$inventory,'exclude'=>0);
	$work_order['wo-maintenance'] = array('title'=>'Maintenance','path'=>null,'exclude'=>0);	
		$work_order['wo_cats'] = array('title'=>'Types','path'=>'work_orders','exclude'=>0);
		$work_order['wo_stages'] = array('title'=>'Stages','path'=>'work_orders','exclude'=>0);
		$work_order['wo_items'] = array('title'=>'Items','path'=>'items','exclude'=>0);
		$work_order['wo_items_cat'] = array('title'=>'Item Categories','path'=>'items/categories','exclude'=>0);
$nav['work_order'] = array('title'=>'<i class="fa fa-ticket"></i> Work Order','path'=>$work_order,'exclude'=>0);
$config['sideNav'] = $nav;
//////////////////////////////////////////////////
/// RIGHT SIDE BAR LINKS ///
////////////////////////////////////////////////
$rnav = array();	
		$controlSettings['users'] = array('title'=>'Users','path'=>'users','exclude'=>0);
		$controlSettings['roles'] = array('title'=>'Roles','path'=>'admin/roles','exclude'=>0);
		$controlSettings['company'] = array('title'=>'Company','path'=>'admin/company','exclude'=>0);
$rnav['control'] = array('title'=>'<i class="fa fa-cogs"></i> Setup ','path'=>$controlSettings,'exclude'=>0);
$config['rightSideNav'] = $rnav;