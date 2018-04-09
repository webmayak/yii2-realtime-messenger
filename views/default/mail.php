<table style='display: block; width: 800px; margin: 0 auto; border-collapse: collapse;'>
    <tbody style='display: block;'>
    <tr>
        <td style='padding: 15px 0 15px 20px; border-bottom: 1px solid #ccc; width: 400px;'>
            <?php if (isset($title)): ?>
                <h1 style='margin: 0; padding: 0; font-size: 22px; font-weight: normal;'><?php echo $title; ?></h1>
            <?php endif; ?>
        </td>
        <td style='text-align: right; padding: 15px 20px 15px 0; border-bottom: 1px solid #ccc; width: 400px;'>
            <img src='<?php echo Yii::app()->params->siteName; ?>/img/logo.png'/>
        </td>
    </tr>
    <tr>
        <td colspan='2' style='padding: 25px 0 40px; border-bottom: 1px solid #ccc;'>
            <?php echo $content; ?>
        </td>
    </tr>
    <?php if (!empty($signature)): ?>
        <tr>
            <td colspan='2' style='padding: 15px 0; border-bottom: 1px solid #ccc;'>
                <?= $signature ?>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td style='background-color: #E5E5E5; padding: 15px 0 15px 20px;'>
            <p style='margin: 0 0 7px;'>(с) <?php echo date('Y'); ?> <?php echo Yii::app()->params->siteName; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a
                    href='<?php echo Yii::app()->params->siteName; ?>/user/profile'>перейти в личный кабинет</a></p>
            <p style='margin: 0'>Сервис по поиску запчастей. Мы работаем по всему СНГ</p></td>
        <td style='text-align: right; background-color: #E5E5E5; padding: 15px 20px 15px 0;'>
            <a href='<?php echo Yii::app()->params->siteName; ?>/subscribe/unSubscribe'>отписаться от рассылки</a>
        </td>
    </tr>
    </tbody>
</table>