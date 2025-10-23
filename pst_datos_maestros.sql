/*
================================================================================
  SCRIPT DE DATOS MAESTROS - PST (Post-Proceso Salmones y Trucha)
================================================================================
  
  Archivo: pst_datos_maestros.sql
  Versión: 2.0 - DATOS REALES
  Fecha: Octubre 2024
  Autor: Sistema PST
  
  DESCRIPCIÓN:
  Este script contiene los INSERT de datos maestros/lookup para el sistema PST.
  Incluye SOLO las tablas que contienen datos de catálogos y usuarios.
  
  PREREQUISITOS:
  1. El script pst_estructura_limpia.sql debe haber sido ejecutado previamente
  2. La base de datos PST debe existir
  3. SQL Server 2012 o superior
  
  TABLAS INCLUIDAS:
  - roles (4 registros)
  - sala (9 registros)
  - tipo_planilla (23 registros)
  - calibre (87 registros)
  - calidad (15 registros)
  - destino (43 registros)
  - corte (58 registros)
  - departamentos (5 registros)
  - usuarios_pst (96 usuarios)
  
  NOTA DE SEGURIDAD:
  Este script contiene credenciales de usuarios. En producción, se recomienda
  cambiar las contraseñas después de la instalación.
  
================================================================================
*/

USE [pst]
GO

SET NOCOUNT ON;
GO

PRINT '================================================';
PRINT '  INICIANDO INSERCIÓN DE DATOS MAESTROS PST';
PRINT '================================================';
PRINT '';

-- ============================================
-- TABLA: roles
-- ============================================
PRINT 'Insertando datos en tabla: roles...';
SET IDENTITY_INSERT [dbo].[roles] ON;

INSERT [dbo].[roles] ([cod_rol], [nombre_rol]) VALUES (1, N'Planillero')
INSERT [dbo].[roles] ([cod_rol], [nombre_rol]) VALUES (2, N'Supervisor')
INSERT [dbo].[roles] ([cod_rol], [nombre_rol]) VALUES (3, N'Admin')
INSERT [dbo].[roles] ([cod_rol], [nombre_rol]) VALUES (4, N'JefeTurno')

SET IDENTITY_INSERT [dbo].[roles] OFF;
PRINT '  ✓ Roles insertados correctamente (4 registros)';
PRINT '';

-- ============================================
-- TABLA: sala
-- ============================================
PRINT 'Insertando datos en tabla: sala...';
SET IDENTITY_INSERT [dbo].[sala] ON;

INSERT [dbo].[sala] ([cod_sala], [nombre], [activo]) VALUES (1, N'SALA 1', 1)
INSERT [dbo].[sala] ([cod_sala], [nombre], [activo]) VALUES (2, N'SALA 2', 1)
INSERT [dbo].[sala] ([cod_sala], [nombre], [activo]) VALUES (3, N'SALA 3', 1)
INSERT [dbo].[sala] ([cod_sala], [nombre], [activo]) VALUES (4, N'SALA 4', 1)
INSERT [dbo].[sala] ([cod_sala], [nombre], [activo]) VALUES (5, N'SALA 5', 1)
INSERT [dbo].[sala] ([cod_sala], [nombre], [activo]) VALUES (6, N'SALA 6', 1)
INSERT [dbo].[sala] ([cod_sala], [nombre], [activo]) VALUES (7, N'SALA 7', 1)
INSERT [dbo].[sala] ([cod_sala], [nombre], [activo]) VALUES (8, N'SALA AHUMADO', 1)
INSERT [dbo].[sala] ([cod_sala], [nombre], [activo]) VALUES (9, N'SALA EMPAQUE', 1)

SET IDENTITY_INSERT [dbo].[sala] OFF;
PRINT '  ✓ Salas insertadas correctamente (9 registros)';
PRINT '';

-- ============================================
-- TABLA: tipo_planilla
-- ============================================
PRINT 'Insertando datos en tabla: tipo_planilla...';
SET IDENTITY_INSERT [dbo].[tipo_planilla] ON;

INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (1, N'Filete', 0)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (2, N'Porciones', 0)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (3, N'HG', 0)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (4, N'Ahumado', 0)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (5, N'Fileteo Salmon', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (6, N'Sellado', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (7, N'Despielado', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (8, N'Despinado', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (9, N'Descongelacion', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (10, N'Recepcion Ahumado', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (11, N'Despielado Ahumado', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (12, N'Rebanado Ahumado', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (13, N'Lavado Filete Corte', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (14, N'Emparrillado Filete Corte', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (15, N'Corte Porciones', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (16, N'ACC Bloqueo', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (17, N'Fileteo Jibia', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (18, N'Empaque', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (19, N'Reempaque', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (20, N'Reetiquetado', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (21, N'Lavado HON-HG', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (22, N'Desgrasado', 1)
INSERT [dbo].[tipo_planilla] ([cod_tipo_planilla], [nombre], [activo]) VALUES (23, N'FIleteo Reineta', 1)

SET IDENTITY_INSERT [dbo].[tipo_planilla] OFF;
PRINT '  ✓ Tipos de planilla insertados correctamente (23 registros)';
PRINT '';

-- ============================================
-- TABLA: departamentos
-- ============================================
PRINT 'Insertando datos en tabla: departamentos...';
SET IDENTITY_INSERT [dbo].[departamentos] ON;

INSERT [dbo].[departamentos] ([cod_departamento], [nombre], [activo]) VALUES (1, N'Mantención', 1)
INSERT [dbo].[departamentos] ([cod_departamento], [nombre], [activo]) VALUES (2, N'Calidad', 1)
INSERT [dbo].[departamentos] ([cod_departamento], [nombre], [activo]) VALUES (3, N'Producción', 1)
INSERT [dbo].[departamentos] ([cod_departamento], [nombre], [activo]) VALUES (4, N'Frigorífico', 1)
INSERT [dbo].[departamentos] ([cod_departamento], [nombre], [activo]) VALUES (5, N'Servicios', 1)

SET IDENTITY_INSERT [dbo].[departamentos] OFF;
PRINT '  ✓ Departamentos insertados correctamente (5 registros)';
PRINT '';

-- ============================================
-- TABLA: calibre
-- ============================================
PRINT 'Insertando datos en tabla: calibre...';
SET IDENTITY_INSERT [dbo].[calibre] ON;

INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1, N'7-9 Oz', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2, N'9-11 Oz', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (3, N'11-13 Oz', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (4, N'13-15 Oz', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (5, N'15-Up Oz', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (6, N'3-4 Kg', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (7, N'4-5 Kg', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (8, N'5-6 Kg', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (9, N'6-Up Kg', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (10, N'100-200 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (11, N'200-300 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (12, N'300-400 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (13, N'400-Up gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (14, N'S/Calibrar', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (15, N'PORCIONES CON PIEL', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (16, N'10 Lb', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (17, N'Seleccion A', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (18, N'Seleccion B', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (19, N'Seleccion C', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (20, N'X-LARGE', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (21, N'U15', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (22, N'1-2 LARGE', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (23, N'2-3 MEDIUM', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (24, N'3-4 SMALL', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (25, N'U5', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (26, N'2P', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (27, N'3P', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (28, N'4P', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (29, N'5P', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (30, N'6P', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (31, N'7P', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (32, N'8P', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (33, N'REINETA-GRANDE', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (34, N'REINETA-CHICA', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1010, N'100-150 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1011, N'150-170 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1012, N'170-200 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1013, N'200-227 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1014, N'227-Up gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1015, N'85-100 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1016, N'150-227 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1017, N'227-283 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1018, N'283-Up gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (1019, N'150-200 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2014, N'45-60 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2015, N'60-85 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2016, N'60-170 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2017, N'40-60 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2018, N'15-30 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2019, N'30-45 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2020, N'45-55 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2021, N'55-75 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2022, N'75-Up gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2023, N'20-50 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2024, N'50-Up gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2025, N'150-Up gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2026, N'100-170 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2027, N'200-Up gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2028, N'30-50 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2029, N'50-80 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2030, N'80-Up gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2031, N'113-170 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2032, N'125-200 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2033, N'200-300 Gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2034, N'85-125 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2035, N'125-300 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2036, N'1-2 Kg', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2037, N'2-3 Kg', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2038, N'3-4 Kg', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2039, N'4-6 Kg', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2040, N'4-Up Kg', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2041, N'170-227 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2042, N'125-150 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2043, N'125-170 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2044, N'170-200 Gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2045, N'200-250 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2046, N'200-275 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2047, N'250-300 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2048, N'170-250 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2049, N'125-227 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (2050, N'250-Up gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (3044, N'300-500 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (3045, N'500-Up gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (3046, N'0-100 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (3047, N'160-190 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (3048, N'190-220 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (3049, N'130-170 gr', 1)
INSERT [dbo].[calibre] ([cod_calib], [nombre], [activo]) VALUES (3050, N'10 Porciones', 1)

SET IDENTITY_INSERT [dbo].[calibre] OFF;
PRINT '  ✓ Calibres insertados correctamente (87 registros)';
PRINT '';

-- ============================================
-- TABLA: calidad
-- ============================================
PRINT 'Insertando datos en tabla: calidad...';
SET IDENTITY_INSERT [dbo].[calidad] ON;

INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (1, N'Premium', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (2, N'Standard', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (3, N'Medium', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (4, N'Trim D', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (5, N'Trim E', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (6, N'Trim C', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (7, N'Multiple', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (8, N'MIXED', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (9, N'CLASE A', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (10, N'CLASE B', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (11, N'CLASE C', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (12, N'ENTERO', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (13, N'SUPER PREMIUM', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (14, N'Trim F', 1)
INSERT [dbo].[calidad] ([cod_cald], [nombre], [activo]) VALUES (15, N'TRIM B', 1)

SET IDENTITY_INSERT [dbo].[calidad] OFF;
PRINT '  ✓ Calidades insertadas correctamente (15 registros)';
PRINT '';

-- ============================================
-- TABLA: destino
-- ============================================
PRINT 'Insertando datos en tabla: destino...';
SET IDENTITY_INSERT [dbo].[destino] ON;

INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (1, N'AHUMADO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2, N'SIN DESTINO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (3, N'POR SELLAR', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (4, N'EMPAQUE', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (5, N'SALDO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (6, N'CONGELADO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (7, N'ASERRIN', 0)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (8, N'DESECHO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (9, N'RECHAZO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (10, N'MUESTRA CALIDAD', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (11, N'DECOMISO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (12, N'FILETE IQF', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (13, N'LAVADO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (14, N'RECEPCION AHUMADO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (15, N'POST AHUMADO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (16, N'REBANADO AHUMADO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (17, N'DESPIELADO AHUMADO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (18, N'PORCIONES', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (19, N'MUESTRA LABORATORIO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (20, N'DEGRADACION', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (21, N'POR CORTAR PORCIONES', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (22, N'REETIQUETADO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (23, N'DEVOLUCION CLIENTE', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (24, N'DESCONGELAR', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (1023, N'RECHAZO (RECORTES)', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (1024, N'RECHAZO (COLOR)', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (1025, N'MUESTRA PLANTA', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2023, N'DESECHO PISO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2024, N'FILETE IVP', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2025, N'FRESCO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2026, N'SAM', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2027, N'CSG', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2028, N'SG', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2029, N'FRESCATO', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2030, N'POR DESPIELAR', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2031, N'POR DESPINAR', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2032, N'Revisión de perdida vacío.', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2033, N'ERROR  DE LOTE', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2034, N'ERROR DE CLASIFICACION.', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2035, N'ERROR DE CALIBRE', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2036, N'ERROR DE ETIQUETA', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2037, N'DESTINO BITAN PIECES.', 1)
INSERT [dbo].[destino] ([cod_destino], [nombre], [activo]) VALUES (2038, N'HON CONG', 1)

SET IDENTITY_INSERT [dbo].[destino] OFF;
PRINT '  ✓ Destinos insertados correctamente (43 registros)';
PRINT '';

-- ============================================
-- TABLA: corte
-- ============================================
PRINT 'Insertando datos en tabla: corte...';
SET IDENTITY_INSERT [dbo].[corte] ON;

INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (1, N'TRIM A', 0)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (2, N'TRIM B', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3, N'TRIM C', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (4, N'TRIM D  IVP', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (5, N'TRIM E  IVP', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (6, N'HON', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (7, N'HG', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (9, N'PORCION CON PIEL IVP', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (10, N'BITS AND PIECES', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (11, N'TROZOS CON PIEL', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (12, N'HON DESCAMADO', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (13, N'TRIM D S/E', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (14, N'JIBIA', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (15, N'ALETAS', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (16, N'FILETE DE JIBIA', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (17, N'TENTACULO', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (18, N'TENTACULO REPR.', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (19, N'NUCA', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (20, N'PIEL', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (21, N'SLICED', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (22, N'DESPUNTE', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (23, N'RECORTE SIN PIEL', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (24, N'DESECHO', 0)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (25, N'ASERRIN', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (26, N'PORCIONES', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (27, N'PORCION SIN PIEL', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (28, N'TRIM C S/E', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (29, N'BITS AND PIECES C/PIEL', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (30, N'BITS AND PIECES S/PIEL', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (31, N'SLICED S/PIEL', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (32, N'SLICED C/PIEL', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (33, N'PULPO', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (34, N'PULPO EVISCERADO', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (35, N'PATAS Y CABEZA', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (36, N'ESQUELON', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (37, N'PULPA', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (38, N'REINETA ENTERA', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (39, N'FILETE DE REINETA', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (1033, N'HG S/E', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (1034, N'COLLARES EN MITADES', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (1035, N'HON SIN ESCAMAS', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (1036, N'TROZOS SIN PIEL GRANDES', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (1037, N'TROZOS CON PIEL GRANDES', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (1038, N'TROZOS CON PIEL MEDIANO', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (1039, N'TROZOS CON PIEL CHICOS', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (2036, N'DESECHO PISO', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (2037, N'DESECHO ORGANICO', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3036, N'HARASU', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3037, N'GRASA', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3038, N'FILETE S/P REINETA', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3039, N'ovas', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3040, N'TRIM F', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3041, N'PORCION CON PIEL IQF', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3042, N'TRIM D IQF', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3043, N'TRIM E IQF', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3044, N'Trim D', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3045, N'TRIM E', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3046, N'CUBOS', 1)
INSERT [dbo].[corte] ([cod_corte], [nombre], [activo]) VALUES (3047, N'FISHBLOCK', 1)

SET IDENTITY_INSERT [dbo].[corte] OFF;
PRINT '  ✓ Cortes insertados correctamente (58 registros)';
PRINT '';

-- ============================================
-- TABLA: usuarios_pst
-- ============================================
PRINT 'Insertando datos en tabla: usuarios_pst...';
SET IDENTITY_INSERT [dbo].[usuarios_pst] ON;

INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (1, N'juan_perez', N'123', N'Juan', N'Perez', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2, N'maria_gomez', N'123', N'Maria', N'Gomez', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (3, N'carla_rodriguez', N'123', N'Carla', N'Rodriguez', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (4, N'ana_martinez', N'123', N'Ana', N'Martinez', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (5, N'luis_sanchez', N'123', N'Luis', N'Sanchez', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (6, N'admin', N'Seag2020.', N'Admin', N'SG', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (12, N'Gaguero', N'SALA7', N'Gladys', N'Agüero', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (13, N'alexb', N'alexbq', N'Alex', N'Barrientos', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (14, N'Cnancuch', N'CNANCUCHEON', N'Claudio', N'Nancucheo', 3, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (15, N'mmolina', N'123456', N'Manuel', N'Molina', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (16, N'Ecarrillo', N'1234', N'Erwin', N'Carrillo', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (17, N'Kmansilla', N'2018', N'Kelly', N'Mansilla', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (18, N'Naltamirano', N'1312', N'Natalie', N'Altamirano', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (19, N'Yrivas', N'1234', N'Yormaris', N'Rivas', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (21, N'Amanriquez', N'4321', N'Alex', N'Manriquez', 3, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (22, N'Ncastillo', N'ncastillo', N'Nicole', N'Castillo', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (23, N'Rbarrientos', N'1234', N'Rodrigo', N'Barrientos', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (24, N'Aparedes', N'1234', N'Albita', N'Paredes Riquelme', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (25, N'Hbravo', N'2614', N'Horacio', N'Bravo', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (26, N'Bsanto', N'20132015', N'Blanca', N'Santo', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (27, N'Nalmonacid', N'nalmonacid', N'Nadia', N'Almonacid', 3, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (28, N'Malvarado', N'1234', N'Maritza', N'Alvarado', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (29, N'Ksilva', N'1234', N'Katerin', N'Silva', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (30, N'Ejorquera', N'1234', N'Evelyn', N'Jorquera', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (31, N'Arain', N'1618', N'Andrea', N'Rain', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (32, N'Dlagos', N'*99*', N'Dennis', N'Lagos', 3, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (33, N'Cvillanueva', N'1234', N'Carlos', N'Villanueva', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (34, N'Jjobis', N'1234', N'Juana', N'Jobis', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (35, N'Cvaldebenito', N'1234', N'Claudio', N'Valdebenito', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (36, N'Mgutierrez', N'8207', N'Margarita', N'Gutiérrez', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (37, N'Asoto', N'VALAYE', N'Ariela', N'Soto', 3, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (38, N'Cparedes', N'1234', N'Cesar', N'Paredes', 3, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (39, N'Rsaldivia', N'1214', N'Rocio', N'Saldivia', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (40, N'Fpedrero', N'Fpedrero', N'Francisco', N'Pedrero', 3, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (41, N'Sfritsch', N'1234', N'Stefan', N'Fritsch', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (42, N'Cunquen', N'1234', N'Claudia', N'Unquen', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (43, N'Ogomez', N'1234', N'Olga', N'Gomez', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (44, N'PSG', N'123456789', N'Planillero', N'SG', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (45, N'SSG', N'123456789', N'Supervisor', N'SG', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (46, N'Ssalgado', N'sdsr23', N'Sonia', N'Salgado', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (47, N'Vgonzalez', N'1619', N'Victor', N'Gonzalez', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (48, N'Nmaldonado', N'19080226', N'Nicole', N'Maldonado', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (49, N'Jsolis', N'1234', N'Jessica', N'Solis', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (50, N'Mnavarro', N'12345', N'Magaly', N'Navarro', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (51, N'Claudia_unquen', N'1234', N'Claudia', N'Unquen', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (1051, N'Doyarzun', N'1234', N'Doris', N'Oyarzún', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2051, N'M_ALVARADO', N'252525', N'MIXCI', N'ALVARADO', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2052, N'Nmancilla', N'1234', N'Nicolas', N'Mancilla', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2053, N'Kzuniga', N'1234', N'Katherine', N'Zuñiga', 3, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2054, N'Jvera', N'1234', N'Jaqueline', N'Vera', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2055, N'Yojeda', N'1234', N'Yohanna', N'Ojeda', 3, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2056, N'evelin_marileo', N'1234', N'Evelin', N'Marileo', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2057, N'rbarria', N'rodrigo', N'Rodrigo', N'Barria', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2058, N'Mpinda', N'1234', N'Marcela', N'Pinda', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2059, N'Esandoval', N'1996', N'Estefania', N'Sandoval', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2060, N'Egonzalez', N'2024', N'Eliseo', N'Gonzalez', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2061, N'TIARE_HERNANDEZ', N'sofia0101', N'Tiare', N'Hernández', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2062, N'VILMA_MALDONADO', N'Vilma705', N'Vilma', N'Maldonado', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2063, N'Bojeda', N'1234', N'Belen', N'Ojeda', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2064, N'Abriones', N'12779032', N'Aldo', N'Briones', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2065, N'YLEIVA', N'1992', N'YOSELINE', N'LEIVA', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2066, N'GLOPEZ', N'4321', N'GERARDO', N'LOPEZ', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2067, N'JALMONACID', N'MALDONADO78', N'JOSE', N'ALMONACID', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2068, N'FREYES', N'1234', N'FRANSISCO', N'REYES', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2069, N'Kbravo', N'140202', N'kristin', N'bravo', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2070, N'Mfuentez', N'1234', N'Macarena', N'Fuentez', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2071, N'BFUENTES', N'1998BF', N'BELEN', N'FUENTES', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2072, N'DCOÑUECAR', N'D1984', N'DANIEL', N'COÑUECAR', NULL, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2073, N'DCOÑUECA', N'1234', N'DANIEL', N'COÑUECAR', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2074, N'AGAJARDO', N'Cobreloa123', N'ALEX', N'GAJARDO', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2075, N'AULE', N'Montiel1550.', N'ANTONIA', N'ULE', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2076, N'LCASTILLO', N'1234', N'LUIS', N'CASTILLO', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2077, N'MALVARADO.', N'1234', N'Manuel', N'Alvarado', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2078, N'VAGUILA', N'Aguila1550.', N'VICTOR', N'AGUILA', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2079, N'mgallardo', N'12345', N'Maritza', N'Gallardo', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2080, N'Ytriviño', N'1234', N'Yuyunis', N'Triviño', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2081, N'amilanca', N'1234', N'Andres', N'Milanca', 3, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2082, N'lvillegas', N'1234', N'Lorena', N'Villegas', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2083, N'vmontiel', N'1234', N'Victor', N'Montiel', 2, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2084, N'CPEREZ', N'2019', N'CARLA', N'PEREZ', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2085, N'cparedes2', N'2711', N'CESAR', N'PAREDES', 4, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2086, N'asoto', N'VALAYE', N'ARIELA', N'SOTO', 4, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2087, N'nvargas', N'2024', N'nancy', N'vargas', 1, 0)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2088, N'GOYARZUN', N'1405205', N'Gloria', N'Oyarzun', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2089, N'cgutierrez', N'1234', N'Carolina', N'Gutierrez', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2090, N'mnuñez', N'1234', N'Maria', N'Nuñez', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2091, N'mvaldevenito', N'Maca2025', N'Macarena', N'Valdevenito', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2092, N'cgallardo', N'informe turno', N'Cristian', N'Gallardo', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2093, N'voyarzo', N'12345', N'Veronica', N'Oyarzo', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2094, N'jaravena', N'Angol303', N'Julio', N'Aravena', NULL, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2095, N'dgalindo', N'123123', N'Daniel', N'Galindo', 3, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2096, N'ASAAVEDRA', N'1234', N'amitzaday', N'saavedra', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2097, N'mherrera', N'1801', N'Maritza', N'herrera', 2, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2098, N'phernandez', N'2607', N'patricia', N'hernandez', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2099, N'margel', N'1371', N'maria jose', N'argel Bustamante', 1, 1)
INSERT [dbo].[usuarios_pst] ([cod_usuario], [usuario], [pass], [nombre], [apellido], [cod_rol], [activo]) VALUES (2100, N'smartinez', N'1008', N'samuel  enrique', N'martinez jara', 2, 1)

SET IDENTITY_INSERT [dbo].[usuarios_pst] OFF;
PRINT '  ✓ Usuarios insertados correctamente (96 registros)';
PRINT '';

-- ============================================
-- RESUMEN FINAL
-- ============================================
PRINT '================================================';
PRINT '  INSERCIÓN DE DATOS MAESTROS COMPLETADA';
PRINT '================================================';
PRINT '';
PRINT 'RESUMEN DE REGISTROS INSERTADOS:';
PRINT '  - roles: 4 registros';
PRINT '  - sala: 9 registros';
PRINT '  - tipo_planilla: 23 registros';
PRINT '  - calibre: 87 registros';
PRINT '  - calidad: 15 registros';
PRINT '  - destino: 43 registros';
PRINT '  - corte: 58 registros';
PRINT '  - departamentos: 5 registros';
PRINT '  - usuarios_pst: 96 registros';
PRINT '  ';
PRINT '  TOTAL: 340 registros';
PRINT '';
PRINT 'NOTA IMPORTANTE:';
PRINT '  Por seguridad, se recomienda cambiar las contraseñas';
PRINT '  de los usuarios en producción.';
PRINT '';
PRINT '¡Script ejecutado exitosamente!';
PRINT '================================================';
GO
