sociableCore
============

Ultra lightweight flexible full stack framework wrote in PHP5 (5.4)

- On the fly ORM
- Connect to any relational SGBD structure and scaffold your business logic objects from it 
- Scaffold forms, views and also translations
- Autocast every SGBD data types and auto validate data integrity of your objects attributes on CRUD actions
- Flexible ACL managment
- Namespaces
- MVC pattern, modules enabled with a common couch to each layers
- Lightweight render engine (Haanga), this framework can render pages that fetch a hundred objects under 0.002 secs 

Dependancy

Memcached

Installation

Just clone this repo and add a new folder in the root of the project called tmp/ and chmod it to 0777.

mkdir ./tmp/ && chmod 777 -R ./tmp/
