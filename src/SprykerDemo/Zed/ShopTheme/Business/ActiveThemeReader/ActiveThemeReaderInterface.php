<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\ActiveThemeReader;

use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ActiveThemeReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function getActiveTheme(?StoreTransfer $storeTransfer = null): ShopThemeTransfer;
}
