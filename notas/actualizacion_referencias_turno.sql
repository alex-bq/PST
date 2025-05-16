USE [pst]
GO

-- Actualizar vista v_planilla_pst
IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_planilla_pst')
    DROP VIEW [dbo].[v_planilla_pst]
GO

CREATE VIEW [dbo].[v_planilla_pst] AS
SELECT DISTINCT TOP (100) PERCENT
    pst.dbo.planillas_pst.cod_planilla,
    bdsystem.dbo.lotes.nombre AS lote,
    pst.dbo.planillas_pst.fec_turno,
    tt.nombre AS turno,
    bdsystem.dbo.empresas.descripcion AS empresa,
    bdsystem.dbo.proveedores.descripcion AS proveedor,
    bdsystem.dbo.especies.descripcion AS especie,
    bdsystem.dbo.subproceso.nombre AS proceso,
    pst.dbo.planillas_pst.cod_planillero,
    planillero.nombre + ' ' + planillero.apellido AS planillero_nombre,
    pst.dbo.planillas_pst.cod_supervisor,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre,
    pst.dbo.planillas_pst.cod_jefe_turno,
    jefe_turno.nombre + ' ' + jefe_turno.apellido AS jefe_turno_nombre,
    pst.dbo.planillas_pst.guardado,
    pst.dbo.planillas_pst.fec_crea_planilla,
    user_crea.cod_usuario AS cod_usuario_crea,
    user_crea.usuario AS usuario_crea,
    pst.dbo.sala.nombre as sala,
    CONVERT(TIME(0), pst.dbo.planillas_pst.hora_inicio) as hora_inicio,
    CONVERT(TIME(0), pst.dbo.planillas_pst.hora_termino) as hora_termino,
    pst.dbo.planillas_pst.cod_tipo_planilla,
    tp.nombre AS tipo_planilla_nombre
FROM
    pst.dbo.planillas_pst
LEFT OUTER JOIN bdsystem.dbo.lotes ON pst.dbo.planillas_pst.cod_lote = bdsystem.dbo.lotes.cod_lote
LEFT OUTER JOIN administracion.dbo.tipos_turno tt ON pst.dbo.planillas_pst.cod_turno = tt.id
LEFT OUTER JOIN bdsystem.dbo.empresas ON pst.dbo.planillas_pst.cod_empresa = bdsystem.dbo.empresas.cod_empresa
LEFT OUTER JOIN bdsystem.dbo.detalle_lote ON pst.dbo.planillas_pst.cod_lote = bdsystem.dbo.detalle_lote.cod_lote
LEFT OUTER JOIN bdsystem.dbo.proveedores ON bdsystem.dbo.detalle_lote.cod_proveedor = bdsystem.dbo.proveedores.cod_proveedor
LEFT OUTER JOIN bdsystem.dbo.especies ON pst.dbo.planillas_pst.cod_especie = bdsystem.dbo.especies.cod_especie
LEFT OUTER JOIN bdsystem.dbo.subproceso ON pst.dbo.planillas_pst.cod_proceso = bdsystem.dbo.subproceso.cod_sproceso
LEFT OUTER JOIN pst.dbo.usuarios_pst AS planillero ON pst.dbo.planillas_pst.cod_planillero = planillero.cod_usuario
LEFT OUTER JOIN pst.dbo.usuarios_pst AS supervisor ON pst.dbo.planillas_pst.cod_supervisor = supervisor.cod_usuario
LEFT OUTER JOIN pst.dbo.usuarios_pst AS jefe_turno ON pst.dbo.planillas_pst.cod_jefe_turno = jefe_turno.cod_usuario
LEFT OUTER JOIN pst.dbo.usuarios_pst AS user_crea ON pst.dbo.planillas_pst.cod_usuario_crea_planilla = user_crea.cod_usuario
LEFT OUTER JOIN pst.dbo.detalle_planilla_pst AS detalle ON pst.dbo.planillas_pst.cod_planilla = detalle.cod_planilla
LEFT OUTER JOIN pst.dbo.sala ON pst.dbo.sala.cod_sala = detalle.cod_sala
LEFT OUTER JOIN pst.dbo.tipo_planilla tp ON pst.dbo.planillas_pst.cod_tipo_planilla = tp.cod_tipo_planilla
WHERE tt.activo = 1
GO

-- Actualizar vista v_resumen_turno
IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_resumen_turno')
    DROP VIEW [dbo].[v_resumen_turno]
GO

CREATE VIEW [dbo].[v_resumen_turno] AS
SELECT 
    p.fec_turno,
    tt.nombre as turno,
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
    pst.dbo.planillas_pst p
    INNER JOIN pst.dbo.detalle_planilla_pst d ON p.cod_planilla = d.cod_planilla
    INNER JOIN administracion.dbo.tipos_turno tt ON p.cod_turno = tt.id
WHERE 
    p.guardado = 1
    AND tt.activo = 1
GROUP BY 
    p.fec_turno,
    tt.nombre
GO

-- Actualizar función fn_GetInformesDiarios
IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fn_GetInformesDiarios]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
    DROP FUNCTION [dbo].[fn_GetInformesDiarios]
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
        tt.nombre as turno,
        CONCAT(u.nombre, ' ', u.apellido) as jefe_turno_nom,
        u.cod_usuario as jefe_turno,
        i.comentarios,
        SUM(d.dotacion_real) as dotacion_total,
        SUM(d.dotacion_esperada) as dotacion_esperada,
        SUM(d.kilos_entrega) as total_kilos_entrega,
        SUM(d.kilos_recepcion) as total_kilos_recepcion
    FROM dbo.informes_turno i
    JOIN administracion.dbo.tipos_turno tt ON i.cod_turno = tt.id
    JOIN dbo.usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
    LEFT JOIN dbo.detalle_informe_sala d ON i.cod_informe = d.cod_informe
    WHERE i.fecha_turno = @fecha 
    AND i.estado = 1
    AND tt.activo = 1
    GROUP BY 
        i.cod_informe,
        i.fecha_turno,
        i.cod_turno,
        tt.nombre,
        u.nombre,
        u.apellido,
        u.cod_usuario,
        i.comentarios
)
GO

PRINT 'Actualización de referencias a turno completada exitosamente'
GO 