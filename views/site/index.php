<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';


use \himiklab\yii2\recaptcha\ReCaptcha;
use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\PrintTree;

?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
                <?= PrintTree::run($data); ?>
            </div>

        </div>

    </div>
</div>


<div id="inline">
    <form id="myForms" method="post" action="<?=Url::to(['site/giphy']) ?>">
        <div class="block_captch">
            <?= \himiklab\yii2\recaptcha\ReCaptcha::widget([
                'name' => 'reCaptcha',
                'siteKey' => '6LdrThQUAAAAAIbpQxyv04GZZ8vo9PlXg8C1-euy',
                'widgetOptions' => ['class' => 'myCapt']
            ]) ?>
        </div>
        <div class="block_img">

        </div>

      <?= Html :: hiddenInput(\Yii :: $app->getRequest()->csrfParam, \Yii :: $app->getRequest()->getCsrfToken(), []); ?>

        <div class="g-recaptcha" data-sitekey="6LdrThQUAAAAAIbpQxyv04GZZ8vo9PlXg8C1-euy"></div>

        <input type="submit" value="Продолжить">
        </form>
</div>


