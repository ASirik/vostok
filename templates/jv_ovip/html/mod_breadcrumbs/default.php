<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$zex = 50;
$total = count($list);
?>
<div class="breadcrumbs pathway">
<?php for ($i = 0; $i < $count; $i ++) :

	// If not the last item in the breadcrumbs add the separator
	if ($i < $count -1) {
		$classforhome = $list[$i]==$list[0]?" home png":" png";
		if(!empty($list[$i]->link)) {
			
			echo '<a href="'.$list[$i]->link.'" class="pathway'.$classforhome.'" style="position: relative; z-index:'.$zex.'">'.$list[$i]->name.'</a>';
		} else {
			echo '<span class="pathway'.$classforhome.'" style="position: relative; z-index:'.$zex.'">'.$list[$i]->name.'</span>';
		}
		//echo ' '.$separator.' ';
	}  else if ($params->get('showLast', 1)) { // when $i == $count -1 and 'showLast' is true
		if($total == 1) {
	    	echo '<span class="pathway home" style="position: relative; z-index:'.$zex.'">'.$list[$i]->name.'</span>';
		} else {
			echo '<span class="pathway" style="position: relative; z-index:'.$zex.'">'.$list[$i]->name.'</span>';
		}
	}
	$zex--;
endfor; ?>
</div>
