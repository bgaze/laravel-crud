[< Back](../README.md#table-of-content)

# CRUD command options

Any step of the CRUD generation process can be set using options.  
Any option directly passed to the command will be skipped by the wizard.

Please see [CRUD command options](doc/crud-options.md) for more details.

Example, CRUD generation without any interractions:

```
php artisan crud:classic Article -n -s=none \
-c "string title" \
-c "enum category foo bar foobar -i" \
-c "text body -n" \
-c "unsignedInteger views -d 0" \
-c "boolean active -d 1" \
&& php artisan migrate \
&& php artisan db:seed --class=ArticlesTableSeeder 
```
