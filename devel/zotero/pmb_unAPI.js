{
	"translatorID":"e30f9b92-edf8-43ab-b042-57214380e0b4",
	"translatorType":4,
	"label":"PMB-unAPI",
	"creator":"Arnaud Renou - PMB Services",
	"target":null,
	"minVersion":"1.0.0b4.r1",
	"maxVersion":"",
	"priority":300,
	"inRepository":true,
	"lastUpdated":"2010-09-04 20:28:04"
}

var RECOGNIZABLE_FORMATS = ["pmbmarc"];
var FORMAT_GUIDS = {
	"pmbmarc" :"5ae0f27c-6504-4db9-988e-594b4e8d72fb"
};

var unAPIResolver, unsearchedIds, foundIds, foundItems, foundFormat, foundFormatName, domain;

function detectWeb(doc, url) {
	// initialize variables
	unsearchedIds = [];
	foundIds = [];
	foundItems = [];
	foundFormat = [];
	foundFormatName = [];

	// Set the domain we're scraping
	domain = doc.location.href.match(/https?:\/\/([^/]+)/);

	unAPIResolver = "unapi.php";

	// look for abbrs
	var test = doc.getElementsByTagName("span");
	for each(var div in test){
		if(div.getAttribute("class") && div.getAttribute("class") == "header_title" && div.getAttribute("notice")){
			unsearchedIds.push(div.getAttribute("notice"));
		}
	}
	if(!unsearchedIds.length) return false;
	
	// now we need to see if the server actually gives us bibliographic metadata.
	Zotero.wait();
	
	if(unsearchedIds.length == 1) {
		// if there's only one abbr tag, we should go ahead and retrieve types for it
		getItemType();
	} else {
		// if there's more than one, we should first see if the resolver gives metadata for all of them
		Zotero.Utilities.HTTP.doGet(unAPIResolver, function(text) {
			var format = checkFormats(text);
			if(format) {
				// move unsearchedIds to foundIds
				foundIds = unsearchedIds;
				unsearchedIds = [];
				// save format and formatName
				foundFormat = format[0];
				foundFormatName = format[1];
				
				Zotero.done("multiple");
			} else {
				getItemType();
			}
		});
	}
}

function getItemType() {
	// if there are no items left to search, use the only item's type (if there is one) or give up
	if(!unsearchedIds.length) {
		if(foundIds.length) {
			getOnlyItem();
		} else {
			Zotero.done(false);
		}
		return;
	}
	
	var id = unsearchedIds.shift();
	Zotero.Utilities.HTTP.doGet(unAPIResolver+"?id="+id, function(text) {
		var format = checkFormats(text);
		if(format) {
			// save data
			foundIds.push(id);
			foundFormat.push(format[0]);
			foundFormatName.push(format[1]);
			
			if(foundIds.length == 2) {
				// this is our second; use multiple
				Zotero.done("multiple");
				return;
			}
		}
		
		// keep going
		getItemType();
	});
}

function checkFormats(text) {
	text = text.replace(/<!DOCTYPE[^>]*>/, "").replace(/<\?xml[^>]*\?>/, "");
	var xml = new XML(text);
	
	var foundFormat = new Object();
	
	// this is such an ugly, disgusting hack, and I hate how Mozilla decided to neuter an ECMA standard
	for each(var format in xml.format) {
		var name = format.@name.toString();
		var lowerName = name.toLowerCase();
		
		if(lowerName.match(/^marc\b/)) {
			if(!foundFormat["marc"] || lowerName.indexOf("utf8") != -1) {
				foundFormat["marc"] = escape(name);
			}
		} else if(lowerName.match(/^pmbmarc\b/)) {
			if(!foundFormat["pmbmarc"] || lowerName.indexOf("utf8") != -1) {
				foundFormat["pmbmarc"] = escape(name);
			}
		}
	}
	
	// loop through again, this time respecting preferences
	for each(var format in RECOGNIZABLE_FORMATS) {
		if(foundFormat[format]) return [format, foundFormat[format]];
	}
	
	return false;
}

function getOnlyItem() {
	// retrieve the only item
	retrieveItem(foundIds[0], foundFormat[0], foundFormatName[0], function(obj, item) {
		foundItems.push(item);
		Zotero.done(item.itemType);
	});
}

function retrieveItem(id, format, formatName, callback) {
	// retrieve URL
	Zotero.Utilities.HTTP.doGet(unAPIResolver+"?id="+id+"&format="+formatName, function(text) {
		var translator = Zotero.loadTranslator("import");
		translator.setTranslator(FORMAT_GUIDS[format]);
		translator.setString(text);
		translator.setHandler("itemDone", callback);
		translator.translate();
	});
}

/**
 * Get formats and names for all usable ids; when done, get all items
 **/
function getAllIds() {
	if(!unsearchedIds.length) {
		// once all ids have been gotten, get all items
		getAllItems();
		return;
	}
	
	var id = unsearchedIds.shift();
	Zotero.Utilities.HTTP.doGet(unAPIResolver+"?id="+id, function(text) {
		var format = checkFormats(text);
		if(format) {
			// save data
			foundIds.push(id);
			foundFormat.push(format[0]);
			foundFormatName.push(format[1]);
		}
		
		// keep going
		getAllIds();
	});
}

/**
 * Get all items; when done, show selectItems or scrape
 **/
function getAllItems() {
	if(foundItems.length == foundIds.length) {
		if(foundItems.length == 1) {
			// Set the item Repository to the domain
			foundItems[0].repository = domain[1];
			// if only one item, send complete()
			foundItems[0].complete();
		} else if(foundItems.length > 0) {
			// if multiple items, show selectItems
			var itemTitles = [];
			for(var i in foundItems) {
				itemTitles[i] = foundItems[i].title;
			}
			
			var chosenItems = Zotero.selectItems(itemTitles);
			if(!chosenItems) Zotero.done(true);
			
			for(var i in chosenItems) {
				// Set the item Repository to the domain
				foundItems[i].repository = domain[1];
				foundItems[i].complete();
			}
		}
		
		// reset items
		foundItems = [];
		
		Zotero.done();
		return;
	}
	
	var id = foundIds[foundItems.length];
	// foundFormat can be either a string or an array
	if(typeof(foundFormat) == "string") {
		var format = foundFormat;
		var formatName = foundFormatName;
	} else {
		var format = foundFormat[foundItems.length];
		var formatName = foundFormatName[foundItems.length];
	}
	
	// get item
	retrieveItem(id, format, formatName, function(obj, item) {
		foundItems.push(item);
		getAllItems();
	});
}

function doWeb() {
	Zotero.wait();
	
	// retrieve data for all ids
	getAllIds();
}