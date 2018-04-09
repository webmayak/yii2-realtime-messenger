<style>

</style>
<a href="#"
   style="
            position:relative;
            top:-11px;
            right:-7px;
            cursor: pointer;
            color:silver;
            font-size:24px;
            text-decoration: none;
        "
   class="pull-right"
   onclick="$('#myModal').modal('hide')">&times;
</a>
    <div style="height: 150px;padding-top: 13px;font-size: 15px;text-align:center;">
        Ваш запрос успешно отправлен продавцу <b><?= Company::model()->find('user_id = ' . $sellerId)->name ?></b>! <br>Ожидайте
        ответа.
        <div>
            <div style="height: 20px;"></div>
            <?php if(!Yii::app()->user->isGuest):?>
            <a class="btn btn-default btn-sm"
               href="/messages/?user=<?= $sellerId ?>">Перейти в мои сообщения</a>
            <?php endif;?>
            <a href="#" class="close-confirm-modal btn btn-primary btn-sm" style="
        display:  inline-block;
        width: 80px;" onclick="$('#myModal').modal('hide')">ОК</a>
        </div>
        <small style="display:block;margin-top:15px;font-size:85%;">Это окно будет закрыто через 5 секунд.</small>
    </div>