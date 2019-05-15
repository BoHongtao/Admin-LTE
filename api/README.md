## 这是基于Yii2.0的api框架，集成了认证，限流等功能
本api框架需要post方式请求并传递参数，header中认证token，获取token后，以后每次请求都需要在header中携带token参数

## 获取token
### /v1/site/login
#### params
| Name | title | Data Type |  Require |
| ------ | ------ | ------ | ------ |
| userName | 用户名 | String | 是 |
| password | 密码 | String | 是 |

![](https://github.com/BoHongtao/Admin-LTE/blob/master/api/web/upload/1.png)

![](https://github.com/BoHongtao/Admin-LTE/blob/master/api/web/upload/1.png)

## 限流功能
防止恶意请求，如果超过请求次数，返回409错误码
```
    public function getRateLimit($request, $action)
    {
        return [1, 1]; // 每秒中允许请求的次数，前面是次数，后面是秒数
    }
```
```
	{
		"name": "Too Many Requests",
		"message": "Rate limit exceeded.",
		"code": 0,
		"status": 429,
		"type": "yii\\web\\TooManyRequestsHttpException"
	}
```



