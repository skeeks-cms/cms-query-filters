<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\queryfilters\filters;

use skeeks\cms\queryfilters\QueryFiltersEvent;
use skeeks\yii2\form\Field;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class StringFilterField extends Field
{

    const MODE_LIKE = 'like';
    const MODE_EQUALLY = 'equally';
    const MODE_NOT_EMPTY = 'not_empty';
    const MODE_EMPTY = 'empty';


    /**
     * @var string
     */
    public $defaultMode = self::MODE_LIKE;


    public function init()
    {
        parent::init();

        $this->on('apply', [$this, '_applyEvent']);
    }

    public function _applyEvent(QueryFiltersEvent $queryFiltersEvent)
    {
        $value = $queryFiltersEvent->field->value;
        $mode = ArrayHelper::getValue($value, 'mode');
        $value = ArrayHelper::getValue($value, 'value');


        if (!$mode) {
            return;
        } elseif ($mode == self::MODE_LIKE) {
            $queryFiltersEvent->query->andWhere(['like', $queryFiltersEvent->field->attribute, $value]);
        } elseif ($mode == self::MODE_EQUALLY) {
            $queryFiltersEvent->query->andWhere([$queryFiltersEvent->field->attribute => $value]);
        } elseif ($mode == self::MODE_NOT_EMPTY) {
            $queryFiltersEvent->query->andWhere([
                'or',
                ['!=', $queryFiltersEvent->field->attribute, ''],
                ['is not', $queryFiltersEvent->field->attribute, null],
            ]);
        } elseif ($mode == self::MODE_EMPTY) {
            $queryFiltersEvent->query->andWhere([
                'or',
                [$queryFiltersEvent->field->attribute => ''],
                [$queryFiltersEvent->field->attribute => null],
            ]);
        }
    }

    /**
     * @return \yii\widgets\ActiveField
     */
    public function getActiveField()
    {
        if (!$this->_activeForm || !$this->_model || !$this->_attribute) {
            throw new InvalidConfigException('Not found form or model or attribute');
        }

        $activeField = $this->_activeForm->field($this->_model, $this->_attribute."[value]", $this->_options);

        if ($this->label !== null || $this->labelOptions) {
            $activeField->label($this->label, $this->labelOptions);
        }

        if ($this->hint !== null || $this->labelOptions) {
            $activeField->hint($this->hint, $this->hintOptions);
        }

        $activeField->textInput();
        $mode = (string)Html::activeListBox($this->model, $this->attribute."[mode]", [
            ''          => ' -- ',
            'like'      => 'Значение содержит',
            'equally'   => 'Значение точно совпадает с',
            'not_empty' => 'Значение заполнено',
            'empty'     => 'Значение не заполнено',
        ],
            [
                'size'  => 1,
                'class' => 'form-control',
            ]);
        
        $activeField->parts['{input}'] = "
            <div class='row'>           
                <div class='col-md-3'>{$mode}</div>            
                <div class='col-md-9'>" . $activeField->parts['{input}'] . "</div>         
            </div>
        ";

        return $activeField;
    }
}