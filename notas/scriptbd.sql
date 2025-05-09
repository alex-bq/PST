-- Drops en orden correcto (primero las tablas que tienen foreign keys)
DROP TABLE pst_2.dbo.registro_planilla_pst;      -- Depende de planillas_pst
DROP TABLE pst_2.dbo.tiempos_muertos;            -- Depende de planillas_pst
DROP TABLE pst_2.dbo.detalle_planilla_pst;       -- Depende de planillas_pst
DROP TABLE pst_2.dbo.planillas_pst;              -- Tabla principal
DROP TABLE pst_2.dbo.usuarios_pst;               -- Depende de roles
DROP TABLE pst_2.dbo.corte;                      -- Tabla independiente
DROP TABLE pst_2.dbo.sala;                       -- Tabla independiente
DROP TABLE pst_2.dbo.calibre;                    -- Tabla independiente
DROP TABLE pst_2.dbo.calidad;                    -- Tabla independiente
DROP TABLE pst_2.dbo.destino;                    -- Tabla independiente
DROP TABLE pst_2.dbo.roles;                      -- Tabla referenciada por usuarios_pst
DROP TABLE pst_2.dbo.tipo_planilla;              -- Agregar este drop



-- Tables
CREATE TABLE pst_2.dbo.corte (
    cod_corte INT PRIMARY KEY  IDENTITY(1,1),
    nombre NVARCHAR(255),
    activo INT,

);

CREATE TABLE pst_2.dbo.sala (
    cod_sala INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(255),
    activo INT
);

CREATE TABLE pst_2.dbo.calibre (
    cod_calib INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(255),
    activo INT,

);

CREATE TABLE pst_2.dbo.calidad (
    cod_cald INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(255),
    activo INT
);

CREATE TABLE pst_2.dbo.roles (
    cod_rol INT PRIMARY KEY IDENTITY(1,1),
    nombre_rol NVARCHAR(255)
);

CREATE TABLE pst_2.dbo.planillas_pst (
    cod_planilla SMALLINT PRIMARY KEY IDENTITY(1,1),
    cod_lote BIGINT,
    fec_turno DATE,
    hora_inicio TIME,
    hora_termino TIME,
    tiempo_trabajado DECIMAL(10,2),
    cod_turno SMALLINT,
    cod_empresa NUMERIC(18,0),
    cod_proveedor numeric(18,0),
    cod_especie SMALLINT,
    cod_proceso SMALLINT,
    cod_planillero NUMERIC(18,0),
    cod_supervisor NUMERIC(18,0),
    cod_jefe_turno NUMERIC(18,0),
    fec_crea_planilla DATETIME DEFAULT GETDATE(),
    cod_usuario_crea_planilla INT,
    guardado NUMERIC(18,0),
    UNIQUE(cod_planilla) 
);

CREATE TABLE pst_2.dbo.detalle_planilla_pst (
    cod_detalle INT PRIMARY KEY IDENTITY(1,1),
    cod_planilla SMALLINT UNIQUE, 
    cajas_entrega INT,
    kilos_entrega FLOAT,
    piezas_entrega INT,
    cajas_recepcion INT,
    kilos_recepcion FLOAT,
    piezas_recepcion INT,
    embolsado_terminado INT,
    kilos_terminado FLOAT,
    dotacion INT,
    cod_sala INT,
    productividad DECIMAL(10,2),
    rendimiento DECIMAL(10,2),
    observacion NVARCHAR(MAX),
    FOREIGN KEY (cod_planilla) REFERENCES pst_2.dbo.planillas_pst(cod_planilla)
);

CREATE TABLE pst_2.dbo.registro_planilla_pst (
    cod_reg SMALLINT PRIMARY KEY IDENTITY(1,1),
    cod_planilla SMALLINT,
    cod_corte_ini BIGINT,
    cod_corte_fin BIGINT,
    cod_destino NUMERIC(18,0),
    cod_calibre SMALLINT,
    cod_calidad SMALLINT,
    piezas NUMERIC(18,0),
    kilos FLOAT,
    guardado NUMERIC(18,0),	
    FOREIGN KEY (cod_planilla) REFERENCES pst_2.dbo.planillas_pst(cod_planilla)
);

