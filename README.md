# Admin-LTE
 This is a background template based on Yii 2.0 and Admin-LTE front-end template （it's base on bootstrap）
# use
before you use it , you need php7.0+ , mysql 5.6+ , apache or nginx

1. you need create a new database and run init.sql to creat tables;

2. creat a folder and run 'git clone https://github.com/BoHongtao/Admin-LTE.git', clone code to your folder；

3. create a virtual site by update nginx config or apache config;

4. cd admin-lte/config , open and update db.php; as you mysql-config update this file;

5. open chrome and visit this virtual site ; Administrator is admin and password is 111aaa ; enjoy it!!

![](https://github.com/BoHongtao/Admin-LTE/blob/master/admin-lte/web/uploads/info.png)
# generate code
if you want use auto generate code , you need python 3.6 , and run python auto_code_controller.py ,input controller name and model name ,
and you'll get a new base controller
![](https://github.com/BoHongtao/Admin-LTE/blob/master/admin-lte/web/uploads/info2.png)
