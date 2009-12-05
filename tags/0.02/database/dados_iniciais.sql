insert into usuario (login, senha, nome) value ('admin', md5('admin'), 'admin');

insert into parametro_extra (id_parametro_extra, nome, tem_valor, tem_relacao, tabela) values 
('1', 'Nenhum', false, false, null), 
('2', 'Especie', true, true, 'especie');

insert into categoria_extra (id_categoria_extra, nome, tem_valor, tem_relacao, tabela) values 
('1', 'Nenhum', false, false, null), 
('2', 'Profundidade', true, false, null);
