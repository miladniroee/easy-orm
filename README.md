# PHP easy ORM

**Easy ORM** is a simple and lightweight PHP database class.

# Installation

using composer:
```
composer require niroee/easy-orm
```


# Usage

You can use this orm for CRUD from one table.

First you should make a class that extends `Niroee\EasyOrm` :

```php
use Niroee\EasyOrm\Model;  
  
class Users extends Model{}
```

> For every table you have to make a specific model. for example `Users` class points to `users` table and `UserAddresses` points to `user_addresses` table.

<br>

### Making model with custom table name

you can define in your class by passing `$table` attribute to your class:

```php
use Niroee\EasyOrm\Model;  
  
class OtherTable extends Model  
{  
  protected string|null $Table = "users";  
}
```

### Access to model

```php
$Users = Users::get(); // return all users

// or

$UsersInstance = new Users();
[$Users = $UsersInstance->get(); // return all users]()
```

You have to use `get()`, `first()`,`json()`,`toArray()` method for select and `exec()` method for insert, update and delete query.

#### get()

return all entries;

```php
$Users = Users::get(); // return all users
$FirstUserName = $Users[0]->name;
```

#### first()

return first entry;

```php
$User = Users::first()->name; // return name of first user
```

#### json()

return all entries as json serialized string;

```php
$Users = Users::json(); // return json string
```

#### toArray()

return all entries as an array (nested-array);

```php
$Users = Users::toArray();
$FirstUserName = $Users[0]['name'];
```

if you want to update, insert or delete entries, you have to use `exec()` method after you query.

```php
Users::insert(['name' => 'milad'])->exec(); 

Users::update(['name' => 'ali'])->where('name', '=', 'milad')->exec();
 
Users::delete()->where('name', '=', 'ali')->exec();
```

if you want to see query, you can use `query()` method;

```php
Users::query(); // SELECT * FROM `users`

Users::insert(['name' => 'milad'])->query(); // INSERT INTO users(name) VALUES(?)
```

<hr>

## Conditions

if you want to specify condition for queries, you can use `where()`,`andWhere()` and `orWhere()` methods.

```php
Users::update(['name' => 'milad',])
->where('id', '=', 2)
->exec();

//or

Users::update(['name' => 'milad'])
->where('id', '=', 2)
->andWhere('name', '=', 'ali')
->orWhere('id', '=', 1)
->exec();
```

These three methods accepts three arguments:

* Column: string
* Operator: string
* Value: mixed

```php
where($Column, $Operator, $Value)
````

## Select

For Select all columns (`*`) you can call  `get()`, `first()`,`json()` or `toArray()` method;

```php
$Users = Users::get(); // return all users
```

you can specify column to return from table.

```php
Users::select('name')->get(); // return all users name

// or

Users::select(['name','id'])->get(); // return all users name and id
```

<br>

## Update

update method accepts array contains key => value fo update from table

```php
Users::update([
    'name' => 'milad',
    'app_id' => 5
])->exec(); // will return count of row affected
   
```

you can use conditions to update rows:

```php
Users::update(['name' => 'milad',])
->where('id', '=', 2)
->exec(); // will return count of row affected
```

## Insert

insert method accepts array for inserting by key => value

```php
Users::insert([
    'name' => 'milad',
    'app_id' => 5
])->exec(); // will return count of row affected
```

## Delete

delete method would remove selected rows.

```php
Users::delete()->exec(); // delete all rows

// or

Users::delete()->where('id', '=', 5)->exec(); // delete user with id = 5
```

