var config = {
  config: {

    mixins: {
      'Magento_Checkout/js/action/set-shipping-information': {
        'Smsapi_Smsapi2/js/action/set-shipping-information-mixin': true
      },
      'Magento_Checkout/js/action/create-shipping-address': {
        'Smsapi_Smsapi2/js/action/create-shipping-address-mixin': true
      },
      'Magento_Checkout/js/action/create-billing-address': {
        'Smsapi_Smsapi2/js/action/create-billing-address-mixin': true
      },
      'Magento_Checkout/js/action/place-order': {
        'Smsapi_Smsapi2/js/action/place-order-mixin': true
      },
    }
  }
};