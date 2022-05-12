CREATE TABLE immobot_stato(
    chatId bigint,
    stato varchar(50), -- stato: login, proprietari, zone...
    step int, -- step: 0, 1, 2...
    expireTime date,
    logged integer,

    primary key(chatId)
)

CREATE TABLE immobot_proprietari(
    CF varchar(16) not null,
    nome varchar(30) not null,
    cognome varchar(30) not null,
    telefono bigint not null,
    email varchar(30) not null,
    password varchar(50),
    tempChatId bigint, -- serve per tenere conto dove inserire i dati inviati dall'utente passo passo

    primary key(CF)
)

CREATE TABLE immobot_tipozona(
    Id integer unsigned AUTO_INCREMENT,
    zona varchar(30),

    primary key(Id)
)

CREATE TABLE immobot_tipoimm(
    Id integer unsigned AUTO_INCREMENT,
    tipo varchar(30),

    primary key(Id)
)

CREATE TABLE immobot_immobili(
    Id integer unsigned AUTO_INCREMENT,
    nome varchar(30) not null,
    via varchar(30) not null,
    civico int not null,
    metratura int not null,
    piano int not null,
    nLocali int not null,
    IdTipo integer unsigned,
    IdZona integer unsigned,

    primary key(Id),
    foreign key(IdTipo) references immobot_tipoimm(Id),
    foreign key(IdZona) references immobot_tipozona(Id)
)

CREATE TABLE immobot_intestazioni(
    Id integer unsigned AUTO_INCREMENT,
    data date not null,
    versamento integer not null,
    IdProp varchar(16), -- CF
    IdImmob integer unsigned,

    primary key(Id),
    foreign key(IdProp) references immobot_proprietari(CF),
    foreign key(IdImmob) references immobot_immobili(Id)
)