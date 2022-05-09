CREATE TABLE immobiliare_proprietari(
    CF varchar(16) not null,
    nome varchar(30) not null,
    cognome varchar(30) not null,
    telefono bigint not null,
    email varchar(30) not null,

    primary key(CF)
)

CREATE TABLE immobiliare_tipozona(
    Id integer unsigned AUTO_INCREMENT,
    zona varchar(30),

    primary key(Id)
)

CREATE TABLE immobiliare_tipoimm(
    Id integer unsigned AUTO_INCREMENT,
    tipo varchar(30),

    primary key(Id)
)

CREATE TABLE immobiliare_immobili(
    Id integer unsigned AUTO_INCREMENT,
    nome varchar(30) not null,
    via varchar(30) not null,
    civico varchar(30) not null,
    metratura varchar(30) not null,
    piano varchar(30) not null,
    nLocali varchar(30) not null,
    IdTipo integer unsigned,
    IdZona integer unsigned,

    primary key(Id),
    foreign key(IdTipo) references immobiliare_tipoimm(Id),
    foreign key(IdZona) references immobiliare_tipozona(Id)
)

CREATE TABLE IMMOBILIARE_intestazioni(
    Id integer unsigned AUTO_INCREMENT,
    data date not null,
    versamento integer not null,
    IdProp varchar(16),
    IdImmob integer unsigned,

    primary key(Id),
    foreign key(IdProp) references immobiliare_proprietari(CF),
    foreign key(IdImmob) references immobiliare_immobili(Id)
)