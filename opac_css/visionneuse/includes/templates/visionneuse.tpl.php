<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: visionneuse.tpl.php,v 1.10 2011-01-06 14:21:05 arenou Exp $

$visionneuse ="
	<div style='overflow:hidden;position:absolute;top:0%;left:0%;text-align:center;height:100%;width:100%'>
		<div id='visio_current_object' style='overflow-y:auto;'>
			<div id='visio_current_titre'><h1>!!titre!!</h1></div>
			<div id='visio_current_doc'>!!doc!!</div>
			<div id='visio_current_description'>!!desc!!</div>
		</div>
		<div id='visio_navigator' >
			<form method='POST' action='' name='docnumForm' id='docnumForm'>
				!!hiddenFields!!
				<input type='hidden' id='position' name='position' value='!!position!!' />
				<table style='text-align:center;width:100%'>
					<tr>
						<td style='width:45%;text-align:right;'>
							<img src='$visionneuse_path/images/prev.gif' id='previous' style='display:!!previous_style!!' onclick='visionneuseNav(\"previous\");' />
						</td>
						<td style='width:10%;'>!!current_position!!</td>
						<td style='width:45%;text-align:left;'>
							<img src='$visionneuse_path/images/next.gif'  id='next' style='display:!!next_style!!' onclick='visionneuseNav(\"next\");'/>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<div class='close'><a href='#' onclick='close_visonneuse();return false;'>!!close!!</a></div>
	<div class='linkFullscreen'><a href='#' id='linkFullscreen' onclick='open_fullscreen();return false;'>!!fullscreen!!</a></div>
	<script type='text/javascript'>
		document.body.style.background = 'none';

		function visionneuseNav(where){
			switch(where){
				case 'next' :
					if ((document.forms['docnumForm'].position.value*1+1)> !!max_pos!!){
						document.forms['docnumForm'].position.value= !!max_pos!!
					}else{
						document.forms['docnumForm'].position.value++;
					}
					break;
				case 'previous' :
					if((document.forms['docnumForm'].position.value*1-1)< 0) {
						document.forms['docnumForm'].position.value= 0
					}else{
						document.forms['docnumForm'].position.value--;
					}
					break;
			}
			document.forms['docnumForm'].submit();	
		}

		document.getElementById('visio_current_object').style.height=getFrameHeight()-80+'px';	
	
		window.onresize = function(){
			document.getElementById('visio_current_object').style.height=getFrameHeight()-80+'px';	
			if (typeof(checkSize) != 'undefined') checkSize();
		}

		function close_visonneuse(){
			window.parent.window.close_visionneuse();
		}

		function open_fullscreen(){
			var visionneuseIframe =window.parent.document.getElementById('visionneuseIframe');
			var linkFullscreen =document.getElementById('linkFullscreen');
			if (linkFullscreen.innerHTML == \"!!fullscreen!!\"){
				visionneuseIframe.style.width = getWindowWidth()+'px';
				visionneuseIframe.style.height = getWindowHeight()+'px';
				visionneuseIframe.style.left = '0px';
				visionneuseIframe.style.top = '0px';
				linkFullscreen.innerHTML=\"!!normal!!\";
			}else{
				visionneuseIframe.style.width = '60%';
				visionneuseIframe.style.height = '80%';
				visionneuseIframe.style.left = '20%';
				visionneuseIframe.style.top = '8%';
				linkFullscreen.innerHTML=\"!!fullscreen!!\";
			}
		}

		function getFrameHeight(){
			if (window.innerHeight)
				return window.innerHeight;
			else return window.parent.document.getElementById('visionneuseIframe').clientHeight;
		}

		function getFrameWidth(){
			if(window.innerWidth) 
				return window.innerWidth;
			else return window.parent.document.getElementById('visionneuseIframe').clientWidth;
		}
		
		function getWindowHeight(){
			if (window.parent.innerHeight)
				return window.parent.innerHeight;
			else return window.parent.document.getElementById('visionneuse').clientHeight;
		}

		function getWindowWidth(){
			if (window.parent.innerWidth)
				return window.parent.innerWidth;
			else return window.parent.document.getElementById('visionneuse').clientWidth;
		}
	</script>
";
?>