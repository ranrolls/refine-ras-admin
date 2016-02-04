jQuery(document).ready(function ($) {

    // -------------- Clear recently view listings --------------------------------
    $('#judir-clear-recent-listings').click(function () {
        var x = confirm(Joomla.JText._('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_CLEAR_ALL_RECENTLY_VIEWED_LISTINGS', 'Are you sure you want to clear all recently viewed listings?'));
        if (x) {
            document.cookie = 'judir_recently_viewed_listings=;expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
            alert(Joomla.JText._('COM_JUDIRECTORY_CLEAR_ALL_RECENTLY_VIEWED_LISTINGS_SUCCESSFULLY', 'Clear recently viewed listings successfully'));
            $(this).hide();
        }
        return false;
    });
});