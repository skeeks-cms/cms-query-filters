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

class FilterModeNotEmpty extends FilterMode
{
    const ID = 'not_empty';

    public function init()
    {
        if (!$this->name) {
            $this->name = 'Значение заполнено';
        }
    }

    /**
     * @param ActiveQuery $activeQuery
     * @param FilterField $field
     * @return $this
     */
    public function applyQuery(ActiveQuery $activeQuery)
    {
        $activeQuery->andWhere([
            'or',
            ['!=', $this->attributeName, ''],
            ['is not', $this->attributeName, null],
        ]);

        return $this;
    }


}