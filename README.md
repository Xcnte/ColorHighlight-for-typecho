# ColorHighlight-for-typecho
typecho代码高亮插件，支持显示行号，代码复制，需要Jq https://www.xcnte.com/archives/377/

基于 Highlight的代码语法高亮插件 for Typecho，可显示语言类型、行号，有复制代码到剪切板功能

github开源地址：[https://github.com/Xcnte/ColorHighlight-for-typecho][1]

## 起始

本插件是基于 Highlight 插件，改自泽泽( https://qqdie.com/archives/typecho-highlightjs-plugin.html )

在原有的功能上新增复制功能，Mac风格代码高亮，代码块窗口化

(请勿与其它同类插件同时启用，以免互相影响)


## 使用方法

第 1 步：下载本插件，解压，放到 `usr/plugins/` 目录中；

第 2 步：文件夹名改为 `ColorHighlight`；

第 3 步：登录管理后台，激活插件；

第 4 步：设置：选择主题风格，是否显示行号等。

**代码写法**

```
\```php
<?php echo 'hello jrotty!'; ?>
\```
删除上边代码中的\
```


**高亮效果图**

![代码高亮.png][3]

**窗口化效果图**

![代码窗口化.png][6]

## 重要说明

### 可设置项

**1. 选择高亮主题风格** (官方提供的 92 种风格切换，本人自己添加了一种（Mac风格）)

- atom-one-dark.css
- dark.css
- default.css
- github.css
- customemin.css (默认选中，Mac风格（本人看着最顺眼）)
- pojoaque.css
- tomorrow-night.css
- vs2015.css
- xcode.css

**2. 是否在代码左侧显示行号** (默认开启)

### 在插件中不方便实现的设置项

本插件支持常见的一些语言高亮。您可以打开以下链接查看详情：

[https://highlightjs.org/download/][4]


## 与我联系

作者：Xcnte

如果有任何意见或发现任何BUG请联系我

博客：[https://www.xcnte.com/archives/377/][5]


  [1]: https://github.com/Xcnte/ColorHighlight-for-typecho
  [3]: https://ws1.sinaimg.cn/large/006Xmmmgly1g0bxd8uogjj31hc0u049i.jpg
  [4]: https://highlightjs.org/download/
  [5]: https://www.xcnte.com/archives/377/
  [6]: https://ws4.sinaimg.cn/large/006Xmmmgly1g0bvmkyoztj319w0na795.jpg
