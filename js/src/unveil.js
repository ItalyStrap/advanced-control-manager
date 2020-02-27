/**
 * jQuery Unveil
 * A very lightweight jQuery plugin to lazy load images
 * http://luis-almeida.github.com/unveil
 *
 * Licensed under the MIT license.
 * Copyright 2013 Luís Almeida
 * https://github.com/luis-almeida
 */

;(function($) {

  $.fn.unveil = function(threshold, callback) {

    var $window = $(window),
        th = threshold || 0,
        retina = window.devicePixelRatio > 1,
        attrib = retina? "data-src-retina" : "data-lazy-src",
        images = this,
        loaded;

    this.one("unveil", function() {
      var source = this.getAttribute(attrib);
      source = source || this.getAttribute("data-lazy-src");
      if (source) {
        /* Start responsive images patch */
        var sizes  = this.getAttribute("data-sizes");
        var srcset = this.getAttribute("data-lazy-srcset");

        if ( sizes && srcset ) {
          this.setAttribute( "sizes", sizes );
        }
        if ( srcset ) {
          this.setAttribute( "srcset", srcset );
        }
        /* End responsive images patch */

        this.setAttribute("src", source);
        if (typeof callback === "function") {
          callback.call(this);
        }
      }
    });

    function unveil() {
      var inview = images.filter(function() {
        var $e = $(this);
        if ($e.is(":hidden")){
          return;
        }

        var wt = $window.scrollTop(),
            wb = wt + $window.height(),
            et = $e.offset().top,
            eb = et + $e.height();

        return eb >= wt - th && et <= wb + th;
      });

      loaded = inview.trigger("unveil");
      images = images.not(loaded);
    }

    $window.on("scroll.unveil resize.unveil lookup.unveil", unveil);

    unveil();

    return this;

  };

})(window.jQuery || window.Zepto);

/**
 *   var src = img.data("lazy-src");
 if (typeof src !== "undefined" && src !== "") {
    img.attr("src", src);
    img.data("lazy-src", "");
  }
 * @param img
 */
function force_load_img(img) {
  img.trigger("unveil");
}

jQuery(document).ready(function($){
  var img = $("img[data-lazy-src]");
  img.unveil(0, function(){
    img.load(function(){
      this.style.opacity = 1;
    });
  });
});