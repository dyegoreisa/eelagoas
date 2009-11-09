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
