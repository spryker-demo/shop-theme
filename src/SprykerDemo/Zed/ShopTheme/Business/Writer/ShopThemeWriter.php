<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\Writer;

use Generated\Shared\Transfer\ShopThemeResponseTransfer;
use Generated\Shared\Transfer\ShopThemeTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerDemo\Zed\ShopTheme\Business\Writer\Saver\ShopThemeLogoSaverInterface;
use SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeEntityManagerInterface;
use SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface;
use Throwable;

class ShopThemeWriter implements ShopThemeWriterInterface
{
    use TransactionTrait;
    use LoggerTrait;

    /**
     * @var string
     */
    protected const SHOP_THEME_SAVE_ERROR_MESSAGE = 'Shop theme was not saved successfully.';

    /**
     * @var \SprykerDemo\Zed\ShopTheme\Business\Writer\Saver\ShopThemeLogoSaverInterface
     */
    protected ShopThemeLogoSaverInterface $shopThemeLogoSaver;

    /**
     * @var \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeEntityManagerInterface
     */
    protected ShopThemeEntityManagerInterface $shopThemeEntityManager;

    /**
     * @var \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface
     */
    protected ShopThemeRepositoryInterface $shopThemeRepository;

    /**
     * @param \SprykerDemo\Zed\ShopTheme\Business\Writer\Saver\ShopThemeLogoSaverInterface $shopThemeLogoSaver
     * @param \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeEntityManagerInterface $shopThemeEntityManager
     * @param \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface $shopThemeRepository
     */
    public function __construct(
        ShopThemeLogoSaverInterface $shopThemeLogoSaver,
        ShopThemeEntityManagerInterface $shopThemeEntityManager,
        ShopThemeRepositoryInterface $shopThemeRepository
    ) {
        $this->shopThemeLogoSaver = $shopThemeLogoSaver;
        $this->shopThemeEntityManager = $shopThemeEntityManager;
        $this->shopThemeRepository = $shopThemeRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeResponseTransfer
     */
    public function saveShopTheme(ShopThemeTransfer $shopThemeTransfer): ShopThemeResponseTransfer
    {
        $shopThemeResponseTransfer = (new ShopThemeResponseTransfer())->setIsSuccess(true);

        $shopThemeData = $shopThemeTransfer->getShopThemeData();

        try {
            $this->shopThemeLogoSaver->saveShopThemeLogos($shopThemeData);

            $this->getTransactionHandler()->handleTransaction(function () use ($shopThemeTransfer) {
                $this->executeSaveShopThemeTransaction($shopThemeTransfer);
            });
        } catch (Throwable $e) {
            $this->getLogger()->error(static::SHOP_THEME_SAVE_ERROR_MESSAGE, ['exception' => $e]);
            $shopThemeResponseTransfer->setIsSuccess(false)
                ->setErrorMessage(static::SHOP_THEME_SAVE_ERROR_MESSAGE);
        }

        return $shopThemeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeResponseTransfer
     */
    public function duplicateShopTheme(ShopThemeTransfer $shopThemeTransfer): ShopThemeResponseTransfer
    {
        $shopThemeResponseTransfer = (new ShopThemeResponseTransfer())->setIsSuccess(true);

        $shopThemeTransfer->setIdShopTheme(null);
        $shopThemeData = $shopThemeTransfer->getShopThemeData();

        try {
            $this->shopThemeLogoSaver->duplicateShopThemeLogos($shopThemeData);

            $this->getTransactionHandler()->handleTransaction(function () use ($shopThemeTransfer) {
                $this->executeSaveShopThemeTransaction($shopThemeTransfer);
            });
        } catch (Throwable $e) {
            $this->getLogger()->error(static::SHOP_THEME_SAVE_ERROR_MESSAGE, ['exception' => $e]);
            $shopThemeResponseTransfer->setIsSuccess(false)
                ->setErrorMessage(static::SHOP_THEME_SAVE_ERROR_MESSAGE);
        }

        return $shopThemeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return void
     */
    protected function executeSaveShopThemeTransaction(ShopThemeTransfer $shopThemeTransfer): void
    {
        $shopThemeTransfer = $this->shopThemeEntityManager->saveShopTheme($shopThemeTransfer);
        $this->saveShopThemeStoreRelations($shopThemeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeTransfer $shopThemeTransfer
     *
     * @return void
     */
    protected function saveShopThemeStoreRelations(ShopThemeTransfer $shopThemeTransfer): void
    {
        $existingShopThemeStoreIds = $this->shopThemeRepository->getShopThemeStoreIds($shopThemeTransfer->getIdShopTheme());
        $storeIds = $shopThemeTransfer->getStoreRelation()->getIdStores();

        $storeIdsToRemove = array_diff($existingShopThemeStoreIds, $storeIds);
        $storeIdsToAdd = array_diff($storeIds, $existingShopThemeStoreIds);

        if ($storeIdsToRemove) {
            $this->shopThemeEntityManager->deleteStoreRelations($shopThemeTransfer->getIdShopTheme(), $storeIdsToRemove);
        }

        if ($storeIdsToAdd) {
            $this->shopThemeEntityManager->saveStoreRelations($shopThemeTransfer->getIdShopTheme(), $storeIdsToAdd);
        }
    }
}
