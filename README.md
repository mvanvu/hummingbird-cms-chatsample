# hummingbird-cms-chatsample
A sample chat plugin that running on the <a href="https://github.com/mvanvu/hummingbird-cms">Hummingbird CMS</a> Socket Application. 
This plugin is only for development purpose. So, the chatting will be not check permission on save message data. Don't use it in your production site.

![Peek 2021-03-01 19-57](https://user-images.githubusercontent.com/5796168/109500285-a4b05700-7ac8-11eb-93b8-d0dece392c5c.gif)

## How to use
This plugin is ready to use inside the docker
- Download the zip package and install from the back-end CMS (Plugins menu from the left navigation)
- Create a menu for this or browse this URL localhost:9000/chat-sample/index

## Note
After install the plugin you must restart the docker to reload the Socket application

Change path/to/repo/hummingbird-cms to your hummingbird-cms repo

```shell script
cd path/to/repo/hummingbird-cms
```

#### Restart docker
```shell script
docker-compose down
docker-compose up -d
```

#### Alternatively
```shell script
docker-compose exec ubuntu-18.4 bash
supervisorctl stop hummingbird-socket
supervisorctl start hummingbird-socket
```