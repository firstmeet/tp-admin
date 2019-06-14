-- MySQL dump 10.13  Distrib 5.5.58, for Linux (x86_64)
--
-- Host: localhost    Database: mfiirr5
-- ------------------------------------------------------
-- Server version	5.5.58-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bfb_ad`
--

DROP TABLE IF EXISTS `bfb_ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_ad` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `count` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_delete` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_ad`
--

LOCK TABLES `bfb_ad` WRITE;
/*!40000 ALTER TABLE `bfb_ad` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_agent_capital`
--

DROP TABLE IF EXISTS `bfb_agent_capital`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_agent_capital` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销商用户id',
  `flow_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '资金流动类型 (10佣金收入 20提现支出)',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `describe` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=120 DEFAULT CHARSET=utf8 COMMENT='分销商资金明细表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_agent_capital`
--

LOCK TABLES `bfb_agent_capital` WRITE;
/*!40000 ALTER TABLE `bfb_agent_capital` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_agent_capital` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_agent_withdraw`
--

DROP TABLE IF EXISTS `bfb_agent_withdraw`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_agent_withdraw` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分销商用户id',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '打款方式 (10微信 20支付宝 30银行卡)',
  `alipay_name` varchar(30) NOT NULL DEFAULT '' COMMENT '支付宝姓名',
  `alipay_account` varchar(30) NOT NULL DEFAULT '' COMMENT '支付宝账号',
  `bank_name` varchar(30) NOT NULL DEFAULT '' COMMENT '开户行名称',
  `bank_account` varchar(30) NOT NULL DEFAULT '' COMMENT '银行开户名',
  `bank_card` varchar(30) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `apply_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '申请状态 (10待审核 20审核通过 30驳回 40已打款)',
  `audit_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '审核时间',
  `reject_reason` varchar(500) NOT NULL DEFAULT '' COMMENT '驳回原因',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='分销商提现明细表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_agent_withdraw`
--

LOCK TABLES `bfb_agent_withdraw` WRITE;
/*!40000 ALTER TABLE `bfb_agent_withdraw` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_agent_withdraw` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_app`
--

DROP TABLE IF EXISTS `bfb_app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_app` (
  `app_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '小程序id',
  `app_name` varchar(50) NOT NULL DEFAULT '' COMMENT '小程序名称',
  `appkey` varchar(50) NOT NULL DEFAULT '' COMMENT '小程序AppID',
  `app_secret` varchar(50) NOT NULL DEFAULT '' COMMENT '小程序AppSecret',
  `mchid` varchar(50) NOT NULL DEFAULT '' COMMENT '微信商户号id',
  `apikey` varchar(255) NOT NULL DEFAULT '' COMMENT '微信支付密钥',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '是否删除0为删除',
  `ver` varchar(30) DEFAULT NULL,
  `image_id` int(10) NOT NULL COMMENT '图片地址',
  `category_style` int(3) unsigned NOT NULL DEFAULT '10' COMMENT '分类页样式',
  `share_title` varchar(10) NOT NULL COMMENT '分享标题',
  `user_id` int(7) DEFAULT NULL COMMENT '所属用户',
  PRIMARY KEY (`app_id`),
  KEY `id` (`app_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10041 DEFAULT CHARSET=utf8 COMMENT='微信小程序记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_app`
--

LOCK TABLES `bfb_app` WRITE;
/*!40000 ALTER TABLE `bfb_app` DISABLE KEYS */;
INSERT INTO `bfb_app` VALUES (10001,'2号小程序',' ',' ',' ',' ',1542961438,1555671024,1,'1.0.0',1073,20,' ',10001);
/*!40000 ALTER TABLE `bfb_app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_app_help`
--

DROP TABLE IF EXISTS `bfb_app_help`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_app_help` (
  `help_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '帮助标题',
  `content` text NOT NULL COMMENT '帮助内容',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序(数字越小越靠前)',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`help_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10008 DEFAULT CHARSET=utf8 COMMENT='微信小程序帮助';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_app_help`
--

LOCK TABLES `bfb_app_help` WRITE;
/*!40000 ALTER TABLE `bfb_app_help` DISABLE KEYS */;
INSERT INTO `bfb_app_help` VALUES (10001,'关于小程序','小程序本身无需下载，无需注册，不占用手机内存，可以跨平台使用，响应迅速，体验接近原生APP。',100,10001,1535942481,1535942481);
/*!40000 ALTER TABLE `bfb_app_help` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_app_navbar`
--

DROP TABLE IF EXISTS `bfb_app_navbar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_app_navbar` (
  `app_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `app_title` varchar(100) NOT NULL DEFAULT '' COMMENT '小程序标题',
  `top_text_color` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '顶部导航文字颜色(10黑色 20白色)',
  `top_background_color` varchar(10) NOT NULL DEFAULT '' COMMENT '顶部导航背景色',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`app_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10003 DEFAULT CHARSET=utf8 COMMENT='微信小程序导航栏设置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_app_navbar`
--

LOCK TABLES `bfb_app_navbar` WRITE;
/*!40000 ALTER TABLE `bfb_app_navbar` DISABLE KEYS */;
INSERT INTO `bfb_app_navbar` VALUES (10001,'平价小超市',10,'#a9c1d6',0,1555733449);
/*!40000 ALTER TABLE `bfb_app_navbar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_app_page`
--

DROP TABLE IF EXISTS `bfb_app_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_app_page` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '页面id',
  `page_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '页面类型(10首页 20自定义页)',
  `page_name` varchar(255) NOT NULL DEFAULT '' COMMENT '页面名称',
  `page_data` longtext NOT NULL COMMENT '页面数据',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '微信小程序id',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '软删除',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`page_id`),
  KEY `app_id` (`app_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10069 DEFAULT CHARSET=utf8 COMMENT='微信小程序diy页面表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_app_page`
--

LOCK TABLES `bfb_app_page` WRITE;
/*!40000 ALTER TABLE `bfb_app_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_app_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_app_prepay_id`
--

DROP TABLE IF EXISTS `bfb_app_prepay_id`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_app_prepay_id` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `prepay_id` varchar(50) NOT NULL DEFAULT '' COMMENT '微信支付prepay_id',
  `can_use_times` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可使用次数',
  `used_times` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '已使用次数',
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态(1已支付)',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `expiry_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='小程序prepay_id记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_app_prepay_id`
--

LOCK TABLES `bfb_app_prepay_id` WRITE;
/*!40000 ALTER TABLE `bfb_app_prepay_id` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_app_prepay_id` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_auth`
--

DROP TABLE IF EXISTS `bfb_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_auth` (
  `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `role_name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名称',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序(数字越小越靠前)',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '主用户ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10108 DEFAULT CHARSET=utf8 COMMENT='商家用户角色表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_auth`
--

LOCK TABLES `bfb_auth` WRITE;
/*!40000 ALTER TABLE `bfb_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_auth_group`
--

DROP TABLE IF EXISTS `bfb_auth_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_auth_group` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `role_id` int(7) NOT NULL COMMENT '权限组id',
  `app_id` int(7) NOT NULL COMMENT '小程序id',
  `user_id` int(7) NOT NULL COMMENT '用户id',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`,`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_auth_group`
--

LOCK TABLES `bfb_auth_group` WRITE;
/*!40000 ALTER TABLE `bfb_auth_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_auth_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_auth_list`
--

DROP TABLE IF EXISTS `bfb_auth_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_auth_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  `access_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家用户角色权限关系表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_auth_list`
--

LOCK TABLES `bfb_auth_list` WRITE;
/*!40000 ALTER TABLE `bfb_auth_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_auth_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_brand`
--

DROP TABLE IF EXISTS `bfb_brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_brand` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '品牌表',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '品牌名称',
  `logo` varchar(80) NOT NULL DEFAULT '' COMMENT '品牌logo',
  `desc` text NOT NULL COMMENT '品牌描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '品牌地址',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `cat_name` varchar(128) DEFAULT '' COMMENT '品牌分类',
  `parent_cat_id` int(11) DEFAULT '0' COMMENT '分类id',
  `cat_id` int(10) DEFAULT '0' COMMENT '分类id',
  `is_hot` tinyint(1) DEFAULT '0' COMMENT '是否推荐',
  `app_id` int(8) NOT NULL COMMENT '小程序ID',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_brand`
--

LOCK TABLES `bfb_brand` WRITE;
/*!40000 ALTER TABLE `bfb_brand` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_card`
--

DROP TABLE IF EXISTS `bfb_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_card` (
  `card_id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '卡名称',
  `user_id` int(7) DEFAULT NULL COMMENT '用户Id',
  `card` varchar(20) DEFAULT NULL COMMENT '卡号',
  `key` varchar(30) DEFAULT NULL COMMENT '卡类型',
  `create_time` int(11) DEFAULT NULL COMMENT '领卡时间',
  `app_id` int(7) DEFAULT NULL COMMENT '小程序Id',
  `nickName` varchar(48) NOT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_card`
--

LOCK TABLES `bfb_card` WRITE;
/*!40000 ALTER TABLE `bfb_card` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_category`
--

DROP TABLE IF EXISTS `bfb_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品分类id',
  `name` varchar(90) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '商品分类名称',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `sort` tinyint(1) unsigned NOT NULL DEFAULT '50' COMMENT '顺序排序',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `image` varchar(512) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '分类图片',
  `app_id` int(8) NOT NULL COMMENT '小程序ID',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_category`
--

LOCK TABLES `bfb_category` WRITE;
/*!40000 ALTER TABLE `bfb_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_ceshi`
--

DROP TABLE IF EXISTS `bfb_ceshi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_ceshi` (
  `id` int(7) NOT NULL,
  `name` varchar(220) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_ceshi`
--

LOCK TABLES `bfb_ceshi` WRITE;
/*!40000 ALTER TABLE `bfb_ceshi` DISABLE KEYS */;
INSERT INTO `bfb_ceshi` VALUES (2,'name2'),(3,'name3'),(4,'name4'),(2,'name2'),(3,'name3'),(4,'name4');
/*!40000 ALTER TABLE `bfb_ceshi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_comment`
--

DROP TABLE IF EXISTS `bfb_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '评价id',
  `score` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '评分 (10好评 20中评 30差评)',
  `content` text NOT NULL COMMENT '评价内容',
  `is_picture` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为图片评价',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '评价排序',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态(0隐藏 1显示)',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `order_goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单商品id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `is_delete` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '软删除',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='订单评价记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_comment`
--

LOCK TABLES `bfb_comment` WRITE;
/*!40000 ALTER TABLE `bfb_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_coupon`
--

DROP TABLE IF EXISTS `bfb_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_coupon` (
  `coupon_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '优惠券id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '优惠券名称',
  `color` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '优惠券颜色(10蓝 20红 30紫 40黄)',
  `coupon_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '优惠券类型(10满减券 20折扣券)',
  `reduce_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '满减券-减免金额',
  `discount` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '折扣券-折扣率(0-100)',
  `min_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最低消费金额',
  `expire_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '到期类型(10领取后生效 20固定时间)',
  `expire_day` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '领取后生效-有效天数',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '固定时间-开始时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '固定时间-结束时间',
  `apply_range` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '适用范围(10全部商品 20指定商品)',
  `total_num` int(11) NOT NULL DEFAULT '0' COMMENT '发放总数量(-1为不限制)',
  `receive_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '已领取数量',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序方式(数字越小越靠前)',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '软删除',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`coupon_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_coupon`
--

LOCK TABLES `bfb_coupon` WRITE;
/*!40000 ALTER TABLE `bfb_coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_coupon_goods`
--

DROP TABLE IF EXISTS `bfb_coupon_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_coupon_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='优惠券指定商品记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_coupon_goods`
--

LOCK TABLES `bfb_coupon_goods` WRITE;
/*!40000 ALTER TABLE `bfb_coupon_goods` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_coupon_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_delivery`
--

DROP TABLE IF EXISTS `bfb_delivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_delivery` (
  `delivery_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '模板id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '模板名称',
  `method` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '计费方式(10按件数 20按重量)',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序d',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序方式(数字越小越靠前)',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`delivery_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10080 DEFAULT CHARSET=utf8 COMMENT='配送模板主表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_delivery`
--

LOCK TABLES `bfb_delivery` WRITE;
/*!40000 ALTER TABLE `bfb_delivery` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_delivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_delivery_rule`
--

DROP TABLE IF EXISTS `bfb_delivery_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_delivery_rule` (
  `rule_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id',
  `delivery_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '配送模板id',
  `region` text NOT NULL COMMENT '可配送区域(城市id集)',
  `first` double unsigned NOT NULL DEFAULT '0' COMMENT '首件(个)/首重(Kg)',
  `first_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '运费(元)',
  `additional` double unsigned NOT NULL DEFAULT '0' COMMENT '续件/续重',
  `additional_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '续费(元)',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`rule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='配送模板区域及运费表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_delivery_rule`
--

LOCK TABLES `bfb_delivery_rule` WRITE;
/*!40000 ALTER TABLE `bfb_delivery_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_delivery_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_express`
--

DROP TABLE IF EXISTS `bfb_express`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_express` (
  `express_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '物流公司id',
  `express_name` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司名称',
  `express_code` varchar(30) NOT NULL DEFAULT '' COMMENT '物流公司代码 (快递100)',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序 (数字越小越靠前)',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`express_id`),
  KEY `express_code` (`express_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物流公司记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_express`
--

LOCK TABLES `bfb_express` WRITE;
/*!40000 ALTER TABLE `bfb_express` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_express` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_game`
--

DROP TABLE IF EXISTS `bfb_game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_game` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(7) NOT NULL COMMENT '所属app',
  `title` varchar(200) NOT NULL COMMENT '活动标题',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `type` int(7) NOT NULL COMMENT '1为大转盘,',
  `start_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL,
  `details` text NOT NULL COMMENT '活动详情',
  `setnum` int(5) NOT NULL COMMENT '中奖次数',
  `setup` int(1) NOT NULL COMMENT '1为每日中奖次数,0为本轮活动中奖次数',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '默认活动',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_game`
--

LOCK TABLES `bfb_game` WRITE;
/*!40000 ALTER TABLE `bfb_game` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_game` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_images`
--

DROP TABLE IF EXISTS `bfb_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_images` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片id 自增',
  `image_id` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `app_id` int(8) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `pid` int(10) DEFAULT NULL COMMENT '关联id',
  `type` varchar(30) DEFAULT NULL COMMENT '类型 如：产品item,评论comment',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_images`
--

LOCK TABLES `bfb_images` WRITE;
/*!40000 ALTER TABLE `bfb_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_integral`
--

DROP TABLE IF EXISTS `bfb_integral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_integral` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `app_id` int(7) NOT NULL,
  `user_id` int(7) NOT NULL,
  `create_time` int(11) NOT NULL,
  `give_integral` int(7) NOT NULL DEFAULT '0' COMMENT '积分',
  `note` text NOT NULL COMMENT '收入记录',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_integral`
--

LOCK TABLES `bfb_integral` WRITE;
/*!40000 ALTER TABLE `bfb_integral` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_integral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_item`
--

DROP TABLE IF EXISTS `bfb_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_item` (
  `goods_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `sales_initial` int(6) DEFAULT '0' COMMENT '初始销量',
  `goods_name` varchar(120) NOT NULL DEFAULT '' COMMENT '商品名称',
  `click` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击数',
  `brand_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '品牌id',
  `store_count` smallint(5) unsigned NOT NULL DEFAULT '10' COMMENT '库存数量',
  `comment_count` smallint(5) DEFAULT '0' COMMENT '商品评论数',
  `weight` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品重量克为单位',
  `goods_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '商品简单描述',
  `goods_content` text COMMENT '商品详细描述',
  `is_real` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否为实物',
  `is_on_sale` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架',
  `is_free_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否包邮0否1是',
  `sort` smallint(4) unsigned NOT NULL DEFAULT '50' COMMENT '商品排序',
  `is_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否新品',
  `is_hot` tinyint(1) DEFAULT '0' COMMENT '是否热卖',
  `coupon` int(1) NOT NULL DEFAULT '0' COMMENT '0为不支持1为支持',
  `vip` int(1) NOT NULL DEFAULT '0' COMMENT '0为不支持1为支持',
  `goods_type` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '商品所属类型id，取值表goods_type的cat_id',
  `spec_type` smallint(5) DEFAULT '0' COMMENT '商品规格类型，取值表goods_type的cat_id',
  `give_integral` mediumint(8) DEFAULT '0' COMMENT '购买商品赠送积分',
  `exchange_integral` int(10) NOT NULL DEFAULT '0' COMMENT '积分兑换：0不参与积分兑换，积分和现金的兑换比例100：1',
  `sales_sum` int(11) DEFAULT '0' COMMENT '商品销量',
  `prom_type` tinyint(1) DEFAULT '0' COMMENT '0 普通订单,1 限时抢购, 2 团购 , 3 促销优惠',
  `prom_id` int(11) DEFAULT '0' COMMENT '优惠活动id',
  `app_id` int(8) NOT NULL COMMENT '小程序ID',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `delivery_id` int(6) DEFAULT '0' COMMENT '运费模板id',
  `discount` float NOT NULL COMMENT '折扣',
  `agent_type` int(2) NOT NULL DEFAULT '1' COMMENT '(1百分比2固定)',
  `agent_price` varchar(20) NOT NULL COMMENT '分销金额',
  PRIMARY KEY (`goods_id`),
  KEY `cat_id` (`cat_id`),
  KEY `brand_id` (`brand_id`),
  KEY `goods_number` (`store_count`),
  KEY `goods_weight` (`weight`),
  KEY `sort_order` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_item`
--

LOCK TABLES `bfb_item` WRITE;
/*!40000 ALTER TABLE `bfb_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_item_attr`
--

DROP TABLE IF EXISTS `bfb_item_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_item_attr` (
  `goods_attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品属性id自增',
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `attr_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '属性id',
  `attr_value` text NOT NULL COMMENT '属性值',
  `attr_price` varchar(255) NOT NULL DEFAULT '' COMMENT '属性价格',
  `app_id` int(8) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`goods_attr_id`),
  KEY `goods_id` (`goods_id`),
  KEY `attr_id` (`attr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_item_attr`
--

LOCK TABLES `bfb_item_attr` WRITE;
/*!40000 ALTER TABLE `bfb_item_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_item_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_item_attribute`
--

DROP TABLE IF EXISTS `bfb_item_attribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_item_attribute` (
  `attr_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性id',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '属性名称',
  `type_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '属性分类id',
  `values` text NOT NULL COMMENT '可选值列表',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '属性排序',
  `app_id` int(8) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`attr_id`),
  KEY `cat_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_item_attribute`
--

LOCK TABLES `bfb_item_attribute` WRITE;
/*!40000 ALTER TABLE `bfb_item_attribute` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_item_attribute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_item_images`
--

DROP TABLE IF EXISTS `bfb_item_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_item_images` (
  `img_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片id 自增',
  `image_id` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `app_id` int(8) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `item_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`img_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_item_images`
--

LOCK TABLES `bfb_item_images` WRITE;
/*!40000 ALTER TABLE `bfb_item_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_item_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_item_type`
--

DROP TABLE IF EXISTS `bfb_item_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_item_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id自增',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '类型名称',
  `app_id` int(8) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_item_type`
--

LOCK TABLES `bfb_item_type` WRITE;
/*!40000 ALTER TABLE `bfb_item_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_item_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_login_log`
--

DROP TABLE IF EXISTS `bfb_login_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `create_time` int(11) NOT NULL COMMENT '登录时间',
  `ip` varchar(30) NOT NULL COMMENT '登录用户名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_login_log`
--

LOCK TABLES `bfb_login_log` WRITE;
/*!40000 ALTER TABLE `bfb_login_log` DISABLE KEYS */;
INSERT INTO `bfb_login_log` VALUES (1,10001,1555474050,'171.43.214.203'),(2,10001,1555474053,'171.43.214.203'),(3,10001,1555474073,'171.43.214.203'),(4,10001,1555474455,'171.43.214.203'),(5,10001,1555474832,'171.43.214.203'),(6,10001,1555474839,'171.43.214.203'),(7,10001,1555475367,'171.43.214.203'),(8,10001,1555480530,'42.243.58.144'),(9,10001,1555480559,'42.243.58.144'),(10,10001,1555480987,'58.48.189.110'),(11,10001,1555482377,'171.43.214.203'),(12,10065,1555482471,'171.43.214.203'),(13,10001,1555482483,'171.43.214.203'),(14,10001,1555482576,'115.216.125.206'),(15,10086,1555482760,'171.43.214.203'),(16,10088,1555482785,'171.43.214.203'),(17,10001,1555482833,'171.43.214.203'),(18,10065,1555482983,'171.43.214.203'),(19,10086,1555482998,'171.43.214.203'),(20,10001,1555483006,'171.43.214.203'),(21,10001,1555483071,'171.43.214.203'),(22,10089,1555483835,'171.43.214.203'),(23,10089,1555483835,'171.43.214.203'),(24,10001,1555483849,'115.216.125.206'),(25,10001,1555485304,'171.43.214.203'),(26,10001,1555486736,'58.48.189.110'),(27,10001,1555490340,'171.43.214.203'),(28,10001,1555490813,'171.43.214.203'),(29,10001,1555492233,'171.43.214.203'),(30,10001,1555492972,'42.243.58.144'),(31,10001,1555493654,'171.43.214.203'),(32,10090,1555493801,'121.19.245.65'),(33,10091,1555494149,'1.180.115.81'),(34,10001,1555494282,'114.216.221.42'),(35,10001,1555494363,'114.216.221.42'),(36,10092,1555494381,'114.216.221.42'),(37,10093,1555495339,'171.43.214.203'),(38,10001,1555496686,'171.43.214.203'),(39,10001,1555498021,'171.43.214.203'),(40,10001,1555498031,'171.43.214.203'),(41,10001,1555498084,'171.43.214.203'),(42,10093,1555498140,'171.43.214.203'),(43,10001,1555552010,'127.0.0.1'),(44,10094,1555555259,'127.0.0.1'),(45,10094,1555555523,'127.0.0.1'),(46,10094,1555555571,'127.0.0.1'),(47,10001,1555555597,'127.0.0.1'),(48,10001,1555557268,'127.0.0.1'),(49,10001,1555561928,'127.0.0.1'),(50,10001,1555571207,'127.0.0.1'),(51,10001,1555571315,'127.0.0.1'),(52,10001,1555571376,'127.0.0.1'),(53,10001,1555571721,'127.0.0.1'),(54,10001,1555571794,'127.0.0.1'),(55,10001,1555572609,'127.0.0.1'),(56,10001,1555572708,'127.0.0.1'),(57,10001,1555575893,'127.0.0.1'),(58,10001,1555576139,'127.0.0.1'),(59,10001,1555576384,'127.0.0.1'),(60,10001,1555576481,'127.0.0.1'),(61,10001,1555576752,'127.0.0.1'),(62,10001,1555576982,'127.0.0.1'),(63,10001,1555577204,'127.0.0.1'),(64,10001,1555577584,'127.0.0.1'),(65,10104,1555577802,'127.0.0.1'),(66,10104,1555577928,'127.0.0.1'),(67,10104,1555577969,'127.0.0.1'),(68,10104,1555578007,'127.0.0.1'),(69,10001,1555578033,'127.0.0.1'),(70,10104,1555578060,'127.0.0.1'),(71,10001,1555578085,'127.0.0.1'),(72,10104,1555579680,'127.0.0.1'),(73,10001,1555579706,'127.0.0.1'),(74,10104,1555580433,'127.0.0.1'),(75,10001,1555580450,'127.0.0.1'),(76,10104,1555580749,'127.0.0.1'),(77,10001,1555580763,'127.0.0.1'),(78,10104,1555580867,'127.0.0.1'),(79,10104,1555581324,'127.0.0.1'),(80,10104,1555581554,'127.0.0.1'),(81,10001,1555582563,'127.0.0.1'),(82,10104,1555582886,'127.0.0.1'),(83,10001,1555582904,'127.0.0.1'),(84,10105,1555583013,'127.0.0.1'),(85,10001,1555583132,'127.0.0.1'),(86,10104,1555583320,'127.0.0.1'),(87,10001,1555583434,'127.0.0.1'),(88,10104,1555583652,'127.0.0.1'),(89,10104,1555584342,'127.0.0.1'),(90,10001,1555584795,'127.0.0.1'),(91,10001,1555585307,'127.0.0.1'),(92,10001,1555636985,'127.0.0.1'),(93,10105,1555644814,'127.0.0.1'),(94,10001,1555644829,'127.0.0.1'),(95,10105,1555644855,'127.0.0.1'),(96,10001,1555644953,'127.0.0.1'),(97,10106,1555645079,'127.0.0.1'),(98,10106,1555645337,'127.0.0.1'),(99,10001,1555645363,'127.0.0.1'),(100,10107,1555645532,'127.0.0.1'),(101,10001,1555645598,'127.0.0.1'),(102,10001,1555647484,'127.0.0.1'),(103,10001,1555656749,'127.0.0.1'),(104,10106,1555656799,'127.0.0.1'),(105,10001,1555656828,'127.0.0.1'),(106,10106,1555656859,'127.0.0.1'),(107,10106,1555656905,'127.0.0.1'),(108,10106,1555656962,'127.0.0.1'),(109,10001,1555657043,'127.0.0.1'),(110,10108,1555657642,'127.0.0.1'),(111,10106,1555657676,'127.0.0.1'),(112,10001,1555657687,'127.0.0.1'),(113,10106,1555657713,'127.0.0.1'),(114,10108,1555657735,'127.0.0.1'),(115,10001,1555657783,'127.0.0.1'),(116,10109,1555657909,'127.0.0.1'),(117,10001,1555657919,'127.0.0.1'),(118,10001,1555664385,'127.0.0.1'),(119,10001,1555666560,'127.0.0.1'),(120,10001,1555669151,'171.43.214.203'),(121,10001,1555671361,'171.43.214.203'),(122,10001,1555727612,'171.43.250.111'),(123,10001,1555728831,'171.43.250.111'),(124,10001,1555737083,'171.43.250.111'),(125,10001,1555737083,'171.43.250.111'),(126,10001,1555737210,'171.43.250.111'),(127,10001,1555746416,'171.43.250.111'),(128,10001,1555895833,'171.43.250.111'),(129,10001,1555898133,'171.43.250.111'),(130,10001,0,'171.43.250.111'),(131,10001,0,'171.43.250.111'),(132,10109,0,'171.43.250.111'),(133,10109,0,'171.43.250.111'),(134,10109,0,'171.43.250.111'),(135,10001,0,'171.43.250.111'),(136,10001,0,'171.43.250.111'),(137,10001,0,'171.43.250.111');
/*!40000 ALTER TABLE `bfb_login_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_menu`
--

DROP TABLE IF EXISTS `bfb_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '权限名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '权限url',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序(数字越小越靠前)',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `model` varchar(255) DEFAULT NULL COMMENT '模块',
  `icon` varchar(255) DEFAULT NULL COMMENT 'icom图标',
  `app_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100254 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商家用户权限表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_menu`
--

LOCK TABLES `bfb_menu` WRITE;
/*!40000 ALTER TABLE `bfb_menu` DISABLE KEYS */;
INSERT INTO `bfb_menu` VALUES (10001,'首页','user/index/index',0,1,1540628721,1540781975,'index','mdi-home',10001),(10002,'会员','user/user/index',0,9,1540628721,1540628721,'user','mdi-account',10001),(10003,'商品','user/item/index',0,3,1540628721,1540628721,'item','mdi-gift',10001),(10004,'订单','user/order/deliverylist',0,4,1540628721,1540628721,'order','mdi-clipboard-text',10001),(10005,'营销','user/market/coupon',0,2,1540628721,1540628721,'market','mdi-dropbox',10001),(10006,'应用','user/app/setting',0,5,1540628721,1540628721,'app','mdi-bank',10001),(10007,'模板','user/tpl/index',0,6,1540628721,1540628721,'tpl','mdi-camera',10001),(10009,'权限','user/auth/userindex',0,10,1540628721,1540628721,'auth','mdi-clipboard-account',10001),(10012,'会员卡','user/user/index',10002,100,0,0,'user',NULL,10001),(10016,'用户列表','user/user/index',10012,100,0,0,'user',NULL,10001),(10017,'冻结列表','user/user/frozen',10012,100,0,0,'user',NULL,10001),(10018,'推荐','user/user/index',10013,100,0,0,'user',NULL,10001),(100015,'商品设置','user/item/index',10003,100,0,0,'item',NULL,10001),(100016,'商品分类','user/item/categorylist',100015,100,0,0,'item',NULL,10001),(100020,'商品列表','user/item/index',100015,100,0,0,'item',NULL,10001),(100024,'商品类型','user/item/itemtype',100015,100,0,0,'item',NULL,10001),(100028,'商品规格','user/item/speclist',100015,100,0,0,'item',NULL,10001),(100032,'商品属性','user/item/attrbute',100015,100,0,0,'item',NULL,10001),(100036,'品牌列表','user/item/brand',100015,100,0,0,'item',NULL,10001),(100040,'商品评论','user/item/comment',100015,100,0,0,'item',NULL,10001),(100017,'新增/编辑','user/item/editcategory',100016,100,0,0,'item',NULL,10001),(100019,'删除','user/item/item/deletecategory',100016,100,0,0,'item',NULL,10001),(100021,'新增/编辑','user/item/edit',100020,100,0,0,'item',NULL,10001),(100023,'删除','user/item/delete',100020,100,0,0,'item',NULL,10001),(100025,'新增/编辑','user/item/savetype',100024,100,0,0,'item',NULL,10001),(100027,'删除','user/setting/delete',100024,100,0,0,'item',NULL,10001),(100029,'新增/修改','user/item/savespec',100028,100,0,0,'item',NULL,10001),(100031,'删除','user/setting/delete',100028,100,0,0,'item',NULL,10001),(100033,'新增/编辑','user/item/saveattrbute',100032,100,0,0,'item',NULL,10001),(100035,'删除','user/setting/delete',100032,100,0,0,'item',NULL,10001),(100037,'新增/编辑','user/item/savebrand',100036,100,0,0,'item',NULL,10001),(100039,'删除','user/setting/delete',100036,100,0,0,'item',NULL,10001),(100041,'编辑','user/item/detail',100040,100,0,0,'item',NULL,10001),(100042,'删除','user/Market/delete',100040,100,0,0,'item',NULL,10001),(100043,'订单设置','user/order/deliverylist',10004,100,0,0,'order',NULL,10001),(100044,'待发货','user/order/deliverylist',100043,100,0,0,'order',NULL,10001),(100234,'全部订单','user/order/alllist',100043,100,0,0,'order',NULL,10001),(100149,'待收货','user/order/receiptList',100043,100,0,0,'order',NULL,10001),(100233,'已取消','user/order/cancellist',100043,100,0,0,'order',NULL,10001),(100154,'待付款','user/order/paylist',100043,100,0,0,'order',NULL,10001),(100156,'已完成','user/order/completelist',100043,100,0,0,'order',NULL,10001),(100155,'订单导出','user/operate/export',100154,100,0,0,'order',NULL,10001),(100157,'订单导出','user/operate/export',100156,100,0,0,'order',NULL,10001),(100179,'优惠券','user/market/coupon',10005,100,0,0,'market',NULL,10001),(100180,'优惠券列表','user/market/coupon',100179,100,0,0,'market',NULL,10001),(100184,'领取记录','user/market/receivecoupon',100179,100,0,0,'market',NULL,10001),(100055,'新增/编辑','user/market/editassemble',100054,100,0,0,'market',NULL,10001),(100057,'删除','user/Market/delete',100054,100,0,0,'market',NULL,10001),(100059,'新增/编辑','user/market/editspike',100058,100,0,0,'market',NULL,10001),(100061,'删除','user/Market/delete',100058,100,0,0,'market',NULL,10001),(100063,'新增/编辑','user/market/editrobbuy',100062,100,0,0,'market',NULL,10001),(100065,'删除','user/Market/delete',100062,100,0,0,'market',NULL,10001),(100067,'新增/编辑','user/market/editpromgoods',100066,100,0,0,'market',NULL,10001),(100069,'删除','user/Market/deleteprmogoods',100066,100,0,0,'market',NULL,10001),(100071,'新增/编辑','user/market/editpromorder',100070,100,0,0,'market',NULL,10001),(100073,'删除','user/Market/delete',100070,100,0,0,'market',NULL,10001),(100181,'新增/编辑','user/market/editcoupon',100180,100,0,0,'market',NULL,10001),(100183,'删除','user/market/delete',100180,100,0,0,'market',NULL,10001),(100080,'应用','user/app/setting',10006,100,0,0,'app',NULL,10001),(100082,'提交','user/app/setting',100081,100,0,0,'app',NULL,10001),(100083,'页面管理','user/tpl/index',10007,100,0,0,'tpl',NULL,10001),(100084,'页面设计','user/tpl/index',100083,100,0,0,'tpl',NULL,10001),(100089,'分类模板','user/tpl/category',100083,100,0,0,'tpl',NULL,10001),(100235,'顶部设置','user/tpl/tabbar',100083,100,0,0,'tpl',NULL,10001),(100085,'新增/编辑','user/tpl/edit',100084,100,0,0,'tpl',NULL,10001),(100087,'删除','user/tpl/delete',100084,100,0,0,'tpl',NULL,10001),(100088,'设为首页','user/tpl/sethome',100084,100,0,0,'tpl',NULL,10001),(100090,'提交','user/tpl/category',100089,100,0,0,'tpl',NULL,10001),(100204,'权限设置','user/auth/userindex',10009,100,1540628721,1540628721,'auth',NULL,10001),(100205,'管理员列表','user/auth/userindex',100204,100,1540628721,1540628721,'auth',NULL,10001),(100209,'角色管理','user/auth/roleindex',100204,100,1540628721,1540628721,'auth',NULL,10001),(100206,'新增/编辑','user/auth/edituser',100205,100,1540628721,1540628721,'auth',NULL,10001),(100208,'删除','user/auth/deleteuser',100205,100,1540628721,1540628721,'auth',NULL,10001),(100241,'新增/编辑','user/auth/editrole',100209,100,0,0,'auth',NULL,10001),(100242,'删除','user/auth/roledelete',100209,100,0,0,'auth',NULL,10001),(100203,'网站设置','user/setting/index',100210,100,0,0,'setting',NULL,10001),(100211,'配送设置','user/setting/delivery',100203,100,0,0,'setting',NULL,10001),(100213,'物流公司','user/setting/express',100203,100,0,0,'setting',NULL,10001),(100217,'分销海报','user/setting/qrcode',100203,100,0,0,'setting',NULL,10001),(100225,'清理缓存','user/setting/clear',100203,100,0,0,'setting',NULL,10001),(100223,'分销设置','user/setting/agent',100203,100,0,0,'setting',NULL,10001),(100245,'增加/编辑','user/setting/addexpress',100213,100,0,0,'setting',NULL,10001),(100243,'增加/编辑','user/setting/edit',100211,100,0,0,'setting',NULL,10001),(100244,'删除','user/setting/delete',100211,100,0,0,'setting',NULL,10001),(100210,'设置','user/setting/index',0,7,1540628721,1540628721,'setting','mdi-brightness-5',10001),(100246,'删除','setting/delete',100213,100,0,0,'setting',NULL,10001),(100247,'售后分佣','user/order/deliverylist',10004,100,0,0,'order',NULL,10001),(100249,'待处理售后','user/order/etcsaleList',100247,100,0,0,'order',NULL,10001),(100250,'已处理售后','user/order/insaleList',100247,100,0,0,'order',NULL,10001),(100251,'分佣列表','user/order/gold',100247,100,0,0,'order',NULL,10001);
/*!40000 ALTER TABLE `bfb_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_order`
--

DROP TABLE IF EXISTS `bfb_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `order_no` varchar(20) NOT NULL DEFAULT '' COMMENT '订单号',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单金额(不含运费)',
  `coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `coupon_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠券抵扣金额',
  `pay_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际付款金额(包含运费)',
  `update_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '后台修改的订单金额（差价）',
  `buyer_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '买家留言',
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '付款状态(10未付款 20已付款)',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `express_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '运费金额',
  `delivery_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '发货状态(10未发货 20已发货)',
  `receipt_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '收货状态(10未收货 20已收货)',
  `order_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '订单状态(10进行中 20取消 30已完成)',
  `transaction_id` varchar(30) NOT NULL DEFAULT '' COMMENT '微信支付交易号',
  `is_comment` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否已评价(0否 1是)',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `prom_type` int(2) NOT NULL COMMENT '订单分类：拼团，秒杀，抢购',
  `prom_num` int(2) NOT NULL COMMENT '参团人数',
  `prom_statis` int(2) NOT NULL COMMENT '拼团状态：1为待拼团2位成功',
  `prom_time` int(3) NOT NULL COMMENT '拼团限时',
  `rebate` float NOT NULL COMMENT '折扣',
  `item_id` int(8) NOT NULL COMMENT '商品id',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `give_integral` int(7) NOT NULL DEFAULT '0' COMMENT '积分',
  `sub_status` int(7) NOT NULL COMMENT '售后状态10为申请20为已处理',
  `sub_type` int(7) NOT NULL COMMENT '售后方式1为退款2为换货',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_no` (`order_no`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_order`
--

LOCK TABLES `bfb_order` WRITE;
/*!40000 ALTER TABLE `bfb_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_order_address`
--

DROP TABLE IF EXISTS `bfb_order_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_order_address` (
  `order_address_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '地址id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `province_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在省份id',
  `city_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在城市id',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在区id',
  `detail` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`order_address_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单收货地址记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_order_address`
--

LOCK TABLES `bfb_order_address` WRITE;
/*!40000 ALTER TABLE `bfb_order_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_order_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_order_delivery`
--

DROP TABLE IF EXISTS `bfb_order_delivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_order_delivery` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) NOT NULL COMMENT '订单号',
  `express_id` int(11) NOT NULL COMMENT '物流公司id',
  `express_no` varchar(50) NOT NULL COMMENT '物流单号',
  `company` varchar(50) NOT NULL COMMENT '物流公司',
  `type` int(2) NOT NULL COMMENT '1卖家发货2买家退货',
  `app_id` int(7) NOT NULL,
  `create_time` int(11) NOT NULL,
  `receipt_time` int(11) NOT NULL COMMENT '收货时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_order_delivery`
--

LOCK TABLES `bfb_order_delivery` WRITE;
/*!40000 ALTER TABLE `bfb_order_delivery` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_order_delivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_order_goods`
--

DROP TABLE IF EXISTS `bfb_order_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_order_goods` (
  `order_goods_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `image` varchar(150) NOT NULL DEFAULT '0' COMMENT '商品封面图id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '20' COMMENT '库存计算方式(10下单减库存 20付款减库存)',
  `spec_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '规格类型(10单规格 20多规格)',
  `spec_sku_id` varchar(255) NOT NULL DEFAULT '' COMMENT '商品sku标识',
  `item_sku_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品规格id',
  `goods_attr` varchar(500) NOT NULL DEFAULT '' COMMENT '商品规格信息',
  `content` longtext NOT NULL COMMENT '商品详情',
  `goods_no` varchar(100) NOT NULL DEFAULT '' COMMENT '商品编码',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `line_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品划线价',
  `goods_weight` double unsigned NOT NULL DEFAULT '0' COMMENT '商品重量(Kg)',
  `total_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品总价(数量×单价)',
  `total_pay_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际付款价(折扣和优惠后)',
  `is_comment` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否已评价(0否 1是)',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`order_goods_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单商品记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_order_goods`
--

LOCK TABLES `bfb_order_goods` WRITE;
/*!40000 ALTER TABLE `bfb_order_goods` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_order_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_prize`
--

DROP TABLE IF EXISTS `bfb_prize`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_prize` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '奖品名称',
  `app_id` int(7) NOT NULL COMMENT '所属app',
  `game_id` int(7) NOT NULL COMMENT '对应活动id',
  `number` int(7) NOT NULL COMMENT '奖品数量',
  `ratio` int(7) NOT NULL COMMENT '中奖率',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `original_img` varchar(255) NOT NULL COMMENT '图片路径',
  `is_state` int(1) NOT NULL COMMENT '是否中奖商品',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_prize`
--

LOCK TABLES `bfb_prize` WRITE;
/*!40000 ALTER TABLE `bfb_prize` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_prize` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_prize_list`
--

DROP TABLE IF EXISTS `bfb_prize_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_prize_list` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `user_id` int(7) NOT NULL COMMENT '用户ID',
  `app_id` int(7) NOT NULL COMMENT '应用id',
  `game_id` int(7) NOT NULL COMMENT '参与活动id',
  `prize_id` int(7) NOT NULL COMMENT '奖品id',
  `update_time` int(11) NOT NULL COMMENT '创建时间',
  `create_time` int(11) NOT NULL COMMENT '修改时间',
  `state` int(1) NOT NULL COMMENT '0为参与',
  `phone` varchar(20) NOT NULL,
  `prize_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_prize_list`
--

LOCK TABLES `bfb_prize_list` WRITE;
/*!40000 ALTER TABLE `bfb_prize_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_prize_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_profit_log`
--

DROP TABLE IF EXISTS `bfb_profit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_profit_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id (买家)',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `order_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单总金额(不含运费)',
  `proportion` float(5,2) NOT NULL COMMENT '分成比例',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `profit_money` float(7,2) NOT NULL COMMENT '分成收益',
  `graded` int(1) NOT NULL COMMENT '分成级别',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商订单记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_profit_log`
--

LOCK TABLES `bfb_profit_log` WRITE;
/*!40000 ALTER TABLE `bfb_profit_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_profit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_prom_goods`
--

DROP TABLE IF EXISTS `bfb_prom_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_prom_goods` (
  `prom_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '活动ID',
  `name` varchar(60) NOT NULL COMMENT '促销活动名称',
  `type` int(2) NOT NULL DEFAULT '0' COMMENT '促销类型',
  `expression` varchar(100) NOT NULL COMMENT '优惠体现',
  `description` text COMMENT '活动描述',
  `start_time` int(11) NOT NULL COMMENT '活动开始时间',
  `end_time` int(11) NOT NULL COMMENT '活动结束时间',
  `is_close` tinyint(1) DEFAULT '0',
  `group` varchar(255) DEFAULT NULL COMMENT '适用范围',
  `prom_img` varchar(150) DEFAULT NULL COMMENT '活动宣传图片',
  `app_id` int(5) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`prom_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_prom_goods`
--

LOCK TABLES `bfb_prom_goods` WRITE;
/*!40000 ALTER TABLE `bfb_prom_goods` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_prom_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_prom_order`
--

DROP TABLE IF EXISTS `bfb_prom_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_prom_order` (
  `prom_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT '活动名称',
  `type` int(2) NOT NULL DEFAULT '0' COMMENT '活动类型',
  `money` float(10,2) DEFAULT '0.00' COMMENT '最小金额',
  `expression` varchar(100) DEFAULT NULL COMMENT '优惠体现',
  `description` text COMMENT '活动描述',
  `start_time` int(11) DEFAULT NULL COMMENT '活动开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '活动结束时间',
  `is_close` tinyint(1) DEFAULT '0',
  `group` varchar(255) DEFAULT NULL COMMENT '适用范围',
  `app_id` int(11) NOT NULL,
  PRIMARY KEY (`prom_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_prom_order`
--

LOCK TABLES `bfb_prom_order` WRITE;
/*!40000 ALTER TABLE `bfb_prom_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_prom_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_region`
--

DROP TABLE IF EXISTS `bfb_region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) DEFAULT NULL COMMENT '父id',
  `shortname` varchar(100) DEFAULT NULL COMMENT '简称',
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `merger_name` varchar(255) DEFAULT NULL COMMENT '全称',
  `level` tinyint(4) unsigned DEFAULT '0' COMMENT '层级 1 2 3 省市区县',
  `pinyin` varchar(100) DEFAULT NULL COMMENT '拼音',
  `code` varchar(100) DEFAULT NULL COMMENT '长途区号',
  `zip_code` varchar(100) DEFAULT NULL COMMENT '邮编',
  `first` varchar(50) DEFAULT NULL COMMENT '首字母',
  `lng` varchar(100) DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) DEFAULT NULL COMMENT '纬度',
  PRIMARY KEY (`id`),
  KEY `name,level` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_region`
--

LOCK TABLES `bfb_region` WRITE;
/*!40000 ALTER TABLE `bfb_region` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_region` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_robbuy`
--

DROP TABLE IF EXISTS `bfb_robbuy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_robbuy` (
  `robbuy_id` int(7) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `item_name` varchar(80) NOT NULL COMMENT '商品名称',
  `price` float NOT NULL COMMENT '抢购价格',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `title` varchar(120) NOT NULL COMMENT '活动名称',
  `start_time` varchar(90) NOT NULL COMMENT '开始日期',
  `end_time` varchar(90) NOT NULL COMMENT '结束日期',
  `item_num` int(5) NOT NULL COMMENT '发放总量',
  `description` text NOT NULL COMMENT '秒杀折扣',
  `sort` int(8) NOT NULL COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `buy_limit` int(5) NOT NULL COMMENT '限购数量',
  PRIMARY KEY (`robbuy_id`),
  KEY `category_id` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='商品记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_robbuy`
--

LOCK TABLES `bfb_robbuy` WRITE;
/*!40000 ALTER TABLE `bfb_robbuy` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_robbuy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_set_meal`
--

DROP TABLE IF EXISTS `bfb_set_meal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_set_meal` (
  `meal_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '套餐Id',
  `meal_name` varchar(255) NOT NULL DEFAULT '' COMMENT '套餐名称',
  `meal_price` float(11,0) unsigned NOT NULL DEFAULT '0' COMMENT '套餐价格',
  `meal_func` varchar(255) NOT NULL DEFAULT '' COMMENT '功能Id',
  `is_delete` int(2) NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`meal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='套餐表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_set_meal`
--

LOCK TABLES `bfb_set_meal` WRITE;
/*!40000 ALTER TABLE `bfb_set_meal` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_set_meal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_setting`
--

DROP TABLE IF EXISTS `bfb_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_setting` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(30) NOT NULL COMMENT '设置项标示',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '设置项描述',
  `values` text NOT NULL COMMENT '设置内容（json格式）',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商城设置记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_setting`
--

LOCK TABLES `bfb_setting` WRITE;
/*!40000 ALTER TABLE `bfb_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_sign`
--

DROP TABLE IF EXISTS `bfb_sign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_sign` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `day` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `app_id` int(7) DEFAULT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_sign`
--

LOCK TABLES `bfb_sign` WRITE;
/*!40000 ALTER TABLE `bfb_sign` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_sign` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_spec`
--

DROP TABLE IF EXISTS `bfb_spec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '规格表',
  `type_id` int(11) DEFAULT '0' COMMENT '规格类型',
  `name` varchar(55) DEFAULT NULL COMMENT '规格名称',
  `order` int(11) DEFAULT '50' COMMENT '排序',
  `search_index` tinyint(1) DEFAULT '0' COMMENT '是否需要检索',
  `app_id` int(8) NOT NULL COMMENT '小程序ID',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_spec`
--

LOCK TABLES `bfb_spec` WRITE;
/*!40000 ALTER TABLE `bfb_spec` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_spec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_spec_item`
--

DROP TABLE IF EXISTS `bfb_spec_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '规格项id',
  `spec_id` int(11) DEFAULT NULL COMMENT '规格id',
  `item` varchar(54) DEFAULT NULL COMMENT '规格项',
  `app_id` int(8) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_spec_item`
--

LOCK TABLES `bfb_spec_item` WRITE;
/*!40000 ALTER TABLE `bfb_spec_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_spec_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_spec_item_price`
--

DROP TABLE IF EXISTS `bfb_spec_item_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_spec_item_price` (
  `goods_id` int(11) DEFAULT '0' COMMENT '商品id',
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '规格键名',
  `key_name` varchar(200) NOT NULL COMMENT '规格键名中文',
  `shop_price` decimal(8,2) DEFAULT NULL COMMENT '规格键名中文',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `store_count` int(11) unsigned DEFAULT '10' COMMENT '库存数量',
  `sku` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT 'SKU',
  `app_id` int(7) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_spec_item_price`
--

LOCK TABLES `bfb_spec_item_price` WRITE;
/*!40000 ALTER TABLE `bfb_spec_item_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_spec_item_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_spike`
--

DROP TABLE IF EXISTS `bfb_spike`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_spike` (
  `spike_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `name` varchar(120) NOT NULL COMMENT '活动名称',
  `start_time` varchar(90) NOT NULL COMMENT '开始日期',
  `end_time` varchar(90) NOT NULL COMMENT '结束日期',
  `development_time` varchar(255) NOT NULL COMMENT '开发时间',
  `total_num` int(5) NOT NULL COMMENT '发放总量',
  `discount` float NOT NULL COMMENT '秒杀折扣',
  `sort` int(8) NOT NULL COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`spike_id`),
  KEY `category_id` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COMMENT='商品记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_spike`
--

LOCK TABLES `bfb_spike` WRITE;
/*!40000 ALTER TABLE `bfb_spike` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_spike` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_upload_file`
--

DROP TABLE IF EXISTS `bfb_upload_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_upload_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件id',
  `storage` varchar(20) NOT NULL DEFAULT '' COMMENT '存储方式',
  `group_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件分组id',
  `file_url` varchar(255) NOT NULL DEFAULT '' COMMENT '存储域名',
  `file_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小(字节)',
  `file_type` varchar(20) NOT NULL DEFAULT '' COMMENT '文件类型',
  `extension` varchar(20) NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `is_user` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否为c端用户上传',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '软删除',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `user_id` int(7) NOT NULL COMMENT '用户I',
  PRIMARY KEY (`id`),
  UNIQUE KEY `path_idx` (`file_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件库记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_upload_file`
--

LOCK TABLES `bfb_upload_file` WRITE;
/*!40000 ALTER TABLE `bfb_upload_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_upload_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_upload_group`
--

DROP TABLE IF EXISTS `bfb_upload_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_upload_group` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `group_type` varchar(10) NOT NULL DEFAULT '' COMMENT '文件类型',
  `group_name` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类排序(数字越小越靠前)',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`group_id`),
  KEY `type_index` (`group_type`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='文件库分组记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_upload_group`
--

LOCK TABLES `bfb_upload_group` WRITE;
/*!40000 ALTER TABLE `bfb_upload_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_upload_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_upload_type`
--

DROP TABLE IF EXISTS `bfb_upload_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_upload_type` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片id 自增',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '类型如:商品item ',
  `app_id` int(8) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `image_id` int(10) DEFAULT NULL,
  `link_id` int(8) NOT NULL COMMENT '关联产品或其他id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_upload_type`
--

LOCK TABLES `bfb_upload_type` WRITE;
/*!40000 ALTER TABLE `bfb_upload_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_upload_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_user`
--

DROP TABLE IF EXISTS `bfb_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `phone` varchar(15) NOT NULL,
  `open_id` varchar(255) NOT NULL DEFAULT '' COMMENT '微信openid(唯一标示)',
  `nickName` varchar(255) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `avatarUrl` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `country` varchar(50) NOT NULL DEFAULT '' COMMENT '国家',
  `province` varchar(50) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(50) NOT NULL DEFAULT '' COMMENT '城市',
  `address_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '默认收货地址',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用户余额',
  `shop_money` float(8,2) NOT NULL COMMENT '消费金额',
  `profit` float(8,2) NOT NULL COMMENT '推广收益',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `password` varchar(32) DEFAULT NULL,
  `pid` int(5) NOT NULL DEFAULT '0' COMMENT '推荐',
  `level` varchar(20) DEFAULT NULL,
  `sign` int(7) DEFAULT '0' COMMENT '用户签到天数',
  `integral` int(7) DEFAULT '0' COMMENT '用户积分',
  `path` text NOT NULL COMMENT '关系树',
  `agio` float(5,2) NOT NULL DEFAULT '10.00' COMMENT '折扣',
  PRIMARY KEY (`user_id`),
  KEY `openid` (`open_id`),
  KEY `open_id` (`open_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_user`
--

LOCK TABLES `bfb_user` WRITE;
/*!40000 ALTER TABLE `bfb_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_user_address`
--

DROP TABLE IF EXISTS `bfb_user_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_user_address` (
  `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `province_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在省份id',
  `city_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在城市id',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在区id',
  `detail` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`address_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收货地址表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_user_address`
--

LOCK TABLES `bfb_user_address` WRITE;
/*!40000 ALTER TABLE `bfb_user_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_user_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_user_coupon`
--

DROP TABLE IF EXISTS `bfb_user_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_user_coupon` (
  `user_coupon_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `coupon_id` int(11) unsigned NOT NULL COMMENT '优惠券id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '优惠券名称',
  `color` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '优惠券颜色(10蓝 20红 30紫 40黄)',
  `coupon_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '优惠券类型(10满减券 20折扣券)',
  `reduce_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '满减券-减免金额',
  `discount` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '折扣券-折扣率(0-100)',
  `min_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最低消费金额',
  `expire_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '到期类型(10领取后生效 20固定时间)',
  `expire_day` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '领取后生效-有效天数',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '有效期开始时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '有效期结束时间',
  `apply_range` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '适用范围(10全部商品 20指定商品)',
  `is_expire` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否过期(0未过期 1已过期)',
  `is_use` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否已使用(0未使用 1已使用)',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`user_coupon_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户优惠券记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_user_coupon`
--

LOCK TABLES `bfb_user_coupon` WRITE;
/*!40000 ALTER TABLE `bfb_user_coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `bfb_user_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bfb_web_user`
--

DROP TABLE IF EXISTS `bfb_web_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bfb_web_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '登录密码',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(2) NOT NULL DEFAULT '0',
  `real_name` varchar(200) NOT NULL,
  `is_super` int(2) NOT NULL DEFAULT '0' COMMENT '超级管理员',
  `pid` int(7) DEFAULT '0' COMMENT '主帐号id',
  `role_id` int(7) unsigned DEFAULT NULL COMMENT '权限组ID',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号',
  `note` text NOT NULL COMMENT '备注',
  `vip` int(2) NOT NULL COMMENT 'vip等级',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  KEY `id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10099 DEFAULT CHARSET=utf8 COMMENT='商家用户记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bfb_web_user`
--

LOCK TABLES `bfb_web_user` WRITE;
/*!40000 ALTER TABLE `bfb_web_user` DISABLE KEYS */;
INSERT INTO `bfb_web_user` VALUES (10001,'admin','0c284312a8db9d10b32fb8c40774ef5d',10001,1542015252,1555749817,0,'111',0,0,10093,'13098881688','你好',0),(10012,'admin02','0c284312a8db9d10b32fb8c40774ef5d',10001,1542015252,1542090009,0,'5664',0,0,10027,'13098881688','你们好',0),(10026,'订单','0c284312a8db9d10b32fb8c40774ef5d',10001,1551424795,1553676751,0,'埃及法',0,0,10027,'13098881688','欢迎你们',0),(10078,'777888','e0f6b256274852ab3189cdead6096151',0,1555398189,1555398189,0,'',0,0,NULL,NULL,'',0),(10085,'3333','1e7877db505385acd7eb576c7175094a',0,1555409666,1555409666,0,'1',10083,0,10095,'','1',0),(10083,'nihao','0c284312a8db9d10b32fb8c40774ef5d',0,1555408217,1555408217,0,'',0,0,NULL,NULL,'',0),(10084,'weixin','0c284312a8db9d10b32fb8c40774ef5d',0,1555409084,1555409084,0,'',0,0,NULL,NULL,'',0),(10086,'niao','0c284312a8db9d10b32fb8c40774ef5d',10037,1555411523,1555560805,0,'',0,0,NULL,NULL,'',0),(10088,'1233','0c284312a8db9d10b32fb8c40774ef5d',10037,1555414926,1555470393,0,'1',10086,0,10096,'1','1',0),(10089,'小白','0c284312a8db9d10b32fb8c40774ef5d',0,1555472800,1555472800,0,'',0,0,NULL,NULL,'',0),(10090,'276355449','208419d1940f82a74088a4f4e0f4a9b7',0,1555493793,1555493793,0,'',0,0,NULL,NULL,'',0),(10091,'ruiming','22a8b1e967807e4dfd3ea26f32323532',10039,1555494142,1555494351,0,'',0,0,NULL,NULL,'',0),(10092,'ruoxing','0c284312a8db9d10b32fb8c40774ef5d',0,1555494353,1555494353,0,'',0,0,NULL,NULL,'',0),(10093,'666','8241bc372d2e110e3bd37ee5d6fc27d5',10044,1555495330,1555579464,0,'',0,0,NULL,NULL,'',0),(10094,'laoyue2015','1c66306b1e283041f3146a14ab127271',10041,1555506197,1555506535,0,'',0,0,NULL,NULL,'',0),(10096,'liushu','23c1fe7df0a7759adc305ea7a62370fd',10042,1555569283,1555569321,0,'',0,0,NULL,NULL,'',0),(10097,'987','9d0ffcf4be20b33ac5594166c74d774e',0,1555583692,1555583692,0,'',0,0,NULL,NULL,'',0),(10098,'toye','0c284312a8db9d10b32fb8c40774ef5d',10045,1555653378,1555653430,0,'',0,0,NULL,NULL,'',0);
/*!40000 ALTER TABLE `bfb_web_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-23 17:00:13
