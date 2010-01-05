create table projeto(
    id_projeto int(11) not null auto_increment,
    nome varchar(200) not null,
    primary key(id_projeto)
)engine=innodb;

insert into projeto (nome) values ('projeto A');

alter table lagoa modify id_projeto int(11) not null after id_lagoa;
alter table lagoa add foreign key (id_projeto) references projeto (id_projeto) on delete cascade on update cascade;

create table especie(
    id_especie int(11) not null auto_increment,
    id_parametro int(11) not null,
    nome varchar(200) not null,
    primary key(id_especie),
    foreign key (id_parametro) references parametro (id_parametro) on delete cascade on update cascade
)engine=innodb;

alter table coleta modify data datetime not null;
alter table coleta add tipo_periodo enum('mensal', 'diario') not null default 'mensal' after data;
alter table coleta_parametro change nivel extra varchar(200) default null;

create table extra(
    id_extra int(11) not null auto_increment,
    nome varchar(200) not null,
    tem_valor boolean default false,
    tem_relacao boolean default false,
    tabela varchar(200) default null,
    primary key(id_extra)
)engine=innodb;

insert into extra (id_extra, nome, tem_valor, tem_relacao, tabela) values 
('1', 'Nenhum', false, false, null), 
('2', 'Especie', true, true, 'especie'), 
('3', 'Profundidade', true, false, null);

alter table parametro add id_extra int(11) not null default '1' after id_parametro, add foreign key (id_extra) references extra (id_extra) on update cascade;

create table coleta_parametro_especie(
    id_coleta_parametro_especie int(11) not null auto_increment,
    id_coleta_parametro int(11) not null,
    id_especie int(11) not null,
    primary key (id_coleta_parametro_especie),
    foreign key (id_coleta_parametro) references coleta_parametro (id_coleta_parametro) on update cascade,
    foreign key (id_especie) references especie (id_especie) on update cascade
)engine=innodb;
