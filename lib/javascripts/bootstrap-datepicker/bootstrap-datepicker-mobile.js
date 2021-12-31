
//     bootstrap-datepicker-mobile
//     Copyright (c) 2014- Nick Baugh <niftylettuce@gmail.com> (http://niftylettuce.com)
//     MIT Licensed

// An add-on for <https://github.com/eternicode/bootstrap-datepicker> to add
// responsive support for mobile devices with consideration for native
// input[type=date] support using Modernizr and Moment.js.

// * Author: [@niftylettuce](https://twitter.com/#!/niftylettuce)
// * Source: <https://github.com/niftylettuce/bootstrap-datepicker-mobile>

// # bootstrap-datepicker-mobile

(function($, Modernizr, window) {

  // Set the default datepicker format
  $.fn.datepicker.defaults.format = "mm/dd/yy";

  // Add support for datepickers globally to use input[type=date]
  var nativeDateFormat = /^\d{4}-\d{2}-\d{2}$/;
  var datepickerDateFormat = /^\d{2}\/\d{2}\/\d{2}$/;

  /*globals moment*/
  function bootstrapDatepickerMobile(ev) {

    var $inputs = $('input.date-picker');
    var isMobile = $(window).width() <= 480 || Modernizr.touch;

    $inputs.each(function() {

      var $input = $(this);
      var val = $input.val();
      var valMoment;

      if (nativeDateFormat.test(val)) {
        valMoment = moment(val, 'YYYY-MM-DD');
      } else if (datepickerDateFormat.test(val)) {
        valMoment = moment(val, 'MM/DD/YY');
      }

      var isMoment = moment.isMoment(valMoment);

      if (isMobile && Modernizr.inputtypes.date) {
        if (isMoment) val = valMoment.format('YYYY-MM-DD');
        $input.datepicker('remove');
        $input.val(val);
        $input.attr('type', 'date');
      } else {
        if (isMoment) val = valMoment.format('MM/DD/YY');
        $input.attr('type', 'text');
        $input.val(val);
        if (isMobile) {
          $input.datepicker('remove');
        } else {
          if (isMoment)
            $input.datepicker('update', valMoment.toDate());
          else
            $input.datepicker();
          if ($input.is(':focus'))
            $input.datepicker('show');
        }
      }

    });

  }

  $(window).on('resize.bootstrapDatepickerMobile', bootstrapDatepickerMobile);

  bootstrapDatepickerMobile();

}(jQuery, Modernizr, window));
