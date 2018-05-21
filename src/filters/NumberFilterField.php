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
use skeeks\cms\queryfilters\filters\modes\FilterModeGt;
use skeeks\cms\queryfilters\filters\modes\FilterModeGte;
use skeeks\cms\queryfilters\filters\modes\FilterModeLt;
use skeeks\cms\queryfilters\filters\modes\FilterModeLte;
use skeeks\cms\queryfilters\filters\modes\FilterModeNe;
use skeeks\cms\queryfilters\filters\modes\FilterModeNotEmpty;
use skeeks\cms\queryfilters\filters\modes\FilterModeRange;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class NumberFilterField extends FilterField
{
    public $defaultMode = FilterModeEq::ID;

    public $modes = [
        FilterModeEmpty::class,
        FilterModeNotEmpty::class,
        FilterModeEq::class,
        FilterModeNe::class,
        FilterModeGt::class,
        FilterModeLt::class,
        FilterModeGte::class,
        FilterModeLte::class,
        FilterModeRange::class,
    ];
}