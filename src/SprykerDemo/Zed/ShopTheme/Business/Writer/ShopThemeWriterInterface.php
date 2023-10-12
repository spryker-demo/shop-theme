<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\Writer;

use Generated\Shared\Transfer\ShopThemeResponseTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;

interface ShopThemeWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeResponseTransfer
     */
    public function saveShopTheme(ShopThemeTransfer $shopThemeTransfer): ShopThemeResponseTransfer;
}
