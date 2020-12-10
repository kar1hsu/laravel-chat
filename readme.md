## 介绍

laravel 整合 workerman 实现聊天室功能，支持增加好友，项目主要是练手用，并没有使用过多的技术，本人前端技术水平不够，对 vue 的应用也是能用的水准

## 使用

php 版本 >= 7.2

代码拉取下来后，先执行 `composer update` 安装好依赖包，前端使用 vue 如果需要修改页面请自行安装开发环境

执行 `php artisan migrate` 创建sql

在项目目录下执行 `php artisan worker start` 开启 websocket 监听， 默认 8080 端口

可在 `app\Console\Commands\Workerman.php` 中的 `startGateWay` 方法中修改相关配置信息

若修改了监听端口，前端 vue 文件也对应修改请求端口

本地配置了项目地址，访问进入的是群聊聊天室，若想体验好友聊天，可手动输入 url , `配置的域名/friend` 即可

好友聊天框，点击左边列表好友名称即可切换聊天对象

本人前端技术渣渣，并且练手项目，没有消耗时间做前端交互优化
