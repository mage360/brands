# Shop by brand Magento 2 extension

This shop by brand extension allows you to create brands for your shop; each brand can be named, described and assigned a logo.
A brand will be linked to an attribute of the product, so on the brand page you would see all the products associated to it with layered navigation for easy filtration. 
Brands main page and individual brand have clean url. You can also specify meta keywords and description. 

### Useful links

|  |  |
| ------ | ------ |
| Module homepage | [mage360.com/shop-by-brand](https://mage360.com/shop-by-brand/) |
| Demo | [bmdemo.mage360.com/brands.html](https://bmdemo.mage360.com/brands.html/) |

### Installation
```sh
$ composer require mage360/brands
$ composer update
$ php bin/magento setup:upgrade
$ php bin/magento setup:di:compile
$ php bin/magento setup:static-content:deploy
$ php bin/magento cache:clean
```

### Features
  - Brand details - Name, Description, logo, featured, active.
  - Main Brands page - Display all brands. Featured brands will have a separate section.
  - Clean urls - choose main brands page and individual brand page urls.
  - SEO - Meta keywords and description
  - Products collection: Products are linked to individual brand by an attribute. On the brand page, you will see all the product with pagination and sortings.
  - Layered navigation - Individual brand page will also display layered navigation.
  - Breadcrumbs
  - Add shop by brand link to the main navigation.

### Usage

  - Set Product attribute on the config page, Store > Configuration > Mage360 > Brands
  - You can see brands page by going on url /brands. If you set url key in configuration then url will be /URLKEY.html like /brands.html
  - Individual brand - Prefix 'brand' will be added to the url and .html at the end. So the final url wil be /brand/URLKEY.html