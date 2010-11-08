// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: handle_drop.js,v 1.2 2008-11-10 13:26:06 touraine37 Exp $

function title_textfield(dragged,target) {
	target.value=dragged.firstChild.data;
}

function image_textfield(dragged,target) {
	target.value=dragged.firstChild.src;
}

function textfield_image(dragged,target) {
	var childs=dragged.parentNode.childNodes;
	var i;
	for (i=0; i<childs.length; i++) {
		if (childs[i].nodeName=="INPUT") {
			break;
		}
	}
	if (i<childs.length) target.firstChild.src=childs[i].value;
}

function notice_cart(dragged,target) {
	id=dragged.getAttribute("id");
	id=id.substring(10);
	target.src="cart_info.php?id="+id+"&header="+escape(dragged.innerHTML);
}