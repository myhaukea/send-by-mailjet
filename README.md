# 一个简单的在线邮件发送

- 需要注册一个mailjet账号；
- 推荐使用自己的域名，可以用如 send01.example.com 二级域，使用二级域时不需验证主域名；
- 代码简单，部署方便
- 支持自定义收件人、主题、摘要，添加附件


## 使用教程
1.去mailjet （https://app.mailjet.com/signup ）注册一个账号

2.验证邮箱后，在 https://app.mailjet.com/account/sender 添加自己的域名，需要配置txt记录等，不再赘述

3.在 https://app.mailjet.com/account/apikeys  创建API Key 和 Secret Key，注意使用主API key ，即Primary API Key

4.在 [https://app.mailjet.com/account/sender](https://app.mailjet.com/account/sender "https://app.mailjet.com/account/sender")  添加发件人，如你的域名是 01.test.com，那么可以添加 a@01.test.com 

4.复制本项目中 mailsend.php 到你的服务器，注意把8、9、58、59行的参数改成你自己的 

5.访问该文件，填写内容后测试效果

## 注意事项
 1.  免费资源，请勿滥用，如发送广告、商业邮件等
 
 2.  由于mailjet对免费版限制 6,000 emails/month；200 emails per day，故不建议在互联网公开使用

3. 目前附件限制在15m以下（官方限制）。且由于本人技术一般，无法支持多文件发送，欢迎提PR帮助修改
