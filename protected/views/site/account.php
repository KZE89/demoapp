<?php
/* @var $this SiteController */
/* @var $model Users */
/* @var $balance BalanceHistory */
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
    'action'=>'index.php?r=site/edit',
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
    
    <p>Последние операции по балансу (не более 10):</p>
    
    <?php 
        
        if($balance === NULL)
        {
            echo "<b>Нет операций</b>";
        }
        else
        {
            echo "<ul>";
            foreach($balance as $value)
             {
                echo "<li>" . $value->operationDateTime . " | " . $value->value . "</li>"; 
             }
            echo "</ul>";
        }
    ?>
	
	<?php echo CHtml::link('Выйти',array('site/logout')); ?>
    
<?php $this->endWidget(); ?>
</div><!-- form -->
