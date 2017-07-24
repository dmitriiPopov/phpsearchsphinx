#### Structure ####

-config (common static settings and examples of different related services settings)
-data (directory for different source data. Files, dumps etc.)
-lib (tree of files with main classes, core)
-public (web access must be ONLY here)




#### Full integration info in a local environments ####

1) install sphinx (recommended version >=2.2.9) for centOS/ubuntu and install mysql:
- for centOS - https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-sphinx-on-centos-7
   http://sphinxsearch.com/docs/current/installing-redhat.html
- http://zaan.ru/ustanovka-sphinx-na-centos-6/
- for Ubuntu - https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-sphinx-on-ubuntu-16-04

2) Create database and table; import data from csv to database's table:
    1. make sure that terminator between fields in csv-file NOT `;` and NOT `,`. Use a more specific symbol - `|` for example.

    2. create database if it isn't created (open mysql tool):
    ```
       CREATE SCHEMA `deshevshe` DEFAULT CHARACTER SET utf8 ;
```
    3. drop old full table and create empty table  (open mysql tool):
    ```
        DROP TABLE IF EXISTS `deshevshe`.`deshevshe_products`;
        CREATE TABLE IF NOT EXISTS `deshevshe`.`deshevshe_products` (
          `name` varchar(127) DEFAULT NULL,
          `url` varchar(511) DEFAULT NULL,
          `categoryId` varchar(45) DEFAULT NULL,
          `price` varchar(45) DEFAULT NULL,
          `picture` varchar(511) DEFAULT NULL,
          `description` text
        ) ENGINE=InnoDB AUTO_INCREMENT=20304 DEFAULT CHARSET=utf8;
```
    4. import actual data from csv file to table by command:
    ```
        mysqlimport --ignore-lines=1 --fields-terminated-by="|" --verbose --local -u root \
         deshevshe /var/www/vhosts/deshevshe/data/deshevshe_products.csv
```
    5. add primary autoincrement key with command below  (open mysql tool):
    ```
        ALTER TABLE `deshevshe`.`deshevshe_products`
        ADD COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
        ADD PRIMARY KEY (`id`);
```
    6. full mysql table with integer primary keys is ready for indexing by sphinx!

3) update your sphinxsearch config with example from `config/sphinx.conf.example`.
Example: copy data from `config/sphinx.conf.example` to `/etc/sphinx/sphinx.conf`

4) To copy files with stop words:
`config/stopwords-en.txt` and `config/stopwords-ru.txt` FROM `config/` TO `/etc/sphinx/*`

5) To check and create if directories and files don't exist:
```
sudo mkdir "/var/run/sphinx"
sudo chown sphinx "/var/run/sphinx/"
touch "/var/run/sphinx/searchd.pid"
chmod 0777  "/var/run/sphinx/searchd.pid"
sudo mkdir "/var/lib/sphinx/data"
chmod 0777 "/var/lib/sphinx/data"
```
6) run indexer
```
sudo indexer --all
```
7) run daemon:
```
sudo searchd --config /etc/sphinx/sphinx.conf
```
Notice: for stopping use -   ```sudo searchd --config /etc/sphinx/sphinx.conf --stop```

8) run indexer (USE THIS COMMAND ALWAYS WHEN YOU WANT TO REFRESH INDEX DATA)
```
sudo indexer deshevshe --rotate
```
9) set command to crontab (for automatic index rotation):
config/crontab.example




#### HOW TO REFRESH INDEX DATA ####

Use points: 2) and 8) from list above.
If you forget to do 8 point it'll be refreshed every day by cron.




#### Example of using on web: ####

1)
Request: http://deshevshe.example.com/?q=купить+картридж+для+принтера+украина
Response:
```{
    "status":true,
    "result": {
       {
          name : "MLT-D203E (MLT-D203E/SEE)"
          url : "https://ad.admitad.com/g/b9028da5eb972d3053a367b1580933/?i=5&ulp=http%3A%2F%2Fdeshevshe.net.ua%2Fcartridge-samsung%2Fsamsung_mlt_d203esee.html"
          categoryid : "Картриджи"
          price : 5036
          picture : "http://photo.deshevshe.net.ua/products/259/259088/samsung-mlt-d203esee.html.b.jpg?1477557484"
          description : ""
       },
       {
          ...
       }
    }
}```

2) Invalid request:
Request: POST http://deshevshe.example.com/?q=...
Response:
```{
    "status":false,
    "message": "For GET-request only.",
    "result": []
}```

3) Not found any matches:
Request: http://deshevshe.example.com/?q=qwertyuiopasdfg
Response:
```{
     "status":true,
     "result": []
}```



#### DEV LOG ####
1) Why is mysql source being using in sphinx?
Because csv/tsv throw errors in release versions. Possible bugs were fixed in beta versions but beta version has another bugs (it has been cheked manually).
