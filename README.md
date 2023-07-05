# IP-PHP
IP签名档PHP开源版

今天，我们将为大家介绍一个有趣的IP签名档项目。通过将源代码部署在服务器上，您可以轻松地为自己的社交媒体、论坛等地创建一个独特的签名档，使您的网站更加出彩！ 

接下来，我们将详细向大家展示如何搭建PHP开源版IP签名档以及相关环境配置的方法。

## 部署环境
- 宝塔面板
- PHP
- Redis服务

## 设计思路
首先，我想创建一个图片，可以显示天气日期、浏览器信息、操作系统信息和用户的IP地址。此外，它还可以随机显示不同的图片样式。  

获取用户的IP地址，我可以获取其所在地的信息，并使用腾讯地图API和今日头条API来查询该地区的天气。  

![](https://img-blog.csdnimg.cn/img_convert/68b224f8b6839804acccfdf3429e6ac0.png)

### API调用
腾讯地图API的主要作用是根据用户的IP地址获取其所在地的信息。对于个人开发者，每天可以发起1000次请求；对于企业开发者，每天可以发起300万次请求。


![](https://img-blog.csdnimg.cn/img_convert/2ba40ddd13a36b34bbef2f65beec22c9.png)



另一方面，今日头条的API暂时没有调用次数限制，但需要使用腾讯地图API返回的归属地信息来查询天气信息。  
### Redis服务
IP地址对应的归属地信息是固定的，因此我们可以使用Redis服务对这些数据进行缓存。  

我们可以以用户的IP地址作为键名，以归属地信息作为键值，并设置适当的缓存时间。这样可以大大减少IP定位的请求次数，也可以提高查询的效率。

![](https://img-blog.csdnimg.cn/img_convert/975bcbfd390b5545b22ae669c35a0656.png)



## 部署源码  
在下载并解压缩压缩包后，我们需要修改源代码中的腾讯地图API对接密钥，并将Redis服务的相关信息进行修改。  
这样可以确保程序能够正常地与腾讯地图API和Redis服务进行通信。
### 获取腾讯地图API密钥
首先，您需要在腾讯地图开放平台注册账号并登录，在控制台中创建应用并选择需要使用的腾讯地图API服务，创建应用后，您将获得一个唯一的密钥，这个密钥将用于对接腾讯地图API服务。

![](https://img-blog.csdnimg.cn/img_convert/dba4e3aa8e64d2dac1deb6523b2cb95f.png)

![](https://img-blog.csdnimg.cn/img_convert/7aa7db71a7fd85f9445470fb41c2dce2.png)
## 安装Redis服务
登录宝塔面板，进入软件管理页面，在搜索框中输入“Redis”，选择最新版本即可，击“安装”按钮，等待安装完成。  

安装完成后，您可以在软件管理页面中查看Redis服务的状态并进行相关操作。
根据需求进行相关配置，例如设置端口号、密码等，默认端口号6379，无密码。

`注意：为了安全着想需要可设置Redis密码。`

![](https://img-blog.csdnimg.cn/img_convert/906ffec37abe39c95f88744376be40c9.png)

##  获取用户真实IP

您需要在宝塔面板的“软件商店”中打开Nginx，并添加以下规则，这样子可以在使用内容分发网络时获取用户真实IP。
```
set_real_ip_from 0.0.0.0/0;
real_ip_header X-Forwarded-For;
```
        
![](https://img-blog.csdnimg.cn/img_convert/4d8d769d31a911e2e433f05d869bb26a.png)

## 修改源代码

复制腾讯地图API密钥到源代码中第7行，并替换成自己的密钥。这个密钥是用来访问腾讯地图API的。
![](https://img-blog.csdnimg.cn/img_convert/f3e051fb8a0b12bd9b0a93789a3774e5.png)

如果您的Redis服务设置了密码，请将源代码第66行的注释删除，并将密码修改成自己的密码，如果不替换成自己的密码，程序将无法连接到Redis服务。
![](https://img-blog.csdnimg.cn/img_convert/d4af2749632beb81efb853a15206a257.png)
  
## 测试上线

我们可以通过访问自己的域名（例如qq.com）来输出随机图片，只需直接访问qq.com即可。    

如果您需要输出特定编号的图片（编号从0到5），您可以通过访问 qq.com/index.php?type=1 来实现。其中，type 参数后面的数字即为您所需输出的图片的编号。

![](https://img-blog.csdnimg.cn/img_convert/67764ea76cc38b4574b57ef13633d709.png)


经过测试图片输出速度平均400-500毫秒，证明Redis服务起到了缓存加速，用户第一次访问没有命中缓存，会慢一点。

![](https://img-blog.csdnimg.cn/img_convert/17f8bf5298ed40c6001090caa1424d0a.png)
