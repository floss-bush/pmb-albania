{
	"translatorID":"5ae0f27c-6504-4db9-988e-594b4e8d72fb",
	"translatorType":1,
	"label":"pmbmarc",
	"creator":"Arnaud RENOU - PMB Services",
	"target":"pmbmarc",
	"minVersion":"1.0.0b3.r1",
	"maxVersion":"",
	"priority":100,
	"inRepository":true,
	"lastUpdated":"2010-02-04 02:00:00"
}


var ISO5426_dia = new Array(
	"\xc1\x41","\xc1\x45","\xc1\x49",
	"\xc1\x4f","\xc1\x55","\xc1\x61",
	"\xc1\x65","\xc1\x69","\xc1\x6f",
	"\xc1\x75","\xc2\x41","\xc2\x45",
	"\xc2\x49","\xc2\x4f","\xc2\x55",
	"\xc2\x59","\xc2\x61","\xc2\x65",
	"\xc2\x69","\xc2\x6f","\xc2\x75",
	"\xc2\x79","\xc3\x41","\xc3\x45",
	"\xc3\x49","\xc3\x4f","\xc3\x55",
	"\xc3\x61","\xc3\x65","\xc3\x69",
	"\xc3\x6f","\xc3\x75","\xc4\x41",
	"\xc4\x4e","\xc4\x4f","\xc4\x61",
	"\xc4\x6e","\xc4\x6f","\xc8\x41",
	"\xc8\x45","\xc8\x49","\xc8\x4f",
	"\xc8\x55","\xc8\x59","\xc8\x61",
	"\xc8\x65","\xc8\x69","\xc8\x6f",
	"\xc8\x75","\xc8\x79","\xc9\x41",
	"\xc9\x45","\xc9\x49","\xc9\x4f",
	"\xc9\x55","\xc8\x59","\xc9\x61",
	"\xc9\x65","\xc9\x69","\xc9\x6f",
	"\xc9\x75","\xc9\x79","\xca\x41",
	"\xca\x61","\xd0\x43","\xd0\x63",
	"\xcf\x53","\xcf\x73","\xcf\x5a",
	"\xc5\x20","\xca\x20","\xc7\x20" 
);
//
var ISO16_dia = new Array(
	"\xc0","\xc8","\xcc",
	"\xd2","\xd9","\xe0",
	"\xe8","\xec","\xf2",
	"\xf9","\xc1","\xc9",
	"\xcd","\xd3","\xda",
	"\xdd","\xe1","\xe9",
	"\xed","\xf3","\xfa",
	"\xfd","\xe2","\xca",
	"\xce","\xd4","\xdb",
	"\xe2","\xea","\xee",
	"\xf4","\xfb","\xc3",
	"\xd1","\xd5","\xe3",
	"\xf1","\xf5","\xc4",
	"\xcb","\xcf","\xd6",
	"\xdc","\xbe","\xe4",
	"\xeb","\xef","\xf6",
	"\xfc","\xff","\xc4",
	"\xcb","\xcf","\xd6",
	"\xdc","\xbe","\xe4",
	"\xeb","\xef","\xf6",
	"\xfc","\xff","\xc5",
	"\xe5","\xc7","\xe7",
	"\xa6","\xa8","\xb4",
	"\xaf","\xb0","\xba"
);

var ISO5426= new Array({
	"\xa0":"\xa0","\xa1":"\xa1","\xa2":"\x22","\xa4":"\xa4",
	"\xa5":"\xa5","\xa6":"\x3f","\xa7":"\xa7",
	"\xa8":"\x27","\xa9":"\x60","\xaa":"\x22","\xab":"\xab",
	"\xac":"\x62","\xad":"\xa9","\xae":"\x28\x50\x29",
	"\xaf":"\xae","\xb0":"\xb0","\xb1":"\x3f","\xb2":"\x2c",
	"\xb3":"\x3f","\xb4":"\x3f","\xb5":"\x3f","\xb6":"\x3f",
	"\xb7":"\xb7","\xb8":"\x27\x27","\xb9":"\x27","\xba":"\x22",
	"\xbb":"\xbb","\xbc":"\x23","\xbd":"\x27","\xbe":"\x22",
	"\xbf":"\xbf","\xe0":"\x3f","\xe1":"\xc6","\xe2":"\xd0",
	"\xe3":"\x3f","\xe4":"\x3f","\xe5":"\x3f","\xe6":"\x49\x4a",
	"\xe7":"\x3f","\xe8":"\x4c","\xe9":"\xd8","\xea":"\xbc",
	"\xeb":"\x3f","\xec":"\xde","\xed":"\x3f","\xee":"\x3f",
	"\xef":"\x3f","\xf0":"\x3f","\xf1":"\xe6","\xf2":"\x64",
	"\xf3":"\xf0","\xf4":"\x3f","\xf5":"\x69","\xf6":"\x69\x6a",
	"\xf7":"\x3f","\xf8":"\x6c","\xf9":"\xf8","\xfa":"\xbd",
	"\xfb":"\xdf","\xfc":"\xfe","\xfd":"\x3f","\xfe":"\x3f",
	"\xff":"\x3f"});

