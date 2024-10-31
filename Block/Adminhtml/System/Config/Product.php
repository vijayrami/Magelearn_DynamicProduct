<?php
declare(strict_types=1);

namespace Magelearn\DynamicProduct\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Widget\Grid\Extended;

class Product extends Field
{
    protected $_template = 'Magelearn_DynamicProduct::system/config/product.phtml';
    
    public function __construct(
        Context $context,
        array $data = []
        ) {
            parent::__construct($context, $data);
    }
    
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->setElement($element);
        return $this->_toHtml();
    }
    
    public function getProductGridUrl()
    {
        return $this->getUrl('dynamicproduct/product/grid', [
            'element_id' => $this->getElement()->getHtmlId()
        ]);
    }
    
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
}