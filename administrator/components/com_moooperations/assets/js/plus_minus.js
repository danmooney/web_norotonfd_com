;(function($) {
    // this assumes only one multivalue field per single view
    $(document).ready(function () {
        var row,
            newRow,
            rowToInsertAfter;
            count = $('tr[data-count]').length - 1;

        $('.plus_button').live('click', function () {
            row = $(this).closest('tr');
            rowToInsertAfter = (count === 0)
                ? row
                : $('[data-count]').last();

            newRow = $(row).clone().insertAfter(rowToInsertAfter);
            count += 1;
            newRow.attr('data-count', count).find('.plus_button').remove();
            newRow.find('select, input, textarea').val('');
            newRow.find('.minus_button').css('margin-left', '60px');
            removeWFEditorClassesOnTextarea();
            startNewJCEEditors(newRow);
        });
        
        $('[data-count]').find('select').live('change', function () {
            var that  = $(this),
                value = that.val();

            if (value === '') {
                return;
            }
            $('[data-count]').find('select').not(that).each(function () {
                if ($(this).attr('name').indexOf('id') === -1) {
                    return 'continue';
                }
                if (value === $(this).val()) {
                    alert('That ' + $(this).attr('id').split('_')[0] + ' has already been selected.  Please select another.');
                    $(that).val('');
                }
            });
        });
        
        $('.minus_button').live('click', function () {
            var textareaId;
            row = $(this).closest('tr');
            if (row.attr('data-count') !== '0') {
                row.remove();
                count -= 1;
            } else {
                row.find('select, input, textarea').val('');
                if (textareaId = row.find('div.textarea').children('label').attr('for')) {
                    WFEditor.setContent(textareaId, '');
                }
            }
        });
        
        $('[data-count]').each(function () {
            $(this).addClass('relative'); 
        });


        function removeWFEditorClassesOnTextarea () {
            $('textarea').each(function () {
                $(this).removeAttr('class');
            });
        }

        function startNewJCEEditors(newRow) {
            var jceContainerEls = newRow.find('div.textarea');
            jceContainerEls.each(function () {
                var el = $(this),
                    textareaEl = el.children('textarea'),
                    id = textareaEl.attr('id'),
                    newId = /[^0-9]+/.exec(id)[0] + newRow.attr('data-count');

                textareaEl.text('').attr('class', 'wfEditor mce_editable source');

                el.contents().not(textareaEl).remove();
                textareaEl.attr('id', newId).css('display', 'block');
                WFEditor.init();
            });
        }
    });
})(jQuery);
