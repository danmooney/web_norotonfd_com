(function($) {
    'use strict';
    function validateForms () {
        $.validator.addMethod('alpha', function (value, element) {
            return this.optional(element) || /^[\sa-z\-\.']+$/i.test(value);
        },
            'This field must only consist of the alphabet, spaces, hyphens (-), periods (.) and single quotes (\')'
        );

        $.validator.addMethod('zip', function (value, element) {
            return this.optional(element) || /^\d{5}(-\d{4})?$/.test(value);
        },
            'Please enter a valid zip code.'
        );

        $.validator.addMethod('phone', function (value, element) {
            return this.optional(element) || /^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/.test(value);
        },
            'Please enter a valid phone number.'
        );

        $.validator.addMethod('moodate', function (value, element) {
            return this.optional(element) || /^\d{2}\/\d{2}\/\d{4}$/.test(value);
        },
            'Please enter a date in format MM/DD/YYYY.'
        );

        function positionLabel (label, element) {
            if (label.length === 0) {
                return;
            }

            label.show(); // to get height

            var top,
                left;

            top = (label.height() * -1) - element.height();

            left = element.position().left - (label.width() / 2) + (element.width() / 2);

            if (parseInt(element.css('padding-top'), 10)) {
                top += Math.floor(
                    parseInt(element.height() / 2, 10) + parseInt(element.css('padding-top'), 10)
                );
            } else {
                top += element.height();
            }

            label.css({
                'top': top + 'px',
                'left': left + 'px'
            });
        }

        $('form').validate({
            wrapper: 'div',
            focusInvalid: false,
            focusCleanup: true,
            errorPlacement: function (label, element) {
                $('<div></div>').attr('class', 'label-triangle').appendTo(label);
                label.removeAttr('for').addClass('error-message').insertBefore(element);

                positionLabel(label, element);
            },
            highlight: function(element, errorClass, validClass) {
                if ($(element).is(':focus')) {
                    return;
                }

                if (element.type === "radio") {
                    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                } else {
                    $(element).addClass(errorClass).removeClass(validClass);
                }
            },
            showErrors: function (errorMap, errorList) { // override showErrors to get rid of annoying error generation while the user is typing into the field
                var focusedEl = $(':focus'),
                    focusedElName = focusedEl.attr('name'),
                    name,
                    errorListEl,
                    i;

                if (focusedEl.length) {
                    for (name in errorMap) {
                        if (!errorMap.hasOwnProperty(name)) {
                            continue;
                        }
                        if (name === focusedElName) {
                            delete errorMap[name];

                            for (i = 0; i < errorList.length; i += 1) {
                                if (errorList[i].element === focusedEl[0]) {
                                    errorList.splice(i, 1);
                                    break;
                                }
                            }

        //                    focusingOnElWithErrors = true;
                            break;
                        }
                    }
                } else { // probably triggered by submit; just show earliest field with error
                    if (errorList.length) {
                        for (i = 1; i < errorList.length; i += 1) {
                            errorListEl = $(errorList[i].element);
                            delete errorMap[errorListEl.attr('name')];
                        }

                        errorList = [errorList[0]]; // overwrite errorList and leave first index

                        positionLabel($(errorList[0].element).siblings('.error-message'), $(errorList[0].element));

                        this.errorList = errorList;
    //                    this.errorMap = errorMap;
                    }
                }

                this.defaultShowErrors(errorMap, errorList);

                for (i = 0; i < errorList.length; i += 1) {
                    errorListEl = $(errorList[i].element);
                    positionLabel(errorListEl.siblings('.error-message'), errorListEl);
                }
            }
        });
    }
    function bindGalleryHomepagePopups () {
        $.fancybox.helpers.overlay.onReady($.fancybox.opts, $.fancybox.helpers.overlay);
        (function() {
            var cidArr = [];

            function openGallery (cid) {
                $('.first.gallery-' + cid).trigger('click');
            }

            (function() {
                $('.event-general').children('a').on('click', function (e) {
                    e.preventDefault();

                    var cid = $(this).data('cid');

                    if (cidArr[cid]) {
                        openGallery(cid);
                        return;
                    }

                    cidArr[cid] = true;

                    // show fancybox loading
                    $.fancybox.helpers.overlay.open();
                    $.fancybox.showLoading();

                    $.ajax({
                        type: 'POST',
                        url: '/',
                        data: {
                            ajax: 1,
                            cid: cid
                        },
                        cid: cid,
                        success: function (data) {
//                            console.log(data);

                            var that = this,
                                img,
                                imgEl,
                                anchorEl,
                                src,
                                i;

                            try {
                                data = $.parseJSON(data);
                            } catch (e) {
                                this.error();
                            }

                            for (i = 0; i < data.length; i += 1) {
                                src = '/images/gallery/' + data[i];

                                anchorEl = $('<a></a>');

                                anchorEl.attr({
                                    'class': 'gallery-' + that.cid,
                                    rel: 'gallery-' + that.cid,
                                    href: src
                                });

                                if (0 === i) {
                                    anchorEl.addClass('first');
                                }

                                anchorEl.appendTo($('#image-cache'));

                                img = new Image();
                                img.onload = function () {
                                    imgEl = $('<img />');
//                                    imgEl.attr({
//                                        'class': 'gallery-' + that.cid,
//                                        rel: 'gallery-' + that.cid,
//                                        src: this.src
//                                    });

                                    if (this.src.indexOf(data[0]) !== -1) { // first image loaded, let's go!
                                        $.fancybox.hideLoading();
    //                                    $(document).on('click', function () {
    //                                        $(this).fancybox();
    //                                    }, '.gallery-' + that.cid);

                                        openGallery(that.cid);
                                    }
                                };

                                img.src = src;

                                if (i === (data.length - 1)) {
                                    $('.gallery-' + that.cid).fancybox({
                                        padding: [15, 80, 15, 80]
                                    });
                                }

                            }
                        },
                        error: function (data) {
                            alert('An error has occurred.  Please try again later.');
                            $.fancybox.helpers.overlay.close();
                            $.fancybox.hideLoading();
//                            console.dir(data);
                        }
                    });
                });
            }());
        }());
    }


    $(document).ready(function () {
        var loginAnchorEl,
            loginAnchorHref;

        $('form').find('#hp').parent().hide(); // hide honeypot
        validateForms();
        if ($('.event-container').length > 0) {
            bindGalleryHomepagePopups();
        } else if ($('#gallery-container').length > 0) {
            $('.image-container').children('a').fancybox({
                padding: [15, 80, 15, 80]
            });
        } else if ($('#calendar-container').length > 0) {
            $('a.fc-event, #notices-container a').fancybox({
                type: 'iframe',
//                height: 600,
                autoSize: false
            });
        }

        if ($('#login-menu-item').length > 0) {
            loginAnchorEl = $('#login-menu-item').children('a'),
            loginAnchorHref = loginAnchorEl.attr('href');

            if (loginAnchorHref.indexOf('?') !== -1) {
                loginAnchorHref += '&tmpl=login';
            } else {
                loginAnchorHref += '?tmpl=login';
            }

            loginAnchorEl.attr('href', loginAnchorHref);

            loginAnchorEl.fancybox({
                type: 'iframe'
            });
        }
    });
}(jQuery));