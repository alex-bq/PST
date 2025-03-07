-- Script para agregar nuevos campos a la tabla informes_turno

-- Verificar si la columna horas_trabajadas_empaque ya existe
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'informes_turno' AND COLUMN_NAME = 'horas_trabajadas_empaque' AND TABLE_SCHEMA = 'pst')
BEGIN
    -- Agregar columna horas_trabajadas_empaque
    ALTER TABLE pst.dbo.informes_turno ADD horas_trabajadas_empaque FLOAT NULL;
    PRINT 'Columna horas_trabajadas_empaque agregada exitosamente';
END
ELSE
BEGIN
    PRINT 'La columna horas_trabajadas_empaque ya existe';
END

-- Verificar si la columna tiempo_muerto_empaque ya existe
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'informes_turno' AND COLUMN_NAME = 'tiempo_muerto_empaque' AND TABLE_SCHEMA = 'pst')
BEGIN
    -- Agregar columna tiempo_muerto_empaque
    ALTER TABLE pst.dbo.informes_turno ADD tiempo_muerto_empaque INT NULL;
    PRINT 'Columna tiempo_muerto_empaque agregada exitosamente';
END
ELSE
BEGIN
    PRINT 'La columna tiempo_muerto_empaque ya existe';
END

-- Verificar si la columna productividad_empaque ya existe
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'informes_turno' AND COLUMN_NAME = 'productividad_empaque' AND TABLE_SCHEMA = 'pst')
BEGIN
    -- Agregar columna productividad_empaque
    ALTER TABLE pst.dbo.informes_turno ADD productividad_empaque FLOAT NULL;
    PRINT 'Columna productividad_empaque agregada exitosamente';
END
ELSE
BEGIN
    PRINT 'La columna productividad_empaque ya existe';
END

-- Actualizar valores por defecto para registros existentes
UPDATE pst.dbo.informes_turno 
SET 
    horas_trabajadas_empaque = 0 
WHERE 
    horas_trabajadas_empaque IS NULL;

UPDATE pst.dbo.informes_turno 
SET 
    tiempo_muerto_empaque = 0 
WHERE 
    tiempo_muerto_empaque IS NULL;

UPDATE pst.dbo.informes_turno 
SET 
    productividad_empaque = 0 
WHERE 
    productividad_empaque IS NULL;

PRINT 'Valores por defecto actualizados para registros existentes';
