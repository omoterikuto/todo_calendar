$(function() {
  'use strict';
  $('#new-todo').focus();
  // update
  $('#todos ,#all_todos').on('click', '.update-todo', function() {
    var id = $(this).parents('li').data('id');
    $.post('/works/todo_calendar/lib/_ajax.php', {
      id: id,
      mode: 'update',
      token: $('#token').val()
    }, function(res) {
      if (res.state === '1') {
        $('.todo_' + id).find('.todo-title').addClass('done');
      } else {
        $('.todo_' + id).find('.todo-title').removeClass('done');
      }
    });
    var parents_class = $(this).parent('li').attr("class");
    if ($(`.${parents_class}`).children('.update-todo').prop('checked')) {
      $(`.${parents_class}`).children('.update-todo').attr('checked', true).prop('checked', true).change();
    } else {
      $(`.${parents_class}`).children('.update-todo').removeAttr('checked').prop('checked', false).change();
    }
    if ($(`#all_todos .${parents_class}`).children('span').hasClass('done')) {
      $(`.${parents_class}`).children('.update-todo').removeAttr('checked').prop('checked', false).change();
    } else {
      $(`.${parents_class}`).children('.update-todo').attr('checked', true).prop('checked', true).change();
    }
  });

  // delete
  $('#all_todos, #todos').on('click', '.delete-todo', function() {
    var id = $(this).parents('li').data('id');
    if (confirm('消しちゃうよ？')) {
      $.post('/works/todo_calendar/lib/_ajax.php', {
        id: id,
        mode: 'delete',
        token: $('#token').val()
      }, function() {
        $('.todo_' + id).fadeOut(400);
      });
    }
  });

  // create
  $('#new-todo-form').on('submit', function() {
    if ($('.select').attr('id')) {
      var day = $('.select').attr('id');
    } else {
      var day = $('.today').attr('id');
    }
    var year_month = $('#year_month').text();
    var title = $('#new-todo').val();
    $.post('/works/todo_calendar/lib/_ajax.php', {
      year_month: year_month,
      day: day,
      title: title,
      mode: 'create',
      token: $('#token').val()
    }, function(res) {
      var $li = $('#todo_template').clone();
      $li
        .attr('id', 'todo_' + res.id)
        .addClass('todo_' + res.id)
        .data('id', res.id)
        .find('.todo-title').text(title);
      var $all_li = $('#all_todo_template').clone();
      $all_li
        .attr('id', 'todo_' + res.id)
        .data('id', res.id)
        .addClass('todo_' + res.id)
        .find('.todo-title').text(title);
      $all_li.find('.todo-date').text('タスク日 ' + String(res.date).substr(0, 10));
      $all_li.find('.todo-created').text('作成日 ' + res.created);
      $('#todos').prepend($li.fadeIn());
      $('#all_todos').prepend($all_li.fadeIn());
      $('#new-todo').val('').focus();
      $('#none_message').css('display', 'none');
    });
    return false;
  });
  // display
  $('.date td').on('click', function() {
    $('#todos li:not(#todo_template)').remove();
    $('.date td').removeClass('select');
    var day = $(this).attr('id');
    var year_month = $('#year_month').text();
    $('#' + day).addClass('select');
    $.post('/works/todo_calendar/lib/_ajax.php', {
      year_month: year_month,
      day: day,
      mode: 'display',
      token: $('#token').val()
    }, function(res) {
      if (res.obj !== null) {
        $('#none_message').css('display', 'none');
        $.each(res.obj, function(index, value) {
          var $li = $('#todo_template').clone();
          $li
            .attr('id', '')
            .data('id', value.id)
            .addClass('todo_' + value.id)
            .find('.todo-title').text(value.title);
          $('#todos').prepend($li.fadeIn());
          if (value.state === '1') {
            $li.find('input').attr('checked', true).prop('checked', true).change();
            $('input[checked = "checked"] + .todo-title').addClass('done');
          }
        });
      } else {
        $('#none_message').fadeIn();
      }
      $('#new-todo').attr('placeholder', res.date);
    });
    return false;
  });
});