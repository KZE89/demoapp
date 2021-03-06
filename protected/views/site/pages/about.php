<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - О Нас';
$this->breadcrumbs=array(
	'О нас',
);
?>
<h1>О Нас</h1>

<h3>Тестовое задание:</h3>

 

<p>1)      Развернуть веб-приложение на основе yii 1 с авторизацией пользователей.</p>

<p>После ввода email пользователю высылается одноразовая ссылка. После перехода по ссылке, если пользователь не зарегистрирован, создается учетная запись и пользователь авторизуется, в противном случае просто авторизуется.</p>

<p>Авторизованный пользователь попадает в личный кабинет, где может изменить имя и разлогиниться.</p>

<p>2)      Вновь зарегистрированному пользователю начисляется приветственный бонус в 1000 рублей и персональный ключ API, который доступен в личном кабинете. Пользователь может отправить запрос через JSON API на вывод с баланса любой суммы. </p>

 

<h3>Требования:</h3>
<ul>
    <li>Yii1</li>

    <li>mysql с миграциями</li>

    <li>github</li>

    <li>composer</li>

    <li>Наличие юнит-тестов будет плюсом.</li>
</ul>