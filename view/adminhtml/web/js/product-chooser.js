define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate'
], function($, modal, $t) {
    'use strict';
    
    return function(config) {
        var fieldId = config.fieldId;
        var buttonId = fieldId + '_button';
        var modalId = fieldId + '_modal';
        
        // Initialize modal
        var modalElement = $('#' + modalId);
        var modalOptions = {
            type: 'slide',
            responsive: true,
            innerScroll: true,
            modalClass: 'product-chooser-modal-container',
            title: $t('Choose Product'),
            buttons: [{
                text: $t('Select'),
                class: 'action-primary',
                click: function() {
					var selected = modalElement.find('input[name="product_id[]"]:checked');
                    if (selected.length) {
						var productIds = selected.map(function() {
						    return $(this).val();
						}).get();
                        var productName = selected.closest('tr').find('td:nth-child(3)').text().trim();
						console.log('Selected product name:', productName);
						$('#' + fieldId).val(productIds.join(','));
                        modalElement.modal('closeModal');
                    } else {
                        alert($t('Please select a product.'));
                    }
                }
            }, {
                text: $t('Cancel'),
                class: 'action-secondary',
                click: function() {
                    modalElement.modal('closeModal');
                }
            }]
        };
        
        modalElement.modal(modalOptions);
        
        // Handle button click
        $('#' + buttonId).on('click', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: config.gridUrl,
                type: 'POST',
                dataType: 'html',
                data: {
                    form_key: config.formKey
                },
                showLoader: true,
                success: function(data) {
                    modalElement.html(data).trigger('contentUpdated');
                    modalElement.modal('openModal');
                    
                    // Add click handler for rows
                    modalElement.on('click', 'tr.data-row', function(e) {
                        // Don't trigger if clicking on the checkbox button itself
                        if (!$(e.target).is('input[type="checkbox"]')) {
                            var checkbox = $(this).find('input[type="checkbox"]');
                            checkbox.prop('checked', !checkbox.prop('checked'));
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading product grid:', error);
                }
            });
        });
    };
});