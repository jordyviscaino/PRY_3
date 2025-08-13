CREATE TABLE usuarios(
	id int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(255),
    email varchar(255),
    edad int
    
);

CREATE TABLE materias(
	idM int AUTO_INCREMENT PRIMARY KEY,
    nombre_m varchar(255),
    nrc varchar(255),
    
);


CREATE TABLE notas(
	idN int AUTO_INCREMENT PRIMARY KEY,
    usuario_id int not null,
    materia_id int not null,
    n1 decimal(5,2) not null,
    n2 decimal(5,2) not null,
    n3 decimal(5,2) not null,
    promedio decimal(5,2),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) on DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES materias(idM) on DELETE CASCADE
);