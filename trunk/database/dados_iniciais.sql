insert into usuario (login, senha, nome) value ('admin', md5('admin'), 'admin');
desc parametro_extra;
insert into parametro_extra (id_parametro_extra, nome, descricao, tem_valor, tem_relacao, tabela) values 
('1', 'nenhum', 'Nenhum', false, false, null), 
('2', 'especie', 'Especie', true, true, 'especie');

insert into categoria_extra (id_categoria_extra, nome, descricao, tem_valor, tem_relacao, tabela) values 
('1', 'nenhum', 'Nenhum', false, false, null), 
('2', 'profundidade', 'Profundidade', true, false, null);
