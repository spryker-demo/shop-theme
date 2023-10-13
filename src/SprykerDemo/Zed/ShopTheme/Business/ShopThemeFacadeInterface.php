<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business;

use Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer;
use Generated\Shared\Transfer\ShopThemeCriteriaTransfer;
use Generated\Shared\Transfer\ShopThemeResponseTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ShopThemeFacadeInterface
{
    /**
     * Specification:
     *  - Finds shop theme by provided `ShopThemeCriteriaTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findShopTheme(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): ?ShopThemeTransfer;

    /**
     * Specification:
     *  - Returns shop themes by provided `ShopThemeCriteriaTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ShopThemeTransfer>
     */
    public function getShopThemes(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): array;

    /**
     * Specification:
     *  - Returns shop them ids by provided `ShopThemeCriteriaTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return array<int>
     */
    public function getShopThemeIds(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): array;

    /**
     * Specification:
     *  - Get active theme for frontend for particular store.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeTransfer|null
     */
    public function findActiveTheme(StoreTransfer $storeTransfer): ?ShopThemeTransfer;

    /**
     * Specification:
     *  - Saves theme for frontend.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeResponseTransfer
     */
    public function saveShopTheme(ShopThemeTransfer $shopThemeTransfer): ShopThemeResponseTransfer;

    /**
     * Specification:
     *  - Deletes theme for frontend.
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return void
     */
    public function deleteShopTheme(int $idShopTheme): void;

    /**
     * Specification:
     *  - Deactivates theme.
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return void
     */
    public function deactivateTheme(int $idShopTheme): void;

    /**
     * Specification:
     *  - Activates theme.
     *
     * @api
     *
     * @param int $idShopTheme
     *
     * @return \Generated\Shared\Transfer\ActivateShopThemeActionResponseTransfer
     */
    public function activateTheme(int $idShopTheme): ActivateShopThemeActionResponseTransfer;

    /**
     *  Specification:
     *  - Validates store relation for the shop theme.
     *  - If the theme with $shopThemeId is active and provided stores are assigned to another active themes then return false.
     *  - Otherwise returns true
     *
     * @api
     *
     * @param int $shopThemeId
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelation
     *
     * @return bool
     */
    public function validateStoreRelationForShopThemeByShopThemeId(int $shopThemeId, StoreRelationTransfer $storeRelation): bool;

    /**
     * Specification:
     *  - Checks if shop them exists by provided `ShopThemeCriteriaTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer
     *
     * @return bool
     */
    public function shopThemeExists(ShopThemeCriteriaTransfer $shopThemeCriteriaTransfer): bool;
}