//
//
function ISO_646_5426_decode(string) {
	//Remplacement des symboles et caractères spéciaux
	string_r="";
	for (var i=0; i<string.length; i++) {
		//Si c'est un caractère avant 0xA0 alors rien a changer
		if (string[i]<"\xA0") 
			string_r+=string[i];
		else if ((string[i]>="\xC0")&&(string[i]<="\xDF")) {
			//Si c'est un diacritique on regarde le caractère suivant et on cherche dans la table de correspondance
			car=string[i]+string[i+1];
			//Si le caractère est connu
			var found = false;
			for (var j=0 ; j<ISO5426_dia.length ; j++){
				if (ISO5426_dia[j] == car) {
					string_r+=ISO16_dia[j];
					found = true;
				}
			}
			if(!found) {
				//Sinon on ne tient juste pas compte du diacritique
				string_r+=string[i+1];
			}
			//On avance d'un caractère
			i++;
		} else {
			//Sinon c'est un catactère spécial ou un symbole
			car=string[i];
			string_r+=ISO5426[car];
		}
	}
	string_r=string_r.replace("\x88","");
	string_r=string_r.replace("\x89","");
	return string_r;
}


function detectImport() {
	var marcRecordRegexp = /^[0-9]{5}[a-z ]{3}$/
	var read = Zotero.read(8);
	if(marcRecordRegexp.test(read)) {
		return true;
	}
}
//test
var fieldTerminator = "\x1E";
var recordTerminator = "\x1D";
var subfieldDelimiter = "\x1F";

/*
 * CLEANING FUNCTIONS
 */
 

 

// general purpose cleaning
function clean(value) {
	value = ISO_646_5426_decode(value);
	
	value = value.replace(/^[\s\.\,\/\:;]+/, '');
	value = value.replace(/[\s\.\,\/\:;]+$/, '');
	value = value.replace(/ +/g, ' ');
	
	var char1 = value[0];
	var char2 = value[value.length-1];
	if((char1 == "[" && char2 == "]") || (char1 == "(" && char2 == ")")) {
		// chop of extraneous characters
		return value.substr(1, value.length-2);
	}
	
	return value;
}

// number extraction
function pullNumber(text) {
	var pullRe = /[0-9]+/;
	var m = pullRe.exec(text);
	if(m) {
		return m[0];
	}
}

// ISBN extraction
function pullISBN(text) {
	var pullRe = /[0-9X\-]+/;
	var m = pullRe.exec(text);
	if(m) {
		return m[0];
	}
}

// corporate author extraction
function corpAuthor(author) {
	return {lastName:author, fieldMode:true};
}

// regular author extraction
function author(author, type, useComma) {
	return Zotero.Utilities.cleanAuthor(author, type, useComma);
}

/*
 * END CLEANING FUNCTIONS
 */

var record = function() {
	this.directory = new Object();
	this.leader = "";
	this.content = "";
	
	// defaults
	this.indicatorLength = 2;
	this.subfieldCodeLength = 2;
}

// import a binary MARC record into this record
record.prototype.importBinary = function(record) {
	// get directory and leader
	var directory = record.substr(0, record.indexOf(fieldTerminator));
	this.leader = directory.substr(0, 24);
	var directory = directory.substr(24);
	
	// get various data
	this.indicatorLength = parseInt(this.leader[10], 10);
	this.subfieldCodeLength = parseInt(this.leader[11], 10);
	var baseAddress = parseInt(this.leader.substr(12, 5), 10);
	
	// get record data
	var contentTmp = record.substr(baseAddress);
	
