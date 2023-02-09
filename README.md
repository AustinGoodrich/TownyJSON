# TownyJSON
Simple PHP &amp; MYSQL based JSON Formatted API for the popular Minecraft Towny server plugin.

## Requirements: 
* MySQL (prefer 8.0)
* PHP (prefer 7.4)
* WebServer (prefix nginx)

### Towny Requirements:
* Towny in MySQL Mode
* Towny 0.96.5.6+

## Installation
1. Create a MySQL user that has READ/SHOW access only to your Towny Database.
   1. Run the command in Mysql, `GRANT SELECT, SHOW VIEW ON towny.* TO 'towny_readonly'@’localhost′ IDENTIFIED BY ‘supercalifragilisticexpialidocious‘`
2. Drag and drop `config.php & keys.php` and `json/towny` into your web servers directory.
   1. Configure `includes/config.php` by setting your Host, Username, Password, Database name and Mysql Port.
   2. Configure each `$column_?` variables such as `$column_nations = 'towny_nations';` in `config.php` to match your case sensitive database, some database servers (MySQL UNIX) require you set this as `$column_nations = 'TOWNY_NATIONS';`
3. Once configured properly, you should be able to visit your web servers address and call into the `town.php, resident.php, nations.php` files. For example, `http://localhost/json/towny/town.php`

## API Usage (Edit me)
1. Read the documentation for this api (coming soon)
2. You can do `{method}.php?name={townname|resident|nationname}` to get individual data, or abstain for using `?name=` and pull all of the data at once.
