/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************!*\
  !*** ./resources/assets/js/scripts.js ***!
  \****************************************/
(function (window, undefined) {
  'use strict';


  $(document).on('change', '.marketToScripts', function (e) {
      var this_m = this;
      $.ajax({
          url: Base_url + 'helper/getmarket-to-scripts',
          method: 'POST',
          data: { 'MarketId': $(this).val() },
          success: function (response) {
              console.log(response);

              if (response?.Status == 200) {
                  var nearestMarkerScripts = $($('.markerScripts').closest(this_m).prevObject[0]);
                  nearestMarkerScripts.empty();
                  nearestMarkerScripts.append($("<option />").val('').text('Select Script'));
                  $.each(response.Data, function (index, item) {
                      nearestMarkerScripts.append($("<option />").val(item.id).text(item.script_name));
                  });
                  nearestMarkerScripts.select2();
              } else {
                  console.info(response);
              }
          }
      });
  });
  $('.select2').select2();



  /*
  NOTE:
  ------
  PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
  WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */
})(window);
/******/ })()
;

