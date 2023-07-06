jQuery(document).ready(() => {
    jQuery('#wprse-submit-button').on('click', (event) => {
        event.preventDefault();
        jQuery.ajax({
            type: 'POST',
            url: wprse.admin_url + 'admin-ajax.php',
            data: {
                action: 'wprse_async_save',
                more_posts_count: jQuery('#wprse_more_posts_count').val(), // your option variable
                posts_per_page: jQuery('#wprse_posts_per_page').val()
            },
            dataType: 'json'
        }).done(function (json) {
            if (json.success) {
                showMessage(json.message);
            } else if (!json.success) {
                showMessage(json.message, false);
            }
        }).fail(function () {
            showMessage("The Ajax call itself failed.", false);
        });
    });

    jQuery(document).on('click', '.notice-dismiss', () => {
        jQuery('.notice').remove();
    });
});

function showMessage(msg, success = true) {
    let type = success ? 'success' : 'error';
    jQuery('.wrap').find('h1').after('<div class="notice notice-' + type + ' settings-error is-dismissible"><p>' + msg + '</p><button class="notice-dismiss"></button></button></div>');
}