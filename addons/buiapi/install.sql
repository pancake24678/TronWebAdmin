CREATE TABLE IF NOT EXISTS `__PREFIX__buiapi_field`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `table` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '表名称',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '字段标题',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '字段名称',
  `field` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '字段名',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '字段类型',
  `length` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '字段长度',
  `default` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '默认值',
  `remark` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '字段备注',
  `field_string` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '只有单选多选才有字段String格式',
  `field_json` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '只有单选多选才有字段JSON格式',
  `rule_add` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '规则添加',
  `rule_edit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '规则修改',
  `is_show` int(1) DEFAULT '1' COMMENT '是否显示',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '字段管理表';


CREATE TABLE IF NOT EXISTS `__PREFIX__buiapi_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '表中文名称',
  `table` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '表名称',
  `desc` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '模型名称',
  `remark` varchar(122) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
  `is_show` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '模型管理表';
