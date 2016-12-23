<?php defined('iPHP') OR exit('What are you doing?');?>
[{
    "id": "tools",
    "sort": "9999998",
    "caption": "工具",
    "icon": "gavel",
    "children": [{
        "caption": "文件管理",
        "sort": "-999",
        "href": "files",
        "icon": "folder"
    }, {
        "caption": "上传文件",
        "sort": "-998",
        "href": "files&do=multi&from=modal",
        "icon": "upload",
        "data-toggle": "modal",
        "data-meta": "{\"width\":\"85%\",\"height\":\"640px\"}"
    }, {
        "-": "-",
        "sort": "-997"
    }, {
        "caption": "模板管理",
        "sort": "-996",
        "href": "template",
        "icon": "desktop"
    }, {
        "-": "-",
        "sort": "-995"
    }, {
        "-": "-"
    }, {
        "id": "cache",
        "caption": "清理缓存",
        "icon": "refresh",
        "children": [{
            "caption": "更新所有缓存",
            "href": "cache&do=all",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "-": "-"
        }, {
            "caption": "更新系统设置",
            "href": "cache&acp=admincp.setting.app",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "caption": "更新菜单缓存",
            "href": "cache&do=menu",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "caption": "清除模板缓存",
            "href": "cache&do=tpl",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "-": "-"
        }, {
            "caption": "更新所有分类缓存",
            "href": "cache&do=allcategory",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "caption": "更新文章栏目缓存",
            "href": "cache&do=category",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "caption": "更新推送版块缓存",
            "href": "cache&do=pushcategory",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "caption": "更新标签分类缓存",
            "href": "cache&do=tagcategory",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "caption": "更新属性缓存",
            "href": "cache&acp=prop.admincp",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "caption": "更新内链缓存",
            "href": "cache&acp=keywords.admincp",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "caption": "更新过滤缓存",
            "href": "cache&acp=filter.admincp",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }, {
            "-": "-"
        }, {
            "caption": "重计栏目文章数",
            "href": "cache&do=article_count",
            "icon": "refresh",
            "target": "iPHP_FRAME"
        }]
    }]
}]