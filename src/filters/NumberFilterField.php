<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\queryfilters\filters;

use skeeks\cms\queryfilters\filters\modes\FilterModeEq;
use skeeks\cms\queryfilters\filters\modes\FilterModeEqually;
use skeeks\cms\queryfilters\filters\modes\FilterModeLike;
use skeeks\cms\queryfilters\filters\modes\FilterModeRange;
use yii\helpers\ArrayHelper;

class NumberFilterField extends FilterField
{
    public $defaultMode = FilterModeEq::ID;
    
    public function init()
    {
        if (!$this->modes) {
            $this->modes = $this->getBaseModes();
            $modes = $this->modes;
            ArrayHelper::removeValue($modes, FilterModeLike::class);
            $this->modes = $modes;
        } else {

        }
        
        parent::init();
    }
    
}