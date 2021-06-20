(function ($) {
    var $progressContainer = $('.postman-progress-container'),
        $form = $('.postman-form');
    if ($progressContainer.length) {
        $(window).on('postman.stat.reload', function () {
            $progressContainer.load($progressContainer.data('url'), function (content) {
                if (/Not Found/.test(content)) {
                    return;
                }
                if ($progressContainer.find('.postman-progress-stat').length
                    && $progressContainer.find('.postman-progress-done').length === 0) {
                    $form.hide();
                    setTimeout(function () {
                        $(window).trigger('postman.stat.reload');
                    }, 2000);
                } else {
                    $form.show();
                }
            });
        });
        $(window).trigger('postman.stat.reload');
    }
})(jQuery);
