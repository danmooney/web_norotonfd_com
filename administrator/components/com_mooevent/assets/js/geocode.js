(function($) {
    'use strict';
    function onReady () {
        var option = $('[name="option"]').val(),
            fullAddress = '',
            geocoder;
        if ('com_locations' !== option) {
            return;
        }
        
        
        function calculateAddress () {
            var newAddress = '';
            newAddress += $('#country').val() + ' '
                   + $('#address').val() + ' '
                   + $('#address2').val() + ' '
                   + $('#city').val() + ' '
                   + $('#state').val() + ' '
                   + $('#zip').val();
            return newAddress;
        };
         
        function geocodeAddress (address) {
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({address: address}, function (results, status) {
                console.log(results);
                if ('OK' === status) {
                    $('#lat').val(results[0].geometry.location.lat());
                    $('#lng').val(results[0].geometry.location.lng());
                }
            });
        }           
        
        $('input, select').change(function () {
            var newAddress = calculateAddress();
            if (newAddress !== fullAddress) {
                fullAddress = newAddress;
                geocodeAddress(fullAddress);
            }
        });
        
        fullAddress = calculateAddress();
        if ($.trim(fullAddress).length > 0) {
            geocodeAddress(fullAddress);
        }
    }
    
    $(document).ready(onReady);
}(jQuery));