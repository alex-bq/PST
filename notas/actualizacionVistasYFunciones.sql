USE [pst]
GO

-- Eliminar todas las vistas existentes
IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_data_usuario')
    DROP VIEW [dbo].[v_data_usuario]
GO

IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_informe_por_turno')
    DROP VIEW [dbo].[v_informe_por_turno]
GO

IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_planilla_pst')
    DROP VIEW [dbo].[v_planilla_pst]
GO

IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_registro_planilla_pst')
    DROP VIEW [dbo].[v_registro_planilla_pst]
GO

IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_planillas_pst_excel')
    DROP VIEW [dbo].[v_planillas_pst_excel]
GO

IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_resumen_diario')
    DROP VIEW [dbo].[v_resumen_diario]
GO

IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_resumen_informes')
    DROP VIEW [dbo].[v_resumen_informes]
GO

IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_resumen_turno')
    DROP VIEW [dbo].[v_resumen_turno]
GO

IF EXISTS (SELECT * FROM sys.views WHERE name = 'vw_analisis_informes')
    DROP VIEW [dbo].[vw_analisis_informes]
GO

-- Eliminar todas las funciones existentes
IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fn_GetDetalleProcesamiento]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
    DROP FUNCTION [dbo].[fn_GetDetalleProcesamiento]
GO

IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fn_GetInformacionPorSala]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
    DROP FUNCTION [dbo].[fn_GetInformacionPorSala]
GO

IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fn_GetInformesDiarios]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
    DROP FUNCTION [dbo].[fn_GetInformesDiarios]
GO

IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fn_GetTiemposMuertos]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
    DROP FUNCTION [dbo].[fn_GetTiemposMuertos]
GO

IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fn_tiempos_muertos_dashboard]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
    DROP FUNCTION [dbo].[fn_tiempos_muertos_dashboard]
GO

-- Crear vistas
CREATE VIEW [dbo].[v_data_usuario] AS
SELECT
    usuarios_pst.cod_usuario,
    usuarios_pst.usuario,
    usuarios_pst.nombre + ' ' + usuarios_pst.apellido AS nombre,
    usuarios_pst.nombre AS snombre,
    usuarios_pst.apellido AS sapellido,
    usuarios_pst.pass,
    usuarios_pst.cod_rol,
    roles.nombre_rol AS rol,
    usuarios_pst.activo
FROM
    usuarios_pst
INNER JOIN
    roles ON roles.cod_rol = usuarios_pst.cod_rol;
GO

CREATE VIEW [dbo].[v_informe_por_turno] AS
SELECT 
    p.fec_turno as fecha,
    p.cod_turno,
    t.NomTurno as nombre_turno,
    p.cod_supervisor,
    u.nombre as nombre_supervisor,
    COUNT(DISTINCT r.cod_planilla) as total_registros,
    ISNULL(dp.dotacion, 0) as total_dotacion,
    ISNULL(CAST(dp.productividad AS DECIMAL(10,2)), 0) as promedio_productividad,
    ISNULL(CAST(dp.rendimiento AS DECIMAL(10,2)), 0) as promedio_rendimiento,
    ISNULL(CAST(dp.kilos_entrega AS DECIMAL(10,2)), 0) as total_kilos_entrega,
    ISNULL(CAST(dp.kilos_recepcion AS DECIMAL(10,2)), 0) as total_kilos_recepcion
FROM planillas_pst p
LEFT JOIN detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
LEFT JOIN registro_planilla_pst r ON p.cod_planilla = r.cod_planilla
LEFT JOIN bdsystem.dbo.turno t ON p.cod_turno = t.codTurno
LEFT JOIN v_data_usuario u ON p.cod_supervisor = u.cod_usuario
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

CREATE VIEW [dbo].[v_planilla_pst] AS
SELECT DISTINCT TOP (100) PERCENT
    planillas_pst.cod_planilla,
    bdsystem.dbo.lotes.nombre AS lote,
    planillas_pst.fec_turno,
    bdsystem.dbo.turno.NomTurno AS turno,
    bdsystem.dbo.empresas.descripcion AS empresa,
    bdsystem.dbo.proveedores.descripcion AS proveedor,
    bdsystem.dbo.especies.descripcion AS especie,
    bdsystem.dbo.subproceso.nombre AS proceso,
    planillas_pst.cod_planillero,
    planillero.nombre + ' ' + planillero.apellido AS planillero_nombre,
    planillas_pst.cod_supervisor,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre,
    planillas_pst.cod_jefe_turno,
    jefe_turno.nombre + ' ' + jefe_turno.apellido AS jefe_turno_nombre,
    planillas_pst.guardado,
    planillas_pst.fec_crea_planilla,
    user_crea.cod_usuario AS cod_usuario_crea,
    user_crea.usuario AS usuario_crea,
    sala.nombre as sala,
    CONVERT(TIME(0), planillas_pst.hora_inicio) as hora_inicio,
    CONVERT(TIME(0), planillas_pst.hora_termino) as hora_termino,
    planillas_pst.cod_tipo_planilla,
    tp.nombre AS tipo_planilla_nombre
