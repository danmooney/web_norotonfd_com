(function($) {

    function validateForms () {
        $.validator.addMethod('alpha', function (value, element) {
            return this.optional(element) || /^[\sa-z\-\.']+$/i.test(value);
        },
            'This field should only consist of the alphabet, spaces, hyphens (-), periods (.) and single quotes (\')'
        );

        $.validator.addMethod('zip', function (value, element) {
            return this.optional(element) || /^\d{5}(-\d{4})?$/.text(value);
        },
            'Please enter a valid zip code.'
        );

        function positionLabel (label, element) {
            if (label.length === 0) {
                return;
            }

            label.show(); // to get height

            var top,
                left;

            top = (label.height() * -1) - 20;

            left = element.position().left - (label.width() / 2) + (element.width() / 2);

            if (parseInt(element.css('padding-top'), 10)) {
                top += Math.floor(
                    parseInt(element.height() / 2, 10) + parseInt(element.css('padding-top'), 10)
                );
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
                console.log(arguments);

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

    $(document).ready(function () {
        validateForms();
    });
}(jQuery));