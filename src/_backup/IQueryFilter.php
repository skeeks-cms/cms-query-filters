<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 */

namespace skeeks\cms\queryfilters;

use yii\db\ActiveQueryInterface;
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
interface IQueryFilter
{
    /**
     * @param ActiveQueryInterface $activeQuery
     * @return $this
     */
    public function apply(ActiveQueryInterface $activeQuery);
}