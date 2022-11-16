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

class FilterModeEmpty extends FilterMode
{
    const ID = 'empty';

    /**
     * @var bool
     */
    public $isHaving = false;

    /**
     * @return string
     */
    public function getAndWhereQuery()
    {
        return $this->isHaving ? "andHaving" : "andWhere";
    }

    public function init()
    {
        if (!$this->name) {
            $this->name = 'Значение не заполнено';
        }
    }

    /**
     * @param ActiveQuery $activeQuery
     * @param FilterField $field
     * @return $this
     */
    public function applyQuery(ActiveQuery $activeQuery)
    {
        $activeQuery->{$this->getAndWhereQuery()}([
            'or',
            [$this->attributeName => ''],
            [$this->attributeName => null],
        ]);
    }


}