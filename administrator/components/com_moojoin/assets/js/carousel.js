/**
 * View for manipulating carousel text box position/height/width
 */
(function($) {
    'use strict';
    var highlightedRowColorStr = '#FCFCFC';
    $(document).ready(function () {
        var imgEl,
            elCarouselTextWrapper,
            elCarouselText,
            backgroundImgUrl,
            elCarouselContainer,
            elRow,
            /**
             * input fields
             */
            elHeight,
            elWidth,
            elX,
            elY,
            /**
             * Actual numbers from input fields
             */
            heightNum,
            widthNum,
            xNum,
            yNum,
            htmlStr,
            textIsMovingBool = false,
            initialMouseOffsetX,
            initialMouseOffsetY;

        $('[data-name="ordering"]').hide();

        // search for carousel image
        function findImgEl () {
            $('img').each(function () {
                if (this.src.indexOf('carousel') === -1) {
                    return 'continue';
                }
                this.draggable = false;
                imgEl = $(this);
                imgEl.attr('unselectable', 'on');
                // break
                return false;
            });
        }

        // append carousel text div/container div to img-encapsulating td
        // imgEl should be defined by this point
        function appendCarouselText () {
            if (elCarouselText instanceof jQuery) {
                return;
            }

            var elTd = imgEl.parent('td');

            elCarouselContainer = $('<div class="carousel-container content"></div>');
            elCarouselTextWrapper = $('<div class="carousel-text-wrapper carousel"></div>');
            elCarouselText = $('<div class="carousel-text"></div>');
            backgroundImgUrl = elCarouselText.css('background-image');

            elCarouselText.appendTo(elCarouselTextWrapper);
            elCarouselTextWrapper.attr('unselectable', 'on').appendTo(elCarouselContainer);
            imgEl.appendTo(elCarouselContainer);

            elCarouselContainer.prependTo(elTd);
        }

        function onRowActive (e) {
            var el = this || elRow || $('tr.relative').eq(0),
                elTr = $(el).closest('tr'),
                elTrs = $('tr.relative'),
                textareaIdStr = elTr.find('textarea').attr('id');

            elRow = elTr;

            // set all trs to bg color to transparent
            elTrs.css('background-color', 'transparent');

            // set active el to highlighted color
            elTr.css('background-color', highlightedRowColorStr);

            // set carousel textbox params
            elWidth   = elTr.find('[name="text_width[]"]');
            elHeight  = elTr.find('[name="text_height[]"]');
            elX       = elTr.find('[name="text_pos_x[]"]');
            elY       = elTr.find('[name="text_pos_y[]"]');

            widthNum  = parseInt(elWidth.val()) || 0;
            heightNum = parseInt(elHeight.val()) || 0;
            xNum      = parseInt(elX.val()) || 0;
            yNum      = parseInt(elY.val()) || 0;
            htmlStr   = WFEditor.getContent(textareaIdStr);

            elCarouselText.css({
                top: yNum + 'px',
                left: xNum + 'px'
            }).html(htmlStr);

            if (widthNum > 0) {
                elCarouselText.css('width', widthNum + 'px');
            } else {
                elCarouselText.css('width', 'auto');
                elWidth.val(elCarouselText.width());
            }

            if (heightNum > 0) {
                elCarouselText.css('height', heightNum + 'px');
            } else {
                elCarouselText.css('height', 'auto');
                elHeight.val(elCarouselText.height());
            }

            changeBackgroundColor(elRow.find('[name="background[]"]').val());

        }

        function updateCarouselText (updatedHtmlStr) {
            elCarouselText.html(updatedHtmlStr);
        }

        // carousel text box mouse down
        function  onMouseDown (e) {
            textIsMovingBool = true;
            initialMouseOffsetX = e.pageX;
            initialMouseOffsetY = e.pageY;
        }

        // carousel text box move
        function onMouseMove (e) {
            if (false === textIsMovingBool) {
                return;
            }

            var currentLeft = parseInt($(this).css('left'), 10),
                currentTop  = parseInt($(this).css('top'), 10),
                newTop,
                newLeft,
                mouseX = e.pageX,
                mouseY = e.pageY;
//                originalMouseOffsetX = mouseX = e.offsetX,
//                originalMouseOffsetY = mouseY = e.offsetY;

            mouseX -= initialMouseOffsetX;
            mouseY -= initialMouseOffsetY;

            initialMouseOffsetX += mouseX;
            initialMouseOffsetY += mouseY;

            newLeft = currentLeft + mouseX;
            newTop = currentTop + mouseY;

//            console.log(newLeft, newTop);
            elX.val(newLeft);
            elY.val(newTop);
            elCarouselText.css({
                top: newTop,
                left: newLeft
            });
        }

        // carousel text box mouse up
        function onMouseUp (e) {
            textIsMovingBool = false;
        }

        findImgEl();
        appendCarouselText();

        elCarouselText.mousedown(onMouseDown).mousemove(onMouseMove).mouseup(onMouseUp);
        imgEl.hover(onMouseUp);

        $('tr.relative').live('click', onRowActive).find('select, input').focus(onRowActive);

        function changeBackgroundColor(value) {
            value = value || 'gray';
            switch (value) {
                case 'black':
                    elCarouselText.css({
                        background: '#000'
                    });
                    break;
                case 'gray':
                    elCarouselText.css({
                        background: backgroundImgUrl
                    });
                    break;
                case 'none':
                    elCarouselText.css({
                        background: 'none'
                    });
                    break;
            }
        }

        $('[name="background[]"]').live('change', function () {
            var value = $(this).val();
            changeBackgroundColor(value);
        });

        // just to initialize onload
        onRowActive();

        // bind event handlers to tinyMCE editors
        setInterval(function () {
            var editors = tinyMCE.editors || [],
                editorDoc,
                elTextarea,
                i;
            for (i = 0; i < editors.length; i += 1) {
                if (editors[i].ADDED_EVENTS) {
                    continue;
                }

                editors[i].ADDED_EVENTS = true;

                editorDoc = tinyMCE.get(editors[i].editorId).getDoc();
                elTextarea = $('#' + editors[i].editorId);

                tinymce.dom.Event.add(editorDoc, 'click', function (e) {
                    onRowActive.call(this, e);
                }, elTextarea);

                tinymce.dom.Event.add(editorDoc, 'keyup', function (e) {
                    updateCarouselText.call(null, this.getContent());
                }, editors[i]);

            }
        }, 2000);

    });
}(jQuery));