<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\queryfilters\filters\modes;

use skeeks\cms\IHasImage;
use skeeks\cms\IHasName;
use skeeks\cms\queryfilters\filters\FilterField;
use skeeks\cms\traits\THasName;
use yii\base\Component;
use yii\db\ActiveQuery;

class FilterModeNotLike extends FilterMode
{
    const ID = 'not_like';

    public function init()
    {
        if (!$this->name) {
            $this->name = 'Не содержит';
        }
    }

    /**
     * @var bool
     */
    public $isValue = true;

    public function applyQuery(ActiveQuery $activeQuery)
    {
        if (is_string($this->value) && $this->value == '') {
            return;
        }
        
        $activeQuery->andWhere(['not like', $this->attributeName, $this->value]);
    }


}