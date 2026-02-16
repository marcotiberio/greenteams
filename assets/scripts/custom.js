import $ from 'jquery'

$(document).ready(function () {
  $('#showMyCalendar').click(function () {
    $('#myCalendarWrapper').toggle()
  })
  $('#hideMyCalendar').click(function () {
    $('#myCalendarWrapper').hide()
  })
})

$(document).ready(function () {
  $('#showFilters').click(function () {
    $('.filterSection--inner').slideToggle('fast')
  })
})

// Menu items color change
$(document).ready(function () {
  $(window).scroll(function () {
    if ($(this).scrollTop() >= 250) {
      $('.wrapper--menu').addClass('scrolled')
    } else {
      $('.wrapper--menu').removeClass('scrolled')
    }
  })
})
