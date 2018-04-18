<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\queryfilters\filters;

use skeeks\cms\queryfilters\QueryFilter;
use yii\db\ActiveQueryInterface;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class StringQueryFilter extends QueryFilter
{
    /**
     * @var
     */
    public $value;
    /**
     * @var
     */
    public $attribute;

    /**
     * @param ActiveQueryInterface $activeQuery
     * @return $this
     */
    public function apply(ActiveQueryInterface $activeQuery)
    {
        $activeQuery->andWhere(['like', $this->attribute, $this->value]);
        return $this;
    }

    /**
     * @return array
     */
    /*public function attributeLabels()
    {
        return [
            'value' => $this->name
        ];
    }*/

    /**
     * @return array
     */
    /*public function rules()
    {
        return [
            ['value', 'string']
        ];
    }

    public function builderFields()
    {
        return [
            'value'
        ];
    }*/
}