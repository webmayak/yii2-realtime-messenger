<div class="col-md-3">
<?php
if(!empty($data->user_id)) {
    $userName = User::model()->findByPk($data->user_id)->username;
}
else{
    $userName = $data->email;
}
?>
</div>
