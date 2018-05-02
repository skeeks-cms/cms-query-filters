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

class FilterModeLike extends FilterMode
{
    const ID = 'like';

    public function init()
    {
        if (!$this->name) {
            $this->name = 'Содержит';
        }
    }

    /**
     * @var bool
     */
    public $isValue = true;

    public function applyQuery(ActiveQuery $activeQuery)
    {
        if (!$this->value) {
            return;
        }
        
        $activeQuery->andWhere(['like', $this->attributeName, $this->value]);
    }


}