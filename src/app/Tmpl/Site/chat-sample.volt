<div class="uk-inline uk-width-large" id="chat-sample-container">
    <div class="uk-card uk-card-default uk-card-small">
        <div class="uk-card-header uk-background-muted">
            <div class="uk-card-title">Chat Sample Room</div>
        </div>
        <div class="message-body uk-card-body" uk-margin>
            {% for message in messages %}
                <article class="uk-comment uk-padding-small uk-position-relative" data-name="{{ message['name'] }}">
                    <header class="uk-comment-header uk-margin-remove">
                        <h5 class="uk-comment-title">{{ message['name'] }}</h5>
                    </header>
                    <div class="uk-comment-body">
                        <p>{{ message['message'] }}</p>
                    </div>
                    <a class="uk-position-small uk-position-top-right remove" uk-icon="icon: close"></a>
                </article>
            {% endfor %}
        </div>
        <div class="uk-card-footer uk-background-muted" uk-margin>
            <div>
                <textarea class="uk-textarea input-emoji message uk-width-1-1" cols="15" rows="2" placeholder="Enter your message..."></textarea>
            </div>
            <div>
                <button class="uk-button uk-button-primary uk-width-1-1" type="button">
                    <span uk-icon="icon: comment"></span> Send
                </button>
            </div>
        </div>
    </div>
    <div class="form">
        <div class="uk-overlay-default uk-position-cover"></div>
        <div class="uk-card uk-card-default uk-background-muted uk-card-body uk-position-center uk-position-z-index">
            <div class="uk-inline">
                <a class="uk-form-icon uk-form-icon-flip icon-name" uk-icon="icon: check"></a>
                <input class="uk-input" type="text" name="name" placeholder="Please enter your name"/>
            </div>
        </div>
    </div>
</div>