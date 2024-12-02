DROP VIEW v_data_usuario;
DROP VIEW v_planilla_pst;
DROP VIEW v_registro_planilla_pst;
DROP VIEW v_planillas_pst_excel;

-- v_data_usuario
CREATE VIEW v_data_usuario AS
SELECT
    u.cod_usuario,
    u.usuario,
    u.nombre + ' ' + u.apellido AS nombre,
    u.nombre AS snombre,
    u.apellido AS sapellido,
    u.pass,
    u.cod_rol,
    r.nombre_rol AS rol,
    u.activo
FROM
    pst.dbo.usuarios_pst u
INNER JOIN
    pst.dbo.roles r ON r.cod_rol = u.cod_rol;

-- v_planilla_pst
CREATE VIEW v_planilla_pst AS
SELECT DISTINCT TOP (100) PERCENT
    pst.cod_planilla,
    l.nombre AS lote,
    pst.fec_turno,
    t.NomTurno AS turno,
    e.descripcion AS empresa,
    p.descripcion AS proveedor,
    esp.descripcion AS especie,
    sp.nombre AS proceso,
    pst.cod_planillero,
    planillero.nombre + ' ' + planillero.apellido AS planillero_nombre,
    pst.cod_supervisor,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre,
    pst.guardado,
    pst.fec_crea_planilla,
    user_crea.cod_usuario AS cod_usuario_crea,
    user_crea.usuario AS usuario_crea,
    s.nombre as sala
FROM
    pst.dbo.planillas_pst pst
LEFT OUTER JOIN [RemoteServer].[bdsystem].[dbo].[lotes] l ON pst.cod_lote = l.cod_lote
LEFT OUTER JOIN [RemoteServer].[bdsystem].[dbo].[turno] t ON pst.cod_turno = t.CodTurno
LEFT OUTER JOIN [RemoteServer].[bdsystem].[dbo].[empresas] e ON pst.cod_empresa = e.cod_empresa
LEFT OUTER JOIN [RemoteServer].[bdsystem].[dbo].[detalle_lote] dl ON pst.cod_lote = dl.cod_lote
LEFT OUTER JOIN [RemoteServer].[bdsystem].[dbo].[proveedores] p ON dl.cod_proveedor = p.cod_proveedor
LEFT OUTER JOIN [RemoteServer].[bdsystem].[dbo].[especies] esp ON pst.cod_especie = esp.cod_especie
LEFT OUTER JOIN [RemoteServer].[bdsystem].[dbo].[subproceso] sp ON pst.cod_proceso = sp.cod_sproceso
LEFT OUTER JOIN pst.dbo.usuarios_pst AS planillero ON pst.cod_planillero = planillero.cod_usuario
LEFT OUTER JOIN pst.dbo.usuarios_pst AS supervisor ON pst.cod_supervisor = supervisor.cod_usuario
LEFT OUTER JOIN pst.dbo.usuarios_pst AS user_crea ON pst.cod_usuario_crea_planilla = user_crea.cod_usuario
LEFT OUTER JOIN pst.dbo.detalle_planilla_pst AS detalle ON pst.cod_planilla = detalle.cod_planilla
LEFT OUTER JOIN pst.dbo.sala s ON s.cod_sala = detalle.cod_sala
ORDER BY
    pst.fec_turno,
    pst.fec_crea_planilla;

-- v_registro_planilla_pst
CREATE VIEW v_registro_planilla_pst AS
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

-- v_planillas_pst_excel
CREATE VIEW v_planillas_pst_excel AS 
SELECT DISTINCT TOP (100) PERCENT
    vrp.cod_reg,
    pst.cod_planilla,
    l.nombre AS lote,
    pst.fec_turno,
    t.NomTurno AS turno,
    e.descripcion AS empresa,
    p.descripcion AS proveedor,
    esp.descripcion AS especie,
    sp.nombre AS proceso,
    planillero.nombre + ' ' + planillero.apellido AS planillero_nombre,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre,
    pst.fec_crea_planilla,
    vp.usuario_crea,
    vrp.cInicial,
    vrp.cFinal,
    vrp.destino,
    vrp.calibre,
    vrp.calidad,
    vrp.piezas,
    vrp.kilos,
    dp.cajas_entrega AS cajas_ef,
    dp.piezas_entrega AS piezas_ef,
    dp.kilos_entrega AS kilos_ef, 
    dp.cajas_recepcion AS cajas_rp,
    dp.piezas_recepcion AS piezas_rp,
    dp.kilos_recepcion AS kilos_rp,
    dp.dotacion,
    s.nombre AS sala,
    dp.observacion
FROM
    pst.dbo.planillas_pst pst
INNER JOIN [RemoteServer].[bdsystem].[dbo].[lotes] l ON pst.cod_lote = l.cod_lote
INNER JOIN [RemoteServer].[bdsystem].[dbo].[turno] t ON pst.cod_turno = t.CodTurno
INNER JOIN [RemoteServer].[bdsystem].[dbo].[empresas] e ON pst.cod_empresa = e.cod_empresa
INNER JOIN [RemoteServer].[bdsystem].[dbo].[detalle_lote] dl ON pst.cod_lote = dl.cod_lote
INNER JOIN [RemoteServer].[bdsystem].[dbo].[proveedores] p ON dl.cod_proveedor = p.cod_proveedor
INNER JOIN [RemoteServer].[bdsystem].[dbo].[especies] esp ON pst.cod_especie = esp.cod_especie
INNER JOIN [RemoteServer].[bdsystem].[dbo].[subproceso] sp ON pst.cod_proceso = sp.cod_sproceso
INNER JOIN pst.dbo.usuarios_pst AS planillero ON pst.cod_planillero = planillero.cod_usuario
INNER JOIN pst.dbo.usuarios_pst AS supervisor ON pst.cod_supervisor = supervisor.cod_usuario
INNER JOIN v_registro_planilla_pst vrp ON vrp.cod_planilla = pst.cod_planilla
INNER JOIN v_planilla_pst vp ON vp.cod_planilla = pst.cod_planilla
INNER JOIN pst.dbo.detalle_planilla_pst dp ON pst.cod_planilla = dp.cod_planilla
INNER JOIN pst.dbo.sala s ON dp.cod_sala = s.cod_sala
WHERE pst.guardado = 1
AND pst.fec_turno >= '2024-02-01' -- Fecha de inicio 
AND pst.fec_turno <= '2024-03-01' -- Fecha de fin
ORDER BY
    pst.fec_turno,
    pst.fec_crea_planilla;