	// MARC wants one-byte characters, so when we have multi-byte UTF-8
	// sequences, add null characters so that the directory shows up right. we
	// can strip the nulls later.
	this.content = "";
	for(i=0; i<contentTmp.length; i++) {
		this.content += contentTmp[i];
		if(contentTmp.charCodeAt(i) > 0x00FFFF) {
			this.content += "\x00\x00\x00";
		} else if(contentTmp.charCodeAt(i) > 0x0007FF) {
			this.content += "\x00\x00";
		} else if(contentTmp.charCodeAt(i) > 0x00007F) {
			this.content += "\x00";
		}
	}
	
	// read directory
	for(var i=0; i<directory.length; i+=12) {
		var tag = parseInt(directory.substr(i, 3), 10);
		var fieldLength = parseInt(directory.substr(i+3, 4), 10);
		var fieldPosition = parseInt(directory.substr(i+7, 5), 10);
		
		if(!this.directory[tag]) {
			this.directory[tag] = new Array();
		}
		this.directory[tag].push([fieldPosition, fieldLength]);
	}
}

// add a field to this record
record.prototype.addField = function(field, indicator, value) {
	field = parseInt(field, 10);
	// make sure indicator is the right length
	if(indicator.length > this.indicatorLength) {
		indicator = indicator.substr(0, this.indicatorLength);
	} else if(indicator.length != this.indicatorLength) {
		indicator = Zotero.Utilities.lpad(indicator, " ", this.indicatorLength);
	}
	
	// add terminator
	value = indicator+value+fieldTerminator;
	
	// add field to directory
	if(!this.directory[field]) {
		this.directory[field] = new Array();
	}
	this.directory[field].push([this.content.length, value.length]);
	
	// add field to record
	this.content += value;
}

// get all fields with a certain field number
record.prototype.getField = function(field) {
	field = parseInt(field, 10);
	var fields = new Array();
	
	// make sure fields exist
	if(!this.directory[field]) {
		return fields;
	}
	
	// get fields
	for(var i in this.directory[field]) {
		var location = this.directory[field][i];
		
		// add to array, replacing null characters
		fields.push([this.content.substr(location[0], this.indicatorLength),
		             this.content.substr(location[0]+this.indicatorLength,
		               location[1]-this.indicatorLength-1).replace(/\x00/g, "")]);
	}
	
	return fields;
}

// get subfields from a field
record.prototype.getFieldSubfields = function(tag) { // returns a two-dimensional array of values
	var fields = this.getField(tag);
	var returnFields = new Array();
	
	for(var i in fields) {
		returnFields[i] = new Object();
		
		var subfields = fields[i][1].split(subfieldDelimiter);
		if (subfields.length == 1) {
			returnFields[i]["?"] = fields[i][1];
		} else {
			for(var j in subfields) {
				if(subfields[j]) {
					var subfieldIndex = subfields[j].substr(0, this.subfieldCodeLength-1);
					if(!returnFields[i][subfieldIndex]) {
						returnFields[i][subfieldIndex] = subfields[j].substr(this.subfieldCodeLength-1);
					}
				}
			}
		}
	}
	
	return returnFields;
}

// add field to DB
record.prototype._associateDBField = function(item, fieldNo, part, fieldName, execMe, arg1, arg2) {
	var field = this.getFieldSubfields(fieldNo);
	
	Zotero.debug('PMBMARC: found '+field.length+' matches for '+fieldNo+part);
	if(field) {
		for(var i in field) {
			var value = false;
			for(var j=0; j<part.length; j++) {
				var myPart = part[j];
				if(field[i][myPart]) {
					if(value) {
						value += " "+field[i][myPart];
					} else {
						value = field[i][myPart];
					}
				}
			}
			if(value) {
				value = clean(value);
				
				if(execMe) {
					value = execMe(value, arg1, arg2);
				}
				
				if(fieldName == "creator") {
					item.creators.push(value);
				} else {
					item[fieldName] = value;
					return;
				}
			}
		}
	}
}

// add field to DB as tags
record.prototype._associateTags = function(item, fieldNo, part) {
	var field = this.getFieldSubfields(fieldNo);
	Zotero.debug('PMBMARC: found '+field.length+' matches for '+fieldNo+part);
	for(var i in field) {
		for(var j=0; j<part.length; j++) {
			var myPart = part[j];
			if(field[i][myPart]) {
				item.tags.push(clean(field[i][myPart]));
			}
		}
	}
}

// this function loads a MARC record into our database
record.prototype.translate = function(item) {
	// get item type
	if(this.leader) {
		var marcType = this.leader[6];
		if(marcType == "g") {
			item.itemType = "film";
		} else if(marcType == "e" || marcType == "f") {
			item.itemType = "map";
		} else if(marcType == "k") {
			item.itemType = "artwork";
		} else if(marcType == "t" || marcType == "b") {
			// 20091210: in unimarc, the code for manuscript is b, unused in marc21.
			item.itemType = "manuscript";
		} else {
			item.itemType = "book";
		}
	} else {
		item.itemType = "book";
	}

