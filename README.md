<h1 align="center">Advanced Promotion</h1>

[![Advanced Promotion Plugin license](https://img.shields.io/github/license/monsieurbiz/SyliusAdvancedPromotionPlugin?public)](https://github.com/monsieurbiz/SyliusAdvancedPromotionPlugin/blob/master/LICENSE.txt)
[![Tests Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusAdvancedPromotionPlugin/tests.yaml?branch=master&logo=github)](https://github.com/monsieurbiz/SyliusAdvancedPromotionPlugin/actions?query=workflow%3ATests)
[![Recipe Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusAdvancedPromotionPlugin/recipe.yaml?branch=master&label=recipes&logo=github)](https://github.com/monsieurbiz/SyliusAdvancedPromotionPlugin/actions?query=workflow%3ASecurity)
[![Security Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusAdvancedPromotionPlugin/security.yaml?branch=master&label=security&logo=github)](https://github.com/monsieurbiz/SyliusAdvancedPromotionPlugin/actions?query=workflow%3ASecurity)

This plugins add features on promotions.

You can define if a cart promotion need to be applied before or after tax.

![You can define if a cart promotion need to be applied before or after tax.](docs/images/promotion-admin.jpg)

You can use multiple coupons in your orders.

![You can use multiple coupons in your orders.](docs/images/promotion-front.jpg)

## Compatibility

| Sylius Version | PHP Version     |
|----------------|-----------------|
| 1.12           | 8.1 - 8.2 - 8.3 |
| 1.13           | 8.1 - 8.2 - 8.3 |
| 1.14           | 8.1 - 8.2 - 8.3 |

## Installation

If you want to use our recipes, you can configure your composer.json by running:

```bash
composer config --no-plugins --json extra.symfony.endpoint '["https://api.github.com/repos/monsieurbiz/symfony-recipes/contents/index.json?ref=flex/master","flex://defaults"]'
```
```bash
composer require monsieurbiz/sylius-advanced-promotion-plugin
```

## Configuration 

Copy files in `dist` folder to your Sylius project.

Then run the migrations.
