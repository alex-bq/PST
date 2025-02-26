USE [master]
GO
/****** Object:  Database [pst]    Script Date: 20-02-2025 15:40:23 ******/
CREATE DATABASE [pst]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'pst', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL12.MSSQLSERVER\MSSQL\DATA\pst.mdf' , SIZE = 7168KB , MAXSIZE = UNLIMITED, FILEGROWTH = 1024KB )
 LOG ON 
( NAME = N'pst_log', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL12.MSSQLSERVER\MSSQL\DATA\pst_log.ldf' , SIZE = 3072KB , MAXSIZE = 2048GB , FILEGROWTH = 10%)
GO
ALTER DATABASE [pst] SET COMPATIBILITY_LEVEL = 120
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [pst].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [pst] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [pst] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [pst] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [pst] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [pst] SET ARITHABORT OFF 
GO
ALTER DATABASE [pst] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [pst] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [pst] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [pst] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [pst] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [pst] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [pst] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [pst] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [pst] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [pst] SET  DISABLE_BROKER 
GO
ALTER DATABASE [pst] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [pst] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [pst] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [pst] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [pst] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [pst] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [pst] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [pst] SET RECOVERY FULL 
GO
ALTER DATABASE [pst] SET  MULTI_USER 
GO
ALTER DATABASE [pst] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [pst] SET DB_CHAINING OFF 
GO
ALTER DATABASE [pst] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [pst] SET TARGET_RECOVERY_TIME = 0 SECONDS 
GO
ALTER DATABASE [pst] SET DELAYED_DURABILITY = DISABLED 
GO
USE [pst]
GO
/****** Object:  Table [dbo].[calibre]    Script Date: 20-02-2025 15:40:26 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[calibre](
	[cod_calib] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [nvarchar](255) NULL,
	[activo] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_calib] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[calidad]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[calidad](
	[cod_cald] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [nvarchar](255) NULL,
	[activo] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_cald] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[corte]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[corte](
	[cod_corte] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [nvarchar](255) NULL,
	[activo] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_corte] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[destino]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[destino](
	[cod_destino] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [nvarchar](255) NULL,
	[activo] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_destino] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[detalle_planilla_pst]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[detalle_planilla_pst](
	[cod_detalle] [int] IDENTITY(1,1) NOT NULL,
	[cod_planilla] [smallint] NULL,
	[cajas_entrega] [int] NULL,
	[kilos_entrega] [float] NULL,
	[piezas_entrega] [int] NULL,
	[cajas_recepcion] [int] NULL,
	[kilos_recepcion] [float] NULL,
	[piezas_recepcion] [int] NULL,
	[dotacion] [int] NULL,
	[cod_sala] [int] NULL,
	[observacion] [nvarchar](max) NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_detalle] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
UNIQUE NONCLUSTERED 
(
	[cod_planilla] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[planillas_pst]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[planillas_pst](
	[cod_planilla] [smallint] IDENTITY(1,1) NOT NULL,
	[cod_lote] [bigint] NULL,
	[fec_turno] [date] NULL,
	[cod_turno] [smallint] NULL,
	[cod_empresa] [numeric](18, 0) NULL,
	[cod_proveedor] [numeric](18, 0) NULL,
	[cod_especie] [smallint] NULL,
	[cod_proceso] [smallint] NULL,
	[cod_planillero] [numeric](18, 0) NULL,
	[cod_supervisor] [numeric](18, 0) NULL,
	[fec_crea_planilla] [datetime] NULL,
	[cod_usuario_crea_planilla] [int] NULL,
	[guardado] [numeric](18, 0) NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_planilla] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
UNIQUE NONCLUSTERED 
(
	[cod_planilla] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[registro_planilla_pst]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[registro_planilla_pst](
	[cod_reg] [smallint] IDENTITY(1,1) NOT NULL,
	[cod_planilla] [smallint] NULL,
	[cod_corte_ini] [bigint] NULL,
	[cod_corte_fin] [bigint] NULL,
	[cod_destino] [numeric](18, 0) NULL,
	[cod_calibre] [smallint] NULL,
	[cod_calidad] [smallint] NULL,
	[piezas] [numeric](18, 0) NULL,
	[kilos] [float] NULL,
	[guardado] [numeric](18, 0) NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_reg] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[roles]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[roles](
	[cod_rol] [int] IDENTITY(1,1) NOT NULL,
	[nombre_rol] [nvarchar](255) NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_rol] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[sala]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[sala](
	[cod_sala] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [nvarchar](255) NULL,
	[activo] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_sala] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[usuarios_pst]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[usuarios_pst](
	[cod_usuario] [int] IDENTITY(1,1) NOT NULL,
	[usuario] [nvarchar](255) NULL,
	[pass] [nvarchar](255) NULL,
	[nombre] [nvarchar](255) NULL,
	[apellido] [nvarchar](255) NULL,
	[cod_rol] [int] NULL,
	[activo] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_usuario] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_planilla_pst]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_planilla_pst] AS


SELECT DISTINCT TOP (100) PERCENT
    pst.dbo.planillas_pst.cod_planilla,
    bdsystem.dbo.lotes.nombre AS lote,
    pst.dbo.planillas_pst.fec_turno,
    bdsystem.dbo.turno.NomTurno AS turno,
    bdsystem.dbo.empresas.descripcion AS empresa,
    bdsystem.dbo.proveedores.descripcion AS proveedor,
    bdsystem.dbo.especies.descripcion AS especie,
    bdsystem.dbo.subproceso.nombre AS proceso,
    pst.dbo.planillas_pst.cod_planillero,
    planillero.nombre + ' ' + planillero.apellido AS planillero_nombre,
    pst.dbo.planillas_pst.cod_supervisor,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre,
    pst.dbo.planillas_pst.guardado,
    pst.dbo.planillas_pst.fec_crea_planilla,
	user_crea.cod_usuario AS cod_usuario_crea,
	user_crea.usuario AS usuario_crea,
	pst.dbo.sala.nombre as sala

FROM
    pst.dbo.planillas_pst
LEFT OUTER JOIN bdsystem.dbo.lotes ON pst.dbo.planillas_pst.cod_lote = bdsystem.dbo.lotes.cod_lote
LEFT OUTER JOIN bdsystem.dbo.turno ON pst.dbo.planillas_pst.cod_turno = bdsystem.dbo.turno.CodTurno
LEFT OUTER JOIN bdsystem.dbo.empresas ON pst.dbo.planillas_pst.cod_empresa = bdsystem.dbo.empresas.cod_empresa
LEFT OUTER JOIN bdsystem.dbo.detalle_lote ON pst.dbo.planillas_pst.cod_lote = bdsystem.dbo.detalle_lote.cod_lote
LEFT OUTER JOIN bdsystem.dbo.proveedores ON bdsystem.dbo.detalle_lote.cod_proveedor = bdsystem.dbo.proveedores.cod_proveedor
LEFT OUTER JOIN bdsystem.dbo.especies ON pst.dbo.planillas_pst.cod_especie = bdsystem.dbo.especies.cod_especie
LEFT OUTER JOIN bdsystem.dbo.subproceso ON pst.dbo.planillas_pst.cod_proceso = bdsystem.dbo.subproceso.cod_sproceso
LEFT OUTER JOIN pst.dbo.usuarios_pst AS planillero ON pst.dbo.planillas_pst.cod_planillero = planillero.cod_usuario
LEFT OUTER JOIN pst.dbo.usuarios_pst AS supervisor ON pst.dbo.planillas_pst.cod_supervisor = supervisor.cod_usuario
LEFT OUTER JOIN pst.dbo.usuarios_pst AS user_crea ON pst.dbo.planillas_pst.cod_usuario_crea_planilla = user_crea.cod_usuario
LEFT OUTER JOIN pst.dbo.detalle_planilla_pst AS detalle ON pst.dbo.planillas_pst.cod_planilla = detalle.cod_planilla
LEFT OUTER JOIN pst.dbo.sala ON pst.dbo.sala.cod_sala = detalle.cod_sala
ORDER BY
    pst.dbo.planillas_pst.fec_turno,
    pst.dbo.planillas_pst.fec_crea_planilla;
GO
/****** Object:  View [dbo].[v_registro_planilla_pst]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_registro_planilla_pst] AS

SELECT
    rp.cod_reg,
    rp.cod_planilla,
    ini.nombre AS cInicial,
    fin.nombre AS cFinal,
    de.nombre AS destino,
    c.nombre AS calibre,
    ca.nombre AS calidad,
    rp.piezas,
    rp.kilos,
    rp.guardado
FROM
    pst.dbo.registro_planilla_pst AS rp
LEFT OUTER JOIN
    pst.dbo.corte AS ini ON rp.cod_corte_ini = ini.cod_corte
LEFT OUTER JOIN
    pst.dbo.corte AS fin ON rp.cod_corte_fin = fin.cod_corte
LEFT OUTER JOIN
    pst.dbo.calibre AS c ON rp.cod_calibre = c.cod_calib
LEFT OUTER JOIN
    pst.dbo.calidad AS ca ON rp.cod_calidad = ca.cod_cald
LEFT OUTER JOIN
    pst.dbo.destino AS de ON rp.cod_destino = de.cod_destino;
GO
/****** Object:  View [dbo].[v_planillas_pst_excel]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_planillas_pst_excel]
AS
SELECT DISTINCT 
                         TOP (100) PERCENT dbo.v_registro_planilla_pst.cod_reg, dbo.planillas_pst.cod_planilla, bdsystem.dbo.lotes.nombre AS lote, dbo.planillas_pst.fec_turno, bdsystem.dbo.turno.NomTurno AS turno, 
                         bdsystem.dbo.empresas.descripcion AS empresa, bdsystem.dbo.proveedores.descripcion AS proveedor, bdsystem.dbo.especies.descripcion AS especie, bdsystem.dbo.subproceso.nombre AS proceso, 
                         planillero.nombre + ' ' + planillero.apellido AS planillero_nombre, supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre, dbo.planillas_pst.fec_crea_planilla, dbo.v_planilla_pst.usuario_crea, 
                         dbo.v_registro_planilla_pst.cInicial, dbo.v_registro_planilla_pst.cFinal, dbo.v_registro_planilla_pst.destino, dbo.v_registro_planilla_pst.calibre, dbo.v_registro_planilla_pst.calidad, dbo.v_registro_planilla_pst.piezas, 
                         dbo.v_registro_planilla_pst.kilos, dbo.detalle_planilla_pst.cajas_entrega AS cajas_ef, dbo.detalle_planilla_pst.piezas_entrega AS piezas_ef, dbo.detalle_planilla_pst.kilos_entrega AS kilos_ef, 
                         dbo.detalle_planilla_pst.cajas_recepcion AS cajas_rp, dbo.detalle_planilla_pst.piezas_recepcion AS piezas_rp, dbo.detalle_planilla_pst.kilos_recepcion AS kilos_rp, dbo.detalle_planilla_pst.dotacion, dbo.sala.nombre AS sala, 
                         dbo.detalle_planilla_pst.observacion, bdsystem.dbo.detalle_lote.n_guia
FROM            dbo.planillas_pst INNER JOIN
                         bdsystem.dbo.lotes ON dbo.planillas_pst.cod_lote = bdsystem.dbo.lotes.cod_lote INNER JOIN
                         bdsystem.dbo.turno ON dbo.planillas_pst.cod_turno = bdsystem.dbo.turno.CodTurno INNER JOIN
                         bdsystem.dbo.empresas ON dbo.planillas_pst.cod_empresa = bdsystem.dbo.empresas.cod_empresa INNER JOIN
                         bdsystem.dbo.detalle_lote ON dbo.planillas_pst.cod_lote = bdsystem.dbo.detalle_lote.cod_lote INNER JOIN
                         bdsystem.dbo.proveedores ON bdsystem.dbo.detalle_lote.cod_proveedor = bdsystem.dbo.proveedores.cod_proveedor INNER JOIN
                         bdsystem.dbo.especies ON dbo.planillas_pst.cod_especie = bdsystem.dbo.especies.cod_especie INNER JOIN
                         bdsystem.dbo.subproceso ON dbo.planillas_pst.cod_proceso = bdsystem.dbo.subproceso.cod_sproceso INNER JOIN
                         dbo.usuarios_pst AS planillero ON dbo.planillas_pst.cod_planillero = planillero.cod_usuario INNER JOIN
                         dbo.usuarios_pst AS supervisor ON dbo.planillas_pst.cod_supervisor = supervisor.cod_usuario INNER JOIN
                         dbo.v_registro_planilla_pst ON dbo.v_registro_planilla_pst.cod_planilla = dbo.planillas_pst.cod_planilla INNER JOIN
                         dbo.v_planilla_pst ON dbo.v_planilla_pst.cod_planilla = dbo.planillas_pst.cod_planilla INNER JOIN
                         dbo.detalle_planilla_pst ON dbo.planillas_pst.cod_planilla = dbo.detalle_planilla_pst.cod_planilla INNER JOIN
                         dbo.sala ON dbo.detalle_planilla_pst.cod_sala = dbo.sala.cod_sala
WHERE        (dbo.planillas_pst.guardado = 1) AND (dbo.planillas_pst.fec_turno >= '2024-02-01') AND (dbo.planillas_pst.fec_turno > CONVERT(DATETIME, '2024-11-01 00:00:00', 102))
ORDER BY dbo.planillas_pst.fec_turno, dbo.planillas_pst.fec_crea_planilla
GO
/****** Object:  View [dbo].[v_data_usuario]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_data_usuario] AS

SELECT
    pst.dbo.usuarios_pst.cod_usuario,
    pst.dbo.usuarios_pst.usuario,
    pst.dbo.usuarios_pst.nombre + ' ' + pst.dbo.usuarios_pst.apellido AS nombre,
	pst.dbo.usuarios_pst.nombre AS snombre,
	pst.dbo.usuarios_pst.apellido AS sapellido,
    pst.dbo.usuarios_pst.pass,
    pst.dbo.usuarios_pst.cod_rol,
    pst.dbo.roles.nombre_rol AS rol,
	pst.dbo.usuarios_pst.activo
FROM
    pst.dbo.usuarios_pst
INNER JOIN
    pst.dbo.roles ON pst.dbo.roles.cod_rol = pst.dbo.usuarios_pst.cod_rol;
GO
/****** Object:  View [dbo].[v_produccion_excel]    Script Date: 20-02-2025 15:40:27 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_produccion_excel] AS
SELECT 
    p.fec_turno,
    t.NomTurno as turno,
    l.nombre as lote,
    e.descripcion as especie,
    sp.nombre as proceso,
    ci.nombre as corte_inicial,
    cf.nombre as corte_final,
    cal.nombre as calidad,
    cab.nombre as calibre,
    dp.piezas_entrega as piezas_iniciales,
    dp.piezas_recepcion as piezas_finales,
    dp.kilos_entrega as kilos_iniciales,
    dp.kilos_recepcion as kilos_finales,
    SUM(rp.piezas) as piezas_totales,
    SUM(rp.kilos) as kilos_totales,
    CASE 
        WHEN SUM(rp.piezas) > 0 
        THEN ROUND(SUM(rp.kilos) / SUM(rp.piezas), 2)
        ELSE 0 
    END as peso_promedio
FROM pst.dbo.planillas_pst p
INNER JOIN bdsystem.dbo.turno t ON p.cod_turno = t.CodTurno
INNER JOIN bdsystem.dbo.lotes l ON p.cod_lote = l.cod_lote
INNER JOIN bdsystem.dbo.especies e ON p.cod_especie = e.cod_especie
INNER JOIN bdsystem.dbo.subproceso sp ON p.cod_proceso = sp.cod_sproceso
INNER JOIN pst.dbo.registro_planilla_pst rp ON p.cod_planilla = rp.cod_planilla
INNER JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
INNER JOIN pst.dbo.corte ci ON rp.cod_corte_ini = ci.cod_corte
INNER JOIN pst.dbo.corte cf ON rp.cod_corte_fin = cf.cod_corte
INNER JOIN pst.dbo.calidad cal ON rp.cod_calidad = cal.cod_cald
INNER JOIN pst.dbo.calibre cab ON rp.cod_calibre = cab.cod_calib
WHERE p.guardado = 1 
GROUP BY 
    p.fec_turno,
    t.NomTurno,
    l.nombre,
    e.descripcion,
    sp.nombre,
    ci.nombre,
    cf.nombre,
    cal.nombre,
    cab.nombre,
    dp.piezas_entrega,
    dp.piezas_recepcion,
    dp.kilos_entrega,
    dp.kilos_recepcion
GO
ALTER TABLE [dbo].[planillas_pst] ADD  DEFAULT (getdate()) FOR [fec_crea_planilla]
GO
ALTER TABLE [dbo].[detalle_planilla_pst]  WITH CHECK ADD FOREIGN KEY([cod_planilla])
REFERENCES [dbo].[planillas_pst] ([cod_planilla])
GO
ALTER TABLE [dbo].[registro_planilla_pst]  WITH CHECK ADD FOREIGN KEY([cod_planilla])
REFERENCES [dbo].[planillas_pst] ([cod_planilla])
GO
ALTER TABLE [dbo].[usuarios_pst]  WITH CHECK ADD FOREIGN KEY([cod_rol])
REFERENCES [dbo].[roles] ([cod_rol])
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[41] 4[20] 2[15] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = -480
         Left = 0
      End
      Begin Tables = 
         Begin Table = "planillas_pst"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 136
               Right = 261
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "lotes (bdsystem.dbo)"
            Begin Extent = 
               Top = 138
               Left = 38
               Bottom = 268
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "turno (bdsystem.dbo)"
            Begin Extent = 
               Top = 270
               Left = 38
               Bottom = 383
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "empresas (bdsystem.dbo)"
            Begin Extent = 
               Top = 384
               Left = 38
               Bottom = 514
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "detalle_lote (bdsystem.dbo)"
            Begin Extent = 
               Top = 516
               Left = 38
               Bottom = 646
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 3
         End
         Begin Table = "proveedores (bdsystem.dbo)"
            Begin Extent = 
               Top = 648
               Left = 38
               Bottom = 778
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "especies (bdsystem.dbo)"
            Begin Extent = 
               Top = 780
               Left = 38
 ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_planillas_pst_excel'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane2', @value=N'              Bottom = 910
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "subproceso (bdsystem.dbo)"
            Begin Extent = 
               Top = 912
               Left = 38
               Bottom = 1042
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "planillero"
            Begin Extent = 
               Top = 1044
               Left = 38
               Bottom = 1174
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "supervisor"
            Begin Extent = 
               Top = 1176
               Left = 38
               Bottom = 1306
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "v_registro_planilla_pst"
            Begin Extent = 
               Top = 1308
               Left = 38
               Bottom = 1438
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "v_planilla_pst"
            Begin Extent = 
               Top = 1440
               Left = 38
               Bottom = 1570
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "detalle_planilla_pst"
            Begin Extent = 
               Top = 1572
               Left = 38
               Bottom = 1702
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "sala"
            Begin Extent = 
               Top = 1704
               Left = 38
               Bottom = 1817
               Right = 247
            End
            DisplayFlags = 280
            TopColumn = 0
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
      Begin ColumnWidths = 9
         Width = 284
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 11
         Column = 1440
         Alias = 900
         Table = 1170
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 3585
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_planillas_pst_excel'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=2 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_planillas_pst_excel'
GO
USE [master]
GO
ALTER DATABASE [pst] SET  READ_WRITE 
GO








