<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsBlock
 * @group Business
 * @group Facade
 * @group CmsBlockFacadeTest
 * Add your own group annotations below this line
 */
class CmsBlockFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\CmsBlock\CmsBlockBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindCmsBlockById(): void
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $cmsBlockTransfer = $this->createCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $this->assertInstanceOf(CmsBlockTransfer::class, $cmsBlockTransfer);
    }

    /**
     * @return void
     */
    public function testActivateById(): void
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock(['is_active' => false]);

        $this->assertFalse($cmsBlockTransfer->getIsActive());

        $this->createCmsBlockFacade()
            ->activateById($cmsBlockTransfer->getIdCmsBlock());

        $cmsBlockTransfer = $this->createCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $this->assertTrue($cmsBlockTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testDeactivateById(): void
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock(['is_active' => true]);

        $this->assertTrue($cmsBlockTransfer->getIsActive());

        $this->createCmsBlockFacade()
            ->deactivateById($cmsBlockTransfer->getIdCmsBlock());

        $cmsBlockTransfer = $this->createCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $this->assertFalse($cmsBlockTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testUpdateCmsBlock(): void
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();
        $cmsBlockTransfer->setName('Test name');

        $this->createCmsBlockFacade()
            ->updateCmsBlock($cmsBlockTransfer);

        $cmsBlockTransfer = $this->createCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $this->assertSame('Test name', $cmsBlockTransfer->getName());
    }

    /**
     * @return void
     */
    public function testCreateCmsBlock(): void
    {
        $cmsBlockTemplateTransfer = $this->tester->haveCmsBlockTemplate();

        $cmsBlockTransfer = new CmsBlockTransfer();
        $cmsBlockTransfer->setName('Test name');
        $cmsBlockTransfer->setFkTemplate($cmsBlockTemplateTransfer->getIdCmsBlockTemplate());

        $this->createCmsBlockFacade()
            ->createCmsBlock($cmsBlockTransfer);

        $this->assertNotEmpty($cmsBlockTransfer->getIdCmsBlock());
    }

    /**
     * @return void
     */
    public function testFindGlossaryPlaceholders(): void
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $translation = new CmsBlockGlossaryPlaceholderTranslationTransfer();
        $translation->setFkLocale(66);
        $translation->setTranslation('Test translation');

        $placeholder = new CmsBlockGlossaryPlaceholderTransfer();
        $placeholder->addTranslation($translation);
        $placeholder->setPlaceholder('placeholder');
        $placeholder->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock());
        $placeholder->setTemplateName('test template name');

        $glossary = new CmsBlockGlossaryTransfer();
        $glossary->addGlossaryPlaceholder($placeholder);

        $this->createCmsBlockFacade()
            ->saveGlossary($glossary);

        $glossary = $this->createCmsBlockFacade()
            ->findGlossary($cmsBlockTransfer->getIdCmsBlock());

        foreach ($glossary->getGlossaryPlaceholders() as $placeholder) {
            $this->assertSame('placeholder', $placeholder->getPlaceholder());
        }
    }

    /**
     * @return void
     */
    public function testSaveGlossary(): void
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $translation = new CmsBlockGlossaryPlaceholderTranslationTransfer();
        $translation->setFkLocale(66);
        $translation->setTranslation('Test translation');

        $placeholder = new CmsBlockGlossaryPlaceholderTransfer();
        $placeholder->addTranslation($translation);
        $placeholder->setPlaceholder('placeholder');
        $placeholder->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock());
        $placeholder->setTemplateName('test template name');

        $glossary = new CmsBlockGlossaryTransfer();
        $glossary->addGlossaryPlaceholder($placeholder);

        $this->createCmsBlockFacade()
            ->saveGlossary($glossary);

        $glossary = $this->createCmsBlockFacade()
            ->findGlossary($cmsBlockTransfer->getIdCmsBlock());

        $this->assertNotEmpty($glossary);
    }

    /**
     * @return void
     */
    public function testCreateTemplate(): void
    {
        $this->createCmsBlockFacade()
            ->createTemplate('test name', 'test path');

        $cmsBlockTemplateTransfer = $this->createCmsBlockFacade()
            ->findTemplate('test path');

        $this->assertNotEmpty($cmsBlockTemplateTransfer);
    }

    /**
     * @return void
     */
    public function testFindTemplate(): void
    {
        $this->createCmsBlockFacade()
            ->createTemplate('test name', 'test path');

        $cmsBlockTemplateTransfer = $this->createCmsBlockFacade()
            ->findTemplate('test path');

        $this->assertSame('test name', $cmsBlockTemplateTransfer->getTemplateName());
    }

    /**
     * @dataProvider relationUpdate
     *
     * @param array<int> $originalRelation
     * @param array<int> $modifiedRelation
     *
     * @return void
     */
    public function testUpdateCmsBlockUpdatesStoreRelation(array $originalRelation, array $modifiedRelation): void
    {
        // Assign
        $originalRelationStoreIds = $this->tester->createStoresByNames($originalRelation);
        $modifiedRelationStoreIds = $this->tester->createStoresByNames($modifiedRelation);
        $cmsBlockTransfer = $this->tester->haveCmsBlock(
            [
                CmsBlockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => $originalRelationStoreIds,
                ],
            ],
        );

        $this->createCmsBlockFacade()->updateCmsBlock($cmsBlockTransfer);

        // Act
        $cmsBlockTransfer->getStoreRelation()->setIdStores($modifiedRelationStoreIds);
        $this->createCmsBlockFacade()
            ->updateCmsBlock($cmsBlockTransfer);

        $cmsBlockTransfer = $this->createCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $resultIdStores = $cmsBlockTransfer->getStoreRelation()->getIdStores();

        // Assert
        sort($resultIdStores);
        $this->assertEquals($modifiedRelationStoreIds, $resultIdStores);
    }

    /**
     * @return array
     */
    public function relationUpdate(): array
    {
        return [
            [
                ['DE', 'AT'], ['AT'],
            ],
            [
                ['DE'], ['DE', 'AT'],
            ],
            [
                ['AT'], ['DE'],
            ],
        ];
    }

    /**
     * @return void
     */
    public function testCreateCmsBlockSavesStoreRelation(): void
    {
        // Assign
        $expectedIdStores = $this->tester->createStoresByNames([
            static::STORE_NAME_DE,
            static::STORE_NAME_AT,
        ]);

        $cmsBlockTransfer = $this->tester->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => $expectedIdStores],
        ]);

        // Act
        $cmsBlockTransfer = $this->createCmsBlockFacade()->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());
        $resultIdStores = $cmsBlockTransfer->getStoreRelation()->getIdStores();

        // Assert
        sort($resultIdStores);
        $this->assertEquals($expectedIdStores, $resultIdStores);
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface
     */
    protected function createCmsBlockFacade(): CmsBlockFacadeInterface
    {
        return $this->tester->getLocator()->cmsBlock()->facade();
    }
}
