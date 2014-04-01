<?php
/*======================================================================*\
|| #################################################################### ||
|| # Package - Joomla Template based on YJSimpleGrid Framework          ||
|| # Copyright (C) 2010  Youjoomla.com. All Rights Reserved.            ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla.com                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/
// No direct access.
defined( '_JEXEC' ) or die( 'Restricted index access' );


$loadmenu 		 = &JSite::getMenu();
if($this->countModules('topmenupoz') && $default_menu_style == 6){
	$menu_name_is = $topmenupoz_name;
}else{
	$menu_name_is = $menu_name;
}
$renderer	 		= $document->loadRenderer( 'module' );
$options	 		= array( 'style' => "raw" );
$module	    	 	= JModuleHelper::getModule( 'mod_menu','$menu_name_is' );
$mobile_load     	= false; $subnav = false; $sidenav = false;			
$module->params		= "menutype=$menu_name_is\nshowAllChildren=1";
$mobile_load 		= $renderer->render( $module, $options );
$mobile_menu 	 	= $loadmenu->getItems('menutype',$menu_name_is, false);	

if( isset($loadmenu->getActive()->title)){
	$SetActiveTitle = $loadmenu->getActive()->title;
}else{
	$SetActiveTitle = $mobile_menu[0]->title;
}

?>
<div id="mmenu_holder">
  <span class="yjmm_select" id="yjmm_selectid"><?php echo $SetActiveTitle ?></span>
  <select id="mmenu" class="yjstyled">
      <?php foreach($mobile_menu as $key => $menuitem) : 
        if(count($menuitem->tree) == 1 || $menuitem->home == 1) {
            $addline ='&nbsp;';
        }else{
			$menu_tab = "";		
			for($i = 1; $i <= count($menuitem->tree); $i++){
				$menu_tab .= "-";
			}
            $addline ='&nbsp;'.$menu_tab;
        }
		
		if(isset($loadmenu->getActive()->id)){
			$SetActiveId = $loadmenu->getActive()->id;
		}else{
			$SetActiveId = $mobile_menu[0]->id;
		}
		
		
		if($menuitem->id == $SetActiveId){
			$selected = ' selected="selected"';
		}else{
			$selected ='';
		}
      ?>
      <option value="<?php echo $menuitem->flink?>"<?php echo $selected ?>><?php echo $addline.$menuitem->title?></option>
      <?php endforeach ?>
  </select>
</div>