FROM
    planillas_pst
LEFT OUTER JOIN bdsystem.dbo.lotes ON planillas_pst.cod_lote = bdsystem.dbo.lotes.cod_lote
LEFT OUTER JOIN bdsystem.dbo.turno ON planillas_pst.cod_turno = bdsystem.dbo.turno.CodTurno
LEFT OUTER JOIN bdsystem.dbo.empresas ON planillas_pst.cod_empresa = bdsystem.dbo.empresas.cod_empresa
LEFT OUTER JOIN bdsystem.dbo.detalle_lote ON planillas_pst.cod_lote = bdsystem.dbo.detalle_lote.cod_lote
LEFT OUTER JOIN bdsystem.dbo.proveedores ON bdsystem.dbo.detalle_lote.cod_proveedor = bdsystem.dbo.proveedores.cod_proveedor
LEFT OUTER JOIN bdsystem.dbo.especies ON planillas_pst.cod_especie = bdsystem.dbo.especies.cod_especie
LEFT OUTER JOIN bdsystem.dbo.subproceso ON planillas_pst.cod_proceso = bdsystem.dbo.subproceso.cod_sproceso
LEFT OUTER JOIN usuarios_pst AS planillero ON planillas_pst.cod_planillero = planillero.cod_usuario
LEFT OUTER JOIN usuarios_pst AS supervisor ON planillas_pst.cod_supervisor = supervisor.cod_usuario
LEFT OUTER JOIN usuarios_pst AS jefe_turno ON planillas_pst.cod_jefe_turno = jefe_turno.cod_usuario
LEFT OUTER JOIN usuarios_pst AS user_crea ON planillas_pst.cod_usuario_crea_planilla = user_crea.cod_usuario
LEFT OUTER JOIN detalle_planilla_pst AS detalle ON planillas_pst.cod_planilla = detalle.cod_planilla
LEFT OUTER JOIN sala ON sala.cod_sala = detalle.cod_sala
LEFT OUTER JOIN tipo_planilla tp ON planillas_pst.cod_tipo_planilla = tp.cod_tipo_planilla
ORDER BY
    planillas_pst.fec_turno,
    planillas_pst.fec_crea_planilla;
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
    registro_planilla_pst AS rp
LEFT OUTER JOIN
    corte AS ini ON rp.cod_corte_ini = ini.cod_corte
LEFT OUTER JOIN
    corte AS fin ON rp.cod_corte_fin = fin.cod_corte
LEFT OUTER JOIN
    calibre AS c ON rp.cod_calibre = c.cod_calib
LEFT OUTER JOIN
    calidad AS ca ON rp.cod_calidad = ca.cod_cald
LEFT OUTER JOIN
    destino AS de ON rp.cod_destino = de.cod_destino;
GO

CREATE VIEW [dbo].[v_planillas_pst_excel] AS 
SELECT DISTINCT TOP (100) PERCENT
    v_registro_planilla_pst.cod_reg,
    planillas_pst.cod_planilla,
    bdsystem.dbo.lotes.nombre AS lote,
    planillas_pst.fec_turno,
    bdsystem.dbo.turno.NomTurno AS turno,
    bdsystem.dbo.empresas.descripcion AS empresa,
    bdsystem.dbo.proveedores.descripcion AS proveedor,
    bdsystem.dbo.especies.descripcion AS especie,
    bdsystem.dbo.subproceso.nombre AS proceso,
    planillero.nombre + ' ' + planillero.apellido AS planillero,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor,
    jefe_turno.nombre + ' ' + jefe_turno.apellido AS jefe_turno,
    sala.nombre AS sala,
    v_registro_planilla_pst.cInicial,
    v_registro_planilla_pst.cFinal,
    v_registro_planilla_pst.destino,
    v_registro_planilla_pst.calibre,
    v_registro_planilla_pst.calidad,
    v_registro_planilla_pst.piezas,
    v_registro_planilla_pst.kilos,
    detalle_planilla_pst.dotacion,
    detalle_planilla_pst.rendimiento,
    detalle_planilla_pst.productividad,
    CONVERT(TIME(0), planillas_pst.hora_inicio) as hora_inicio,
    CONVERT(TIME(0), planillas_pst.hora_termino) as hora_termino,
    tp.nombre AS tipo_planilla