CREATE TABLE pst_2.dbo.usuarios_pst (
    cod_usuario INT PRIMARY KEY IDENTITY(1,1),
    usuario NVARCHAR(255),
    pass NVARCHAR(255),
    nombre NVARCHAR(255),
    apellido NVARCHAR(255) NULL,
    cod_rol INT,
	activo INT,
    FOREIGN KEY (cod_rol) REFERENCES pst_2.dbo.roles(cod_rol)
);

CREATE TABLE pst_2.dbo.destino (
    cod_destino INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(255),
    activo INT
);

-- Crear tabla simplificada para tiempos muertos
CREATE TABLE pst_2.dbo.tiempos_muertos (
    cod_tiempo_muerto INT PRIMARY KEY IDENTITY(1,1),
    cod_planilla SMALLINT,
    causa NVARCHAR(MAX),
    hora_inicio TIME,
    hora_termino TIME,
    duracion_minutos INT,
    cod_departamento INT,
    FOREIGN KEY (cod_planilla) REFERENCES pst_2.dbo.planillas_pst(cod_planilla)
);

-- Crear tabla tipo_planilla
CREATE TABLE pst_2.dbo.tipo_planilla (
    cod_tipo_planilla INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(255),
    activo INT
);

CREATE TABLE pst_2.dbo.departamentos (
    cod_departamento INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(255),
    activo INT
);

-- Insertar tipos de planilla
INSERT INTO pst_2.dbo.tipo_planilla (nombre, activo)
VALUES 
    ('Filete', 1),
    ('Porción', 1),
    ('Empaque', 1);

-- Agregar columna a planillas_pst
ALTER TABLE pst_2.dbo.planillas_pst
ADD cod_tipo_planilla INT;

-- Agregar la foreign key
ALTER TABLE pst_2.dbo.planillas_pst
ADD CONSTRAINT FK_planillas_tipo_planilla
FOREIGN KEY (cod_tipo_planilla) REFERENCES pst_2.dbo.tipo_planilla(cod_tipo_planilla);



-- Tablas para los informes de turno
CREATE TABLE pst_2.dbo.informes_turno (
    cod_informe INT PRIMARY KEY IDENTITY(1,1),
    fecha_turno DATE NOT NULL,
    cod_turno SMALLINT NOT NULL,
    cod_jefe_turno NUMERIC(18,0),
    cod_usuario_crea INT,
    comentarios NVARCHAR(MAX),
    fecha_creacion DATETIME DEFAULT GETDATE(),
    estado SMALLINT DEFAULT 1,
    FOREIGN KEY (cod_usuario_crea) REFERENCES pst_2.dbo.usuarios_pst(cod_usuario)
);



CREATE TABLE pst_2.dbo.detalle_informe_sala (
    cod_detalle_informe INT PRIMARY KEY IDENTITY(1,1),
    cod_informe INT,
    cod_sala INT,
    dotacion_real INT,
    dotacion_esperada INT,
    kilos_entrega FLOAT,
    kilos_recepcion FLOAT,
    horas_trabajadas DECIMAL(10,2),
    tiempo_muerto_minutos INT,
    rendimiento DECIMAL(10,2),
    productividad DECIMAL(10,2),
    kilos_premium DECIMAL(18,2) DEFAULT 0,
    premium DECIMAL(5,2) DEFAULT 0,
    FOREIGN KEY (cod_informe) REFERENCES pst_2.dbo.informes_turno(cod_informe),
    FOREIGN KEY (cod_sala) REFERENCES pst_2.dbo.sala(cod_sala)
);



