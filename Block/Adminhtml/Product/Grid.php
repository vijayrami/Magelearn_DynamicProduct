<?php
declare(strict_types=1);

namespace Magelearn\DynamicProduct\Block\Adminhtml\Product;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\Product\Attribute\Source\Status;

class Grid extends Extended
{
    protected $productCollectionFactory;
    protected $visibility;
    protected $status;
    
    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $productCollectionFactory,
        Visibility $visibility,
        Status $status,
        array $data = []
        ) {
            $this->productCollectionFactory = $productCollectionFactory;
            $this->visibility = $visibility;
            $this->status = $status;
            parent::__construct($context, $backendHelper, $data);
    }
    
    protected function _construct()
    {
        parent::_construct();
        $this->setId('productGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(false);
        // Set this to false to show radio buttons instead of checkboxes
        $this->setMassactionBlockName('Magento\Backend\Block\Widget\Grid\Massaction');
    }
    
    protected function _prepareCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect(['name', 'sku', 'price', 'status'])
        ->addAttributeToFilter('status', ['in' => $this->status->getVisibleStatusIds()])
        ->addAttributeToFilter('visibility', ['in' => $this->visibility->getVisibleInSiteIds()]);
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        // Add checkbox column before other columns
        $this->addColumn('in_products', [
            'header_css_class' => 'a-center',
            'type' => 'checkbox', // Use 'radio' for single selection, 'checkbox' for multiple
            'name' => 'in_products',
            'values' => $this->_getSelectedProducts(),
            'align' => 'center',
            'index' => 'entity_id',
            'header' => false,
            'renderer' => 'Magelearn\DynamicProduct\Block\Adminhtml\Grid\Renderer\Checkbox'
            //'html_name' => 'product_id' // For Radio Button only
        ]);
        
        $this->addColumn('entity_id', [
            'header' => __('ID'),
            'type' => 'number',
            'index' => 'entity_id',
            'header_css_class' => 'col-id',
            'column_css_class' => 'col-id'
        ]);
        
        $this->addColumn('name', [
            'header' => __('Name'),
            'index' => 'name'
        ]);
        
        $this->addColumn('sku', [
            'header' => __('SKU'),
            'index' => 'sku'
        ]);
        
        $this->addColumn('price', [
            'header' => __('Price'),
            'type' => 'currency',
            'currency_code' => (string)$this->_scopeConfig->getValue(
                \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
            'index' => 'price'
        ]);
        
        return parent::_prepareColumns();
    }
    
    protected function _getSelectedProducts()
    {
        $selected = $this->getRequest()->getParam('selected', []);
        return is_array($selected) ? $selected : [];
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('dynamicproduct/product/grid', ['_current' => true]);
    }
    
    // This function is important for row click handling
    public function getRowUrl($row)
    {
        return '#';
    }
}