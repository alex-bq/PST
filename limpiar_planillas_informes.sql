USE [pst]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

PRINT '================================================================';
PRINT '  LIMPIAR PLANILLAS E INFORMES DE TURNO';
PRINT '================================================================';
PRINT '';
PRINT 'DESCRIPCIÓN:';
PRINT '  Este script elimina TODAS las planillas e informes de turno';
PRINT '  manteniendo intactos los datos maestros y la estructura.';
PRINT '';
PRINT 'TABLAS A LIMPIAR:';
PRINT '  - planillas_pst (planillas principales)';
PRINT '  - detalle_planilla_pst (detalles de planillas)';
PRINT '  - registro_planilla_pst (registros de planillas)';
PRINT '  - tiempos_muertos (tiempos muertos)';
PRINT '  - informes_turno (informes de turno)';
PRINT '  - detalle_informe_sala (detalles de informes)';
PRINT '  - comentarios_informe_sala (comentarios)';
PRINT '  - fotos_informe (fotos)';
PRINT '';
PRINT 'TABLAS QUE SE MANTIENEN:';
PRINT '  - usuarios_pst (usuarios)';
PRINT '  - sala (salas)';
PRINT '  - tipo_planilla (tipos de planilla)';
PRINT '  - departamentos (departamentos)';
PRINT '  - calibre, calidad, destino, corte (datos maestros)';
PRINT '  - roles (roles de usuario)';
PRINT '';

-- ============================================
-- CONFIRMACIÓN DE SEGURIDAD
-- ============================================
PRINT '⚠️  ADVERTENCIA: Esta operación NO se puede deshacer';
PRINT '⚠️  Asegúrate de tener un backup antes de continuar';
PRINT '';

-- Mostrar conteos actuales
DECLARE @planillas_count INT, @informes_count INT, @detalle_planillas_count INT, @tiempos_muertos_count INT;

SELECT @planillas_count = COUNT(*) FROM [dbo].[planillas_pst];
SELECT @informes_count = COUNT(*) FROM [dbo].[informes_turno];
SELECT @detalle_planillas_count = COUNT(*) FROM [dbo].[detalle_planilla_pst];
SELECT @tiempos_muertos_count = COUNT(*) FROM [dbo].[tiempos_muertos];

PRINT 'DATOS ACTUALES:';
PRINT '  Planillas: ' + CAST(@planillas_count AS NVARCHAR(10));
PRINT '  Informes de turno: ' + CAST(@informes_count AS NVARCHAR(10));
PRINT '  Detalles de planillas: ' + CAST(@detalle_planillas_count AS NVARCHAR(10));
PRINT '  Tiempos muertos: ' + CAST(@tiempos_muertos_count AS NVARCHAR(10));
PRINT '';

-- ============================================
-- DESHABILITAR TRIGGERS TEMPORALMENTE
-- ============================================
PRINT 'Deshabilitando triggers temporalmente...';

-- Deshabilitar trigger de detalle_planilla_pst si existe
IF OBJECT_ID('dbo.trg_detalle_planillas_pst', 'TR') IS NOT NULL
BEGIN
    ALTER TABLE [dbo].[planillas_pst] DISABLE TRIGGER [trg_detalle_planillas_pst];
    PRINT '  ✓ Trigger trg_detalle_planillas_pst deshabilitado';
END

PRINT '';

-- ============================================
-- ELIMINAR DATOS EN ORDEN CORRECTO (RESPETANDO FOREIGN KEYS)
-- ============================================

PRINT 'PASO 1: Eliminando fotos de informes...';
DELETE FROM [dbo].[fotos_informe];
PRINT '  ✓ Fotos de informes eliminadas';

PRINT '';
PRINT 'PASO 2: Eliminando comentarios de informes...';
DELETE FROM [dbo].[comentarios_informe_sala];
PRINT '  ✓ Comentarios de informes eliminados';

PRINT '';
PRINT 'PASO 3: Eliminando detalles de informes...';
DELETE FROM [dbo].[detalle_informe_sala];
PRINT '  ✓ Detalles de informes eliminados';

PRINT '';
PRINT 'PASO 4: Eliminando informes de turno...';
DELETE FROM [dbo].[informes_turno];
PRINT '  ✓ Informes de turno eliminados';

PRINT '';
PRINT 'PASO 5: Eliminando tiempos muertos...';
DELETE FROM [dbo].[tiempos_muertos];
PRINT '  ✓ Tiempos muertos eliminados';

PRINT '';
PRINT 'PASO 6: Eliminando registros de planillas...';
DELETE FROM [dbo].[registro_planilla_pst];
PRINT '  ✓ Registros de planillas eliminados';

