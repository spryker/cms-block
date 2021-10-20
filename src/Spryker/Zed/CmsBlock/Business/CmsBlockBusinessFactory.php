<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business;

use Spryker\Zed\CmsBlock\Business\KeyProvider\CmsBlockKeyProvider;
use Spryker\Zed\CmsBlock\Business\KeyProvider\CmsBlockKeyProviderInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryKeyGenerator;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryKeyGeneratorInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryManager;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryManagerInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryWriter;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryWriterInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapper;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapperInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockReader;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockReaderInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationMapper;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationMapperInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationReader;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationReaderInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationWriter;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationWriterInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateManager;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateManagerInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateMapper;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateMapperInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockWriter;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockWriterInterface;
use Spryker\Zed\CmsBlock\CmsBlockDependencyProvider;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToEventFacadeInterface;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Symfony\Component\Finder\Finder;

/**
 * @method \Spryker\Zed\CmsBlock\CmsBlockConfig getConfig()
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface getRepository()
 */
class CmsBlockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockReaderInterface
     */
    public function createCmsBlockReader(): CmsBlockReaderInterface
    {
        return new CmsBlockReader(
            $this->getQueryContainer(),
            $this->createCmsBlockMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapperInterface
     */
    public function createCmsBlockMapper(): CmsBlockMapperInterface
    {
        return new CmsBlockMapper(
            $this->createCmsBlockStoreRelationMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockWriterInterface
     */
    public function createCmsBlockWrite(): CmsBlockWriterInterface
    {
        return new CmsBlockWriter(
            $this->getQueryContainer(),
            $this->createCmsBlockMapper(),
            $this->createCmsBlockGlossaryWriter(),
            $this->createCmsBlockStoreRelationWriter(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_TOUCH),
            $this->createCmsBlockTemplateManager(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::PLUGIN_CMS_BLOCK_UPDATE),
            $this->createCmsBlockKeyProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchInterface
     */
    public function getTouchFacade(): CmsBlockToTouchInterface
    {
        return $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToEventFacadeInterface
     */
    public function getEventFacade(): CmsBlockToEventFacadeInterface
    {
        return $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateManagerInterface
     */
    public function createCmsBlockTemplateManager(): CmsBlockTemplateManagerInterface
    {
        return new CmsBlockTemplateManager(
            $this->getQueryContainer(),
            $this->createCmsBlockTemplateMapper(),
            $this->getConfig(),
            $this->createFinder(),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryManagerInterface
     */
    public function createCmsBlockGlossaryManager(): CmsBlockGlossaryManagerInterface
    {
        return new CmsBlockGlossaryManager(
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_LOCALE),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryWriterInterface
     */
    public function createCmsBlockGlossaryWriter(): CmsBlockGlossaryWriterInterface
    {
        return new CmsBlockGlossaryWriter(
            $this->getQueryContainer(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_GLOSSARY),
            $this->createCmsBlockGlossaryKeyGenerator(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::QUERY_CONTAINER_GLOSSARY),
            $this->getTouchFacade(),
            $this->getEventFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationWriterInterface
     */
    public function createCmsBlockStoreRelationWriter(): CmsBlockStoreRelationWriterInterface
    {
        return new CmsBlockStoreRelationWriter(
            $this->getQueryContainer(),
            $this->createCmsBlockStoreRelationReader(),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationReaderInterface
     */
    public function createCmsBlockStoreRelationReader(): CmsBlockStoreRelationReaderInterface
    {
        return new CmsBlockStoreRelationReader(
            $this->getQueryContainer(),
            $this->createCmsBlockStoreRelationMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\KeyProvider\CmsBlockKeyProviderInterface
     */
    public function createCmsBlockKeyProvider(): CmsBlockKeyProviderInterface
    {
        return new CmsBlockKeyProvider($this->getRepository());
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createFinder(): Finder
    {
        return new Finder();
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateMapperInterface
     */
    protected function createCmsBlockTemplateMapper(): CmsBlockTemplateMapperInterface
    {
        return new CmsBlockTemplateMapper();
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryKeyGeneratorInterface
     */
    protected function createCmsBlockGlossaryKeyGenerator(): CmsBlockGlossaryKeyGeneratorInterface
    {
        return new CmsBlockGlossaryKeyGenerator(
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_GLOSSARY),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationMapperInterface
     */
    protected function createCmsBlockStoreRelationMapper(): CmsBlockStoreRelationMapperInterface
    {
        return new CmsBlockStoreRelationMapper();
    }
}
