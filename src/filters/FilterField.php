<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\queryfilters\filters;

use skeeks\cms\queryfilters\filters\modes\FilterMode;
use skeeks\cms\queryfilters\filters\modes\FilterModeEmpty;
use skeeks\cms\queryfilters\filters\modes\FilterModeEq;
use skeeks\cms\queryfilters\filters\modes\FilterModeEqually;
use skeeks\cms\queryfilters\filters\modes\FilterModeGt;
use skeeks\cms\queryfilters\filters\modes\FilterModeGte;
use skeeks\cms\queryfilters\filters\modes\FilterModeLike;
use skeeks\cms\queryfilters\filters\modes\FilterModeLt;
use skeeks\cms\queryfilters\filters\modes\FilterModeLte;
use skeeks\cms\queryfilters\filters\modes\FilterModeNe;
use skeeks\cms\queryfilters\filters\modes\FilterModeNotEmpty;
use skeeks\cms\queryfilters\QueryFiltersEvent;
use skeeks\yii2\form\Field;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * @property $filterAttribute;
 * @property $fullFilterAttribute;
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class FilterField extends Field
{
    static public $_isRegisteredAssets = false;
    /**
     * Отрисовка кастомного элемента вместо стандартного инпута
     *
     * @var Field
     */
    public $field;

    /**
     * @var bool
     */
    public $isAllowChangeMode = true;
    
    /**
     * По умолчанию выбранный мод фильтрации
     *
     * @var string
     */
    public $defaultMode = FilterModeLike::ID;
    /**
     * Доступные моды фильтрации
     *
     * @var array|FilterMode[]
     */
    public $modes = [];

    /**
     * Вызывается перед применением определенного мода
     * @var callable|null
     */
    public $beforeModeApplyCallback = null;

    /**
     * @var bool Добавлять название таблицы к атрибуту?
     */
    public $isAddAttributeTableName = true;

    public function getBaseModes()
    {
        return [
            FilterModeEmpty::class,
            FilterModeNotEmpty::class,
        ];
    }
    /**
     * Атрибут в базе данных по которому фильтровать
     *
     * @var
     */
    protected $_filterAttribute;
    
    public function init()
    {
        parent::init();

        if (!$this->modes) {
            $this->modes = $this->getBaseModes();
        }
        
        $modes = [];

        if ($this->modes) {
            foreach ($this->modes as $mode) {
                $tmpMode = \Yii::createObject($mode);
                $modes[$tmpMode->id] = $tmpMode;
            }

            $this->modes = $modes;
        }

        $this->on('apply', [$this, '_applyEvent']);
    }

    public function _applyEvent(QueryFiltersEvent $queryFiltersEvent)
    {
        $value = $queryFiltersEvent->field->value;

        if (!$value) {
            return;
        }

        $mode = ArrayHelper::getValue($value, 'mode');
        $value = ArrayHelper::getValue($value, 'value');

        /**
         * @var $filterMode FilterMode
         */
        $filterMode = ArrayHelper::getValue($this->modes, $mode);

        if (!$filterMode || !$mode) {
            return;
        }

        if ($filterMode->isValue) {
            $filterMode->value = $value[0];
        }
        if ($filterMode->isValue2) {
            $filterMode->value2 = $value[1];
        }

        $fullFilterAttribute = $this->filterAttribute;
        
        $query = $queryFiltersEvent->query;

        if ($this->isAddAttributeTableName) {
            if (!strpos($this->filterAttribute, ".") && $query->modelClass && !$query->from) {
                $modelClass = $query->modelClass;
                $tableName = $modelClass::tableName();
                $fullFilterAttribute = "{$tableName}.{$fullFilterAttribute}";
            }
        }


        $filterMode->attributeName = $fullFilterAttribute;

        if ($this->beforeModeApplyCallback && is_callable($this->beforeModeApplyCallback)) {
            $userCallback = call_user_func($this->beforeModeApplyCallback, $queryFiltersEvent, $this);
            if (!$userCallback) {
                return true;
            }
        }
        $filterMode->applyQuery($queryFiltersEvent->query);
    }
    /**
     * @return \yii\widgets\ActiveField
     */
    public function getActiveField()
    {
        if (!$this->_activeForm || !$this->_model || !$this->_attribute) {
            throw new InvalidConfigException('Not found form or model or attribute');
        }

        if ($this->field) {

            $this->field['attribute'] = $this->_attribute."[value][0]";
            $this->field['model'] = $this->_model;
            $this->field['activeForm'] = $this->_activeForm;

            $this->field = \Yii::createObject($this->field);

            /**
             * @var $field Field
             */
            $field = $this->field;
            $activeField = $field->activeField;

            if ($this->label !== null || $this->labelOptions) {
                $activeField->label($this->label, $this->labelOptions);
            }

            if ($this->hint !== null || $this->labelOptions) {
                $activeField->hint($this->hint, $this->hintOptions);
            }


        } else {
            $activeField = $this->_activeForm->field($this->_model, $this->_attribute."[value][0]", $this->_options);

            if ($this->label !== null || $this->labelOptions) {
                $activeField->label($this->label, $this->labelOptions);
            }

            if ($this->hint !== null || $this->labelOptions) {
                $activeField->hint($this->hint, $this->hintOptions);
            }

            $activeField->textInput();
        }


        $input2 = (string)Html::activeTextInput($this->model, $this->attribute."[value][1]", [
            'class' => 'form-control',
        ]);


        $modes = [
            //'none' => ' -- ',
        ];
        $modesOptions = [];
        if ($this->modes) {

            $modesHtml = '';
            foreach ($this->modes as $key => $mode) {
                if (!$mode instanceof FilterMode) {
                    //var_dump($mode);die;
                }
                $modes[$key] = $mode->name;

                $modesHtml .= '<a class="dropdown-item" data-mode="' . $key . '" href="#">' . $mode->name . '</a>';

                $modesOptions[$key] = [
                    'data-isValue'  => (int)$mode->isValue,
                    'data-isValue2' => (int)$mode->isValue2,
                ];
            }
        }

        $opts = [
            'options' => $modesOptions,
            'size'    => 1,
            'class'   => 'form-control sx-filter-mode',
        ];

        if (!$mode = ArrayHelper::getValue($this->model->{$this->attribute}, 'mode')) {
            $opts['value'] = $this->defaultMode;
        }
        
        
        $mode = (string)Html::activeListBox($this->model, $this->attribute."[mode]", $modes, $opts);

        $dropdownMode = <<<HTML
<div class="sx-mode-title"></div>
<div class="dropdown">
  <a class="" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="hs-admin-settings g-absolute-centered"></i>
  </a>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    {$modesHtml}
  </div>
</div>
HTML;

        $style = '';
        
        if (!$this->isAllowChangeMode) {
            $style = "style='display:none;'";
        }

        if (isset($activeField->parts)) {
            $activeField->parts['{input}'] = "
                <div class='d-flex-filter' id='{$this->id}'>           
                             
                    <div class='sx-input-wrapper'>".$activeField->parts['{input}']."</div>         
                    <div class='sx-input-wrapper-2'>{$input2}</div>     
                    <div class='sx-filter-mode-wrapper' data-default-mode='{$this->defaultMode}' {$style}>
                    {$dropdownMode}{$mode}
                    </div>         
                </div>
            ";
        }
        

        $jsOptions = Json::encode([
            'id' => $this->id,
            'isAllowChangeMode' => $this->isAllowChangeMode,
        ]);

        $this->registerAssets();
        \Yii::$app->view->registerJs(<<<JS
new sx.classes.filters.FilterField({$jsOptions});
JS
        );

        return $activeField;
    }
    public function registerAssets()
    {
        if (!static::$_isRegisteredAssets) {
            static::$_isRegisteredAssets = true;

            \Yii::$app->view->registerCss(<<<CSS
.d-flex-filter {
    display: flex;
    justify-content: right;
}
.sx-filter-mode-wrapper {
    display: flex;
    align-items: center;
    padding-left: 1rem;
}
.sx-filter-mode-wrapper .dropdown i {
    opacity: 0.3;
    transition: right .3s, opacity .3s;
}
.sx-filter-mode-wrapper .dropdown a:hover i {
    opacity: 1;
}
.sx-filter-mode-wrapper .sx-mode-title {
    font-size: 0.8rem;
    padding-right: 1rem;
}
.sx-filter-mode-wrapper .dropdown a {
    cursor: pointer;
}
.sx-filter-mode-wrapper select {
    display: none !important;
}
.sx-filter-mode-wrapper .dropdown-item.sx-selected {
    color: white;
    background: var(--primary-color);
}
CSS
            );

            \Yii::$app->view->registerJs(<<<JS
(function(sx, $, _)
{
    sx.createNamespace('classes.filters', sx);
    sx.classes.filters.FilterField = sx.classes.Component.extend({
    
        _onDomReady: function()
        {
            var self = this;
            
            this.jFilterWrapper = $("#" + this.get('id'));
            this.jFilterMode = $('.sx-filter-mode', this.jFilterWrapper);
            this.jFilterInput = $('.sx-input-wrapper', this.jFilterWrapper);
            this.jFilterInput2 = $('.sx-input-wrapper-2', this.jFilterWrapper);
            
            this.update();
            
            this.jFilterMode.on('change', function () {
                var jWrapper = $(this).closest(".sx-filter-mode-wrapper");
                jWrapper.trigger("render");
                self.update();
                
                return false;
            });
            
            $(".sx-filter-mode-wrapper").on("render", function() {
                var jWrapper = $(this);
                
                var jSelect = $("select", jWrapper);
                var jTitle = $(".sx-mode-title", jWrapper);
                
                $(".dropdown-item", jWrapper).removeClass("sx-selected");
                var jItem = $(".dropdown-item[data-mode=" + jSelect.val() + "]", jWrapper);
                jItem.addClass("sx-selected");
                
                //Мод изменился
                if (jWrapper.data("default-mode") != jSelect.val()) {
                    jTitle.empty().show().append(jItem.text());
                } else {
                    jTitle.empty().hide();
                }
            })
            
            $(".sx-filter-mode-wrapper").each(function() {
                $(this).trigger("render");
            });
            
            $(".sx-filter-mode-wrapper .dropdown-item").on("click", function() {
                var jWrapper = $(this).closest(".sx-filter-mode-wrapper");
                
                var jSelect = $("select", jWrapper);
                jSelect.val($(this).data("mode"));
                jSelect.trigger("change");
                
            });
        },
        
        
        update: function()
        {
            this.jFilterInput.hide().addClass("sx-hidden");
            this.jFilterInput2.hide().addClass("sx-hidden");
            
            var mode = this.jFilterMode.val();
            
            if (mode) {
                var jModeOption = $("option[value=" + mode + "]", this.jFilterMode);
                
                if (jModeOption.data('isvalue')) {
                    this.jFilterInput.show().removeClass("sx-hidden");
                }
                
                if (jModeOption.data('isvalue2')) {
                    this.jFilterInput2.show().removeClass("sx-hidden");
                }
                
                if (jModeOption.data('isvalue') && !jModeOption.data('isvalue2')) {
                 
                }
            }
            
            
        }
    });
})(sx, sx.$, sx._);
JS
            );
        }

        return $this;
    }
    public function getFilterAttribute()
    {
        if ($this->_filterAttribute === null) {
            $this->_filterAttribute = $this->_attribute;
        }

        return $this->_filterAttribute;
    }
    /**
     * @param $filterAttribute
     * @return $this
     */
    public function setFilterAttribute($filterAttribute)
    {
        $this->_filterAttribute = $filterAttribute;
        return $this;
    }
}