-- Y modificar tiempos_muertos para referenciar a las causas predefinidas
ALTER TABLE pst_2.dbo.tiempos_muertos
ADD cod_causa INT,
FOREIGN KEY (cod_causa) REFERENCES pst_2.dbo.causas_tiempo_muerto(cod_causa);

-- Índices para optimizar las consultas
CREATE INDEX idx_informes_turno_fecha ON pst_2.dbo.informes_turno(fecha_turno);
CREATE INDEX idx_detalle_informe_sala_informe ON pst_2.dbo.detalle_informe_sala(cod_informe);

-- Vista para obtener resumen de informes
CREATE VIEW pst_2.dbo.v_resumen_informes AS
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

---------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------

INSERT INTO pst_2.dbo.roles (nombre_rol)
VALUES
    ('Planillero'),
    ('Supervisor'),
    ('Admin'),
    ('Jefe de turno');

INSERT INTO pst_2.dbo.corte ( nombre, activo)
VALUES
    ('TRIM A', 1),
    ('TRIM B', 1),
    ('TRIM C', 1),
    ('TRIM D', 1),
    ('TRIM E', 1);

INSERT INTO pst_2.dbo.sala ( nombre, activo)
VALUES
    ('SALA 1', 1),
    ('SALA 2', 1),
    ('SALA 3', 1),
    ('SALA 4', 1),
	('SALA 5', 1),
	('SALA 6', 1),
	('SALA 7', 1);

INSERT INTO pst_2.dbo.calibre ( nombre, activo)
VALUES
    ( '2-3', 1),
    ( '3-4', 1),
    ( '4-5', 1),
    ( '5-6', 1),
    ( '6-7', 1);

INSERT INTO pst_2.dbo.calidad ( nombre, activo)
VALUES
    ( 'PREMIUM', 1),
    ( 'GRADO 1', 1),
    ( 'INDUSTRIAL A', 1),
    ( 'INDUSTRIAL B', 1),   
    ( 'SIN CALIDAD', 1);

INSERT INTO pst_2.dbo.usuarios_pst (usuario, pass, nombre, apellido, cod_rol,activo)
VALUES
    ('juan_perez', '123', 'Juan', 'Perez', 2,1),   
    ('maria_gomez', '123', 'Maria', 'Gomez', 1,1),  
    ('carla_rodriguez', '123', 'Carla', 'Rodriguez', 1,1),  
    ('ana_martinez', '123', 'Ana', 'Martinez', 2,1),  
    ('luis_sanchez', '123', 'Luis', 'Sanchez', 1,1),  
    ('admin', 'admin', 'Admin', NULL, 3,1); 

-----------------------------------------------------------




-----------------------------------------------------------

-- Inserts para planillas_pst
INSERT INTO pst_2.dbo.planillas_pst (
    cod_lote, fec_turno, hora_inicio, hora_termino, 
    cod_turno, cod_empresa, cod_proveedor, cod_especie, 
    cod_proceso, cod_planillero, cod_supervisor, 
    cod_usuario_crea_planilla, guardado
)
VALUES
    (194134, '2024-03-01', '08:00', '16:00', 1, 1, 1, 1, 1, 2, 1, 6, 1),
    (194135, '2024-03-02', '16:00', '00:00', 2, 2, 2, 2, 2, 3, 4, 6, 1),
    (194136, '2024-03-03', '00:00', '08:00', 3, 3, 3, 1, 3, 5, 1, 6, 1),
    (194137, '2024-03-04', '08:00', '16:00', 1, 1, 4, 2, 1, 2, 4, 6, 1),
    (194138, '2024-03-05', '16:00', '00:00', 2, 2, 5, 1, 2, 3, 1, 6, 1),
    (194139, '2024-03-06', '00:00', '08:00', 3, 3, 1, 2, 3, 5, 4, 6, 1),
    (194140, '2024-03-07', '08:00', '16:00', 1, 1, 2, 1, 1, 2, 1, 6, 1),
    (194141, '2024-03-08', '16:00', '00:00', 2, 2, 3, 2, 2, 3, 4, 6, 1),
    (194142, '2024-03-09', '00:00', '08:00', 3, 3, 4, 1, 3, 5, 1, 6, 1),
    (194143, '2024-03-10', '08:00', '16:00', 1, 1, 5, 2, 1, 2, 4, 6, 1);

