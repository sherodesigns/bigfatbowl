<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Indexer\Test\Unit\Model\Indexer;

class AbstractProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Indexer\Test\Unit\Model\Indexer\AbstractProcessorStub
     */
    protected $model;

    /**
     * @var \Magento\Indexer\Model\IndexerRegistry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $_indexerRegistryMock;

    protected function setUp()
    {
        $this->_indexerRegistryMock = $this->getMock(
            '\Magento\Indexer\Model\IndexerRegistry',
            ['isScheduled', 'get', 'reindexRow', 'reindexList', 'reindexAll', 'invalidate'],
            [],
            '',
            false
        );
        $this->model = new \Magento\Indexer\Test\Unit\Model\Indexer\AbstractProcessorStub(
            $this->_indexerRegistryMock
        );
    }

    public function testGetIndexer()
    {
        $this->_indexerRegistryMock->expects($this->once())->method('get')->with(
            \Magento\Catalog\Model\Indexer\Category\Product::INDEXER_ID
        )->willReturnSelf();
        $this->model->getIndexer();
    }

    public function testReindexAll()
    {
        $this->_indexerRegistryMock->expects($this->once())->method('get')->with(
            \Magento\Catalog\Model\Indexer\Category\Product::INDEXER_ID
        )->willReturnSelf();
        $this->_indexerRegistryMock->expects($this->once())->method('reindexAll')->willReturnSelf();
        $this->model->reindexAll();
    }

    public function testMarkIndexerAsInvalid()
    {
        $this->_indexerRegistryMock->expects($this->once())->method('get')->with(
            \Magento\Catalog\Model\Indexer\Category\Product::INDEXER_ID
        )->willReturnSelf();
        $this->_indexerRegistryMock->expects($this->once())->method('invalidate')->willReturnSelf();
        $this->model->markIndexerAsInvalid();
    }

    public function testGetIndexerId()
    {
        $this->assertEquals(\Magento\Catalog\Model\Indexer\Category\Product::INDEXER_ID, $this->model->getIndexerId());
    }

    /**
     * @param bool $scheduled
     * @dataProvider runDataProvider
     */
    public function testReindexRow($scheduled)
    {
        $id = 1;
        if ($scheduled) {
            $this->_indexerRegistryMock->expects($this->once())->method('get')->with(
                \Magento\Catalog\Model\Indexer\Category\Product::INDEXER_ID
            )->willReturnSelf();
            $this->_indexerRegistryMock->expects($this->once())->method('isScheduled')->willReturn($scheduled);
            $this->assertEquals(null, $this->model->reindexRow($id));
        } else {
            $this->_indexerRegistryMock->expects($this->exactly(2))->method('get')->with(
                \Magento\Catalog\Model\Indexer\Category\Product::INDEXER_ID
            )->willReturnSelf();
            $this->_indexerRegistryMock->expects($this->once())->method('isScheduled')->willReturn($scheduled);
            $this->_indexerRegistryMock->expects($this->once())->method('reindexRow')->with($id)->willReturnSelf();
            $this->assertEquals(null, $this->model->reindexRow($id));
        }
    }

    /**
     * @param bool $scheduled
     * @dataProvider runDataProvider
     */
    public function testReindexList($scheduled)
    {
        $ids = [1];
        if ($scheduled) {
            $this->_indexerRegistryMock->expects($this->once())->method('get')->with(
                \Magento\Catalog\Model\Indexer\Category\Product::INDEXER_ID
            )->willReturnSelf();
            $this->_indexerRegistryMock->expects($this->once())->method('isScheduled')->willReturn($scheduled);
            $this->assertEquals(null, $this->model->reindexList($ids));
        } else {
            $this->_indexerRegistryMock->expects($this->exactly(2))->method('get')->with(
                \Magento\Catalog\Model\Indexer\Category\Product::INDEXER_ID
            )->willReturnSelf();
            $this->_indexerRegistryMock->expects($this->once())->method('isScheduled')->willReturn($scheduled);
            $this->_indexerRegistryMock->expects($this->once())->method('reindexList')->with($ids)->willReturnSelf();
            $this->assertEquals(null, $this->model->reindexList($ids));
        }
    }

    /**
     * @return array
     */
    public function runDataProvider()
    {
        return [
            [true],
            [false]
        ];
    }

    /**
     * Test isIndexerScheduled()
     */
    public function testIsIndexerScheduled()
    {
        $this->_indexerRegistryMock->expects($this->once())->method('get')->with(
            \Magento\Catalog\Model\Indexer\Category\Product::INDEXER_ID
        )->willReturnSelf();
        $this->_indexerRegistryMock->expects($this->once())->method('isScheduled')->willReturn(false);
        $this->model->isIndexerScheduled();
    }
}
