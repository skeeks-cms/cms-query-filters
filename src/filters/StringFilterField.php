<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\queryfilters\filters;

use skeeks\cms\queryfilters\filters\modes\FilterModeEmpty;
use skeeks\cms\queryfilters\filters\modes\FilterModeEq;
use skeeks\cms\queryfilters\filters\modes\FilterModeEqually;
use skeeks\cms\queryfilters\filters\modes\FilterModeLike;
use skeeks\cms\queryfilters\filters\modes\FilterModeNotEmpty;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class StringFilterField extends FilterField
{
    public $defaultMode = FilterModeLike::ID;

    public $modes = [
        FilterModeEmpty::class,
        FilterModeNotEmpty::class,
        FilterModeEq::class,
        FilterModeLike::class,
    ];
}