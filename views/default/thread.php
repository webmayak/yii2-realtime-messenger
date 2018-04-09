<style>
    .message-wrap {
        margin-top: 20px;
        padding-left: 20px;
        padding-right: 20px;
    }
    .message-wrap.from {
        text-align: right;
    }
    .message-wrap.from .message {
        text-align: LEFT;
    }
    .message-wrap.to {
        text-align: left;
    }
    .message {
        display: inline-block;
        min-width: 400px;
        width: 90%;
        border-radius: 3px;
        box-shadow: 0 1px 2px rgba(0,0,0,.3);
        padding: 10px;
        font-size: 12px;
    }
    .message table {
        background: #fff;
    }
    .message-wrap.from .message {
        background: #c7edfc;
    }
    .message-wrap.to .message {
        background: #f0f4f8;
    }

    </style>
<div class="row">
    <div class="col-md-3">
<?php if (0) $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,

    'itemView'=>'_contact_view',   // refers to the partial view named '_post'
    'sortableAttributes'=>array(
        'name',
        'created'=>'Post Time',
    ),
));
?>
    </div>
    <div class="col-md-60">
        <br/>
        <ul class="nav nav-pills">
            <?php foreach($threads as $thread):?>
            <li<?php if ($thread->id==@$_GET['thread_id']) { ?> class="active"<?php } ?>><a href="?thread_id=<?=$thread->id?>"><?=$thread->subject?></a></li>
            <?php endforeach;?>
        </ul>
        <div id="message-all-wrapper" style="height: 400px; background: #fff; border: 1px solid #e5e5e5; margin-top: 10px; position: relative; overflow: hidden">
            <div class="message-all" style="position: absolute; left: 0; bottom: 20px; width: 100%; ">
               <?php if(!empty($messages)):?>
                <?php foreach($messages as $message):?>
                <div class="message-wrap <?php if ($message->user_id==Yii::app()->user->id) { ?>from<?php } else { ?>to<?php } ?>">
                    <div class="message">
                        <?= preg_replace('/^(.*)#(\S+)(.*)$/ui', '$1 <a target="_blank" href="/parts/search/$2">$2</a> $3', $message->body) ?>
                    </div>
                </div>
                <? endforeach; ?>
<? endif; ?>
            </div>
        </div>
        <form id="message-form" style="background: #f7f7f7; padding: 10px 20px;" action="/messages/send" method="post">

                <input type="hidden" name="user_id" value="<?= Yii::app()->user->id ?>"/>
                <input type="hidden" name="thread_id" value="<?= @$_GET['thread_id'] ?>"/>
                <textarea name="text"  style="height: 80px; display: block; width: 100%; margin-bottom: 5px;" onkeypress="if(event.keyCode==10||(event.keyCode==13))formSubmit();"></textarea>
                <div class="text-right">
                    <button class="btn btn-primary" --onclick="addMessage()" type="submit">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
//    function addMessage() {
//        $('.message-all').append('<div class="message-wrap to"><div class="message">'+$('#message_text').val()+'</div></div>');
//        $('#message_text').val('');
//    }

    function formSubmit(){
        if ($('textarea', '#message-form').val()) {
            $('#message-form').submit();
        }
    }

    function refreshList() {
        $('#message-all-wrapper').load(' .message-all');
    }

    setInterval(refreshList, 1000);

    $('#message-form').submit(function(){
        $.post(
            $(this).attr('action'),
            $(this).serialize(),
            function() {
                refreshList();
                $('textarea', '#message-form').val('');
            }
        );
        return false;
    });

</script>