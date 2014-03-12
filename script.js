jQuery(document).ready(function($) {
  var data = {
    action: 'send_test_email',
    //whatever: ajax_object.we_value      // We pass php values differently!
  };
  jQuery("#send-test-email").on('click', function() {
    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
    jQuery.post(ajax_object.ajax_url, data, function(response) {
      alert(response);
    });
  });
});

