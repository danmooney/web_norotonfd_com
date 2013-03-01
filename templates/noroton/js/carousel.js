(function($) {
    'use strict';

    $(document).ready(function setupFader () {
        if ($('#header').find('.carousel').find('li').length <= 1) {
            return;
        }
        $('#header').find('.carousel').aviaSlider({
            animationSpeed : 2000,
            transition: 'fade',
            blockSize: {
                height: 400,
                width: 948
            },
            appendControlls: function () {

            }
        });
    });

    function Reel (containerEl) {
        var thisSlider,
            body = $('body'),
            carouselEl = containerEl.find('.event-carousel'),
            navigationEl = containerEl.find('.circle-small-container');

        navigationEl.css('visibility', 'visible');

        carouselEl.css({
            'position': 'absolute',
            'margin-left': 0
        });

        function setupArrows () {
            containerEl.children('.circle').show().on('click', function () {
                $(this).children('.arrow').hasClass('arrow-left')
                    ? thisSlider.goBack()
                    : thisSlider.goForward();
//                containerEl.find('.arrow.back').trigger('click');
            });
        }

        function changeCircles (currentPage) {
            navigationEl.find('.circle-small-inner').remove();

            $('<div class="circle-small-inner"></div>').appendTo(navigationEl.children().eq(currentPage - 1));
//            navigationEl.find('[data-num="' + currentPage + '"]').eq(0).addClass('active');
        }

        var sliderOptions = {
            buildArrows: true,
            buildStartStop: true,
            startPanel: 0,
            enableKeyboard: false,
            hashTags: false,
            autoPlay: /*true*/false,
            autoPlayLocked: true,
            resumeDelay: -999,
//                width: 1651,
//                height: 779,
            pauseOnHover: true,
            stopAtEnd: false,
            delay: com_noroton.carouselDelay,
            autoPlayDelayed: false,
            onBeforeInitialize:  function (e, slider) { // Callback before the plugin initializes
                setupArrows();
            },
            onInitialized:       function (e, slider) { // Callback when the plugin finished initializing
                thisSlider = slider;
                changeCircles(slider.currentPage);
                navigationEl.children().click(function () {
                    var num = navigationEl.children().index($(this)) + 1;
                    containerEl.find('.panel' + num).trigger('click');
                    changeCircles(num);
                });
            },
            onShowStart:         function (e, slider) { // Callback on slideshow start

            },
            onSlideComplete:     function (slider) {
                changeCircles(slider.currentPage);
            }
        };

        carouselEl.anythingSlider(sliderOptions);
    }

    $(document).ready(function () {
        $('.event-carousel-container').each(function () {
            if ($(this).find('li').length <= 1) {
                return;
            }

            new Reel($(this));
        });
    });
}(jQuery));