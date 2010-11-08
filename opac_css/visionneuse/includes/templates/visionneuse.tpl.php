<?php
// +-------------------------------------------------+
// © 2002-2010 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: visionneuse.tpl.php,v 1.5 2010-07-08 15:28:34 arenou Exp $

$visionneuse ="
	<div style='position:absolute;top:0%;left:0%;text-align:center;height:100%;width:100%'>
		<div id='visio_current_object' style='height:20%;overflow-y:auto;margin-top:1.4em;text-align:center'>
			<div id='visio_current_titre'><h1>!!titre!!</h1></div>
			<div id='visio_current_doc'>!!doc!!</div>
			<div id='visio_current_description' style='text-align:left;margin-top:1em;'>!!desc!!</div>
		</div>
		<div id='visio_navigator'style='height:40px;width:100%;margin-top:1.5em;' >
			<form method='POST' action='' name='docnumForm' id='docnumForm'>
				!!hiddenFields!!
				<input type='hidden' id='position' name='position' value='!!position!!' />
				<table style='text-align:center;width:100%'>
					<tr>
						<td style='width:45%;text-align:right;'>
							<input type='submit' id='previous' value='!!previous!!' style='display:!!previous_style!!' onclick= 'if((this.form.position.value*1-1)< 0) {this.form.position.value= 0} else { this.form.position.value=this.form.position.value*1-1}'/>
						</td>
						<td style='width:10%;'>!!current_position!!</td>
						<td style='width:45%;text-align:left;'>
							<input type='submit' id='next' value='!!next!!' style='display:!!next_style!!' onclick= 'if((this.form.position.value*1+1)> !!max_pos!!) {this.form.position.value= !!max_pos!!} else { this.form.position.value=this.form.position.value*1+1}'/>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<div style='position:absolute;right:2%;top:0%'><a href='#' onclick='close_visonneuse();return false;'>!!close!!</a></div>
	<div style='position:absolute;right:2%;bottom:1%'><a href='#' id='linkFullscreen' onclick='open_fullscreen();return false;'>!!fullscreen!!</a></div>
	<script type='text/javascript'>
		document.getElementById('visio_current_object').style.height=(window.innerHeight-75)+'px';	
		window.onresize = function(){
			document.getElementById('visio_current_object').style.height=(window.innerHeight-75)+'px';
		}

		function close_visonneuse(){
			var myself =window.parent.document.getElementById('visionneuse');
			myself.parentNode.style.overflow = '';	
			myself.parentNode.removeChild(myself);
		}

		function open_fullscreen(){
			var visionneuseIframe =window.parent.document.getElementById('visionneuseIframe');
			var linkFullscreen =document.getElementById('linkFullscreen');
			if (linkFullscreen.innerHTML == \"!!fullscreen!!\"){
				visionneuseIframe.style.width = window.parent.innerWidth+'px';
				visionneuseIframe.style.height = window.parent.innerHeight+'px';
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
	</script>
";
?>