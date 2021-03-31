# Workerman + TP6 实现可视化定时任务

## 系统定时任务使用方法：

1. 首先导入 `data/test.sql` 数据表
2. 拷贝 `.example.env` 文件为 `.env`，并配置正确的数据库
3. 项目根目录下执行 `php think crontab --m debug ` 或 `php think crontab`

## 定时器格式说明：

```
0   1   2   3   4   5
|   |   |   |   |   |
|   |   |   |   |   +------ day of week (0 - 6) (Sunday=0)
|   |   |   |   +------ month (1 - 12)
|   |   |   +-------- day of month (1 - 31)
|   |   +---------- hour (0 - 23)
|   +------------ min (0 - 59)
+-------------- sec (0-59)[可省略，如果没有0位,则最小时间粒度是分钟]
```

