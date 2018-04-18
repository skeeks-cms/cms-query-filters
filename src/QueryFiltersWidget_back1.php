<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\queryfilters;

use skeeks\cms\helpers\PaginationConfig;
use skeeks\cms\IHasModel;
use skeeks\yii2\config\ConfigBehavior;
use skeeks\yii2\config\ConfigTrait;
use skeeks\yii2\config\DynamicConfigModel;
use skeeks\yii2\form\fields\SelectField;
use skeeks\yii2\form\fields\TextField;
use skeeks\yii2\form\fields\WidgetField;
use yii\base\Model;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class QueryFiltersWidget extends Widget
{
    /**
     * @var string
     */
    public $viewFile = '@skeeks/cms/queryfilters/views/filters';


    /**
     * @var QueryFilter[]
     */
    protected $_filters = [];


    /**
     * @var ActiveDataProvider
     */
    public $dataProvider;


    /**
     * @var array по умолчанию включенные колонки
     */
    public $visibleFilters = [];

    /**
     * @var array
     */
    public $configBehaviorData = [];

    /**
     * @var bool генерировать фильтры автоматически
     */
    public $isEnabledAutoFilters = true;

    /**
     * @var IHasModel|array|DynamicConfigModel
     */
    public $filtersModel;

    /**
     * @var array
     */
    public $wrapperOptions = [];

    /**
     * @var array
     */
    public $activeForm = [
        //'class' => ActiveForm::class
    ];

    /**
     *
     */
    public function init()
    {
        $defaultFiltersModel = [
           'class' => DynamicConfigModel::class,
        ];

        //Автомтическое конфигурирование колонок
        $this->_initAutoFilters();

        $defaultFiltersModel = ArrayHelper::merge((array) $this->_autoDynamicModelData, $defaultFiltersModel);

        $this->filtersModel = ArrayHelper::merge($defaultFiltersModel, (array) $this->filtersModel);
        $this->filtersModel = \Yii::createObject($this->filtersModel);
        $this->filtersModel->load(\Yii::$app->request->get());

        if ($this->filtersModel->builderFields()) {
            foreach ($this->filtersModel->builderFields() as $key => $field)
            {

            }
        }

        $defaultActiveForm = [
            'action' => [''],
            'method' => 'get',
            //'layout' => 'horizontal',
            'class' => ActiveForm::class,
        ];

        $this->activeForm = ArrayHelper::merge($defaultActiveForm, $this->activeForm);



        if ($this->_filters) {
            /**
             *
             */
            $tmpFilters = [];
            foreach ($this->_filters as $key => $value)
            {
                if (!ArrayHelper::getValue($value, 'attribute')) {
                    $value['attribute'] = $key;
                }

                /**
                 * @var $filter QueryFilter
                 */
                $filter = \Yii::createObject($value);
                $tmpFilters[$key] = $filter;

                $attributeDefines[] = $filter->attribute;
                $attributeLabels[$filter->attribute] = $filter->name;
                $rules[] = [$filter->attribute, 'safe'];
                $fields[$filter->attribute] = [
                    'class' => TextField::class,
                ];
            }

            $this->_filters = $tmpFilters;

            $this->filtersModel = ArrayHelper::merge($defaultFiltersModel, [
                'attributeDefines' => $attributeDefines,
                'attributeLabels' => $attributeLabels,
                'rules' => $rules,
                'fields' => $fields,
            ]);

            $this->filtersModel = \Yii::createObject($this->filtersModel);
        }
        parent::init();

        //Применение включенных/выключенных фильтров
        $this->_applyFilters();
    }

    public function run()
    {
        $this->wrapperOptions['id'] = $this->id;

        return $this->render($this->viewFile);
    }


    protected function applyColumns()
    {
        $result = [];
        //Есть логика включенных выключенных колонок
        if ($this->visibleFilters && $this->columns) {

            foreach ($this->visibleColumns as $key) {
                $result[$key] = ArrayHelper::getValue($this->columns, $key);
            }

            /*foreach ($this->_resultColumns as $key => $config) {
                $config['visible'] = false;
                $this->_resultColumns[$key] = $config;
            }*/

            /*$result = ArrayHelper::merge($result, $this->_resultColumns);
            $this->_resultColumns = $result;*/
            $this->columns = $result;
        }

        return $this;
    }




    private $_autoDynamicModelData = [];

    /**
     * This function tries to guess the columns to show from the given data
     * if [[columns]] are not explicitly specified.
     */
    protected function _initAutoFilters()
    {
        //Если автоопределение колонок не включено
        if (!$this->isEnabledAutoFilters) {
            return $this;
        }

        if (!$this->dataProvider) {
            return $this;
        }

        $dataProvider = clone $this->dataProvider;
        $models = $dataProvider->getModels();


        /**
         * @var $model Model
         */
        $model = reset($models);

        $result = [];

        $result['attributeDefines'] = $model->attributes();
        $result['attributeLabels'] = $model->attributeLabels();

        $rules = [];
        $fields = [];

        if (is_array($model) || is_object($model)) {
            foreach ($model as $name => $value) {
                if ($value === null || is_scalar($value) || is_callable([$value, '__toString'])) {
                    $fields[(string)$name] = [
                        'class' => TextField::class,
                    ];

                    $rules[] = [(string)$name, 'safe'];
                }
            }
        }

        $result['rules'] = $rules;
        $result['fields'] = $fields;
        $this->_autoDynamicModelData = $result;

        return $this;
    }

    protected function _applyFilters()
    {
        $result = [];
        $fields = $this->filtersModel->builderFields();

        //Есть логика включенных выключенных колонок
        if ($this->visibleFilters && $fields) {

            foreach ($this->visibleFilters as $key) {
                $result[$key] = ArrayHelper::getValue($fields, $key);
            }
        }

        if ($result) {
            $this->filtersModel->setFields($result);
        }

        return $this;
    }


    /**
     * Данные необходимые для редактирования компонента, при открытии нового окна
     * @return array
     */
    public function getEditData()
    {
        return [
            'callAttributes' => $this->callAttributes,
            'availableColumns' => $this->filtersModel->attributeLabels(),
        ];
    }


    public function setFilters($filters)
    {
        $this->_filters = $filters;
        return $this;
    }
    public function getFilters()
    {
        /*foreach ($this->_filters as $ => $)
        {

        }*/

        return $this->_filters;
    }
}