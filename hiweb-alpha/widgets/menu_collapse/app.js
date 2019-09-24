jQuery(document).ready(function(){

    ///ASIDE DESKTOP
    let $aside_menu = $('aside, #mobile-categories-menu-list').find('.menu, .product-categories');
    if ($aside_menu.length > 0) {
        $aside_menu.find('.menu-item-has-children, .cat-parent').each(function () {
            let $li = $(this);
            let $button_expand = $('<button data-expand="0"><i class="fas fa-plus-circle"></i></button>');
            let $button_collapse = $('<button data-expand="1"><i class="fas fa-minus-circle"></i></button>');
            $button_expand.insertAfter($li.find('> .item-link'));
            $button_collapse.insertAfter($button_expand);
            if (!$li.is('.expanded')) {
                $li.addClass('collapsed');
            }
        });
        //events
        $('aside, #mobile-categories-menu-list').on('click', '.collapsed > button, .expanded > button', function () {
            let $this = $(this).closest('li');
            if ($this.is('.expanded')) {
                $this.removeClass('expanded').find('> .children, > .sub-menu').slideUp(500, function () {
                    $this.addClass('collapsed');
                });
            } else {
                $this.parent().find('> *.expanded > button').trigger('click');

                $this.find('> .children, > .sub-menu').slideDown(500, function () {
                    $this.removeClass('collapsed').addClass('expanded');
                });
            }
        });
    }

});