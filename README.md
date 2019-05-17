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
composer require thelia/admin-comment-module ~1.1.0
```

## configuration

no special configurations needed. just set the permissions for administrators.

## Loop

Get the comment by loop

### Input arguments

|Argument   |Description |
|---          |--- |
|**id**  | The id of comment |
|**element_key** | The key of commented element |
|**element_id**   | The id of commented element |

### Ouput arguments

|Variable   |Description |
|---          |--- |
|$ID    |the comment ID |
|$ADMIN_ID    |Author id  |
|$ADMIN_NAME    |Author name  |
|$COMMENT    |The message  |
|$ELEMENT_KEY   |The key of commented element |
|$ELEMENT_ID   |The id of commented element |
|$CREATED_AT   |Comment creation date|
|$UPDATED_AT   |Comment update date |


## Changelog

### 1.1.1

- Add loop for admin comment

### 1.1.0

- Add tab in edit page with comment counter in tab title
- Add comment counter in order list

### 1.0.1

- Fixed module activation
- Removed JS dependency to missing bootbox lib