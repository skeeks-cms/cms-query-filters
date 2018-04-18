<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 25.05.2015
 */

namespace skeeks\cms\queryfilters;

use skeeks\cms\helpers\PaginationConfig;
use skeeks\cms\IHasModel;
use skeeks\yii2\config\ConfigBehavior;
use skeeks\yii2\config\ConfigTrait;
use skeeks\yii2\config\DynamicConfigModel;
use skeeks\yii2\form\Field;
use skeeks\yii2\form\fields\TextField;
use skeeks\yii2\form\fields\WidgetField;
use yii\base\Event;
use yii\base\Model;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class QueryFiltersEvent extends Event
{

    /**
     * @var Field
     */
    public $field;

    /**
     * @var QueryFiltersWidget
     */
    public $widget;

    /**
     * @var ActiveDataProvider
     */
    public $dataProvider;

    /**
     * @var ActiveQuery
     */
    public $query;
}