function getFieldDisplay() {
    var select = document.getElementById('field_display');
    var field_display = [];
	var options = select && select.options;
	var opt;
	for (var i=0, iLen=options.length; i<iLen; i++) {
		opt = options[i];
		if (opt.selected) {
			field_display.push(opt.value || opt.text);
		}
	}

    return field_display;
}

function getOptions() {
	var optionDiv = document.getElementById('options');
	els = optionDiv.getElementsByTagName('input');
	options = [];
	for (i = 0; i < els.length; i++) {
		if (els[i].type == 'checkbox') {
			if (els[i].checked == true) {
				options[els[i].name] = 1;
			} else {
				options[els[i].name] = 0;
			}
		}
	}

	return options;
}

function InsertListing(ename, listingId) {
    var listingIds = [],
        insert_str = [];
    if (listingId) {
        listingIds.push(listingId);
    } else {
        var els = document.getElementsByName('cid[]');
        for (i = 0; i < els.length; i++) {
            if (els[i].checked == true) {
                listingIds.push(els[i].value);
            }
        }
    }

    if (listingIds.length > 0) {
        insert_str.push('{judirectory');
        insert_str.push('listing = "' + listingIds.join('|') + '"');
        field_display = getFieldDisplay();
        if (field_display) {
	        insert_str.push('field_display="' + field_display.join('|') + '"');
        }
	    options = getOptions();
	    if (options) {
		    for (option in options) {
			    if (options.hasOwnProperty(option)) {
				    insert_str.push(option + '="' + options[option] + '"');
			    }
		    }
	    }
        insert_str.push(' }');
        window.parent.jInsertEditorText(insert_str.join(' '), ename);
        window.parent.SqueezeBox.close();
    } else {
        alert(Joomla.JText._('COM_JUDIRECTORY_PLEASE_SELECT_LISTING', 'Please select listing'));
    }

    return false;
}