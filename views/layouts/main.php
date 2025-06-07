<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Json;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);

    // Формируем основной массив пунктов меню
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
        ['label' => 'Товары', 'url' => ['/product']],
        ['label' => 'Заказы', 'url' => ['/order']],
    ];

    if (!Yii::$app->user->isGuest && Yii::$app->user->can('viewUsers')) {
        $menuItems[] = ['label' => 'Пользователи', 'url' => ['/user']];
    }

    // Проверяем условие
    if (!Yii::$app->user->isGuest && Yii::$app->user->can('admin')) {
        // Если условие истинно, добавляем элемент в конец массива
        $menuItems[] = ['label' => 'Админка', 'url' => ['/admin/index']];
    }

    // Добавляем ссылку на вход/выход в зависимости от статуса аутентификации
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li class="nav-item">'
            . Html::beginForm(['/site/logout'])
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'nav-link btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    // --- Передаем сформированный массив в виджет Nav ---
    ?>
    <?= Nav::widget([ // Или Menu::widget
        'options' => ['class' => 'navbar-nav'],
        'items' => $menuItems, // Передаем готовый массив
    ]); ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; My Company <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php if (YII_DEBUG && Yii::$app->session->hasFlash('auth-debug-sweetalert')): ?>
    <?php
    $debugContent = Yii::$app->session->getFlash('auth-debug-sweetalert');
    $this->registerJs("
    Swal.fire({
        title: '🔐 Auth Debug Info',
        html: " . Json::encode($debugContent) . ",
        width: 600,
        showCloseButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#337ab7',
        customClass: {
            container: 'auth-debug-swal'
        }
    });
");
    ?>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