-- Inserts para registro_planilla_pst
INSERT INTO pst_2.dbo.registro_planilla_pst (cod_planilla, cod_corte_ini, cod_corte_fin, cod_destino, cod_calibre, cod_calidad, piezas, kilos, guardado)
VALUES
    (1, 3, 5, 1, 1, 2, 90, 45.0, 1),
    (1, 1, 4, 2, 2, 3, 110, 55.2, 1),
    (2, 2, 3, 3, 3, 4, 100, 50.0, 1),
    (2, 4, 1, 4, 4, 5, 95, 47.5, 1),
    (3, 5, 2, 1, 5, 1, 105, 52.5, 1),
    (3, 3, 5, 2, 1, 2, 115, 57.5, 1),
    (4, 1, 4, 3, 2, 3, 85, 42.5, 1),
    (4, 2, 3, 4, 3, 4, 120, 60.0, 1),
    (5, 4, 1, 1, 4, 5, 130, 65.0, 1),
    (5, 5, 2, 2, 5, 1, 140, 70.0, 1),
    (6, 3, 5, 3, 1, 2, 150, 75.0, 1),
    (6, 1, 4, 4, 2, 3, 160, 80.0, 1),
    (7, 2, 3, 1, 3, 4, 170, 85.0, 1),
    (7, 4, 1, 2, 4, 5, 180, 90.0, 1),
    (8, 5, 2, 3, 5, 1, 190, 95.0, 1),
    (8, 3, 5, 4, 1, 2, 200, 100.0, 1),
    (9, 1, 4, 1, 2, 3, 210, 105.0, 1),
    (9, 2, 3, 2, 3, 4, 220, 110.0, 1),
    (10, 4, 1, 3, 4, 5, 230, 115.0, 1),
    (10, 5, 2, 4, 5, 1, 240, 120.0, 1);
-- Inserts para detalle_planilla_pst
INSERT INTO pst_2.dbo.detalle_planilla_pst (cod_planilla, cajas_entrega, kilos_entrega, piezas_entrega, cajas_recepcion, kilos_recepcion, piezas_recepcion, embolsado_terminado, kilos_terminado, dotacion, cod_sala, observacion)
VALUES
    (1, 50, 250.5, 500, 48, 240.0, 480, 10, 45.0, 10, 1, 'Proceso normal'),
    (2, 60, 300.0, 600, 59, 295.0, 590, 12, 55.2, 12, 2, 'Leve retraso por mantenimiento'),
    (3, 40, 200.0, 400, 40, 200.0, 400, 8, 50.0, 8, 3, 'Sin novedad'),
    (4, 55, 275.5, 550, 54, 270.0, 540, 11, 55.2, 11, 4, 'Calidad excepcional'),
    (5, 70, 350.0, 700, 68, 340.0, 680, 14, 70.0, 14, 5, 'Alta productividad'),
    (6, 45, 225.0, 450, 44, 220.0, 440, 9, 45.0, 9, 1, 'Proceso estándar'),
    (7, 65, 325.5, 650, 63, 315.0, 630, 13, 65.0, 13, 2, 'Buena jornada'),
    (8, 52, 260.0, 520, 51, 255.0, 510, 10, 52.0, 10, 3, 'Sin incidentes'),
    (9, 58, 290.0, 580, 57, 285.0, 570, 12, 58.0, 12, 4, 'Producto de alta calidad'),
    (10, 48, 240.0, 480, 47, 235.0, 470, 10, 48.0, 10, 5, 'Proceso eficiente');

