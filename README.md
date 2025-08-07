## Homeowner Names Parser - Street Tech Test

To get started, please clone the repo locally and then run the following commands:

```
composer install
cp .env-example .env
php artisan key:generate
php artisan migrate
composer run dev
```

You'll then be able to visit your localhost and see the parser results straight away.

In the interest of time, I have included the example CSV in a ``resources/data`` directory so that it can be immediatelly accessible. Alternatively, I could have created a simple file uploader or a console command.

As far as the code itself goes, certain edge cases that are not covered in the example CSV file have been skipped. For instance, it's assumed that any word that's 2 or fewer characters will be an initial whereas in reality, short names such as Jo or Ty do exist and would be misinterpreted. 

Another example is when splitting names. While this version caters for all the examples provided, it would make incorrect assumptions about others. For example, "Mr John and Mrs Jane Smith" would be misinterpreted with "John" being added as the last name. 

Tests have been added that cover all the example cases too, which can be run with:

```
php artisan test --filter=PersonParserServiceTest
```