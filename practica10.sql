SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `ln_diccionario` (
  `palabra` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ln_diccionario` (`palabra`) VALUES
('Aljarafe'),
('barato'),
('busco'),
('casa'),
('centro'),
('chalet'),
('deseo'),
('dormitorios'),
('garaje'),
('metros'),
('Nervión'),
('piso');

CREATE TABLE `ln_patrones` (
  `id` int(11) NOT NULL,
  `patron` varchar(255) DEFAULT NULL,
  `consultasql` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ln_patrones` (`id`, `patron`, `consultasql`) VALUES
(1, 'busco tipo', 'SELECT * FROM viviendas WHERE tipo = \'%1\''),
(2, 'busco zona', 'SELECT * FROM viviendas WHERE zona = \'%1\''),
(3, 'busco tipo zona', 'SELECT * FROM viviendas WHERE tipo = \'%1\' AND zona = \'%2\''),
(4, 'busco tipo numero dormitorios zona', 'SELECT * FROM viviendas WHERE tipo = \'%1\' AND ndormitorios = \'%2\' AND zona = \'%3\''),
(5, 'deseo tipo zona1 o zona2', 'SELECT * FROM viviendas WHERE tipo = \'%1\' AND (zona = \'%2\' OR zona = \'%3\')'),
(6, 'deseo tipo con mas de numero dormitorios en zona', 'SELECT * FROM viviendas WHERE tipo = \'%1\' AND ndormitorios > %2 AND zona = \'%3\''),
(7, 'deseo tipo de mas de metros metros cuadrados', 'SELECT * FROM viviendas WHERE tipo = \'%1\' AND metros_cuadrados > %2'),
(8, 'deseo tipo barato', 'SELECT * FROM viviendas WHERE tipo = \'%1\' AND precio < 100000'),
(9, 'deseo tipo en zona con garaje', 'SELECT * FROM viviendas WHERE tipo = \'%1\' AND zona = \'%2\' AND foto IS NOT NULL');

CREATE TABLE `viviendas` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `zona` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ndormitorios` int(11) DEFAULT NULL,
  `metros_cuadrados` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `viviendas` (`id`, `tipo`, `zona`, `ndormitorios`, `metros_cuadrados`, `precio`, `foto`) VALUES
(1, 'piso', 'centro', 3, 90, 120000.00, 'foto1.jpg'),
(2, 'casa', 'Nervion', 4, 150, 250000.00, 'foto2.jpg'),
(3, 'chalet', 'Aljarafe', 5, 200, 300000.00, 'foto3.jpg'),
(4, 'piso', 'Nervion', 3, 120, 130000.00, 'foto4.jpg'),
(5, 'casa', 'centro', 4, 150, 200000.00, 'foto5.jpg'),
(6, 'casa', 'Nervion', 5, 180, 250000.00, 'foto6.jpg'),
(7, 'casa', 'aljarafe', 4, 200, 300000.00, 'foto7.jpg'),
(8, 'piso', 'Nervion', 5, 85, 85000.00, 'foto8.jpg'),
(9, 'casa', 'Nervion', 4, 220, 280000.00, 'foto9.jpg'),
(10, 'piso', 'centro', 3, 95, 98000.00, 'foto10.jpg');

ALTER TABLE `ln_diccionario`
  ADD PRIMARY KEY (`palabra`);

ALTER TABLE `ln_patrones`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `viviendas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ln_patrones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `viviendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;
