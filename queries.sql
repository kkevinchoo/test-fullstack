-- Crear database
CREATE DATABASE prueba;
\c prueba;

-- Crear la tabla "Types"
CREATE TABLE Types (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50)
);

-- Crear la tabla "Users"
CREATE TABLE Users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(100)
);

-- Crear la tabla "Flights"
CREATE TABLE Flights (
  id SERIAL PRIMARY KEY,
  date_flight DATE,
  time_start TIME,
  time_end TIME,
  duration INTERVAL,
  cost FLOAT,
  id_type INTEGER REFERENCES Types(id),
  id_user INTEGER REFERENCES Users(id)
);

-- Insertar usuario inicial
INSERT INTO Users (username, password) VALUES ('kevin', '$2y$10$KKbknGWTy6oTceP14trHv.H7aukItA0KYEzO7iEbvw88OERyCYz8e');