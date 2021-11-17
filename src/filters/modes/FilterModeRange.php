<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\queryfilters\filters\modes;

use skeeks\cms\queryfilters\filters\FilterField;
use yii\db\ActiveQuery;

class FilterModeRange extends NumberFilterMode
{
    const ID = 'range';

    /**
     * @var bool
     */
    public $isValue = true;
    public $isValue2 = true;

    /**
     *
     */
    public function init()
    {
        if (!$this->name) {
            $this->name = 'Диапазон';
        }
    }

    /**
     * @param ActiveQuery $activeQuery
     * @param FilterField $field
     * @return $this
     */
    public function applyQuery(ActiveQuery $activeQuery)
    {
        if ($this->value) {
            $activeQuery->{$this->getAndWhereQuery()}([">=", $this->attributeName, $this->value]);
        }
        if ($this->value2) {
            $activeQuery->{$this->getAndWhereQuery()}(["<=", $this->attributeName, $this->value2]);
        }
    }


}