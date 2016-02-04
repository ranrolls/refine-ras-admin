jQuery.extend(jQuery.validator.messages, {
	required: Joomla.JText._('COM_JUDIRECTORY_REQUIRED', 'Required'),
	remote  : Joomla.JText._('COM_JUDIRECTORY_PLEASE_RE_ENTER', 'Please re-enter'),
	email   : Joomla.JText._('COM_JUDIRECTORY_INVALID_EMAIL_ADDRESS', 'Invalid email address'),
	url     : Joomla.JText._('COM_JUDIRECTORY_INVALID_URL', 'Invalid URL'),
	date    : Joomla.JText._('COM_JUDIRECTORY_INVALID_DATE', 'Invalid date'),
	number  : Joomla.JText._('COM_JUDIRECTORY_INVALID_NUMBER', 'Invalid number')
	//maxlength  : jQuery.format(Joomla.JText._('COM_JUDIRECTORY_MAX_CHARACTERS', 'Max characters ') + {0}),
	//minlength  : jQuery.format(Joomla.JText._('COM_JUDIRECTORY_MIN_CHARACTERS', 'Min characters ') + {0})
});