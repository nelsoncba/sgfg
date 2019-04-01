CREATE DATABASE  IF NOT EXISTS `sgfg` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `sgfg`;
-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: sgfg
-- ------------------------------------------------------
-- Server version	5.5.24-log

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
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(128) DEFAULT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `postal_code` varchar(15) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id_fk_idx` (`customer_id`),
  CONSTRAINT `customer_id_fk` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` VALUES (1,'Nelson Zabala','Colon 5551','San Martin 221','Cordoba','Cordoba','Argentina','5000',1),(2,'nelson zabala','colon 324','san martin 234','cordoba','cordoba','argentina ','5000',2),(3,'juan perez','address 1','','cordoba','cordoba','argentina','5000',3),(4,'juan perez','address 2','','buenos aires','buenos aires','argentina','1400',3),(5,'','','','','','','',4);
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `almacen`
--

DROP TABLE IF EXISTS `almacen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `almacen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `movimiento` varchar(45) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `documento` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `almacen`
--

LOCK TABLES `almacen` WRITE;
/*!40000 ALTER TABLE `almacen` DISABLE KEYS */;
INSERT INTO `almacen` VALUES (3,'2017-05-20',24,'ENTRADA','-',NULL),(4,'2017-05-20',100,'ENTRADA','-',NULL),(5,'2017-05-21',100,'ENTRADA','-',NULL),(6,'2017-05-21',50,'ENTRADA','-',NULL),(7,'2017-05-22',234,'ENTRADA','-',NULL),(9,'2017-06-04',34,'SALIDA','-','00000001'),(12,'2017-06-04',22,'SALIDA','-','00000002'),(24,'2017-06-05',3,'ENTRADA','-','222098240'),(25,'2017-06-05',4,'ENTRADA','-','222098240'),(26,'2017-03-03',150,'ENTRADA','-',NULL),(29,'2017-06-06',10,'SALIDA','-','00000003'),(30,'2017-06-06',15,'SALIDA','-','00000003'),(31,'2017-06-06',200,'ENTRADA','-','000000124564'),(32,'2017-06-06',100,'ENTRADA','-','000000124564'),(33,'2017-06-11',12,'ENTRADA','-','00003400411'),(34,'2017-06-11',15,'ENTRADA','-','00000444043'),(35,'2017-06-11',15,'SALIDA','-',NULL),(36,'2017-06-11',10,'SALIDA',NULL,NULL),(47,'2017-06-11',19,'SALIDA','MATERIAL',NULL),(48,'2017-06-11',5,'SALIDA',NULL,'00000004'),(49,'2017-06-25',10,'SALIDA',NULL,'00000005'),(50,'2017-06-25',100,'ENTRADA',NULL,NULL),(52,'2017-06-25',100,'SALIDA',NULL,'00000006'),(53,'2017-06-25',200,'ENTRADA',NULL,NULL),(60,'2017-06-25',98,'SALIDA',NULL,'00000007'),(61,'2017-06-25',20,'SALIDA',NULL,'00000007'),(62,'2019-04-01',25,'SALIDA',NULL,'00000008'),(63,'2017-06-10',25,'SALIDA',NULL,'00000009');
/*!40000 ALTER TABLE `almacen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `almacen_material`
--

DROP TABLE IF EXISTS `almacen_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `almacen_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `almacen_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`almacen_id`,`material_id`),
  KEY `fk_AlmacenMaterial_Almacen1_idx` (`almacen_id`),
  KEY `fk_AlmacenMaterial_Material1_idx` (`material_id`),
  CONSTRAINT `fk_AlmacenMaterial_Almacen1` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_AlmacenMaterial_Material1` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `almacen_material`
--

LOCK TABLES `almacen_material` WRITE;
/*!40000 ALTER TABLE `almacen_material` DISABLE KEYS */;
INSERT INTO `almacen_material` VALUES (2,24,1),(3,25,2),(4,31,2),(5,32,1),(6,33,1),(7,34,2),(8,35,1),(9,36,1),(20,47,2);
/*!40000 ALTER TABLE `almacen_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `almacen_producto`
--

DROP TABLE IF EXISTS `almacen_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `almacen_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `almacen_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`almacen_id`,`producto_id`),
  KEY `fk_AlmacenProducto_Almacen1_idx` (`almacen_id`),
  KEY `fk_AlmacenProducto_Producto1_idx` (`producto_id`),
  CONSTRAINT `fk_AlmacenProducto_Almacen1` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_AlmacenProducto_Producto1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `almacen_producto`
--

LOCK TABLES `almacen_producto` WRITE;
/*!40000 ALTER TABLE `almacen_producto` DISABLE KEYS */;
INSERT INTO `almacen_producto` VALUES (2,'','',3,2),(3,'','',4,1),(4,'','',5,2),(5,'','',6,1),(6,'','',7,2),(8,NULL,NULL,9,1),(11,NULL,NULL,12,2),(12,'','',26,3),(15,NULL,NULL,29,3),(16,NULL,NULL,30,1),(17,NULL,NULL,48,1),(18,NULL,NULL,49,1),(19,'','',50,1),(21,NULL,NULL,52,1),(22,'','',53,1),(29,NULL,NULL,60,1),(30,NULL,NULL,61,2),(31,NULL,NULL,62,2),(32,NULL,NULL,63,2);
/*!40000 ALTER TABLE `almacen_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cliente` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(45) DEFAULT NULL,
  `persona_id` int(11) NOT NULL,
  PRIMARY KEY (`codigo`,`persona_id`),
  KEY `fk_cliente_persona_idx` (`persona_id`),
  CONSTRAINT `fk_cliente_persona` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (6,'C',3),(7,'C',4),(8,'C',7),(9,'C',8),(10,'C',9),(18,'C',17),(19,'C',19),(22,'C',22),(23,'C',23),(24,'C',24),(25,'C',26);
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compra`
--

DROP TABLE IF EXISTS `compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numeroFactura` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `importe` decimal(20,2) DEFAULT NULL,
  `notaCredito` decimal(20,2) unsigned DEFAULT '0.00',
  `notaDebito` decimal(20,2) DEFAULT '0.00',
  `proveedor_codigo` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id`,`proveedor_codigo`),
  KEY `fk_Compra_Proveedor1_idx` (`proveedor_codigo`),
  CONSTRAINT `fk_Compra_Proveedor1` FOREIGN KEY (`proveedor_codigo`) REFERENCES `proveedor` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compra`
--

LOCK TABLES `compra` WRITE;
/*!40000 ALTER TABLE `compra` DISABLE KEYS */;
INSERT INTO `compra` VALUES (30,'222098240','ANULADO',1080.00,0.00,0.00,1,'2017-06-05'),(31,'000000124564','CERRADO',44000.00,0.00,0.00,4,'2017-06-06'),(32,'00003400411','CERRADO',2400.00,0.00,0.00,1,'2017-06-11'),(33,'00000444043','CERRADO',1800.00,0.00,0.00,4,'2017-06-11');
/*!40000 ALTER TABLE `compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacto_personal`
--

DROP TABLE IF EXISTS `contacto_personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacto_personal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telefono` varchar(45) DEFAULT NULL,
  `celular` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `persona_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`persona_id`),
  KEY `fk_cp_persona_idx` (`persona_id`),
  CONSTRAINT `fk_cp_persona` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacto_personal`
--

LOCK TABLES `contacto_personal` WRITE;
/*!40000 ALTER TABLE `contacto_personal` DISABLE KEYS */;
INSERT INTO `contacto_personal` VALUES (3,'213423144','','mail@mail',3),(4,'354566788','','',18);
/*!40000 ALTER TABLE `contacto_personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (1,'prueba1');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(32) DEFAULT NULL,
  `last_name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (1,'Nelson','Zabala'),(2,'nelson','zabala'),(3,'juan','perez'),(4,'','');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_compra`
--

DROP TABLE IF EXISTS `detalle_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_compra` (
  `compra_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(20,2) DEFAULT NULL,
  `material_id` int(11) NOT NULL,
  PRIMARY KEY (`compra_id`,`material_id`),
  KEY `fk_DetalleCompra_Material1_idx` (`material_id`),
  CONSTRAINT `fk_DetalleCompra_Compra1` FOREIGN KEY (`compra_id`) REFERENCES `compra` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_DetalleCompra_Material1` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_compra`
--

LOCK TABLES `detalle_compra` WRITE;
/*!40000 ALTER TABLE `detalle_compra` DISABLE KEYS */;
INSERT INTO `detalle_compra` VALUES (30,3,NULL,1),(30,4,NULL,2),(31,100,NULL,1),(31,200,NULL,2),(32,12,NULL,1),(33,15,NULL,2);
/*!40000 ALTER TABLE `detalle_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_pedido`
--

DROP TABLE IF EXISTS `detalle_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cantidad` int(11) DEFAULT NULL,
  `descuento` decimal(20,2) DEFAULT NULL,
  `producto_id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`producto_id`,`pedido_id`),
  KEY `fk_DetalleVenta_Producto1_idx` (`producto_id`),
  KEY `fk_DetallePedido_Pedido1_idx` (`pedido_id`),
  CONSTRAINT `fk_DetallePedido_Pedido1` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_DetalleVenta_Producto1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pedido`
--

LOCK TABLES `detalle_pedido` WRITE;
/*!40000 ALTER TABLE `detalle_pedido` DISABLE KEYS */;
INSERT INTO `detalle_pedido` VALUES (2,34,NULL,1,2),(5,22,NULL,2,5),(6,15,NULL,1,5),(9,10,NULL,3,7),(10,15,NULL,1,7),(14,12,NULL,2,11),(15,25,NULL,2,12),(26,5,NULL,1,22),(35,10,NULL,1,31),(36,100,NULL,1,32),(37,98,NULL,1,33),(39,100,NULL,1,35),(41,20,NULL,2,33),(42,25,NULL,2,36);
/*!40000 ALTER TABLE `detalle_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domicilio`
--

DROP TABLE IF EXISTS `domicilio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domicilio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calle` varchar(45) DEFAULT NULL,
  `numero` varchar(45) DEFAULT NULL,
  `codigoPostal` varchar(45) DEFAULT NULL,
  `localidad_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`localidad_id`),
  KEY `fk_Domicilio_Localidad1_idx` (`localidad_id`),
  CONSTRAINT `fk_Domicilio_Localidad1` FOREIGN KEY (`localidad_id`) REFERENCES `localidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domicilio`
--

LOCK TABLES `domicilio` WRITE;
/*!40000 ALTER TABLE `domicilio` DISABLE KEYS */;
INSERT INTO `domicilio` VALUES (1,'colon','23141','',1),(3,'san martin','234','',1),(10,'Alvarado C.','334','5000',3),(11,'Los Aromos','3445','',1),(12,'','','',1),(13,'Calle 1 ','4458','5000',2),(14,'Calle 2','2234','',1),(15,'','','',1),(16,'Sorrento','32','3244',4),(17,'La Huella','233','',4),(18,'','','',1),(19,'Prueba','3433','4433',2),(20,'Prueba','3433','4433',3),(21,'Prueba','3433','4433',3),(22,'Prueba1','234','',4),(23,'Prueba1','234','2333',4),(24,'Prueba1','234','2333',4),(25,'Prueba2','324','2343',1),(26,'Prueba2','324','2343',2),(27,'Prueba2','324','2343',3),(28,'Prueba2','324','2343',3),(35,'Prueba2','2344','344',1),(36,'Prueba2','2344','344',2),(37,'Prueba2','2344','344',3),(38,'Prueba2','2344','344',3),(39,'2142','2344','23222',4),(40,'Prueba2','324','244',3),(41,'prueba332','23243','2332',1),(44,'San Martin','2399','500',1),(45,'Los Robles','2234','23243',1),(46,'Los Robles ','333','5000',1),(47,'San Martin','1299','5000',1),(48,'Prueba','333','5000',1);
/*!40000 ALTER TABLE `domicilio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `localidad`
--

DROP TABLE IF EXISTS `localidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `localidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `id_provincia` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `localidad`
--

LOCK TABLES `localidad` WRITE;
/*!40000 ALTER TABLE `localidad` DISABLE KEYS */;
INSERT INTO `localidad` VALUES (1,'Córdoba',NULL),(2,'Jesus María',NULL),(3,'Carlos Paz',NULL),(4,'Rio Cuarto',NULL),(5,'San Francisco',NULL);
/*!40000 ALTER TABLE `localidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `localidad_bkp`
--

DROP TABLE IF EXISTS `localidad_bkp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `localidad_bkp` (
  `id` int(11) NOT NULL DEFAULT '0',
  `nombre` varchar(45) DEFAULT NULL,
  `id_provincia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `localidad_bkp`
--

LOCK TABLES `localidad_bkp` WRITE;
/*!40000 ALTER TABLE `localidad_bkp` DISABLE KEYS */;
INSERT INTO `localidad_bkp` VALUES (1,'Buenos Aires',NULL),(2,'Catamarca',NULL),(3,'Chaco',NULL),(4,'Chubut',NULL),(5,'Cordoba',NULL),(6,'Corrientes',NULL),(7,'Entre Rios',NULL),(8,'Formosa',NULL),(9,'Jujuy',NULL),(10,'La Pampa',NULL),(11,'La Rioja',NULL),(12,'Mendoza',NULL),(13,'Misiones',NULL),(14,'Neuquen',NULL),(15,'Rio Negro',NULL),(16,'Salta ',NULL),(17,'San Juan',NULL),(18,'San Luis',NULL),(19,'Santa Cruz',NULL),(20,'Santa Fe',NULL),(21,'Santiago del Estero',NULL),(22,'T. del Fuego',NULL),(23,'Tucuman',NULL),(24,'Capital',5);
/*!40000 ALTER TABLE `localidad_bkp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material`
--

DROP TABLE IF EXISTS `material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `cantidadExistencia` int(11) DEFAULT NULL,
  `cantidadMinitma` int(11) DEFAULT NULL,
  `precioUnitario` decimal(20,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material`
--

LOCK TABLES `material` WRITE;
/*!40000 ALTER TABLE `material` DISABLE KEYS */;
INSERT INTO `material` VALUES (1,'0001','Glucosa','Activo',190,10,200.00),(2,'0002','Merengue','Activo',400,10,120.00);
/*!40000 ALTER TABLE `material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nota_credito`
--

DROP TABLE IF EXISTS `nota_credito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nota_credito` (
  `id` int(11) NOT NULL,
  `codigo` varchar(45) DEFAULT NULL,
  `concepto` varchar(45) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `importe` decimal(20,2) DEFAULT NULL,
  `saldo` decimal(20,2) DEFAULT NULL,
  `venta_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`venta_id`),
  KEY `fk_NotaCredito_Venta1_idx` (`venta_id`),
  CONSTRAINT `fk_NotaCredito_Venta1` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nota_credito`
--

LOCK TABLES `nota_credito` WRITE;
/*!40000 ALTER TABLE `nota_credito` DISABLE KEYS */;
/*!40000 ALTER TABLE `nota_credito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido`
--

DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `fechaPedido` date DEFAULT NULL,
  `fechaEntrega` date DEFAULT NULL,
  `cliente_codigo` int(11) NOT NULL,
  PRIMARY KEY (`id`,`cliente_codigo`),
  KEY `fk_Pedido_Cliente1_idx` (`cliente_codigo`),
  CONSTRAINT `fk_Pedido_Cliente1` FOREIGN KEY (`cliente_codigo`) REFERENCES `cliente` (`codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (2,'00000001','CERRADO','VENTA','2017-06-04',NULL,6),(5,'00000002','CERRADO','VENTA','2017-06-04',NULL,7),(7,'00000003','CERRADO','VENTA','2017-06-06',NULL,7),(11,'00000004','ANULADO','PEDIDO','2017-06-10',NULL,10),(12,'00000005','CERRADO','VENTA','2017-06-10',NULL,7),(22,'00000006','CERRADO','VENTA','2017-06-11',NULL,9),(31,'00000007','CERRADO','VENTA','2017-06-25',NULL,6),(32,'00000008','PENDIENTE','PEDIDO','2017-06-25',NULL,7),(33,'00000009','CERRADO','VENTA','2017-06-25',NULL,8),(35,'00000010','CERRADO','VENTA','2017-06-25',NULL,7),(36,'00000011','CERRADO','VENTA','2019-04-01',NULL,8);
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_person_FK1` (`country_id`),
  CONSTRAINT `tbl_person_FK1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (2,'Juan','perez',NULL,2);
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `apellido` varchar(45) DEFAULT NULL,
  `razonSocial` varchar(45) DEFAULT NULL,
  `tipoDocu` varchar(45) DEFAULT NULL,
  `documento` varchar(45) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `domicilio_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`domicilio_id`),
  KEY `fk_Persona_Domicilio1_idx` (`domicilio_id`),
  CONSTRAINT `fk_Persona_Domicilio1` FOREIGN KEY (`domicilio_id`) REFERENCES `domicilio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (2,'juan','perez','','dni','234242434','2001-12-01',NULL,3),(3,'Pedro','Gerez','','dni','93444221','1982-01-01',NULL,10),(4,'Diego','Gaitan','','dni','908494393',NULL,NULL,11),(5,'','','Proveedor1','cuit','2045521232',NULL,NULL,13),(6,'','','Proveedor2','cuit','20654784493',NULL,NULL,14),(7,'Martin','Molina','','dni','23432432','1991-12-31',NULL,16),(8,'Pablo','Gerez','','dni','23432444','1961-09-01',NULL,17),(9,'Juan','Perez','','dni','24332432','1998-07-07',NULL,19),(17,'Dario','Fernandez','','dni','23423323',NULL,NULL,39),(18,'','','Proveedor3','cuit','20444323443',NULL,NULL,40),(19,'diego','perez','','dni','234242434',NULL,NULL,41),(22,'Daniela','Bernardez','','dni','32443334',NULL,NULL,44),(23,'Diego','García','','dni','20999888',NULL,NULL,45),(24,'Martin ','Rodriguez','','dni','20878999',NULL,NULL,46),(25,'','','Dulcor S.A.','cuit','20307767782',NULL,NULL,47),(26,'Prueba','Prueba','','dni','334434334',NULL,NULL,48);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `cantidadExistencia` decimal(10,2) DEFAULT NULL,
  `cantidadMinima` decimal(10,2) DEFAULT NULL,
  `precioUnitario` decimal(20,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (1,'000001','Chupetin multifrutal',188.00,10.00,25.00),(2,'000002','Chupetin caramelo',266.00,10.00,18.00),(3,'000003','Helado merengue',240.00,20.00,40.00);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proveedor` (
  `codigo` int(11) NOT NULL AUTO_INCREMENT,
  `persona_id` int(11) NOT NULL,
  PRIMARY KEY (`codigo`,`persona_id`),
  KEY `fk_proveedor_persona1_idx` (`persona_id`),
  CONSTRAINT `fk_proveedor_persona1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedor`
--

LOCK TABLES `proveedor` WRITE;
/*!40000 ALTER TABLE `proveedor` DISABLE KEYS */;
INSERT INTO `proveedor` VALUES (1,5),(2,6),(3,18),(4,25);
/*!40000 ALTER TABLE `proveedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (10,'Usuario'),(20,'Ventas'),(30,'Admin'),(40,'Almacen');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(45) DEFAULT NULL,
  `lastName` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `authKey` char(50) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Admin','Admin','Admin','Admin','adf76dfa67df',30),(2,'Nelson','Zabala','nzabala','nzabala','234233',30),(3,'Almacen','Almacen','Almacen','1234','as87fa9f7s9af97df',40);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venta`
--

DROP TABLE IF EXISTS `venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) NOT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `fechaGeneracion` date DEFAULT NULL,
  `fechaCobro` date DEFAULT NULL,
  `monto` decimal(20,2) DEFAULT NULL,
  `montoIva` decimal(20,2) DEFAULT NULL,
  `montoCobrado` decimal(20,2) DEFAULT NULL,
  `pedido_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`pedido_id`),
  KEY `fk_Venta_Pedido1_idx` (`pedido_id`),
  CONSTRAINT `fk_Venta_Pedido1` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta`
--

LOCK TABLES `venta` WRITE;
/*!40000 ALTER TABLE `venta` DISABLE KEYS */;
INSERT INTO `venta` VALUES (2,'00000001','CERRADO','2017-06-04',NULL,NULL,NULL,850.00,2),(5,'00000002','CERRADO','2017-06-04',NULL,NULL,NULL,396.00,5),(7,'00000003','CERRADO','2017-06-06',NULL,NULL,NULL,775.00,7),(8,'00000004','CERRADO','2017-06-11',NULL,NULL,NULL,125.00,22),(9,'00000005','CERRADO','2017-06-25',NULL,NULL,NULL,250.00,31),(11,'00000006','CERRADO','2017-06-25',NULL,NULL,NULL,0.00,35),(17,'00000007','CERRADO','2017-06-25',NULL,NULL,NULL,2810.00,33),(18,'00000008','CERRADO','2019-04-01',NULL,NULL,NULL,450.00,36),(19,'00000009','CERRADO','2017-06-10',NULL,NULL,NULL,450.00,12);
/*!40000 ALTER TABLE `venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'sgfg'
--

--
-- Dumping routines for database 'sgfg'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-01 13:10:07
