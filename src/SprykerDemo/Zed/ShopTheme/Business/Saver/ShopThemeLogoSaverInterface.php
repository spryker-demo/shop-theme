<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\Saver;

use Generated\Shared\Transfer\ShopThemeDataTransfer;

interface ShopThemeLogoSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShopThemeDataTransfer $shopThemeDataTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeDataTransfer
     */
    public function saveShopThemeLogos(ShopThemeDataTransfer $shopThemeDataTransfer): ShopThemeDataTransfer;
}
