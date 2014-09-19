;(function(window, document, $, undefined) {
  $(function() {
    initializeButtonLayoutSelector();
  });

  function initializeButtonLayoutSelector() {
    $('.xing-share-layout-option input').on('change', function() {
      console.log('event');
      $('.xing-share-layout-option').removeClass('selected');
      $(this).parents('.xing-share-layout-option').addClass('selected')
    });
  }
}(window, document, jQuery));
