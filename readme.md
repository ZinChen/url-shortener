# URL shortener

Web service to shorten URLs. Built with Symfony 3 and Angular 4. It allows sharing your shorten URL, watch count of views of current URL. Short URL will be deleted after 2 weeks since creation date.

Demo: https://url-shortener-atoll.herokuapp.com/

### Docker Compose running
```
docker-compose up -d
```

Add urlshortener.dev domain into /etc/hosts. To get docker container IP use this command:

```
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' urlshortener_nginx_1
```

To init database or run other symfony console commands use **docker exec** like that:
```
docker exec urlshortener_php_1 sh -c "php bin/console doctrine:database:create"
docker exec urlshortener_php_1 sh -c "php bin/console doctrine:schema:update"
```

### Application Parameters

Application parameters placed in config.yml. Parameters allow to set **length** of short URL's and enable **using of digits** for them.
```
symfony/app/config/config.yml
...
    short_url.length: 5
    short_url.use_digits: true
...
```

### Application API

**Get application parameters**
```
GET: /params
```
Response:
```
{
    length: 5,
    use_digits: true
}
```

**Get short url details**
```
GET: /info/{shortUrl}
```
Response:
```
{
    status: 'success',
    busy: true,
    fullUrl: 'https://www.youtube.com/watch?v=AQBh9soLSkI',
    shortUrl: 'http://urlshortener.dev/po8vh',
    useCount: 1,
    createDate: '2017-10-01'
}
```

**Increment short url use counter**
```
GET: /used/{shortUrl}
```
Response:
```
{
    status: 'success',
    useCount: 1
}
```

**Create new short url**
```
POST: /create
```
Request:
```
{
    fullUrl: 'https://www.youtube.com/watch?v=AQBh9soLSkI',
    shortUrl: 'Ab123' //null or string
}
```
Response:
```
{
    status: 'success',
    short_url: 'http://urlshortener.dev/po8vh',
    message: 'short URL created successfully'
}
```

**Error response:**
```
{
    status: 'error',
    message: 'full URL is not valid'
}
```

### Command to delete old URLs

```
php bin/console urls:clean
```

cron task

```
0  3  *  *  * /usr/bin/php /var/www/symfony/bin/console urls:clean
```
