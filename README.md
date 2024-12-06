# 异世界百科 选择授权协议
## 使用
配置文件：
```php
$wgIsekaiLicense = [
    '协议名' => [
        'name' => '显示的名称',
        'url' => '协议介绍地址',
        'page' => '协议介绍页面，和url是二选一的关系',
        'icon' => '图标的地址',
    ]
]
```

CC协议示例：
```php
$wgIsekaiLicenses = [
	'cc-by-nd' => [
		'url' => 'http://creativecommons.org/licenses/by/4.0/deed.zh',
		'name' => '知识共享署名 4.0 国际许可协议',
		'icon' => "$wgResourceBasePath/resources/assets/licenses/cc-by.svg",
	],
	'cc-by-nd' => [
		'url' => 'http://creativecommons.org/licenses/by-nd/4.0/deed.zh',
		'name' => '知识共享署名-禁止演绎 4.0 国际许可协议',
		'icon' => "$wgResourceBasePath/resources/assets/licenses/cc-by-nd.svg",
	],
	'cc-by-sa' => [
		'url' => 'http://creativecommons.org/licenses/by-sa/4.0/deed.zh',
		'name' => '知识共享署名-相同方式共享 4.0 国际许可协议',
		'icon' => "$wgResourceBasePath/resources/assets/licenses/cc-by-sa.svg",
	],
	'cc-by-nc' => [
		'url' => 'http://creativecommons.org/licenses/by-nc/4.0/deed.zh',
		'name' => '知识共享署名-非商业性使用 4.0 国际许可协议',
		'icon' => "$wgResourceBasePath/resources/assets/licenses/cc-by-nc.svg",
	],
	'cc-by-nc-nd' => [
		'url' => 'http://creativecommons.org/licenses/by-nc-nd/4.0/deed.zh',
		'name' => '知识共享署名-非商业性使用-禁止演绎 4.0 国际许可协议',
		'icon' => "$wgResourceBasePath/resources/assets/licenses/cc-by-nc-nd.svg",
	],
	'cc-by-nc-sa' => [
		'url' => 'http://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh',
		'name' => '知识共享署名-非商业性使用-相同方式共享 4.0 国际许可协议',
		'icon' => "$wgResourceBasePath/resources/assets/licenses/cc-by-nc-sa.svg",
	],
];
```

在页面中使用：
```
<license>协议名，如：cc-by</license>
```

## 附录
docs/license icons 中有所有CC BY协议的svg图标

### 快速创建协议提示框
安装TemplateStyles扩展和Scribunto扩展

导入docs/提示框.tpl到 模板:提示框
导入docs/styles.css到 模板:Alert/styles.css
导入CCLicenseBox.lua到 模块:CCLicenseBox