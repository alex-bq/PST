-- Drops
DROP TABLE pst.dbo.registro_planilla_pst;
DROP TABLE pst.dbo.detalle_planilla_pst;
DROP TABLE pst.dbo.planillas_pst;
DROP TABLE pst.dbo.usuarios_pst;
DROP TABLE pst.dbo.corte;
DROP TABLE pst.dbo.sala; 
DROP TABLE pst.dbo.calibre;
DROP TABLE pst.dbo.calidad;
DROP TABLE pst.dbo.roles;
DROP TABLE pst.dbo.destino; 



-- Tables
CREATE TABLE pst.dbo.corte (
    cod_corte INT PRIMARY KEY  IDENTITY(1,1),
    nombre NVARCHAR(255),
    inactivo INT,
    transito INT
);

CREATE TABLE pst.dbo.sala (
    cod_sala INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(255),
    inactivo INT
);

CREATE TABLE pst.dbo.calibre (
    cod_calib INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(255),
    inactivo INT,
    transito INT
);

CREATE TABLE pst.dbo.calidad (
    cod_cald INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(255),
    inactivo INT
);

CREATE TABLE pst.dbo.roles (
    cod_rol INT PRIMARY KEY IDENTITY(1,1),
    nombre_rol NVARCHAR(255)
);

CREATE TABLE pst.dbo.planillas_pst (
    cod_planilla SMALLINT PRIMARY KEY IDENTITY(1,1),
    cod_lote BIGINT,
    fec_turno DATE,
    cod_turno SMALLINT,
    cod_empresa NUMERIC(18,0),
    cod_proveedor numeric(18,0),
    cod_especie SMALLINT,
    cod_proceso SMALLINT,
    cod_planillero NUMERIC(18,0),
    cod_supervisor NUMERIC(18,0),
    fec_crea_planilla DATETIME DEFAULT GETDATE(),
    guardado NUMERIC(18,0),
    UNIQUE(cod_planilla) 
);

CREATE TABLE pst.dbo.detalle_planilla_pst (
    cod_detalle INT PRIMARY KEY IDENTITY(1,1),
    cod_planilla SMALLINT UNIQUE, 
    cajas_entrega INT,
    kilos_entrega FLOAT,
    piezas_entrega INT,
    cajas_recepcion INT,
    kilos_recepcion FLOAT,
    piezas_recepcion INT,
    dotacion INT,
    cod_sala INT,
    observacion NVARCHAR(MAX),
    FOREIGN KEY (cod_planilla) REFERENCES pst.dbo.planillas_pst(cod_planilla)
);

CREATE TABLE pst.dbo.registro_planilla_pst (
    cod_reg SMALLINT PRIMARY KEY IDENTITY(1,1),
    cod_planilla SMALLINT,
    cod_corte_ini BIGINT,
    cod_corte_fin BIGINT,
    cod_proceso NUMERIC(18,0),
    cod_destino NUMERIC(18,0),
    cod_calibre SMALLINT,
    cod_calidad SMALLINT,
    piezas NUMERIC(18,0),
    kilos FLOAT,
    guardado NUMERIC(18,0),	
    FOREIGN KEY (cod_planilla) REFERENCES pst.dbo.planillas_pst(cod_planilla)
);

CREATE TABLE pst.dbo.usuarios_pst (
    cod_usuario INT PRIMARY KEY IDENTITY(1,1),
    usuario NVARCHAR(255),
    pass NVARCHAR(255),
    nombre NVARCHAR(255),
    apellido NVARCHAR(255) NULL,
    cod_rol INT,
    FOREIGN KEY (cod_rol) REFERENCES pst.dbo.roles(cod_rol)
);

CREATE TABLE pst.dbo.destino (
    cod_destino INT PRIMARY KEY IDENTITY(1,1),
    nombre NVARCHAR(255),
    inactivo INT
);





INSERT INTO pst.dbo.roles (nombre_rol)
VALUES
    ('Planillero'),
    ('Supervisor'),
    ('Admin');

INSERT INTO pst.dbo.corte ( nombre, inactivo, transito)
VALUES
    ('TRIM A', 0, 1),
    ('TRIM B', 0, 1),
    ('TRIM C', 0, 1),
    ('TRIM D', 0, 1),
    ('TRIM E', 0, 1);

INSERT INTO pst.dbo.sala ( nombre, inactivo)
VALUES
    ('SALA 1', 0),
    ('SALA 2', 0),
    ('SALA 3', 0),
    ('SALA 4', 0),
	('SALA 5', 0),
	('SALA 6', 0),
	('SALA 7', 0);

