<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\queryfilters\QueryFiltersWidget */
/* @var $builder \skeeks\yii2\form\Builder */
$widget = $this->context;
$fields = $widget->filtersModel->builderFields();
?>

<?
$activeFormClassName = \yii\helpers\ArrayHelper::getValue($widget->activeForm, 'class', \yii\widgets\ActiveForm::class);
\yii\helpers\ArrayHelper::remove($widget->activeForm, 'class');

$form = $activeFormClassName::begin((array)$widget->activeForm);

$builder->setActiveForm($form);
echo $builder->render();

?>
<div class="row sx-form-buttons">
    <div class="col-sm-12">
        <div class="col-sm-3"">
        </div>
        <div class="col-sm-6">
            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-filter"></i> Применить</button>
        </div>
        <div class="col-sm-3">

        </div>
    </div>
</div>

<?
$activeFormClassName::end();
?>
