<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 */

namespace skeeks\cms\queryfilters;

use skeeks\cms\IHasName;
use skeeks\cms\traits\THasName;
use skeeks\yii2\form\IHasForm;
use skeeks\yii2\form\traits\THasForm;
use yii\base\Model;
use yii\db\ActiveQueryInterface;
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
abstract class QueryFilter extends Model implements IHasForm, IHasName, IQueryFilter
{
    use THasName;
    use THasForm;

    /**
     * @param ActiveQueryInterface $activeQuery
     * @return $this
     */
    abstract public function apply(ActiveQueryInterface $activeQuery);
}