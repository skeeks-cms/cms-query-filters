<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\queryfilters\filters;

use skeeks\cms\queryfilters\filters\modes\FilterModeEmpty;
use skeeks\cms\queryfilters\filters\modes\FilterModeEq;
use skeeks\cms\queryfilters\filters\modes\FilterModeEqually;
use skeeks\cms\queryfilters\filters\modes\FilterModeGt;
use skeeks\cms\queryfilters\filters\modes\FilterModeGte;
use skeeks\cms\queryfilters\filters\modes\FilterModeLt;
use skeeks\cms\queryfilters\filters\modes\FilterModeLte;
use skeeks\cms\queryfilters\filters\modes\FilterModeNe;
use skeeks\cms\queryfilters\filters\modes\FilterModeNotEmpty;
use skeeks\cms\queryfilters\filters\modes\FilterModeRange;

/**
 *
 *
 * 'marginality_per_filter' => [
    'label'           => 'Маржинальность, %',
    'class'           => NumberFilterField::class,
    'field'           => [
        'class' => NumberField::class
    ],
    'isAddAttributeTableName' => false,
    'beforeModeApplyCallback' => function(QueryFiltersEvent $e, NumberFilterField $field) {
        /**
         * @var $query ActiveQuery
        $query = $e->dataProvider->query;

        if (ArrayHelper::getValue($e->field->value, "value.0") || ArrayHelper::getValue($e->field->value, "value.1")) {

            $field->setIsHaving();

            $query->addSelect([
                'marginality_per_filter' => new Expression("(selling_price - purchase_price) / selling_price * 100"),
            ]);
            $query->groupBy([ShopStoreProduct::tableName().'.id']);
        }


        return true;
    },
],


 * @author Semenov Alexander <semenov@skeeks.com>
 */
class NumberFilterField extends FilterField
{
    public $defaultMode = FilterModeEq::ID;

    public $modes = [
        FilterModeEmpty::class,
        FilterModeNotEmpty::class,

        FilterModeEq::class,
        FilterModeNe::class,
        FilterModeGt::class,
        FilterModeLt::class,
        FilterModeGte::class,
        FilterModeLte::class,
        FilterModeRange::class,
    ];

    /**
     * @param bool $value
     */
    public function setIsHaving($value = true)
    {
        foreach ($this->modes as $mode)
        {
            if (isset($mode->isHaving)) {
                $mode->isHaving = $value;
            }
        }
    }
}