PRINT '';
PRINT 'PASO 7: Eliminando detalles de planillas...';
DELETE FROM [dbo].[detalle_planilla_pst];
PRINT '  ✓ Detalles de planillas eliminados';

PRINT '';
PRINT 'PASO 8: Eliminando planillas principales...';
DELETE FROM [dbo].[planillas_pst];
PRINT '  ✓ Planillas principales eliminadas';

-- ============================================
-- REHABILITAR TRIGGERS
-- ============================================
PRINT '';
PRINT 'Rehabilitando triggers...';

-- Rehabilitar trigger de detalle_planilla_pst si existe
IF OBJECT_ID('dbo.trg_detalle_planillas_pst', 'TR') IS NOT NULL
BEGIN
    ALTER TABLE [dbo].[planillas_pst] ENABLE TRIGGER [trg_detalle_planillas_pst];
    PRINT '  ✓ Trigger trg_detalle_planillas_pst rehabilitado';
END

-- ============================================
-- VERIFICACIÓN FINAL
-- ============================================
PRINT '';
PRINT 'VERIFICACIÓN FINAL:';

-- Verificar que las tablas estén vacías
DECLARE @planillas_final INT, @informes_final INT, @detalle_planillas_final INT, @tiempos_muertos_final INT;

SELECT @planillas_final = COUNT(*) FROM [dbo].[planillas_pst];
SELECT @informes_final = COUNT(*) FROM [dbo].[informes_turno];
SELECT @detalle_planillas_final = COUNT(*) FROM [dbo].[detalle_planilla_pst];
SELECT @tiempos_muertos_final = COUNT(*) FROM [dbo].[tiempos_muertos];

PRINT '  Planillas: ' + CAST(@planillas_final AS NVARCHAR(10)) + ' (debería ser 0)';
PRINT '  Informes de turno: ' + CAST(@informes_final AS NVARCHAR(10)) + ' (debería ser 0)';
PRINT '  Detalles de planillas: ' + CAST(@detalle_planillas_final AS NVARCHAR(10)) + ' (debería ser 0)';
PRINT '  Tiempos muertos: ' + CAST(@tiempos_muertos_final AS NVARCHAR(10)) + ' (debería ser 0)';

-- Verificar que los datos maestros se mantuvieron
DECLARE @usuarios_count INT, @salas_count INT, @tipos_planilla_count INT;

SELECT @usuarios_count = COUNT(*) FROM [dbo].[usuarios_pst];
SELECT @salas_count = COUNT(*) FROM [dbo].[sala];
SELECT @tipos_planilla_count = COUNT(*) FROM [dbo].[tipo_planilla];

PRINT '';
PRINT 'DATOS MAESTROS CONSERVADOS:';
PRINT '  Usuarios: ' + CAST(@usuarios_count AS NVARCHAR(10));
PRINT '  Salas: ' + CAST(@salas_count AS NVARCHAR(10));
PRINT '  Tipos de planilla: ' + CAST(@tipos_planilla_count AS NVARCHAR(10));

-- ============================================
-- RESET DE IDENTITIES (OPCIONAL)
-- ============================================
PRINT '';
PRINT 'RESET DE IDENTITIES:';

-- Reset de planillas_pst
DBCC CHECKIDENT ('[dbo].[planillas_pst]', RESEED, 0);
PRINT '  ✓ Identity de planillas_pst reseteado a 0';

-- Reset de informes_turno
DBCC CHECKIDENT ('[dbo].[informes_turno]', RESEED, 0);
PRINT '  ✓ Identity de informes_turno reseteado a 0';

-- Reset de tiempos_muertos
DBCC CHECKIDENT ('[dbo].[tiempos_muertos]', RESEED, 0);
PRINT '  ✓ Identity de tiempos_muertos reseteado a 0';

-- ============================================
-- RESUMEN FINAL
-- ============================================
PRINT '';
PRINT '================================================================';
PRINT '  LIMPIEZA COMPLETADA EXITOSAMENTE';
PRINT '================================================================';
PRINT '';
PRINT 'RESUMEN:';
PRINT '  ✓ Todas las planillas eliminadas';
PRINT '  ✓ Todos los informes de turno eliminados';
PRINT '  ✓ Todos los detalles y registros eliminados';
PRINT '  ✓ Todos los tiempos muertos eliminados';
PRINT '  ✓ Todas las fotos y comentarios eliminados';
PRINT '  ✓ Identities reseteados a 0';
PRINT '  ✓ Datos maestros conservados';
PRINT '';
PRINT 'PRÓXIMO PASO:';
PRINT '  El sistema está listo para comenzar con datos frescos';
PRINT '  Puedes crear nuevas planillas e informes desde cero';
PRINT '';
PRINT '¡Limpieza completada!';
PRINT '================================================================';
GO

