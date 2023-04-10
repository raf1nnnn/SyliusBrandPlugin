# Sylius Brand Plugin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

<a href="https://sylius.com/plugins/" target="_blank"><img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="100"></a>

If you want to add a brand to your products this is the plugin to use. Use cases:
- Add brand logo to your product pages
- Filter by brand in the frontend or backend, i.e. product lists

## Screenshots

<details><summary>CLICK TO SEE</summary>

Menu:

![Screenshot showing admin menu](docs/images/admin-menu-with-brand.png)

Brand admin pages:

![Screenshot showing brand admin index page](docs/images/admin-brand-index.png)

![Screenshot showing brand admin update page](docs/images/admin-brand-update.png)

![Screenshot showing brand admin media tab at update page](docs/images/admin-brand-update-tab-media.png)

Products admin pages:

![Screenshot showing product admin index page with brand filter](docs/images/admin-product-index-filter-with-brand.png)

![Screenshot showing product admin index page with brand column](docs/images/admin-product-index-brand-column.png)

![Screenshot showing brand tab at product admin update page](docs/images/admin-product-update-tab-brand.png)

</details>

## Installation



This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.


### Step 1: Enable the plugin

Then, enable the plugin by adding it to the list of registered plugins/bundles
in `config/bundles.php` file of your project before (!) `SyliusGridBundle`:

```php
<?php

# config/bundles.php

return [
    // ...
    Loevgaard\SyliusBrandPlugin\LoevgaardSyliusBrandPlugin::class => ['all' => true],
    Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
    // ...
];
```

### Step 3: Configure the plugin

```yaml
# config/packages/loevgaard_sylius_brand.yaml

imports:
    # ...
    - { resource: "@LoevgaardSyliusBrandPlugin/Resources/config/app/config.yaml" }

    - { resource: "@LoevgaardSyliusBrandPlugin/Resources/config/grids/sylius_admin_product.yaml" }
```

```yaml
# config/routes/loevgaard_sylius_brand.yaml

loevgaard_sylius_brand:
    resource: "@LoevgaardSyliusBrandPlugin/Resources/config/routing.yaml"
```

### Step 4: Extend services and entities

#### Extend `Product`

- If you use `annotations` mapping:

    ```php
    <?php
    
    declare(strict_types=1);
    
    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Loevgaard\SyliusBrandPlugin\Model\ProductInterface as LoevgaardSyliusBrandPluginProductInterface;
    use Loevgaard\SyliusBrandPlugin\Model\ProductTrait as LoevgaardSyliusBrandPluginProductTrait;
    use Sylius\Component\Core\Model\Product as BaseProduct;
    
    /**
     * @ORM\Entity
     * @ORM\Table(name="sylius_product")
     */
    class Product extends BaseProduct implements LoevgaardSyliusBrandPluginProductInterface
    {
        use LoevgaardSyliusBrandPluginProductTrait;
    }
    ```
    
- If you use `xml` mapping:

    ```php
    <?php
    // src/Model/Product.php
    
    declare(strict_types=1);
    
    namespace App\Model;
    
    use Loevgaard\SyliusBrandPlugin\Model\ProductInterface as LoevgaardSyliusBrandPluginProductInterface;
    use Loevgaard\SyliusBrandPlugin\Model\ProductTrait as LoevgaardSyliusBrandPluginProductTrait;
    use Sylius\Component\Core\Model\Product as BaseProduct;
    
    class Product extends BaseProduct implements LoevgaardSyliusBrandPluginProductInterface
    {
        use LoevgaardSyliusBrandPluginProductTrait;
        
        // ...
    }
    ```
    
    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    
    <!-- config/doctrine/model/Product.orm.xml -->
    
    <doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                      xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                          http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    
        <mapped-superclass name="App\Model\Product" table="sylius_product">
            <many-to-one field="brand" target-entity="Loevgaard\SyliusBrandPlugin\Model\BrandInterface" inversed-by="products">
                <join-column name="brand_id" on-delete="SET NULL" />
            </many-to-one>
        </mapped-superclass>
    
    </doctrine-mapping>
    ```

#### Extend `ProductRepository`

```php
<?php
# src/Doctrine/ORM/ProductRepository.php

declare(strict_types=1);

namespace App\Doctrine\ORM;

use Loevgaard\SyliusBrandPlugin\Doctrine\ORM\ProductRepositoryInterface as LoevgaardSyliusBrandPluginProductRepositoryInterface;
use Loevgaard\SyliusBrandPlugin\Doctrine\ORM\ProductRepositoryTrait as LoevgaardSyliusBrandPluginProductRepositoryTrait;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;

class ProductRepository extends BaseProductRepository implements LoevgaardSyliusBrandPluginProductRepositoryInterface
{
    use LoevgaardSyliusBrandPluginProductRepositoryTrait;

    // ...
}
```

#### Configure

```yaml
config/services.yaml

sylius_product:
    resources:
        product:
            classes:
                model: App\Model\Product # Or App\Entity\Product
                repository: App\Doctrine\ORM\ProductRepository

```  

### Step 5: Update your database schema

```bash
$ php bin/console d:s:u-f
$ php bin/console sylius:theme:assets:install
$ php bin/console cache:clear

```


