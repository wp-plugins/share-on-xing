;(function(window, document, $, undefined) {
  $(function() {
    initializeButtonLayoutSelector();
    initializeButtonLanguageSelector();
  });

  function initializeButtonLayoutSelector() {
    $('.xing-share-layout-option input').on('change', function() {
      $('.xing-share-layout-option').removeClass('selected');
      $(this).parents('.xing-share-layout-option').addClass('selected')
    });
  }

  function initializeButtonLanguageSelector() {
    var $labeledButtons = $('.xing-share-layout-option.share, .xing-share-layout-option.share-top, .xing-share-layout-option.share-right');

    $labeledButtons.addClass( $('.xing-share-language-option:checked').val() );
    $('.xing-share-language-option').on('change', function() {
      $labeledButtons.removeClass('en', 'de').addClass( $(this).val() );
    });
  }
}(window, document, jQuery));