	// Starting from there, we try to distinguish between unimarc and other marc flavours.
	// In unimarc, the title is in the 200 field and this field isn't used in marc-21 (at least)
	// In marc-21, the title is in the 245 field and this field isn't used in unimarc
	// So if we have a 200 and no 245, we can think we are with an unimarc record. 
	// Otherwise, we use the original association.
	if ( (this.getFieldSubfields("200")[0])) {
		// If we've got a 328 field, we're on a thesis
		if (this.getFieldSubfields("328")[0])
		{
			item.itemType = "thesis";
		}
		
		// Extract ISBNs
		this._associateDBField(item, "010", "a", "ISBN", pullISBN);
		// Extract ISSNs
		this._associateDBField(item, "011", "a", "ISSN", pullISBN);
		
		// Extract creators (700, 701 & 702)
		for (var i = 700; i < 703; i++)
		{
			var authorTab = this.getFieldSubfields(i);
			for (var j in authorTab) 
			{
				var aut = authorTab[j];
				var authorText = "";
				if (aut.b) {
					authorText = ISO_646_5426_decode(aut['a'])+ ", " + ISO_646_5426_decode(aut['b']);
				} 
				else
				{
					authorText = ISO_646_5426_decode(aut['a']);
				}
				
				item.creators.push(Zotero.Utilities.cleanAuthor(authorText, "author", true));
			}
		}
		
		// Extract corporate creators (710, 711 & 712)
		for (var i = 710; i < 713; i++)
		{
			var authorTab = this.getFieldSubfields(i);
			for (var j in authorTab)
			{
				if (authorTab[j]['a'])
				{
					item.creators.push({lastName:ISO_646_5426_decode(authorTab[j]['a']), creatorType:"contributor", fieldMode:true});
				}
			}
		}
		
		// Extract language. In the 101$a there's a 3 chars code, would be better to
		// have a translation somewhere
		this._associateDBField(item, "101", "a", "language");
		
		// Extract abstractNote
		this._associateDBField(item, "328", "a", "abstractNote");
		this._associateDBField(item, "330", "a", "abstractNote");
		
		// Extract tags
		// TODO : Ajouter les autres champs en 6xx avec les autoritï¿½s construites. 
		// nï¿½cessite de reconstruire les autoritï¿½s
		this._associateTags(item, "610", "a");
		
		// Extract scale (for maps)
		this._associateDBField(item, "206", "a", "scale");
		
		// Extract title
		this._associateDBField(item, "200", "ae", "title");
		
		// Extract edition
		this._associateDBField(item, "205", "a", "edition");
		
		// Extract place info
		this._associateDBField(item, "210", "a", "place");
		
		// Extract publisher/distributor
		if(item.itemType == "film")
		{
			this._associateDBField(item, "210", "c", "distributor");
		}
		else
		{
			this._associateDBField(item, "210", "c", "publisher");
		}
		
		// Extract year
		this._associateDBField(item, "210", "d", "date", pullNumber);
		// Extract pages. Not working well because 215$a often contains pages + volume informations : 1 vol ()
		// this._associateDBField(item, "215", "a", "pages", pullNumber);
		
		// Extract series
		this._associateDBField(item, "225", "a", "series");
		// Extract series number
		this._associateDBField(item, "225", "v", "seriesNumber");
		
		// Extract call number
		this._associateDBField(item, "686", "ab", "callNumber");
		this._associateDBField(item, "676", "a", "callNumber");
		this._associateDBField(item, "675", "a", "callNumber");
		this._associateDBField(item, "680", "ab", "callNumber");
		
		this._associateTags(item, "606", "a");
		
	}
}

function doImport() {
	var text;
	var holdOver = "";	// part of the text held over from the last loop
	
	while(text = Zotero.read(4096)) {	// read in 4096 byte increments
		var records = text.split("\x1D");
		
		if(records.length > 1) {
			records[0] = holdOver + records[0];
			holdOver = records.pop(); // skip last record, since it's not done
			
			for(var i in records) {
				var newItem = new Zotero.Item();
				
				// create new record
				var rec = new record();	
				rec.importBinary(records[i]);
				rec.translate(newItem);
				
				newItem.complete();
			}
		} else {
			holdOver += text;
		}
	}
}