FROM
    planillas_pst
LEFT OUTER JOIN v_registro_planilla_pst ON planillas_pst.cod_planilla = v_registro_planilla_pst.cod_planilla
LEFT OUTER JOIN bdsystem.dbo.lotes ON planillas_pst.cod_lote = bdsystem.dbo.lotes.cod_lote
LEFT OUTER JOIN bdsystem.dbo.turno ON planillas_pst.cod_turno = bdsystem.dbo.turno.CodTurno
LEFT OUTER JOIN bdsystem.dbo.empresas ON planillas_pst.cod_empresa = bdsystem.dbo.empresas.cod_empresa
LEFT OUTER JOIN bdsystem.dbo.detalle_lote ON planillas_pst.cod_lote = bdsystem.dbo.detalle_lote.cod_lote
LEFT OUTER JOIN bdsystem.dbo.proveedores ON bdsystem.dbo.detalle_lote.cod_proveedor = bdsystem.dbo.proveedores.cod_proveedor
LEFT OUTER JOIN bdsystem.dbo.especies ON planillas_pst.cod_especie = bdsystem.dbo.especies.cod_especie
LEFT OUTER JOIN bdsystem.dbo.subproceso ON planillas_pst.cod_proceso = bdsystem.dbo.subproceso.cod_sproceso
LEFT OUTER JOIN usuarios_pst AS planillero ON planillas_pst.cod_planillero = planillero.cod_usuario
LEFT OUTER JOIN usuarios_pst AS supervisor ON planillas_pst.cod_supervisor = supervisor.cod_usuario
LEFT OUTER JOIN usuarios_pst AS jefe_turno ON planillas_pst.cod_jefe_turno = jefe_turno.cod_usuario
LEFT OUTER JOIN detalle_planilla_pst ON planillas_pst.cod_planilla = detalle_planilla_pst.cod_planilla
LEFT OUTER JOIN sala ON detalle_planilla_pst.cod_sala = sala.cod_sala
LEFT OUTER JOIN tipo_planilla tp ON planillas_pst.cod_tipo_planilla = tp.cod_tipo_planilla
ORDER BY
    planillas_pst.fec_turno DESC,
    planillas_pst.cod_planilla,
    v_registro_planilla_pst.cod_reg;
GO

