jQuery(document).ready(function ($) {

    // Checked all
    $("#judir-cbAll").click(function () {
        $("input.judir-cb", "form.judir-form").prop("checked", $(this).is(":checked"));
    });

    // Delete multi listings
    $("#judir-delete-listings").on("click", function (e) {
        e.preventDefault();
        var n = $(".judir-cb:checked").length;
        if (n > 0) {
            var x = confirm(Joomla.JText._('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THESE_LISTINGS', 'Are you sure you want to delete these listings?'));
            if (x) {
                $("form#judir-listings-form input[name='task']").val("modlistings.delete");
                $("form#judir-listings-form").submit();
            }
        } else {
            alert(Joomla.JText._('COM_JUDIRECTORY_NO_ITEM_SELECTED', 'No item selected!'));
        }
    });

    $("#judir-edit-listing").on("click", function (e) {
        e.preventDefault();
        var n = $(".judir-cb:checked").length;
        if (n > 0) {
            $("form#judir-listings-form input[name='task']").val("form.edit");
            $("form#judir-listings-form").submit();
        } else {
            alert(Joomla.JText._('COM_JUDIRECTORY_NO_ITEM_SELECTED', 'No item selected!'));
        }
    });

    $("#judir-publish-listings").on("click", function (e) {
        e.preventDefault();
        var n = $(".judir-cb:checked").length;
        if (n > 0) {
            var x = confirm(Joomla.JText._('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_PUBLISH_THESE_LISTINGS', 'Are you sure you want to publish these listings?'));
            if (x) {
                $("form#judir-listings-form input[name='task']").val("modlistings.publish");
                $("form#judir-listings-form").submit();
            }
        } else {
            alert(Joomla.JText._('COM_JUDIRECTORY_NO_ITEM_SELECTED', 'No item selected!'));
        }
    });

    $("#judir-unpublish-listings").on("click", function (e) {
        e.preventDefault();
        var n = $(".judir-cb:checked").length;
        if (n > 0) {
            var x = confirm(Joomla.JText._('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_UNPUBLISH_THESE_LISTINGS', 'Are you sure you want to unpublish listings?'));
            if (x) {
                $("#judir-listings-form input[name='task']").val("modlistings.unpublish");
                $("form#judir-listings-form").submit();
            }
        } else {
            alert(Joomla.JText._('COM_JUDIRECTORY_NO_ITEM_SELECTED', 'No item selected!'));
        }
    });
});