<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ShopTheme\Business\Writer\Saver;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemDeleteTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\ShopThemeDataTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Service\FileSystem\FileSystemServiceInterface;
use SprykerDemo\Service\UrlBuilder\UrlBuilderServiceInterface;
use SprykerDemo\Zed\ShopTheme\ShopThemeConfig;

class ShopThemeLogoSaver implements ShopThemeLogoSaverInterface
{
    /**
     * @var \SprykerDemo\Zed\ShopTheme\ShopThemeConfig
     */
    protected ShopThemeConfig $config;

    /**
     * @var \SprykerDemo\Service\UrlBuilder\UrlBuilderServiceInterface
     */
    protected UrlBuilderServiceInterface $urlBuilderService;

    /**
     * @var \Spryker\Service\FileSystem\FileSystemServiceInterface
     */
    protected FileSystemServiceInterface $fileSystemService;

    /**
     * @param \SprykerDemo\Zed\ShopTheme\ShopThemeConfig $config
     * @param \SprykerDemo\Service\UrlBuilder\UrlBuilderServiceInterface $urlBuilderService
     * @param \Spryker\Service\FileSystem\FileSystemServiceInterface $fileSystemService
     */
    public function __construct(ShopThemeConfig $config, UrlBuilderServiceInterface $urlBuilderService, FileSystemServiceInterface $fileSystemService)
    {
        $this->config = $config;
        $this->urlBuilderService = $urlBuilderService;
        $this->fileSystemService = $fileSystemService;
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeDataTransfer $shopThemeDataTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeDataTransfer
     */
    public function saveShopThemeLogos(ShopThemeDataTransfer $shopThemeDataTransfer): ShopThemeDataTransfer
    {
        $shopThemeDataTransfer = $this->removeLogoFiles($shopThemeDataTransfer);

        return $this->saveLogoFiles($shopThemeDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeDataTransfer $shopThemeDataTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeDataTransfer
     */
    protected function removeLogoFiles(ShopThemeDataTransfer $shopThemeDataTransfer): ShopThemeDataTransfer
    {
        if ($shopThemeDataTransfer->getLogoUrl() && $shopThemeDataTransfer->getDeleteLogo()) {
            $this->deleteLogoFile($shopThemeDataTransfer->getLogoUrl());
            $shopThemeDataTransfer->setLogoUrl(null);
        }

        if ($shopThemeDataTransfer->getMpLogoUrl() && $shopThemeDataTransfer->getDeleteMpLogo()) {
            $this->deleteLogoFile($shopThemeDataTransfer->getMpLogoUrl());
            $shopThemeDataTransfer->setMpLogoUrl(null);
        }

        if ($shopThemeDataTransfer->getBackofficeLogoUrl() && $shopThemeDataTransfer->getDeleteBackofficeLogo()) {
            $this->deleteLogoFile($shopThemeDataTransfer->getBackofficeLogoUrl());
            $shopThemeDataTransfer->setBackofficeLogoUrl(null);
        }

        return $shopThemeDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShopThemeDataTransfer $shopThemeDataTransfer
     *
     * @return \Generated\Shared\Transfer\ShopThemeDataTransfer
     */
    protected function saveLogoFiles(ShopThemeDataTransfer $shopThemeDataTransfer): ShopThemeDataTransfer
    {
        if ($shopThemeDataTransfer->getLogoFile()) {
            $logoFileUrl = $this->saveLogoFile($shopThemeDataTransfer->getLogoFile());
            $shopThemeDataTransfer->setLogoUrl($logoFileUrl);
        }

        if ($shopThemeDataTransfer->getMpLogoFile()) {
            $mpFileUrl = $this->saveLogoFile($shopThemeDataTransfer->getMpLogoFile());
            $shopThemeDataTransfer->setMpLogoUrl($mpFileUrl);
        }

        if ($shopThemeDataTransfer->getBackofficeLogoFile()) {
            $backofficeFileUrl = $this->saveLogoFile($shopThemeDataTransfer->getBackofficeLogoFile());
            $shopThemeDataTransfer->setBackofficeLogoUrl($backofficeFileUrl);
        }

        return $shopThemeDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return string
     */
    protected function saveLogoFile(FileTransfer $fileTransfer): string
    {
        $filePath = $this->generateUniqueFilePath($fileTransfer);
        $fileSystemContentTransfer = new FileSystemContentTransfer();
        $fileSystemContentTransfer->setContent($fileTransfer->getFileContent());
        $fileSystemContentTransfer->setFileSystemName($this->config->getLogoFilesystemName());
        $fileSystemContentTransfer->setPath($filePath);
        $fileSystemContentTransfer->setConfig($this->config->getFileSystemWriterConfig());
        $this->fileSystemService->write($fileSystemContentTransfer);

        return $this->urlBuilderService->buildFileUrl($filePath, $this->config->getLogoFilesystemName());
    }

    /**
     * @param string $filePath
     *
     * @return void
     */
    protected function deleteLogoFile(string $filePath): void
    {
        $fileSystemContentTransfer = new FileSystemDeleteTransfer();
        $fileSystemContentTransfer->setFileSystemName($this->config->getLogoFilesystemName());
        $fileSystemContentTransfer->setPath($filePath);
        $this->fileSystemService->delete($fileSystemContentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $file
     *
     * @return string
     */
    protected function generateUniqueFilePath(FileTransfer $file): string
    {
        return pathinfo($file->getFileName(), PATHINFO_FILENAME)
            . '_'
            . Uuid::uuid4()->toString()
            . '.'
            . pathinfo($file->getFileName(), PATHINFO_EXTENSION);
    }
}