INSERT INTO pst.dbo.calibre ( nombre, inactivo, transito)
VALUES
    ( '2-3', 0, 1),
    ( '3-4', 0, 1),
    ( '4-5', 0, 1),
    ( '5-6', 0, 1),
    ( '6-7', 0, 1);

INSERT INTO pst.dbo.calidad ( nombre, inactivo)
VALUES
    ( 'PREMIUM', 0),
    ( 'GRADO 1', 0),
    ( 'INDUSTRIAL A', 0),
    ( 'INDUSTRIAL B', 0),   
    ( 'SIN CALIDAD', 0);

INSERT INTO pst.dbo.usuarios_pst (usuario, pass, nombre, apellido, cod_rol)
VALUES
    ('juan_perez', '123', 'Juan', 'Perez', 2),   
    ('maria_gomez', '123', 'Maria', 'Gomez', 1),  
    ('carla_rodriguez', '123', 'Carla', 'Rodriguez', 1),  
    ('ana_martinez', '123', 'Ana', 'Martinez', 2),  
    ('luis_sanchez', '123', 'Luis', 'Sanchez', 1),  
    ('admin', 'admin', 'Admin', NULL, 3); 

-----------------------------------------------------------

INSERT INTO pst.dbo.planillas_pst (cod_lote, fec_turno, cod_turno, cod_empresa, cod_especie, cod_planillero, cod_supervisor, guardado)
VALUES 
    (194134, '2024-01-12', 1, 78, 162, 2, 1, 1),
    (194135, '2024-01-12', 3, 79, 163, 3, 1, 1),
    (194136, '2024-01-12', 2, 80, 164, 2, 1, 1),
    (194137, '2024-01-12', 1, 81, 165, 2, 1, 1),
    (194138, '2024-01-12', 3, 82, 166, 3, 1, 1),
    (194139, '2024-01-12', 2, 83, 167, 5, 4, 1),
    (194140, '2024-01-12', 1, 84, 168, 3, 4, 1),
    (194141, '2024-01-12', 3, 85, 169, 5, 4, 1),
    (194142, '2024-01-12', 2, 86, 170, 3, 4, 1),
    (194143, '2024-01-12', 1, 87, 171, 5, 4, 1);

INSERT INTO pst.dbo.registro_planilla_pst (
    cod_planilla,
    cod_corte_ini,
    cod_corte_fin,
    cod_sala,
    cod_calibre,
    cod_calidad,
    piezas,
    kilos,
    guardado
)
VALUES
    (1, 3, 5, 1, 1, 2, 90, 45.0, 1),
    (1, 1, 4, 2, 2, 3, 110, 55.2, 1),
    (1, 2, 5, 3, 3, 4, 120, 60.8, 1),
    (2, 4, 1, 4, 4, 5, 70, 35.1, 1),
    (2, 5, 2, 1, 5, 1, 100, 50.0, 1),
    (2, 1, 3, 2, 1, 2, 85, 42.2, 1),
    (3, 2, 4, 3, 2, 3, 130, 65.7, 1),
    (3, 3, 5, 4, 3, 4, 140, 70.3, 1),
    (3, 4, 1, 1, 4, 5, 75, 37.5, 1),
    (4, 5, 2, 2, 5, 1, 95, 47.5, 1),
    (4, 6, 3, 3, 1, 2, 105, 52.8, 1),
    (4, 7, 4, 4, 2, 3, 115, 57.3, 1),
    (5, 8, 5, 1, 3, 4, 125, 62.5, 1),
    (5, 9, 1, 2, 4, 5, 135, 67.8, 1),
    (5, 10, 2, 3, 5, 1, 145, 72.3, 1),
    (6, 11, 3, 4, 1, 2, 155, 77.5, 1),
    (6, 12, 4, 1, 2, 3, 80, 40.0, 1),
    (6, 13, 5, 2, 3, 4, 90, 45.0, 1),
    (7, 14, 1, 3, 4, 5, 100, 50.0, 1),
    (7, 15, 2, 4, 5, 1, 110, 55.0, 1),
    (7, 16, 3, 1, 1, 2, 120, 60.0, 1),
    (8, 17, 4, 2, 2, 3, 130, 65.0, 1),
    (8, 18, 5, 3, 3, 4, 140, 70.0, 1),
    (8, 19, 1, 4, 4, 5, 150, 75.0, 1),
    (9, 20, 2, 1, 5, 1, 160, 80.0, 1),
    (9, 21, 3, 2, 1, 2, 170, 85.0, 1),
    (9, 22, 4, 3, 2, 3, 180, 90.0, 1),
    (10, 23, 5, 4, 3, 4, 190, 95.0, 1),
	(10, 1, 3, 1, 1, 2, 100, 50.5, 1),
    (10, 2, 4, 2, 2, 3, 120, 60.2, 1);
