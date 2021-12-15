<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

> 根据laravel框架基础，加装项目常用api。注意：项目交付后，删除此文档

## 所用扩展

- `tymon/jwt-aut` jwt
- `overtrue/laravel-lang` 语言包
- `overtrue/easy-sms` 短信
- `gregwar/captcha` 图片验证码
- `jacobcyl/ali-oss-storage` 阿里云oss
- `overtrue/laravel-wechat` easywechat
- `propaganistas/laravel-phone` 手机号验证
- `vinkla/hashids` 哈希数据



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
  
- 安装 dcat-admin 后台管理

  ```shell
  composer require dcat/laravel-admin:"2.*" -vvv
  php artisan admin:publish
  php artisan admin:install
  php artisan db:seed --class=AdminSeeder
  ```

  

## 变动

- 错误处理

  - `app\Exceptions\Handler.php` 里面定义了一些错误信息

    ```php
    User::findOrFail(1);//当未查询到结果(null),直接调用提示错误信息
    throw new \Exception('这是一个错误');//自定义错误信息
    ```

  - 在 `app\Http\Controllers\Api\TestController` 中写有关于数据验证的示例 

- artisan

  - 输入用户 id，查询 id 对应的用户，然后为该用户生成一个有效期为 1 年的 `token`。

    ```shell
    php artisan generate-token
    ```

- .env

  - ```shell
    #开发环境 验证码:1234 在路由中有 `短信验证码` 和 `图片+短信验证码` 两种。
    APP_ENV=local
    #正式环境
    APP_ENV=production
    
    #网易邮箱配置
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.163.com
    MAIL_PORT=465
    MAIL_USERNAME=13460773851@163.com
    MAIL_PASSWORD=JLNUQXQSUWNBCIGA
    MAIL_ENCRYPTION=ssl
    MAIL_FROM_ADDRESS=13460773851@163.com
    MAIL_FROM_NAME="${APP_NAME}"
    ```
    
    **注意**：清理缓存 `php artisan config:cache`

- 哈希 id 加密

  <font style="color:red">项目初始化记得修改 `config\hashids.php` 中的key</font>

- 常用自定义方法

  `app\Helpers\function.php`

- 采用单设备登录(JWT设置过期时间为永不过期)

  **调整为过期**

  1. 修改 `.env` 文件 中 `JWT_TTL` 与 `JWT_REFRESH_TTL` 的值
  2. 修改 `config/jwt.php` 配置文件中 `required_claims.exp` 注释打开

  <font style="color:red">注：jwt只是用于用户登录令牌认证，众所周知http是采用的明文通讯，所以很容易就能够被窃取到http通讯报文。所以可以做 1. 通信层加密，比如采用https。2 . 代码层面安全检测，比如ip地址发生变化，MAC地址发生变化等等，可以要求重新登录。</font>

- 采用阿里云短信、可配置阿里云oss

- 节流限制

- 邮箱验证

- easywechat小程序登录(未写绑定openid)

- 上传模块(本地/云端)

- 轮播图、杂项(各类大型数据管理)

- 自定义每页显示条数 `per_page`

