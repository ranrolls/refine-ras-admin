jQuery(document).ready(function ($) {

    // Checked all
    $("#judir-cbAll").click(function () {
        $("input.judir-cb", "form.judir-form").prop("checked", $(this).is(":checked"));
    });

    // Approval multi listings
    $("#judir-approve-plistings").on("click", function (e) {
        e.preventDefault();
        var n = $(".judir-cb:checked").length;
        if (n > 0) {
            $("form#judir-listings-form input[name='task']").val("modpendinglistings.approve");
            $("form#judir-listings-form").submit();
        } else {
            alert(Joomla.JText._('COM_JUDIRECTORY_NO_ITEM_SELECTED', 'No item selected!'));
        }
    });

    // Reject multi listings
    $("#judir-reject-plistings").on("click", function (e) {
        e.preventDefault();
        var n = $(".judir-cb:checked").length;
        if (n > 0) {
            var x = confirm(Joomla.JText._('COM_JUDIRECTORY_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THESE_LISTINGS', 'Are you sure you want to delete these listings?'));
            if (x) {
                $("form#judir-listings-form input[name='task']").val("modpendinglistings.delete");
                $("form#judir-listings-form").submit();
            }
        } else {
            alert(Joomla.JText._('COM_JUDIRECTORY_NO_ITEM_SELECTED', 'No item selected!'));
        }
    });
});