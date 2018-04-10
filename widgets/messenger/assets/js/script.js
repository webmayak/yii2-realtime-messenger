function $_GET (q, s) {
  s = (s) ? s : window.location.search
  var re = new RegExp('&amp;' + q + '=([^&amp;]*)', 'i')
  return (s = s.replace(/^\&/, '&amp;').match(re)) ? s = s[1] : s = ''
}

function readMessages (threadId) {
  //get the input value
  $.ajax({
    //the url to send the data to
    url: '/messenger/read-messages',
    //the data to send to
    data: {$threadId: threadId},
    //type. for eg: GET, POST
    type: 'POST',
    //datatype expected to get in reply form server
    dataType: 'json',
    //on success
    success: function (data) {
      //do something after something is recieved from php
    },
    //on error
    error: function () {
      //bad request
    }
  })
  refreshLists()
}

function scrollMsgBottom () {
  $('#messagesArea').mCustomScrollbar('scrollTo', 'bottom')
}

$(function () {
  if (typeof scrollMsgBottom == 'function') {
    scrollMsgBottom()
  }

//            setTimeout(function(){
//                 setInterval(refreshList, 1000);
//            }, 2000);
})

function formSubmit () {
  if ($('textarea', '#message-form').val()) {
    $('#message-form').submit()
  }
}

function refreshLists () {

  $('#messagesArea .mCSB_container').load(' #messagesAreaInner', scrollMsgBottom)
  $('#dialogsArea .mCSB_container').load(' #dialogsAreaInner')

}

$('#message-form').submit(function () {
  $.post(
    $(this).attr('action'),
    $(this).serialize(),
    function () {
      refreshLists()
      $('textarea', '#message-form').val('')
    }
  )
  return false
})

$('a[id^="hide_thread-"]').click(function (event) {

    event.preventDefault()
    if (!confirm('Действительно удалить диалог? ')) {
      return false
    }
    $.post('/messages/default/hideThread', {user_id: $(this).data('userId')}).done(
//                       $('#thread_block-<?//=$user->id?>//').hide('slow')


    )

    setTimeout(function () {
      location.replace('/messages/')
    }, 800)


  }
)

function refreshLists () {
  $.get(
    '',
    function (data) {
      $('#messagesArea .mCSB_container').html('<div id="messagesAreaInner">' + $('#messagesAreaInner', data).html() + '</div>')
      $('#dialogsArea .mCSB_container').html('<div id="dialogsAreaInner">' + $('#dialogsAreaInner', data).html() + '</div>')
      if (typeof scrollMsgBottom == 'function') {
        scrollMsgBottom()
      }
    }
  )
//            $('#messagesArea .mCSB_container').load(" #messagesAreaInner", function() {
//                if (typeof scrollMsgBottom == 'function') {
//                    scrollMsgBottom();
//                }
//            });
//            $('#dialogsArea .mCSB_container').load(" #dialogsAreaInner");

}

var connectToSocketIo = function (server, userId, sound, userName) {
  try {
    var socket = io.connect(server + '?hash=' + userId)
    socket.on('notification', function (data) {
      $.playSound(sound)
      refreshLists()
    })
  } catch (e) {
    console.log(e);
  }
}

$.extend({
  playSound: function(){
    return $(
      '<audio autoplay="autoplay" style="display:none;">'
      + '<source src="' + arguments[0] + '.mp3" />'
      + '</audio>'
    ).appendTo('body');
  }
});