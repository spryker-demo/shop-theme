<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerDemo\Zed\ShopTheme\Business\ActiveThemeReader\ActiveThemeReader;
use SprykerDemo\Zed\ShopTheme\Business\ActiveThemeReader\ActiveThemeReaderInterface;
use SprykerDemo\Zed\ShopTheme\Business\StoreRelationValidator\StoreRelationValidator;
use SprykerDemo\Zed\ShopTheme\Business\StoreRelationValidator\StoreRelationValidatorInterface;
use SprykerDemo\Zed\ShopTheme\ShopThemeDependencyProvider;

/**
 * @method \SprykerDemo\Zed\ShopTheme\ShopThemeConfig getConfig()
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeRepositoryInterface getRepository()()
 * @method \SprykerDemo\Zed\ShopTheme\Persistence\ShopThemeEntityManagerInterface getEntityManager()
 */
class ShopThemeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(ShopThemeDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \SprykerDemo\Zed\ShopTheme\Business\ActiveThemeReader\ActiveThemeReaderInterface
     */
    public function createActiveThemeReader(): ActiveThemeReaderInterface
    {
        return new ActiveThemeReader(
            $this->getStoreFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerDemo\Zed\ShopTheme\Business\StoreRelationValidator\StoreRelationValidatorInterface
     */
    public function createStoreRelationValidator(): StoreRelationValidatorInterface
    {
        return new StoreRelationValidator(
            $this->getStoreFacade(),
            $this->getRepository(),
        );
    }
}
