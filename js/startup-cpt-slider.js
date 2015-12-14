//Slider
var $slider = jQuery('#slider');

// Initialize carousel
$slider.carousel({
    //interval: 4000
});

function doAnimations(elems) {
    var animEndEv = 'webkitAnimationEnd animationend';

    elems.each(function () {
        var $this = jQuery(this),
            $animationType = $this.data('animation');

        // Add animate.css classes to
        // the elements to be animated 
        // Remove animate.css classes
        // once the animation event has ended
        $this.addClass($animationType).one(animEndEv, function () {
            $this.removeClass($animationType);
        });
    });
}

// Select the elements to be animated
// in the first slide on page load
var $firstAnimatingElems = $slider.find('.item:first').find('[data-animation ^= "animated"]');

// Apply the animation using our function
doAnimations($firstAnimatingElems);

// Attach our doAnimations() function to the
// carousel's slide.bs.carousel event 
$slider.on('slide.bs.carousel', function (e) {
    // Select the elements to be animated inside the active slide 
    var $animatingElems = jQuery(e.relatedTarget)
        .find("[data-animation ^= 'animated']");
    doAnimations($animatingElems);
});