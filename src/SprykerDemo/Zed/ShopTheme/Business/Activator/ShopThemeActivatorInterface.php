<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\Activator;

use Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer;

interface ShopThemeActivatorInterface
{
    /**
     * @param int $idShopTheme
     *
     * @return \Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer
     */
    public function activate(int $idShopTheme): ActivateShopThemeActionResponseTransfer;
}
