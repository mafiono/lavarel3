## Casino Portugal WebSite

Site de Casino, feito em Laravel.

## Deploy to WebSite

### Cron Job

Create a Cron Job on the web-server to Run the Scheduling.

```
* * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1
```

`php` need to change to the path of Php won the server.  
`/path/to/artisan` need to change to the correct path of the project.

