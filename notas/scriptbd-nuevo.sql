USE [master]
GO
/****** Object:  Database [pst_2]    Script Date: 20-02-2025 15:31:51 ******/
CREATE DATABASE [pst_2]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'pst_2', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL12.MSSQLSERVER\MSSQL\DATA\pst_2.mdf' , SIZE = 5120KB , MAXSIZE = UNLIMITED, FILEGROWTH = 1024KB )
 LOG ON 
( NAME = N'pst_2_log', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL12.MSSQLSERVER\MSSQL\DATA\pst_2_log.ldf' , SIZE = 3776KB , MAXSIZE = 2048GB , FILEGROWTH = 10%)
GO
ALTER DATABASE [pst_2] SET COMPATIBILITY_LEVEL = 120
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [pst_2].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [pst_2] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [pst_2] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [pst_2] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [pst_2] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [pst_2] SET ARITHABORT OFF 
GO
ALTER DATABASE [pst_2] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [pst_2] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [pst_2] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [pst_2] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [pst_2] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [pst_2] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [pst_2] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [pst_2] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [pst_2] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [pst_2] SET  DISABLE_BROKER 
GO
ALTER DATABASE [pst_2] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [pst_2] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [pst_2] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [pst_2] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [pst_2] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [pst_2] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [pst_2] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [pst_2] SET RECOVERY FULL 
GO
ALTER DATABASE [pst_2] SET  MULTI_USER 
GO
ALTER DATABASE [pst_2] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [pst_2] SET DB_CHAINING OFF 
GO
ALTER DATABASE [pst_2] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [pst_2] SET TARGET_RECOVERY_TIME = 0 SECONDS 
GO
ALTER DATABASE [pst_2] SET DELAYED_DURABILITY = DISABLED 
GO
USE [pst_2]
GO
/****** Object:  Table [dbo].[calibre]    Script Date: 20-02-2025 15:31:54 ******/
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
/****** Object:  Table [dbo].[calidad]    Script Date: 20-02-2025 15:31:54 ******/
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
/****** Object:  Table [dbo].[corte]    Script Date: 20-02-2025 15:31:54 ******/
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
/****** Object:  Table [dbo].[departamentos]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[departamentos](
	[cod_departamento] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [nvarchar](255) NULL,
	[activo] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_departamento] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[destino]    Script Date: 20-02-2025 15:31:54 ******/
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
/****** Object:  Table [dbo].[detalle_informe_sala]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[detalle_informe_sala](
	[cod_detalle_informe] [int] IDENTITY(1,1) NOT NULL,
	[cod_informe] [int] NULL,
	[cod_sala] [int] NULL,
	[dotacion_real] [int] NULL,
	[dotacion_esperada] [int] NULL,
	[kilos_entrega] [float] NULL,
	[kilos_recepcion] [float] NULL,
	[horas_trabajadas] [decimal](10, 2) NULL,
	[tiempo_muerto_minutos] [int] NULL,
	[rendimiento] [decimal](10, 2) NULL,
	[productividad] [decimal](10, 2) NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_detalle_informe] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[detalle_planilla_pst]    Script Date: 20-02-2025 15:31:54 ******/
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
	[productividad] [decimal](10, 2) NULL,
	[rendimiento] [decimal](10, 2) NULL,
	[embolsado_terminado] [int] NULL,
	[kilos_terminado] [float] NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_detalle] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[informes_turno]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[informes_turno](
	[cod_informe] [int] IDENTITY(1,1) NOT NULL,
	[fecha_turno] [date] NOT NULL,
	[cod_turno] [smallint] NOT NULL,
	[cod_jefe_turno] [numeric](18, 0) NULL,
	[cod_usuario_crea] [int] NULL,
	[comentarios] [nvarchar](max) NULL,
	[fecha_creacion] [datetime] NULL,
	[estado] [smallint] NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_informe] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[planillas_pst]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[planillas_pst](
	[cod_planilla] [smallint] IDENTITY(1,1) NOT NULL,
	[cod_lote] [bigint] NULL,
	[fec_turno] [date] NULL,
	[hora_inicio] [time](7) NULL,
	[hora_termino] [time](7) NULL,
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
	[cod_jefe_turno] [numeric](18, 0) NULL,
	[cod_tipo_planilla] [int] NULL,
	[tiempo_trabajado] [decimal](10, 2) NULL,
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
/****** Object:  Table [dbo].[registro_planilla_pst]    Script Date: 20-02-2025 15:31:54 ******/
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
/****** Object:  Table [dbo].[roles]    Script Date: 20-02-2025 15:31:54 ******/
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
/****** Object:  Table [dbo].[sala]    Script Date: 20-02-2025 15:31:54 ******/
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
/****** Object:  Table [dbo].[tiempos_muertos]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tiempos_muertos](
	[cod_tiempo_muerto] [int] IDENTITY(1,1) NOT NULL,
	[cod_planilla] [smallint] NULL,
	[causa] [nvarchar](max) NULL,
	[hora_inicio] [time](7) NULL,
	[hora_termino] [time](7) NULL,
	[duracion_minutos] [int] NULL,
	[cod_departamento] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_tiempo_muerto] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[tipo_planilla]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tipo_planilla](
	[cod_tipo_planilla] [int] IDENTITY(1,1) NOT NULL,
	[nombre] [nvarchar](255) NULL,
PRIMARY KEY CLUSTERED 
(
	[cod_tipo_planilla] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[usuarios_pst]    Script Date: 20-02-2025 15:31:54 ******/
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
/****** Object:  View [dbo].[v_data_usuario]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_data_usuario] AS

SELECT
    pst_2.dbo.usuarios_pst.cod_usuario,
    pst_2.dbo.usuarios_pst.usuario,
    pst_2.dbo.usuarios_pst.nombre + ' ' + pst_2.dbo.usuarios_pst.apellido AS nombre,
	pst_2.dbo.usuarios_pst.nombre AS snombre,
	pst_2.dbo.usuarios_pst.apellido AS sapellido,
    pst_2.dbo.usuarios_pst.pass,
    pst_2.dbo.usuarios_pst.cod_rol,
    pst_2.dbo.roles.nombre_rol AS rol,
	pst_2.dbo.usuarios_pst.activo
FROM
    pst_2.dbo.usuarios_pst
INNER JOIN
    pst_2.dbo.roles ON pst_2.dbo.roles.cod_rol = pst_2.dbo.usuarios_pst.cod_rol;

GO
/****** Object:  View [dbo].[v_informe_por_turno]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_informe_por_turno] AS
SELECT 
    p.fec_turno as fecha,
    p.cod_turno,
    t.NomTurno as nombre_turno,
    p.cod_supervisor,  -- asumiendo que este es el nombre correcto
    u.nombre as nombre_supervisor,
    -- resto de los campos existentes
    COUNT(DISTINCT r.cod_planilla) as total_registros,  -- cambiado de cod_registro
    ISNULL(dp.dotacion, 0) as total_dotacion,
    ISNULL(CAST(dp.productividad AS DECIMAL(10,2)), 0) as promedio_productividad,
    ISNULL(CAST(dp.rendimiento AS DECIMAL(10,2)), 0) as promedio_rendimiento,
    ISNULL(CAST(dp.kilos_entrega AS DECIMAL(10,2)), 0) as total_kilos_entrega,
    ISNULL(CAST(dp.kilos_recepcion AS DECIMAL(10,2)), 0) as total_kilos_recepcion
FROM pst_2.dbo.planillas_pst p
LEFT JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
LEFT JOIN pst_2.dbo.registro_planilla_pst r ON p.cod_planilla = r.cod_planilla
LEFT JOIN bdsystem.dbo.turno t ON p.cod_turno = t.codTurno
LEFT JOIN pst_2.dbo.v_data_usuario u ON p.cod_supervisor = u.cod_usuario  -- ajustado el join
WHERE p.guardado = 1
GROUP BY 
    p.fec_turno,
    p.cod_turno,
    t.NomTurno,
    p.cod_supervisor,
    u.nombre,
    dp.dotacion,
    dp.productividad,
    dp.rendimiento,
    dp.kilos_entrega,
    dp.kilos_recepcion;
GO
/****** Object:  View [dbo].[v_planilla_pst]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_planilla_pst] AS
SELECT DISTINCT TOP (100) PERCENT
    pst_2.dbo.planillas_pst.cod_planilla,
    bdsystem.dbo.lotes.nombre AS lote,
    pst_2.dbo.planillas_pst.fec_turno,
    bdsystem.dbo.turno.NomTurno AS turno,
    bdsystem.dbo.empresas.descripcion AS empresa,
    bdsystem.dbo.proveedores.descripcion AS proveedor,
    bdsystem.dbo.especies.descripcion AS especie,
    bdsystem.dbo.subproceso.nombre AS proceso,
    pst_2.dbo.planillas_pst.cod_planillero,
    planillero.nombre + ' ' + planillero.apellido AS planillero_nombre,
    pst_2.dbo.planillas_pst.cod_supervisor,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre,
    pst_2.dbo.planillas_pst.cod_jefe_turno,
    jefe_turno.nombre + ' ' + jefe_turno.apellido AS jefe_turno_nombre,
    pst_2.dbo.planillas_pst.guardado,
    pst_2.dbo.planillas_pst.fec_crea_planilla,
    user_crea.cod_usuario AS cod_usuario_crea,
    user_crea.usuario AS usuario_crea,
    pst_2.dbo.sala.nombre as sala,
    CONVERT(TIME(0), pst_2.dbo.planillas_pst.hora_inicio) as hora_inicio,
    CONVERT(TIME(0), pst_2.dbo.planillas_pst.hora_termino) as hora_termino,
    pst_2.dbo.planillas_pst.cod_tipo_planilla,
    tp.nombre AS tipo_planilla_nombre
FROM
    pst_2.dbo.planillas_pst
LEFT OUTER JOIN bdsystem.dbo.lotes ON pst_2.dbo.planillas_pst.cod_lote = bdsystem.dbo.lotes.cod_lote
LEFT OUTER JOIN bdsystem.dbo.turno ON pst_2.dbo.planillas_pst.cod_turno = bdsystem.dbo.turno.CodTurno
LEFT OUTER JOIN bdsystem.dbo.empresas ON pst_2.dbo.planillas_pst.cod_empresa = bdsystem.dbo.empresas.cod_empresa
LEFT OUTER JOIN bdsystem.dbo.detalle_lote ON pst_2.dbo.planillas_pst.cod_lote = bdsystem.dbo.detalle_lote.cod_lote
LEFT OUTER JOIN bdsystem.dbo.proveedores ON bdsystem.dbo.detalle_lote.cod_proveedor = bdsystem.dbo.proveedores.cod_proveedor
LEFT OUTER JOIN bdsystem.dbo.especies ON pst_2.dbo.planillas_pst.cod_especie = bdsystem.dbo.especies.cod_especie
LEFT OUTER JOIN bdsystem.dbo.subproceso ON pst_2.dbo.planillas_pst.cod_proceso = bdsystem.dbo.subproceso.cod_sproceso
LEFT OUTER JOIN pst_2.dbo.usuarios_pst AS planillero ON pst_2.dbo.planillas_pst.cod_planillero = planillero.cod_usuario
LEFT OUTER JOIN pst_2.dbo.usuarios_pst AS supervisor ON pst_2.dbo.planillas_pst.cod_supervisor = supervisor.cod_usuario
LEFT OUTER JOIN pst_2.dbo.usuarios_pst AS jefe_turno ON pst_2.dbo.planillas_pst.cod_jefe_turno = jefe_turno.cod_usuario
LEFT OUTER JOIN pst_2.dbo.usuarios_pst AS user_crea ON pst_2.dbo.planillas_pst.cod_usuario_crea_planilla = user_crea.cod_usuario
LEFT OUTER JOIN pst_2.dbo.detalle_planilla_pst AS detalle ON pst_2.dbo.planillas_pst.cod_planilla = detalle.cod_planilla
LEFT OUTER JOIN pst_2.dbo.sala ON pst_2.dbo.sala.cod_sala = detalle.cod_sala
LEFT OUTER JOIN pst_2.dbo.tipo_planilla tp ON pst_2.dbo.planillas_pst.cod_tipo_planilla = tp.cod_tipo_planilla
ORDER BY
    pst_2.dbo.planillas_pst.fec_turno,
    pst_2.dbo.planillas_pst.fec_crea_planilla;
GO
/****** Object:  View [dbo].[v_registro_planilla_pst]    Script Date: 20-02-2025 15:31:54 ******/
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
    pst_2.dbo.registro_planilla_pst AS rp
LEFT OUTER JOIN
    pst_2.dbo.corte AS ini ON rp.cod_corte_ini = ini.cod_corte
LEFT OUTER JOIN
    pst_2.dbo.corte AS fin ON rp.cod_corte_fin = fin.cod_corte
LEFT OUTER JOIN
    pst_2.dbo.calibre AS c ON rp.cod_calibre = c.cod_calib
LEFT OUTER JOIN
    pst_2.dbo.calidad AS ca ON rp.cod_calidad = ca.cod_cald
LEFT OUTER JOIN
    pst_2.dbo.destino AS de ON rp.cod_destino = de.cod_destino;

GO
/****** Object:  View [dbo].[v_planillas_pst_excel]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_planillas_pst_excel] AS 

SELECT DISTINCT TOP (100) PERCENT
	pst_2.dbo.v_registro_planilla_pst.cod_reg,
    pst_2.dbo.planillas_pst.cod_planilla,
    bdsystem.dbo.lotes.nombre AS lote,
    pst_2.dbo.planillas_pst.fec_turno,
    bdsystem.dbo.turno.NomTurno AS turno,
    bdsystem.dbo.empresas.descripcion AS empresa,
    bdsystem.dbo.proveedores.descripcion AS proveedor,
    bdsystem.dbo.especies.descripcion AS especie,
    bdsystem.dbo.subproceso.nombre AS proceso,
    planillero.nombre + ' ' + planillero.apellido AS planillero_nombre,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre,
    pst_2.dbo.planillas_pst.fec_crea_planilla,
    pst_2.dbo.v_planilla_pst.usuario_crea,
	pst_2.dbo.v_registro_planilla_pst.cInicial,
	pst_2.dbo.v_registro_planilla_pst.cFinal,
	pst_2.dbo.v_registro_planilla_pst.destino,
	pst_2.dbo.v_registro_planilla_pst.calibre,
	pst_2.dbo.v_registro_planilla_pst.calidad,
	pst_2.dbo.v_registro_planilla_pst.piezas,
	pst_2.dbo.v_registro_planilla_pst.kilos,
	pst_2.dbo.detalle_planilla_pst.cajas_entrega AS cajas_ef,
	pst_2.dbo.detalle_planilla_pst.piezas_entrega AS piezas_ef,
	pst_2.dbo.detalle_planilla_pst.kilos_entrega AS kilos_ef, 
	pst_2.dbo.detalle_planilla_pst.cajas_recepcion AS cajas_rp,
	pst_2.dbo.detalle_planilla_pst.piezas_recepcion AS piezas_rp,
	pst_2.dbo.detalle_planilla_pst.kilos_recepcion AS kilos_rp,
	pst_2.dbo.detalle_planilla_pst.dotacion AS dotacion,
	pst_2.dbo.sala.nombre AS sala,
	pst_2.dbo.detalle_planilla_pst.observacion
FROM
    pst_2.dbo.planillas_pst
INNER JOIN bdsystem.dbo.lotes ON pst_2.dbo.planillas_pst.cod_lote = bdsystem.dbo.lotes.cod_lote
INNER JOIN bdsystem.dbo.turno ON pst_2.dbo.planillas_pst.cod_turno = bdsystem.dbo.turno.CodTurno
INNER JOIN bdsystem.dbo.empresas ON pst_2.dbo.planillas_pst.cod_empresa = bdsystem.dbo.empresas.cod_empresa
INNER JOIN bdsystem.dbo.detalle_lote ON pst_2.dbo.planillas_pst.cod_lote = bdsystem.dbo.detalle_lote.cod_lote
INNER JOIN bdsystem.dbo.proveedores ON bdsystem.dbo.detalle_lote.cod_proveedor = bdsystem.dbo.proveedores.cod_proveedor
INNER JOIN bdsystem.dbo.especies ON pst_2.dbo.planillas_pst.cod_especie = bdsystem.dbo.especies.cod_especie
INNER JOIN bdsystem.dbo.subproceso ON pst_2.dbo.planillas_pst.cod_proceso = bdsystem.dbo.subproceso.cod_sproceso
INNER JOIN pst_2.dbo.usuarios_pst AS planillero ON pst_2.dbo.planillas_pst.cod_planillero = planillero.cod_usuario
INNER JOIN pst_2.dbo.usuarios_pst AS supervisor ON pst_2.dbo.planillas_pst.cod_supervisor = supervisor.cod_usuario

INNER JOIN v_registro_planilla_pst ON pst_2.dbo.v_registro_planilla_pst.cod_planilla = pst_2.dbo.planillas_pst.cod_planilla
INNER JOIN v_planilla_pst ON pst_2.dbo.v_planilla_pst.cod_planilla = pst_2.dbo.planillas_pst.cod_planilla
INNER JOIN pst_2.dbo.detalle_planilla_pst ON pst_2.dbo.planillas_pst.cod_planilla = pst_2.dbo.detalle_planilla_pst.cod_planilla
INNER JOIN pst_2.dbo.sala ON pst_2.dbo.detalle_planilla_pst.cod_sala = pst_2.dbo.sala.cod_sala

WHERE pst_2.dbo.planillas_pst.guardado = 1
-- Rango de fecha de planillas (Formato yyyy-mm-dd)
AND pst_2.dbo.planillas_pst.fec_turno >= '2024-02-01' -- Fecha de inicio 
AND pst_2.dbo.planillas_pst.fec_turno <= '2024-03-01' -- Fecha de fin
ORDER BY
    pst_2.dbo.planillas_pst.fec_turno,
    pst_2.dbo.planillas_pst.fec_crea_planilla;
GO
/****** Object:  UserDefinedFunction [dbo].[fn_GetDetalleProcesamiento]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Función 3: Obtener detalle de procesamiento por sala
CREATE FUNCTION [dbo].[fn_GetDetalleProcesamiento]
(
    @fecha DATE,
    @turno INT
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
    dp.cod_sala,
    tp.cod_tipo_planilla,
    tp.nombre as tipo_planilla,
    c_ini.nombre as corte_inicial,
    c_fin.nombre as corte_final,
    d.nombre as destino,
    cal.nombre as calibre,
    cld.nombre as calidad,
    rp.piezas,
    rp.kilos,
    SUM(rp.piezas) OVER (PARTITION BY dp.cod_sala, tp.cod_tipo_planilla) as total_piezas_sala_tipo,
    SUM(rp.kilos) OVER (PARTITION BY dp.cod_sala, tp.cod_tipo_planilla) as total_kilos_sala_tipo
FROM pst_2.dbo.planillas_pst p
JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
JOIN pst_2.dbo.registro_planilla_pst rp ON p.cod_planilla = rp.cod_planilla
JOIN pst_2.dbo.corte c_ini ON rp.cod_corte_ini = c_ini.cod_corte
JOIN pst_2.dbo.corte c_fin ON rp.cod_corte_fin = c_fin.cod_corte
JOIN pst_2.dbo.destino d ON rp.cod_destino = d.cod_destino
JOIN pst_2.dbo.calibre cal ON rp.cod_calibre = cal.cod_calib
JOIN pst_2.dbo.calidad cld ON rp.cod_calidad = cld.cod_cald
JOIN pst_2.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
WHERE p.fec_turno = @fecha 
AND p.cod_turno = @turno

);
GO
/****** Object:  UserDefinedFunction [dbo].[fn_GetInformacionPorSala]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[fn_GetInformacionPorSala]
(
    @fecha DATE,
    @turno INT
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
        s.nombre as nombre_sala,
        dp.cod_sala,
        tp.nombre as tipo_planilla,
        tp.cod_tipo_planilla,
        COUNT(DISTINCT p.cod_planilla) as cantidad_planillas,
        SUM(p.tiempo_trabajado) as horas_trabajadas ,
        SUM(dp.kilos_entrega) as kilos_entrega_total,
        SUM(dp.kilos_recepcion) as kilos_recepcion_total,
        CASE 
            WHEN tp.cod_tipo_planilla = 2 THEN SUM(dp.embolsado_terminado)
            ELSE NULL
        END as embolsado_terminado_total,
        CASE 
            WHEN tp.cod_tipo_planilla = 2 THEN SUM(dp.kilos_terminado)
            ELSE NULL
        END as kilos_terminado_total
    FROM pst_2.dbo.planillas_pst p
    JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
    JOIN pst_2.dbo.sala s ON dp.cod_sala = s.cod_sala
    JOIN pst_2.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
    WHERE p.fec_turno = @fecha 
    AND p.cod_turno = @turno
    GROUP BY 
        s.nombre,
        dp.cod_sala,
        tp.nombre,
        tp.cod_tipo_planilla
)
GO
/****** Object:  UserDefinedFunction [dbo].[fn_GetInformesDiarios]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[fn_GetInformesDiarios]
(
    @fecha DATE
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
        @fecha as fecha,
        t.NomTurno as turno,
        CONCAT(u.nombre, ' ', u.apellido) as jefe_turno_nom,
		u.cod_usuario as jefe_turno,
        COUNT(DISTINCT p.cod_planilla) as cantidad_planillas,
        AVG(dp.dotacion) as dotacion_promedio,
        ROUND(AVG(dp.productividad), 2) as productividad_promedio,
        SUM(dp.kilos_entrega) as total_kilos_entrega,
        SUM(dp.kilos_recepcion) as total_kilos_recepcion,
        -- Agregamos un campo para poder ordenar después
        CASE 
            WHEN t.NomTurno LIKE '%Dia%' THEN 1
            WHEN t.NomTurno LIKE '%Tarde%' THEN 2
            WHEN t.NomTurno LIKE '%Noche%' THEN 3
        END as orden_turno
    FROM pst_2.dbo.planillas_pst p
    JOIN bdsystem.dbo.turno t ON p.cod_turno = t.CodTurno
    JOIN pst_2.dbo.usuarios_pst u ON p.cod_jefe_turno = u.cod_usuario
    JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
    WHERE p.fec_turno = @fecha
    GROUP BY 
        t.NomTurno,
        u.nombre,
		u.cod_usuario,
        u.apellido
);
GO
/****** Object:  UserDefinedFunction [dbo].[fn_GetTiemposMuertos]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[fn_GetTiemposMuertos]
(
    @fecha DATE,
    @turno INT
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
    dp.cod_sala,
    tp.cod_tipo_planilla,
    tp.nombre as tipo_planilla,
    tm.causa as motivo,
    tm.duracion_minutos,
    SUM(tm.duracion_minutos) OVER (PARTITION BY dp.cod_sala, tp.cod_tipo_planilla) as total_minutos_sala_tipo
FROM pst_2.dbo.planillas_pst p
JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
JOIN pst_2.dbo.tiempos_muertos tm ON p.cod_planilla = tm.cod_planilla
JOIN pst_2.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
WHERE p.fec_turno = @fecha 
AND p.cod_turno = @turno
);
GO
/****** Object:  View [dbo].[v_resumen_diario]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Vista de resumen diario
CREATE VIEW [dbo].[v_resumen_diario] AS
SELECT 
    p.fec_turno,
    COUNT(DISTINCT p.cod_planilla) as total_planillas,
    SUM(d.dotacion) as total_dotacion,
    -- Datos de recepción
    SUM(d.cajas_recepcion) as total_cajas_recepcion,
    SUM(d.kilos_recepcion) as total_kilos_recepcion,
    SUM(d.piezas_recepcion) as total_piezas_recepcion,
    -- Datos de entrega
    SUM(d.cajas_entrega) as total_cajas_entrega,
    SUM(d.kilos_entrega) as total_kilos_entrega,
    SUM(d.piezas_entrega) as total_piezas_entrega,
    -- Cálculos de rendimiento
    CAST(SUM(d.kilos_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as kg_persona,
    CAST(SUM(d.piezas_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as piezas_persona,
    -- Porcentajes de rendimiento
    CAST((SUM(d.kilos_entrega) * 100.0) / NULLIF(SUM(d.kilos_recepcion), 0) AS DECIMAL(10,2)) as porcentaje_rendimiento_kilos,
    CAST((SUM(d.piezas_entrega) * 100.0) / NULLIF(SUM(d.piezas_recepcion), 0) AS DECIMAL(10,2)) as porcentaje_rendimiento_piezas
FROM 
    pst_2.dbo.planillas_pst p
    INNER JOIN pst_2.dbo.detalle_planilla_pst d ON p.cod_planilla = d.cod_planilla
WHERE 
    p.guardado = 1
GROUP BY 
    p.fec_turno;
GO
/****** Object:  View [dbo].[v_resumen_informes]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_resumen_informes] AS
SELECT 
    i.cod_informe,
    i.fecha_turno,
    i.cod_turno,
    SUM(d.dotacion_real) as dotacion_total_real,
    SUM(d.dotacion_esperada) as dotacion_total_esperada,
    CASE 
        WHEN SUM(d.dotacion_esperada) > 0 
        THEN ((SUM(d.dotacion_esperada) - SUM(d.dotacion_real)) / SUM(d.dotacion_esperada) * 100) 
        ELSE 0 
    END as porcentaje_ausentismo,
    SUM(d.kilos_entrega) as total_kilos_entrega,
    SUM(d.kilos_recepcion) as total_kilos_recepcion,
    AVG(d.rendimiento) as rendimiento_promedio,
    AVG(d.productividad) as productividad_promedio
FROM 
    pst_2.dbo.informes_turno i
    LEFT JOIN pst_2.dbo.detalle_informe_sala d ON i.cod_informe = d.cod_informe
WHERE 
    i.estado = 1
GROUP BY 
    i.cod_informe, i.fecha_turno, i.cod_turno;
GO
/****** Object:  View [dbo].[v_resumen_turno]    Script Date: 20-02-2025 15:31:54 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Vista de resumen por turno
CREATE VIEW [dbo].[v_resumen_turno] AS
SELECT 
    p.fec_turno,
    t.NomTurno as turno,
    COUNT(DISTINCT p.cod_planilla) as total_planillas,
    SUM(d.dotacion) as total_dotacion,
    -- Datos de recepción
    SUM(d.cajas_recepcion) as total_cajas_recepcion,
    SUM(d.kilos_recepcion) as total_kilos_recepcion,
    SUM(d.piezas_recepcion) as total_piezas_recepcion,
    -- Datos de entrega
    SUM(d.cajas_entrega) as total_cajas_entrega,
    SUM(d.kilos_entrega) as total_kilos_entrega,
    SUM(d.piezas_entrega) as total_piezas_entrega,
    -- Cálculos de rendimiento
    CAST(SUM(d.kilos_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as kg_persona,
    CAST(SUM(d.piezas_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as piezas_persona
FROM 
    pst_2.dbo.planillas_pst p
    INNER JOIN pst_2.dbo.detalle_planilla_pst d ON p.cod_planilla = d.cod_planilla
    INNER JOIN bdsystem.dbo.turno t ON p.cod_turno = t.CodTurno
WHERE 
    p.guardado = 1
GROUP BY 
    p.fec_turno,
    t.NomTurno;
GO
/****** Object:  Index [idx_detalle_informe_sala_informe]    Script Date: 20-02-2025 15:31:54 ******/
CREATE NONCLUSTERED INDEX [idx_detalle_informe_sala_informe] ON [dbo].[detalle_informe_sala]
(
	[cod_informe] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [idx_informes_turno_fecha]    Script Date: 20-02-2025 15:31:54 ******/
CREATE NONCLUSTERED INDEX [idx_informes_turno_fecha] ON [dbo].[informes_turno]
(
	[fecha_turno] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
ALTER TABLE [dbo].[departamentos] ADD  DEFAULT ((1)) FOR [activo]
GO
ALTER TABLE [dbo].[informes_turno] ADD  DEFAULT (getdate()) FOR [fecha_creacion]
GO
ALTER TABLE [dbo].[informes_turno] ADD  DEFAULT ((1)) FOR [estado]
GO
ALTER TABLE [dbo].[planillas_pst] ADD  DEFAULT (getdate()) FOR [fec_crea_planilla]
GO
ALTER TABLE [dbo].[detalle_informe_sala]  WITH CHECK ADD FOREIGN KEY([cod_informe])
REFERENCES [dbo].[informes_turno] ([cod_informe])
GO
ALTER TABLE [dbo].[detalle_informe_sala]  WITH CHECK ADD FOREIGN KEY([cod_sala])
REFERENCES [dbo].[sala] ([cod_sala])
GO
ALTER TABLE [dbo].[detalle_planilla_pst]  WITH CHECK ADD FOREIGN KEY([cod_planilla])
REFERENCES [dbo].[planillas_pst] ([cod_planilla])
GO
ALTER TABLE [dbo].[informes_turno]  WITH CHECK ADD FOREIGN KEY([cod_usuario_crea])
REFERENCES [dbo].[usuarios_pst] ([cod_usuario])
GO
ALTER TABLE [dbo].[planillas_pst]  WITH CHECK ADD  CONSTRAINT [FK_planillas_tipo_planilla] FOREIGN KEY([cod_tipo_planilla])
REFERENCES [dbo].[tipo_planilla] ([cod_tipo_planilla])
GO
ALTER TABLE [dbo].[planillas_pst] CHECK CONSTRAINT [FK_planillas_tipo_planilla]
GO
ALTER TABLE [dbo].[registro_planilla_pst]  WITH CHECK ADD FOREIGN KEY([cod_planilla])
REFERENCES [dbo].[planillas_pst] ([cod_planilla])
GO
ALTER TABLE [dbo].[tiempos_muertos]  WITH CHECK ADD FOREIGN KEY([cod_planilla])
REFERENCES [dbo].[planillas_pst] ([cod_planilla])
GO
ALTER TABLE [dbo].[tiempos_muertos]  WITH CHECK ADD  CONSTRAINT [FK_tiempos_muertos_departamento] FOREIGN KEY([cod_departamento])
REFERENCES [dbo].[departamentos] ([cod_departamento])
GO
ALTER TABLE [dbo].[tiempos_muertos] CHECK CONSTRAINT [FK_tiempos_muertos_departamento]
GO
ALTER TABLE [dbo].[usuarios_pst]  WITH CHECK ADD FOREIGN KEY([cod_rol])
REFERENCES [dbo].[roles] ([cod_rol])
GO
USE [master]
GO
ALTER DATABASE [pst_2] SET  READ_WRITE 
GO
