# Amadeco WeeeConfigurableProducts Module for Magento 2

[![Latest Stable Version](https://img.shields.io/github/v/release/Amadeco/magento2-weee-configurable-products)](https://github.com/Amadeco/magento2-weee-configurable-products/releases)
[![Magento 2](https://img.shields.io/badge/Magento-2.4.x-brightgreen.svg)](https://magento.com)
[![PHP](https://img.shields.io/badge/PHP-8.1|8.2|8.3-blue.svg)](https://www.php.net)
[![License](https://img.shields.io/github/license/Amadeco/magento2-weee-configurable-products)](LICENSE)

[SPONSOR: Amadeco](https://www.amadeco.fr)

## Overview

The Amadeco WeeeConfigurableProducts module addresses issues related to the calculation of Fixed Product Taxes (FPT) for configurable products in Magento 2. By adjusting the tax computations, the module ensures that FPT is correctly applied by averaging the tax amounts of the child products, overcoming limitations in Magento's default behavior.

## Key Features

- **Improved FPT Calculation**: Adjusts FPT for configurable products by averaging the tax amounts from child items.
- **Seamless Integration**: Overrides Magento's default Weee tax calculation to provide more accurate tax totals on configurable products.
- **Enhanced Compatibility**: Optimized for Magento 2.4.x and PHP 8.1, 8.2, 8.3.

## Installation

### Composer Installation

Run the following commands from your Magento root directory:

```bash
composer require amadeco/module-weee-configurable-products
bin/magento module:enable Amadeco_WeeeConfigurableProducts
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
```

### Manual Installation

1. Create directory `app/code/Amadeco/WeeeConfigurableProducts` in your Magento installation
2. Clone or download this repository into that directory
3. Enable the module and update the database:

```bash
bin/magento module:enable Amadeco_WeeeConfigurableProducts
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
```

## References

This module addresses known issues and incorporates community feedback:

- **Issue: FPT is not applied to configurable products #25554
https://github.com/magento/magento2/issues/25554

- **Issue: FPT not applied on configurable products (children) in checkout summary #28931
https://github.com/magento/magento2/issues/28931

- **Pull Request: Fixed Product Taxes (FPT) are not used from simple products #35883
https://github.com/magento/magento2/pull/35883

## Compatibility

- Magento 2.4.x
- PHP 8.1, 8.2, 8.3

## Contributing

Contributions are welcome! Please read our [Contributing Guidelines](CONTRIBUTING.md).

## Support

If you encounter any issues or have feature requests, please open an issue on our GitHub repository.

## License

This module is licensed under the MIT License. See the LICENSE file for further details.
