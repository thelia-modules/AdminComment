# Admin Comment

This module allows you to add comments on each part of the back office : categories, products, folders, contents,
 orders, customers and coupons.
These comments are not visible for customers. 
   
## Installation
 
### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the 
    module is AdminComment.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/admin-comment-module:~1.0
```

## configuration

no special configurations needed. just set the permissions for administrators.


## Changelog

### 1.0.1

- Fixed module activation
- Removed JS dependency to missing bootbox lib