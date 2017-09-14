-- 主表
CREATE TABLE `$basic_table` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `catid` smallint(5) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL,
  `title` char(80) NOT NULL default '',
  `style` char(24) NOT NULL default '',
  `thumb` char(100) NOT NULL default '',
  `keywords` char(40) NOT NULL default '',
  `description` char(255) NOT NULL default '',
  `content` text NOT NULL,
  `source` char(100) NOT NULL default '',
  `url` char(100) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL default '0',
  `status` tinyint(2) unsigned NOT NULL default '1',
  `stick` tinyint(1) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `inputtime` int(10) unsigned NOT NULL default '0',
  `updatetime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`)
) ENGINE=InnoDB;


[-tag-]

INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'catid', '栏目', '', '', 1, 6, '/^[0-9]{1,6}$/', '请选择栏目', 'catid', '', 0, 1, 0, 1, 1, 1, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'typeid', '类别', '', '', 0, 0, '', '', 'typeid', '', 0, 1, 0, 1, 1, 2, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'title', '标题', '', 'inputtitle', 1, 200, '', '请输入标题', 'title', '', 1, 1, 0, 1, 1, 4, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'keywords', '关键词', '多关键词之间用空格或者“,”隔开', '', 0, 40, '', '', 'keyword', '', 0, 1, 0, 1, 1, 7, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'description', '摘要', '', '', 0, 0, '', '', 'textarea', '', 0, 1, 0, 1, 0, 10, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'updatetime', '更新时间', '', '', 0, 0, '', '', 'datetime', '', 1, 1, 0, 1, 0, 12, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'content', '内容', '', '', 1, 0, '', '内容不能为空', 'editor', '', 0, 0, 0, 1, 0, 13, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'thumb', '缩略图', '', '', 0, 100, '', '', 'image', '', 0, 1, 0, 0, 0, 14, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'inputtime', '发布时间', '', '', 0, 0, '', '', 'datetime', '', 0, 1, 0, 0, 0, 17, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'groupids_view', '阅读权限', '', '', 0, 100, '', '', 'groupid', '', 0, 0, 0, 1, 0, 19, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'url', 'URL', '', '', 0, 100, '', '', 'text', '', 0, 1, 0, 1, 0, 20, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'listorder', '排序', '', '', 0, 6, '', '', 'number', '', 0, 1, 0, 1, 0, 21, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'template', '内容页模板', '', '', 0, 30, '', '', 'template', '', 0, 0, 0, 0, 0, 23, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'source', '来源', '', 'inputtitle',0, 80, '', '请输入来源', 'text', '', 0, 1, 0, 1, 1, 4, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `pattern`, `errortips`, `formtype`, `maxlength`, `minlength`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'stick', '是否置顶', '', '', '', '请选择是否置顶', 'box', 0, 1, '{"boxtype":"radio","options":{"0":"\\u4e0d\\u7f6e\\u9876","1":"\\u7f6e\\u9876"}}', 0, 1, 0, 0, 1, 24, 0);
[-tag-]
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `islist`, `issystem`, `isunique`, `isbase`, `issearch`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'hits', '点击次数', '', '', 0, 0, '', '', 'number', '', 0, 1, 0, 0, 0, 17, 0);
