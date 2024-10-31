<?php
declare(strict_types=1);

namespace Magelearn\DynamicProduct\Block\Adminhtml\Grid\Renderer;

use Magento\Framework\DataObject;

class Checkbox extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Checkbox
{
    public function render(DataObject $row)
    {
        $entityId = $row->getData($this->getColumn()->getIndex());
        $htmlName = 'product_id'; // Define the custom HTML name here

        return '<input type="checkbox" name="' . $htmlName . '[]" value="' . $entityId . '" class="checkbox" />';
    }
}
