CREATE TABLE immobiliare_stato(
    chatId bigint,
    stato varchar(50), -- stato: login, registrati...
    step int, -- step: 0, 1, 2...
    expireTime date, -- probabile da togliere
    logged integer,

    primary key(chatId)
)

CREATE TABLE immobiliare_proprietari(
    CF varchar(16),
    nome varchar(30),
    cognome varchar(30),
    telefono bigint,
    email varchar(30),
    password varchar(50),
    tempChatId bigint, -- serve per tenere conto dove inserire i dati inviati dall'utente passo passo

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
    civico int not null,
    metratura int not null,
    piano int not null,
    nLocali int not null,
    IdTipo integer unsigned,
    IdZona integer unsigned,

    primary key(Id),
    foreign key(IdTipo) references immobiliare_tipoimm(Id),
    foreign key(IdZona) references immobiliare_tipozona(Id)
)

CREATE TABLE immobiliare_intestazioni(
    Id integer unsigned AUTO_INCREMENT,
    data date not null,
    versamento integer not null,
    IdProp varchar(16), -- CF
    IdImmob integer unsigned,

    primary key(Id),
    foreign key(IdProp) references immobiliare_proprietari(CF),
    foreign key(IdImmob) references immobiliare_immobili(Id)
)