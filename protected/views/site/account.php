<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Личный кабинет';
$this->breadcrumbs=array(
	'Личный кабинет',
);
?>

<h1>Личный кабинет</h1>

<p>Ваши данные:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'account-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="row">
		<?php echo $form->label($model,'ID'); ?>
		<?php echo $form->textField($model,'id',array('readonly'=>'readonly')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'Адрес E-mail'); ?>
		<?php echo $form->textField($model,'email',array('readonly'=>'readonly')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'Имя'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>	

	<div class="row">
		<?php echo $form->label($model,'Баланс'); ?>
		<?php echo $form->textField($model,'balance',array('readonly'=>'readonly')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'API-ключ'); ?>
		<?php echo $form->textField($model,'apiLink',array('readonly'=>'readonly')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'Последний визит'); ?>
		<?php echo $form->textField($model,'lastVisit',array('readonly'=>'readonly')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'Изменен'); ?>
		<?php echo $form->textField($model,'updatedAt',array('readonly'=>'readonly')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'Зарегистрирован'); ?>
		<?php echo $form->textField($model,'registiredAt',array('readonly'=>'readonly')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Редактировать'); ?>
	</div>
	
	<?php echo CHtml::link('Выйти',array('site/logout')); ?>

<?php $this->endWidget(); ?>
</div><!-- form -->
