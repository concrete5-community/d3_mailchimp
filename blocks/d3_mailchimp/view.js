function d3_mailchimp_submit(form) {
    var container = $(form).closest('.d3-mailchimp');
    var blockId = $(container).data('block-id');
    var data = $(form).serialize();

    $(form).find('input[type="submit"]').addClass('disabled').prop('disabled', true);

    $.post($(form).attr('action'), data)
        .done(function (data) {
            $(container).html($(data).find('.d3-mailchimp[data-block-id="'+ blockId+'"]').html());
        })
        .fail(function() {
            alert('Something went wrong');
        });

    return false;
}
