/**
 * Created by denmedia on 13.07.2017.
 */

jQuery(document).ready(function ($) {
    var hiweb_field_terms = {

        selector_root: '.hiweb-field-terms',

        make: function (root) {
            $(root).find('select').selectize({
                closeAfterSelect: true,
                allowEmptyOption: true,
                valueField: 'value',
                labelField: 'title',
                searchField: 'title',
                options: [],
                plugins: ['remove_button','drag_drop'],
                create: false
            });
        }

    };
    $('body').on('hiweb-field-repeat-added-row-fadein', '[data-col]', function (e, col, row, root) {
        col.find(hiweb_field_terms.selector_root).each(function(){
            hiweb_field_terms.make($(this));
        });
    });
    $(hiweb_field_terms.selector_root).each(function () {
        hiweb_field_terms.make(this);
    });
});
