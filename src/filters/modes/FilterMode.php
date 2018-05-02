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

class FilterMode extends Component implements IHasName
{
    use THasName;

    const ID = '';

    /**
     * @var
     */
    public $value;

    /**
     * @var
     */
    public $value2;

    /**
     * @var bool
     */
    public $isValue = false;

    /**
     * @var bool
     */
    public $isValue2 = false;

    /**
     * @var string
     */
    public $attributeName;

    public function applyQuery(ActiveQuery $activeQuery)
    {}

    public function getId()
    {
        return static::ID;
    }
}