CREATE VIEW [dbo].[v_resumen_diario] AS
SELECT 
    p.fec_turno,
    COUNT(DISTINCT p.cod_planilla) as total_planillas,
    SUM(d.dotacion) as total_dotacion,
    SUM(d.cajas_recepcion) as total_cajas_recepcion,
    SUM(d.kilos_recepcion) as total_kilos_recepcion,
    SUM(d.piezas_recepcion) as total_piezas_recepcion,
    SUM(d.cajas_entrega) as total_cajas_entrega,
    SUM(d.kilos_entrega) as total_kilos_entrega,
    SUM(d.piezas_entrega) as total_piezas_entrega,
    CAST(SUM(d.kilos_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as kg_persona,
    CAST(SUM(d.piezas_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as piezas_persona,
    CAST((SUM(d.kilos_entrega) * 100.0) / NULLIF(SUM(d.kilos_recepcion), 0) AS DECIMAL(10,2)) as porcentaje_rendimiento_kilos,
    CAST((SUM(d.piezas_entrega) * 100.0) / NULLIF(SUM(d.piezas_recepcion), 0) AS DECIMAL(10,2)) as porcentaje_rendimiento_piezas
FROM 
    planillas_pst p
    INNER JOIN detalle_planilla_pst d ON p.cod_planilla = d.cod_planilla
WHERE 
    p.guardado = 1
GROUP BY 
    p.fec_turno;
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
    informes_turno i
    LEFT JOIN detalle_informe_sala d ON i.cod_informe = d.cod_informe
WHERE 
    i.estado = 1
GROUP BY 
    i.cod_informe, i.fecha_turno, i.cod_turno;
GO

CREATE VIEW [dbo].[v_resumen_turno] AS
SELECT 
    p.fec_turno,
    t.NomTurno as turno,
    COUNT(DISTINCT p.cod_planilla) as total_planillas,
    SUM(d.dotacion) as total_dotacion,
    SUM(d.cajas_recepcion) as total_cajas_recepcion,
    SUM(d.kilos_recepcion) as total_kilos_recepcion,
    SUM(d.piezas_recepcion) as total_piezas_recepcion,
    SUM(d.cajas_entrega) as total_cajas_entrega,
    SUM(d.kilos_entrega) as total_kilos_entrega,
    SUM(d.piezas_entrega) as total_piezas_entrega,
    CAST(SUM(d.kilos_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as kg_persona,
    CAST(SUM(d.piezas_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as piezas_persona
FROM 
    planillas_pst p
    INNER JOIN detalle_planilla_pst d ON p.cod_planilla = d.cod_planilla
    INNER JOIN bdsystem.dbo.turno t ON p.cod_turno = t.CodTurno
WHERE 
    p.guardado = 1
GROUP BY 
    p.fec_turno,
    t.NomTurno;
GO

CREATE VIEW [dbo].[vw_analisis_informes] AS
SELECT 
    i.cod_informe,
    i.fecha_turno,
    i.cod_turno,
    CASE i.cod_turno 
        WHEN 1 THEN 'Día' 
        WHEN 2 THEN 'Tarde' 
        WHEN 3 THEN 'Noche' 
    END AS turno_nombre,
    i.cod_jefe_turno,
    u.nombre AS jefe_turno_nombre,
    i.fecha_creacion,
    s.cod_sala,
    s.nombre,
    d.tipo_planilla,
    d.dotacion_real,
    d.dotacion_esperada,
    CASE 
        WHEN d.dotacion_esperada > 0 
        THEN ((d.dotacion_esperada - d.dotacion_real) * 100.0 / d.dotacion_esperada) 
        ELSE 0 
    END AS porcentaje_ausentismo,
    d.kilos_entrega,
    d.kilos_recepcion,
    d.kilos_premium,
    d.premium AS porcentaje_premium,
    d.horas_trabajadas,
    d.tiempo_muerto_minutos,
    d.horas_trabajadas * 60 - d.tiempo_muerto_minutos AS minutos_efectivos,
    d.rendimiento,
    d.productividad,
    CASE 
        WHEN d.horas_trabajadas > 0 AND d.dotacion_real > 0 
        THEN d.kilos_recepcion / (d.horas_trabajadas * d.dotacion_real) 
        ELSE 0 
    END AS productividad_kg_hora_persona,
    CASE 
        WHEN d.kilos_entrega > 0 
        THEN (d.kilos_premium * 100.0 / d.kilos_entrega) 
        ELSE 0 
    END AS rendimiento_premium,
    DATEPART(WEEK, i.fecha_turno) AS semana,
    DATEPART(MONTH, i.fecha_turno) AS mes,
    DATEPART(YEAR, i.fecha_turno) AS año,
    DATEPART(WEEKDAY, i.fecha_turno) AS dia_semana,
    CASE 
        WHEN d.horas_trabajadas > 0 
        THEN (d.tiempo_muerto_minutos * 100.0 / (d.horas_trabajadas * 60)) 
        ELSE 0 
    END AS porcentaje_tiempo_muerto,
    i.comentarios,
    i.estado,
    d.piezas_entrega,
    d.piezas_recepcion
FROM 
    informes_turno AS i 
    INNER JOIN detalle_informe_sala AS d ON i.cod_informe = d.cod_informe 
    INNER JOIN sala AS s ON d.cod_sala = s.cod_sala 
    LEFT OUTER JOIN usuarios_pst AS u ON i.cod_jefe_turno = u.cod_usuario
WHERE 
    i.estado = 1;
GO

-- Crear funciones
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
        emp.descripcion,
        emp.cod_empresa,
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
    FROM planillas_pst p
    JOIN detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
    JOIN registro_planilla_pst rp ON p.cod_planilla = rp.cod_planilla
    JOIN corte c_ini ON rp.cod_corte_ini = c_ini.cod_corte
    JOIN corte c_fin ON rp.cod_corte_fin = c_fin.cod_corte
    JOIN destino d ON rp.cod_destino = d.cod_destino
    JOIN calibre cal ON rp.cod_calibre = cal.cod_calib
    JOIN calidad cld ON rp.cod_calidad = cld.cod_cald
    JOIN tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
    JOIN bdsystem.dbo.empresas emp ON emp.cod_empresa = p.cod_empresa
    WHERE p.fec_turno = @fecha 
    AND p.cod_turno = @turno
    AND (
        (tp.nombre = 'Porciones' AND c_fin.nombre IN ('PORCION SIN PIEL', 'PORCION CON PIEL', 'PORCIONES'))
        OR
        (tp.nombre != 'Porciones' AND cld.nombre = 'PREMIUM' AND c_fin.nombre != 'COLLARES EN MITADES')
    )
);
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
        s.cod_sala,
        s.nombre as nombre_sala,
        d.tipo_planilla,
        d.dotacion_real,
        d.dotacion_esperada,
        d.kilos_entrega,
        d.kilos_recepcion,
        d.piezas_entrega,
        d.piezas_recepcion,
        d.horas_trabajadas,
        d.tiempo_muerto_minutos,
        d.rendimiento,
        d.productividad,
        d.kilos_premium,
        d.premium
    FROM informes_turno i
    JOIN detalle_informe_sala d ON i.cod_informe = d.cod_informe
    JOIN sala s ON d.cod_sala = s.cod_sala
    WHERE i.fecha_turno = @fecha 
    AND i.cod_turno = @turno 
    AND i.estado = 1
);
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
        i.cod_informe,
        i.fecha_turno,
        i.cod_turno as orden_turno,
        t.NomTurno as turno,
        CONCAT(u.nombre, ' ', u.apellido) as jefe_turno_nom,
        u.cod_usuario as jefe_turno,
        i.comentarios,
        SUM(d.dotacion_real) as dotacion_total,
        SUM(d.dotacion_esperada) as dotacion_esperada,
        SUM(d.kilos_entrega) as total_kilos_entrega,
        SUM(d.kilos_recepcion) as total_kilos_recepcion
    FROM informes_turno i
    JOIN bdsystem.dbo.turno t ON i.cod_turno = t.CodTurno
    JOIN usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
    LEFT JOIN detalle_informe_sala d ON i.cod_informe = d.cod_informe
    WHERE i.fecha_turno = @fecha AND i.estado = 1
    GROUP BY 
        i.cod_informe,
        i.fecha_turno,
        i.cod_turno,
        t.NomTurno,
        u.nombre,
        u.apellido,
        u.cod_usuario,
        i.comentarios
);
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
        d.nombre,
        d.cod_departamento,
        tp.cod_tipo_planilla,
        tp.nombre as tipo_planilla,
        tm.causa as motivo,
        tm.duracion_minutos,
        SUM(tm.duracion_minutos) OVER (PARTITION BY dp.cod_sala, tp.cod_tipo_planilla) as total_minutos_sala_tipo
    FROM planillas_pst p
    JOIN detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
    JOIN tiempos_muertos tm ON p.cod_planilla = tm.cod_planilla
    JOIN tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
    JOIN departamentos d ON tm.cod_departamento = d.cod_departamento
    WHERE p.fec_turno = @fecha 
    AND p.cod_turno = @turno
);
GO

CREATE FUNCTION [dbo].[fn_tiempos_muertos_dashboard]
(
    @fecha_inicio DATE,
    @fecha_fin DATE,
    @tipo_planilla VARCHAR(50)
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
        p.fec_turno,
        CASE p.cod_turno 
            WHEN 1 THEN 'Día'
            WHEN 2 THEN 'Tarde'
            WHEN 3 THEN 'Noche'
        END AS turno_nombre,
        d.nombre AS departamento,
        SUM(tm.duracion_minutos) as total_minutos_muertos,
        CASE 
            WHEN SUM(p.tiempo_trabajado * 60) > 0 
            THEN ROUND((SUM(tm.duracion_minutos) * 100.0 / SUM(p.tiempo_trabajado * 60)), 2)
            ELSE 0 
        END as porcentaje_tiempo_muerto,
        s.nombre AS sala,
        tp.nombre as tipo_planilla,
        STUFF((
            SELECT ', ' + causa
            FROM tiempos_muertos tm2
            WHERE tm2.cod_planilla = p.cod_planilla
            FOR XML PATH('')), 1, 2, '') as causas
    FROM planillas_pst p
    INNER JOIN tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
    INNER JOIN detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
    INNER JOIN sala s ON dp.cod_sala = s.cod_sala
    INNER JOIN tiempos_muertos tm ON p.cod_planilla = tm.cod_planilla
    INNER JOIN departamentos d ON tm.cod_departamento = d.cod_departamento
    WHERE 
        p.fec_turno BETWEEN @fecha_inicio AND @fecha_fin
        AND tp.nombre = @tipo_planilla
        AND p.guardado = 1
    GROUP BY 
        p.fec_turno,
        p.cod_turno,
        d.nombre,
        s.nombre,
        tp.nombre,
        p.cod_planilla
);
GO

PRINT 'Vistas y funciones actualizadas exitosamente'
GO