```
 _			  _	_  	  _   _                               _ 
| |\  	    /| |\ \   / /| |                             | |
| | \      / | | \ \ / / | |     __ _ _ __ __ ___   _____| |
| |\ \    /	/| |   | |   | |    / _` | '__/ _` \ \ / / _ \ |
| | \ \  / / | |   | |   | |___| (_| | | | (_| |\ V /  __/ |
|_|  \ \/ /  |_|   |_|   |______\__,_|_|  \__,_| \_/ \___|_|
                                           
```

> 根据laravel框架基础，加装项目常用api。注意：项目交付后，删除此文档

## 所用扩展

- `overtrue/easy-sms` 短信
- `doctrine/dbal` 修改数据表字段的属性 <font style="color:red">注：因使用2.9.x版本，所有php版本应大于7.1</font>
- `overtrue/laravel-lang` 语言包
- `gregwar/captcha` 图片验证码
- `jacobcyl/ali-oss-storage` 阿里云oss
- `overtrue/laravel-socialite` 第三方登录
- `tymon/jwt-aut` jwt



## 起步

- 安装扩展

  ```shell
  composer install
  ```

- 复制.env.example

  ```shell
  cp .env.example .env
  ```

- 更新密钥

  ```shell
  php artisan key:generate
  ```

- 更新Token 签名

  ```shell
  php artisan jwt:secret
  ```

- 根据需求更改.env配置

- 数据迁移

  ```shell
  php artisan migrate --seed
  ```



## 变动

- 中间件

  - 默认 Accept 头返回 Json 格式

- 验证器

  - 重写了验证器默认返回格式 `app\Http\Requests\Api\FormRequest@failedValidation`
  - 系统错误处理汉化 `app\Http\Exception\Handler`

- artisan

  - 输入用户 id，查询 id 对应的用户，然后为该用户生成一个有效期为 1 年的 `token`。

    ```shell
    php artisan generate-token
    ```

- .env

  - ```shell
    #开发环境 验证码:1234
    APP_ENV=local
    #正式环境
    APP_ENV=production
    ```

    **注意**：清理缓存 `php artisan config:cache`

    在路由中有 `短信验证码` 和 `图片+短信验证码` 两种。



## 新增接口

- 采用单设备登录(JWT设置过期时间为永不过期)

  **调整为过期**

  1. 修改 `.env` 文件 中 `JWT_TTL` 与 `JWT_REFRESH_TTL` 的值
  2. 修改 `config/jwt.php` 配置文件中 `required_claims.exp` 注释打开

  <font style="color:red">注：jwt只是用于用户登录令牌认证，众所周知http是采用的明文通讯，所以很容易就能够被窃取到http通讯报文。所以可以做 1. 通信层加密，比如采用https。2 . 代码层面安全检测，比如ip地址发生变化，MAC地址发生变化等等，可以要求重新登录。</font>

- 采用阿里云短信、可配置阿里云oss

- 节流限制

- 公众号第三方登录

- 上传模块(本地/云端)，通过微信登录获取的头像采用本地上传

- 轮播图

- 关于我们

- 杂项(各类大型数据管理)

