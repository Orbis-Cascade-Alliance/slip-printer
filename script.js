// Check the checkboxes of all printouts in results
function select_all() {
  $('input[name="slip_id"]').prop('checked', true);
}

// Uncheck the checkboxes of all printouts in results
function deselect_all() {
  $('input[name="slip_id"]').prop('checked', false);
}

// Get printouts from the queue and display
function get_slips() {
  $('#loading').show();
  $('#slip-results').hide();
  var printer = $('#printer').val();
  $.ajax({
    url: 'slips.php',
    method: 'post',
    data: {printer: printer},
    success: function (results) {
      $('#slip-results').html(results);
      var columns = $(results).find('#columns').val();
      update_columns(columns);
      $('#slip-results').show();
      $('#loading').hide();
    }
  });
}

// Update the number of columns displayed
function update_columns(columns) {
  var percentage = Math.floor(100/columns);
  $('.slip').css('max-width', percentage + '%');
}

// Trigger bulk action on printouts (print or cancel)
function do_action() {
  var printout_ids = get_printout_ids();
  if (printout_ids.length > 0) {
    var action = $('#action').val();
    switch (action) {
      case 'print':
        print_slips();
      break;
      case 'cancel':
        cancel_slips();
      break;
    }
    return false;
  }
  else {
    $('#dialog-no-selections').dialog('open');
  }
}

// Get IDs of checked printouts
function get_printout_ids() {
  var printout_ids = [];
  $.each($('input[name="slip_id"]:checked'), function() {
    printout_ids.push($(this).val());
  });
  return printout_ids;
}

// Get string of "X printout(s)"
function get_printout_string(printout_ids) {
  var printout_string = printout_ids.length + ' ' + ' printout';
  if (printout_ids.length > 1) {
    printout_string += 's';
  }
  return printout_string;
}

// Send checked printouts to new window for printing
function print_slips() {
  var columns = $('#columns').val();
  var print_contents = '<!DOCTYPE html><html><head><link rel="stylesheet" href="print.css" /><style>td.slip {width: ' + (100/columns).toFixed(2) + '%;}</style><head><body><table id="print-slips"><tbody><tr>';
  var checked_html = $('input[name="slip_id"]:checked').next('.slip-html');
  var last_html = checked_html.last();
  var t = 0;
  $.each(checked_html, function(index) {
    print_contents += '<td class="slip">' + $(this).html() + '</td>';
    t++;
    if (t == columns) {
      print_contents += '</tr>';
      if (!$(this).is(last_html)) {
        print_contents += '<tr>';
      }
      t = 0;
    }
  });
  if (t > 0 && t < columns) {
    for (var c = 0; c <= (columns - t); c++) {
      print_contents += '<td>&nbsp;</td>';
    }
  }
  print_contents += '</tr></tbody></table></body></html>';
  var print_window = window.open('', 'printed_slips');
  print_window.document.write(print_contents);
  print_window.document.close();
  print_window.focus();
  print_window.onload = function() {
    print_window.print();
    print_window.close();
  }
  $('#dialog-mark-printed').dialog('open');
}

// Mark a printout printed or canceled
function mark_printouts(type) {
  printout_ids = get_printout_ids();
  $.ajax({
    url: 'mark.php',
    method: 'post',
    data: {type: type, ids: printout_ids},
    success: function (results) {
      if (results != 'success') {
        $('#dialog-error').dialog('open');
      }
      get_slips();
    }
  });
}

// Cancel printouts
function cancel_slips() {
  $('#dialog-cancel').dialog('open');
}

$(document).ready(function() {
  $('#dialog-error').dialog({
    autoOpen: false,
    buttons: [{
      text: 'OK',
      click: function() {
        $(this).dialog('close');
      }
    }]
  });
  $('#dialog-no-selections').dialog({
    autoOpen: false,
    buttons: [{
      text: 'OK',
      click: function() {
        $(this).dialog('close');
      }
    }]
  });
  $('#dialog-mark-printed').dialog({
    autoOpen: false,
    buttons: [
      {
        text: 'Yes',
        click: function() {
          mark_printouts('printed');
          $(this).dialog('close');
        }
      },
      {
        text: 'No',
        click: function() {
          $(this).dialog('close');
        }
      }
    ]
  });
  $('#dialog-cancel').dialog({
    autoOpen: false,
    buttons: [
      {
        text: 'Yes',
        click: function() {
          mark_printouts('canceled');
          $(this).dialog('close');
        }
      },
      {
        text: 'No',
        click: function() {
          $(this).dialog('close');
        }
      }
    ]
  });
});