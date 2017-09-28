#URL shortener

Web service to shorten your URLs. Built with Symfony 3 and Angular 4.

###Docker up & running
```
docker-compose up
```

Don't forget to place urlshortener.dev domain into /etc/hosts. To get docker container IP use this line

```
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' <nginx_container_name>
```
