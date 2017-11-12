<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Users', 'url' => ['/site/users']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->name . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>

<?php

if (!Yii::$app->user->isGuest) {
    $userId = Yii::$app->user->getId();

    $this->registerJs(<<<JS

var config = {
    apiKey: "AIzaSyDZzJAnmN5pYWwRs64BQMne7k_x6_ixTsI",
    authDomain: "push-notifications-testi-eef48.firebaseapp.com",
    databaseURL: "https://push-notifications-testi-eef48.firebaseio.com",
    projectId: "push-notifications-testi-eef48",
    storageBucket: "push-notifications-testi-eef48.appspot.com",
    messagingSenderId: "1055739976473"
};

firebase.initializeApp(config);

var messaging = firebase.messaging();
messaging.requestPermission()
    .then(function () {
        console.log('Permission Granted.');

        return messaging.getToken();
    })
    .then(function (token) {
        console.log(token);

        notifyAppServer(token);
    })
    .catch(function (e) {
        console.log(e);
    });

messaging.onMessage(function (message) {
    console.log('onMessage:', message);
    var notification = new Notification(message.notification.title, message.notification);
});

function notifyAppServer(token){
    $.ajax({
        url: '/site/token',
        contentType: 'application/json',
        processData: true,
        data: JSON.stringify({token: token, user_id: $userId}),
        method: 'POST',
        success: function(data){
            console.log('data:',data);
        },
        fail: function(xhr){
            console.log('error:',xhr.responseText);
        }
    });
}

Notification.onclick = function(event) { 
    console.log('event:',event);
 };

JS
    );
}

?>

</body>
</html>
<?php $this->endPage() ?>
