_$.ready(function ($) {
    var chat = $('#chat-sample-container'),
        name = $.storage.get('ChatSampleClientName', null),
        input = chat.find('input[name="name"]'),
        msgBox = chat.find('textarea.message'),
        updateName = function (name) {
            if (name.length < 1) {
                UIkit.notification('Please enter your valid name!', {status: 'warning'});
            } else if (name.length < 4) {
                UIkit.notification('The minimum characters must be 4!', {status: 'warning'});
            } else {
                $.storage.set('ChatSampleClientName', name);
                chat.find('.form').hide();
                chat.find('.chat-title').text(name);
            }
        };

    if (null === name) {
        chat.find('a.icon-name').on('click', function (e) {
            e.preventDefault();
            name = $.trim(input.val()).replace(/[^a-z0-9_\-\,\s]/gi, '');
            input.val(name);
            updateName(name);
        });
        input.on('keyup', function (e) {
            e.keyCode === 13 && chat.find('a.icon-name').trigger('click');
        });
    } else {
        chat.find('.form').hide();
        chat.find('.chat-title').text(name);
        chat.find('article[data-name]').each(function () {
            if (name === this.getAttribute('data-name')) {
                this.classList.add('uk-background-muted');
            } else {
                $(this).find('a.remove').remove();
            }
        });
    }

    $hb.socket.create('ChatSample', {
        host: 'localhost',
        plugin: 'ChatSample',
        port: 2053,
        onMessage: function (e) {
            try {
                var data = JSON.parse(e.data);

                if (data.type === 'message') {
                    var line = $('<article class="uk-comment uk-padding-small uk-position-relative" data-name="' + name + '" data-time="' + data.time + '"><header class="uk-comment-header uk-margin-remove"><h5 class="uk-comment-title">' + data.name + '</h5></header><div class="uk-comment-body"><p>' + data.message + '</p></div></article>')
                        .appendTo(chat.find('.message-body'));
                    if (data.name === name) {
                        line.addClass('uk-background-muted')
                            .append('<a class="uk-position-small uk-position-top-right remove" uk-icon="icon: close"></a>');
                    }
                } else if (data.type === 'blur') {
                    chat.find('.typing').addClass('uk-hidden');
                } else if (data.type === 'typing') {
                    data.name !== name && chat.find('.typing').removeClass('uk-hidden')
                        .find('.name').text(data.name);
                } else if (data.time) {
                    chat.find('article[data-time="' + data.time + '"]').remove();
                }
            } catch (e) {
                console.warn(e);
            }
        },
    });

    chat.find('.uk-button-primary').on('click', function () {
        var message = $.trim(msgBox.val());

        if (message.length) {
            var time = Date.now();
            $.http.post($hb.uri.root + '/chat-sample/save', {
                message: {
                    name: name,
                    message: message,
                    time: time
                }
            }, function () {
                $hb.socket.get('ChatSample').send({message: message, name: name, type: 'message', time: time});
            });

            msgBox.val('');
        }
    });

    msgBox.on('keyup', function (e) {
        if (e.keyCode === 13) {
            chat.find('.uk-button-primary').trigger('click');
            msgBox.trigger('blur');
        } else {
            if ($.trim(this.value).length) {
                $hb.socket.get('ChatSample').send({message: '', name: name, type: 'typing'});
            } else {
                msgBox.trigger('blur');
            }
        }
    });

    msgBox.on('blur', function () {
        chat.find('.typing').addClass('uk-hidden');
        $hb.socket.get('ChatSample').send({message: '', name: name, type: 'blur'});
    });

    chat.on('click', 'a.remove', function (e) {
        e.preventDefault();
        var line = $(this).parent('article');

        if (name === line.data('name')) {
            $.http.delete($hb.uri.root + '/chat-sample/delete/' + line.data('time'), function () {
                $hb.socket.get('ChatSample').send({message: '', name: name, type: 'remove', time: line.data('time')});
                line.remove();
            });
        }
    });
});