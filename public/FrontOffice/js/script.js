//BackToTop Button
let mybutton = document.getElementById("btn-back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (
        document.body.scrollTop > 70 ||
        document.documentElement.scrollTop > 70
    ) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}
// When the user clicks on the button, scroll to the top of the document
mybutton.addEventListener("click", backToTop);

function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}



    var map;

    function initialize() {
        var mapOptions = {
            zoom: 13,
            center: new google.maps.LatLng(50.97797382271958, -114.107718560791)
            // styles: style_array_here
        };
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    }

    var google_map_canvas = $('#map-canvas');

    if (google_map_canvas.length) {
        google.maps.event.addDomListener(window, 'load', initialize);
    }

    // Counter

    $('.counter-stat span').counterUp({
        delay: 10,
        time: 1000
    });


    // Shuffle js filter and masonry
    var Shuffle = window.Shuffle;
    var jQuery = window.jQuery;

    var myShuffle = new Shuffle(document.querySelector('.shuffle-wrapper'), {
        itemSelector: '.shuffle-item',
        buffer: 1
    });

    jQuery('input[name="shuffle-filter"]').on('change', function (evt) {
        var input = evt.currentTarget;
        if (input.checked) {
            myShuffle.filter(input.value);
        }

})(jQuery);



