CREATE SCHEMA IF NOT EXISTS inventario;
SET search_path TO inventario;

CREATE TABLE rol (
    id_rol SERIAL PRIMARY KEY,
    nombre_rol VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE usuario (
    id_usuario SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL REFERENCES rol(id_rol)
);

CREATE TABLE producto (
    id_producto SERIAL PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    codigo_qr VARCHAR(255) UNIQUE NOT NULL,
    stock INT DEFAULT 0,
    precio_unitario NUMERIC(10,2) DEFAULT 0.00,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE movimiento_stock (
    id_movimiento SERIAL PRIMARY KEY,
    id_producto INT NOT NULL REFERENCES producto(id_producto) ON DELETE CASCADE,
    id_usuario INT NOT NULL REFERENCES usuario(id_usuario) ON DELETE SET NULL,
    tipo_movimiento VARCHAR(10) CHECK (tipo_movimiento IN ('entrada', 'salida')),
    cantidad INT NOT NULL CHECK (cantidad > 0),
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO rol (nombre_rol) VALUES ('admin'), ('trabajador');


ALTER TABLE inventario.usuario
ADD COLUMN rol VARCHAR(20) DEFAULT 'empleado';

CREATE EXTENSION IF NOT EXISTS pgcrypto;

ALTER TABLE inventario.usuario ALTER COLUMN apellido DROP NOT NULL;


INSERT INTO inventario.usuario (nombre, apellido, email, password, rol_id)
VALUES (
    'Administrador',
    'General',
    'admin@empresa.com',
    crypt('admin123', gen_salt('bf')),
    1
);


CREATE TABLE inventario.movimientos (
    id_movimiento SERIAL PRIMARY KEY,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    tipo VARCHAR(10) CHECK (tipo IN ('entrada', 'salida')) NOT NULL,
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT NOT NULL,

    FOREIGN KEY (id_producto) REFERENCES inventario.producto(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES inventario.usuario(id_usuario) ON DELETE CASCADE
);

ALTER TABLE inventario.producto
ALTER COLUMN codigo_qr DROP NOT NULL;