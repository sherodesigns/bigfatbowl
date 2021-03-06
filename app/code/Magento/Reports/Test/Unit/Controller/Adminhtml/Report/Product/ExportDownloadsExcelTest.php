<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reports\Test\Unit\Controller\Adminhtml\Report\Product;

use Magento\Reports\Controller\Adminhtml\Report\Product\ExportDownloadsExcel;

class ExportDownloadsExcelTest extends \Magento\Reports\Test\Unit\Controller\Adminhtml\Report\AbstractControllerTest
{
    /**
     * @var \Magento\Reports\Controller\Adminhtml\Report\Product\ExportDownloadsExcel
     */
    protected $exportDownloadsExcel;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dateMock;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->dateMock = $this->getMockBuilder('Magento\Framework\Stdlib\DateTime\Filter\Date')
            ->disableOriginalConstructor()
            ->getMock();

        $this->exportDownloadsExcel = new ExportDownloadsExcel(
            $this->contextMock,
            $this->fileFactoryMock,
            $this->dateMock
        );
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $content = ['export'];
        $fileName = 'products_downloads.xml';

        $this->abstractBlockMock
            ->expects($this->once())
            ->method('setSaveParametersInSession')
            ->willReturnSelf();

        $this->abstractBlockMock
            ->expects($this->once())
            ->method('getExcel')
            ->with($fileName)
            ->willReturn($content);

        $this->layoutMock
            ->expects($this->once())
            ->method('createBlock')
            ->with('Magento\Reports\Block\Adminhtml\Product\Downloads\Grid')
            ->willReturn($this->abstractBlockMock);

        $this->fileFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($fileName, $content);

        $this->exportDownloadsExcel->execute();
    }
}
