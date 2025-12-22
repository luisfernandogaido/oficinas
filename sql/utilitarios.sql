-- ALTERTAR TIPO DA CHAVE PRIMÁRIA
-- colunas relacionadas a uma chave primária
SELECT kcu.TABLE_NAME      AS 'Tabela Dependente',
       kcu.COLUMN_NAME     AS 'Coluna Dependente',
       kcu.CONSTRAINT_NAME AS 'Nome da Constraint',
       rc.UPDATE_RULE      AS 'ON UPDATE',
       rc.DELETE_RULE      AS 'ON DELETE'
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS kcu
         INNER JOIN
     INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS AS rc
     ON
         kcu.CONSTRAINT_SCHEMA = rc.CONSTRAINT_SCHEMA
             AND kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
WHERE kcu.CONSTRAINT_SCHEMA = 'gaido'
  AND kcu.REFERENCED_TABLE_NAME = 'usuario'
  AND kcu.REFERENCED_COLUMN_NAME = 'codigo';

/*
assinatura,cod_usuario,assinatura_ibfk_1,NO ACTION,NO ACTION
compra,cod_usuario,compra_usuario_codigo_fk,NO ACTION,NO ACTION
convidado,codigo,convidado_usuario_codigo_fk,NO ACTION,CASCADE
convite,cod_usuario,convite_usuario_codigo_fk,NO ACTION,CASCADE
os,cod_cliente,os_usuario_codigo_fk,NO ACTION,NO ACTION
os_historico,cod_usuario,os_historico_usuario_codigo_fk,NO ACTION,NO ACTION
os_itens,cod_executante,os_itens_executante_fk,NO ACTION,NO ACTION
projeto,cod_usuario,projeto_ibfk_2,NO ACTION,NO ACTION
veiculo,cod_proprietario,veiculo_usuario_codigo_fk,NO ACTION,CASCADE
whatsapp_validacao,cod_usuario,whatsapp_validacao_usuario_codigo_fk,NO ACTION,CASCADE
workspace,cod_criador,workspace_usuario_codigo_fk,NO ACTION,NO ACTION
 */

-- guarde os dados de consulta: serão usados posteriormente.

ALTER TABLE assinatura drop foreign key assinatura_ibfk_1;
ALTER TABLE compra drop foreign key compra_usuario_codigo_fk;
ALTER TABLE convidado  drop foreign key convidado_usuario_codigo_fk;
ALTER TABLE convite  drop foreign key convite_usuario_codigo_fk;
ALTER TABLE os drop foreign key os_usuario_codigo_fk;
ALTER TABLE os_historico drop foreign key os_historico_usuario_codigo_fk;
ALTER TABLE os_itens drop foreign key os_itens_executante_fk;
ALTER TABLE projeto drop foreign key projeto_ibfk_2;
ALTER TABLE veiculo  drop foreign key veiculo_usuario_codigo_fk;
ALTER TABLE whatsapp_validacao  drop foreign key whatsapp_validacao_usuario_codigo_fk;
ALTER TABLE workspace drop foreign key workspace_usuario_codigo_fk;

ALTER TABLE assinatura ADD FOREIGN KEY (cod_usuario) REFERENCES usuario(codigo);
ALTER TABLE compra ADD FOREIGN KEY (cod_usuario) REFERENCES usuario(codigo);
ALTER TABLE convidado ADD FOREIGN KEY (codigo) REFERENCES usuario(codigo);
ALTER TABLE convite ADD FOREIGN KEY (cod_usuario) REFERENCES usuario(codigo);
ALTER TABLE os ADD FOREIGN KEY (cod_cliente) REFERENCES usuario(codigo);
ALTER TABLE os_historico ADD FOREIGN KEY (cod_usuario) REFERENCES usuario(codigo);
ALTER TABLE os_itens ADD FOREIGN KEY (cod_executante) REFERENCES usuario(codigo);
ALTER TABLE projeto ADD FOREIGN KEY (cod_usuario) REFERENCES usuario(codigo);
ALTER TABLE veiculo ADD FOREIGN KEY (cod_proprietario) REFERENCES usuario(codigo);
ALTER TABLE whatsapp_validacao ADD FOREIGN KEY (cod_usuario) REFERENCES usuario(codigo);
ALTER TABLE workspace ADD FOREIGN KEY (cod_criador) REFERENCES usuario(codigo);

-- recrie na mão os cascade perdidos, descritos antes
