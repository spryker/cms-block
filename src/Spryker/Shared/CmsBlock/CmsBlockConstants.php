<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsBlock;

use Spryker\Shared\Kernel\KernelConstants;

interface CmsBlockConstants
{

    /**
     * Specification
     * - Defines the collector resource name
     *
     * @api
     */
    const RESOURCE_TYPE_CMS_BLOCK = 'cms-block';

    /**
     * Specification
     * - Defines project name for absolute path to template folder
     *
     * @api
     */
    const PROJECT_NAMESPACE = KernelConstants::PROJECT_NAMESPACE;

    /**
     * Specification
     * - Defines the name of constant of default Yves template theme
     *
     * @api
     */
    const YVES_THEME = 'YVES_THEME';

}
