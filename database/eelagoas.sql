
create table lagoa(
  id_lagoa int(11) not null auto_increment,
  id_projeto int(11) not null,
  nome varchar(100) not null,
  primary key(id_lagoa),
  foreign key(id_projeto) references projeto (id_projeto) on update cascade on delete cascade,
) engine=innodb;

create table parametro(
  id_parametro int(11) not null auto_increment,
  nome varchar(100) not null,
  primary key(id_parametro)
)engine=innodb;

create table coleta(
  id_coleta int(11) not null auto_increment,
  id_lagoa int(11) not null,
  data date not null,
  primary key(id_coleta),
  foreign key(id_lagoa) references lagoa (id_lagoa) on update cascade on delete cascade
)engine=innodb;

create table ponto_amostral(
  id_ponto_amostral int(11) not null auto_increment,
  id_lagoa int(11) not null,
  nome varchar(100) not null,
  primary key (id_ponto_amostral),
  foreign key(id_lagoa) references lagoa(id_lagoa) on update cascade on delete cascade
)engine=innodb;

create table categoria(
  id_categoria int(11) not null auto_increment,
  nome varchar(100) not null,
  primary key(id_categoria)
)engine=innodb;

create table coleta_ponto(
  id_coleta int(11) not null,
  id_ponto_amostral int(11) not null,
  id_parametro int(11) not null,
  id_categoria int(11) not null,
  nivel float null,
  valor varchar(50) not null,
  foreign key(id_coleta) references coleta (id_coleta) on update cascade on delete cascade,
  foreign key(id_ponto_amostral) references ponto_amostral (id_ponto_amostral) on update cascade on delete cascade,
  foreign key(id_parametro) references parametro (id_parametro) on update cascade on delete cascade,
  foreign key(id_categoria) references categoria (id_categoria) on update cascade on delete cascade
)engine=innodb;

create table projeto(
  id_projeto int(11) not null auto_increment,
  nome varchar(200) not null,
  primary key(id_projeto)
)engine=innodb;
