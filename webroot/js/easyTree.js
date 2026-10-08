/**
 * An easy tree view plugin for jQuery and Bootstrap
 * @Copyright yuez.me 2014
 * @Author yuez
 * @Version 0.1
 */
(function ($) {
    $.fn.EasyTree = function (options) {

        this.each(function () {
            var easyTree = $(this);
            $.each($(easyTree).find('ul > li'), function() {
                if($(this).is('li:has(ul)')) {
                    $(this).find(' > span > i').addClass('fa-sitemap');
                }
                else {
                    $(this).find(' > span > i').addClass('fa-ellipsis-v');
                }
            });

            // collapse or expand
            $(easyTree).delegate('li.parent_li > span', 'click', function (e) {
                var children = $(this).parent('li.parent_li').find(' > ul > li');
                if (children.is(':visible')) {
                    children.hide('fast');
                } else {
                    children.show('fast');
                }
                e.stopPropagation();
            });
        });
    };
})(jQuery);
