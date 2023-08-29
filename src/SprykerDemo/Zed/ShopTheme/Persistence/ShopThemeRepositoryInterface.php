<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Persistence;

use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ShopThemeRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer
     */
    public function getActiveTheme(StoreTransfer $storeTransfer): ShopThemeTransfer;

    /**
     * @param int $idShopTheme
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findShopThemeById(int $idShopTheme): ?ShopThemeTransfer;
}
