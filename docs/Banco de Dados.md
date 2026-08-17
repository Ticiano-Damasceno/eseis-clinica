
RELATÓRIO

Banco de Dados — Sistema de Gestão de Clínica de Psicologia

Disciplina: N688 — Ambiente de DadosCurso: UNIFOREquipe:


1 Definição do Problema

1.1 Descrição do domínio

O sistema modelado é uma plataforma de gestão de clínica de psicologia, chamada Eseís. O problema central é que clínicas de psicologia de pequeno e médio porte operam de forma fragmentada: agenda em uma ferramenta, controle financeiro em outra, comunicação com profissionais em uma terceira — sem integração entre si. Isso gera retrabalho, perda de informação e erros operacionais.

A plataforma resolve esse problema reunindo, em um único banco de dados, todos os dados necessários para:

Reserva de salas — psicólogos alugam salas físicas da clínica por hora ou turno;

Agendamento de atendimentos — registro do vínculo entre psicólogo, paciente, sala e horário;

Controle financeiro — transações, pagamentos e modalidades;

Gestão de usuários — três perfis distintos: Administrador, Psicólogo e Paciente;

Notificações e contatos — multicanal por usuário (e-mail, WhatsApp, SMS).

1.2 Entidades identificadas

O modelo possui 10 entidades, todas relacionadas entre si:

Entidade

Função no domínio

1

USUARIO

Entidade central — base de todos os perfis de usuário

2

PSICOLOGO

Especialização do usuário como psicólogo (armazena CRP)

3

ADMINISTRATIVO

Especialização do usuário como gestor da clínica

4

PACIENTE

Especialização do usuário como paciente

5

SALA

Salas físicas da clínica disponíveis para reserva

6

AGENDA

Agendamentos de uso de sala (data, hora, status)

7

TRANSACÕES

Transações financeiras vinculadas aos agendamentos

8

CONTATO

Contatos de um usuário (telefone, e-mail, WhatsApp)

9

ENDERECO

Endereços físicos dos usuários

10

TBL_PACIENTE_ATENDIMENTO

Entidade associativa N:N entre psicólogo e paciente por agendamento

1.3 Relacionamentos com cardinalidade

Relacionamento

Cardinalidade

Descrição

USUARIO → PSICOLOGO

1:1

Cada usuário pode ser um psicólogo

USUARIO → ADMINISTRATIVO

1:1

Cada usuário pode ser um administrador

USUARIO → PACIENTE

1:1

Cada usuário pode ser um paciente

USUARIO → CONTATO

1:N

Um usuário possui vários meios de contato

USUARIO → ENDERECO

1:N

Um usuário pode ter vários endereços

USUARIO → AGENDA

1:N

Um usuário realiza vários agendamentos

SALA → AGENDA

1:N

Uma sala é reservada em vários agendamentos

TRANSACAO → AGENDA

1:1

Uma transação pode cobrir um agendamento

AGENDA → PACIENTE_ATENDIMENTO

1:N

Um agendamento registra o atendimento de um paciente

PSICOLOGO → PACIENTE_ATENDIMENTO

1:N

Um psicólogo atende muitos pacientes

PACIENTE → PACIENTE_ATENDIMENTO

1:N

Um paciente é atendido em múltiplas sessões

O relacionamento N:N entre PSICOLOGO e PACIENTE é resolvido pela entidade associativa TBL_PACIENTE_ATENDIMENTO, que registra cada sessão individualmente.

2 Modelo Entidade-Relacionamento (DER)

Diagrama Conceitual — mostra entidades, atributos naturais, relacionamentos e cardinalidades. Chaves estrangeiras (FKs) não são exibidas aqui; elas aparecem no Modelo Lógico (Parte 3).

2.1 Descrição textual do DER

O diagrama possui três grupos de entidades conectadas à entidade central USUARIO:

Especialização de perfil (1:1):

USUARIO → PSICOLOGO (armazena CRP profissional)

USUARIO → ADMINISTRATIVO (armazena setor da clínica)

USUARIO → PACIENTE (perfil do paciente)

Dados cadastrais (1:N):

USUARIO → CONTATO (múltiplos canais de comunicação)

USUARIO → ENDERECO → CEP (endereço normalizado)

Operação (1:N):

USUARIO → AGENDA ← SALA (o agendamento une usuário e sala)

AGENDA → TRANSACAO (fluxo financeiro)

AGENDA → PACIENTE_ATENDIMENTO ← PSICOLOGO e PACIENTE (entidade associativa N:N)

3 Modelo Lógico Relacional

Diagrama que representa as tabelas com colunas, tipos de dados, chaves primárias (PK) e chaves estrangeiras (FK). Gerado a partir do DDL implementado.

3.1 Descrição das tabelas

TBL_USUARIO (ID_USUARIO PK, NOME, CPF UNIQUE, SENHA, CREATED_AT)

TBL_CEP (CEP PK, CIDADE, BAIRRO, UF)

TBL_ENDERECO (ID_ENDERECO PK, ID_USUARIO FK, RUA, NUMERO, COMPLEMENTO, CEP FK)

TBL_SALA (ID_SALA PK, NOME_SALA, INFANTIL, ONLINE, AR_CONDICIONADO)

TBL_TRANSACAO (ID_TRANSACAO PK, ID_USUARIO FK, VALOR_TRANSACAO, DATA_TRANSACAO)

TBL_PAGAMENTOS (ID_PAGAMENTO PK, ID_TRANSACAO FK, FORMA_PAGAMENTO, DATA_PAGAMENTO, VALOR_PAGAMENTO)

TBL_AGENDA (ID_AGENDAMENTO PK, ID_SALA FK, ID_USUARIO FK, DATA_AGENDAMENTO, DURACAO_ATENDIMENTO, STATUS,CREATED_AT, UPDATED_AT, ID_USUARIO_UPDATE FK, ID_TRANSACAO FK)

TBL_CONTATO (ID_CONTATO PK, ID_USUARIO FK, CONTATO, TIPO, CONTATO_NOTIFICACAO)

TBL_PSICOLOGO (ID_PSICOLOGO PK, ID_USUARIO FK UNIQUE, CRP)

TBL_ADMINISTRATIVO (ID_SETOR PK, ID_USUARIO FK UNIQUE, NOME_SETOR)

TBL_PACIENTE (ID_PACIENTE PK, ID_USUARIO FK UNIQUE)

TBL_PACIENTE_ATENDIMENTO (ID_ATENDIMENTO PK, ID_AGENDAMENTO FK, ID_PACIENTE FK, ID_PSICOLOGO FK)

3.2 Relacionamentos N:N e entidade associativa

O relacionamento PSICOLOGO × PACIENTE é N:N, pois:

Um psicólogo atende múltiplos pacientes ao longo do tempo;

Um paciente pode ser atendido por múltiplos psicólogos.

Este relacionamento é resolvido por TBL_PACIENTE_ATENDIMENTO, que armazena cada sessão individualmente, vinculada também ao agendamento (TBL_AGENDA).

4 Script DDL

CREATE TABLE TBL_USUARIO (

    ID_USUARIO INT AUTO_INCREMENT PRIMARY KEY,

    NOME VARCHAR(255) NOT NULL,

    CPF VARCHAR(11) NOT NULL UNIQUE,

    SENHA VARCHAR(255) NOT NULL,

    CREATED_AT DATETIME DEFAULT CURRENT_TIMESTAMP

);

CREATE TABLE TBL_CEP (

    CEP VARCHAR(8) PRIMARY KEY,

    CIDADE VARCHAR(100) NOT NULL,

    BAIRRO VARCHAR(100) NOT NULL,

    UF CHAR(2) NOT NULL

);

CREATE TABLE TBL_ENDERECO (

    ID_ENDERECO INT AUTO_INCREMENT PRIMARY KEY,

    ID_USUARIO INT,

    RUA VARCHAR(255),

    NUMERO INT,

    COMPLEMENTO VARCHAR(255),

    CEP VARCHAR(8) NOT NULL,

    CONSTRAINT FK_ENDERECO_USUARIO

    FOREIGN KEY (ID_USUARIO)

    REFERENCES TBL_USUARIO(ID_USUARIO),

    CONSTRAINT FK_ENDERECO_CEP

    FOREIGN KEY (CEP)

    REFERENCES TBL_CEP(CEP)

);

CREATE TABLE TBL_SALA (

    ID_SALA INT AUTO_INCREMENT PRIMARY KEY,

   NOME_SALA VARCHAR(50) NOT NULL,

    INFANTIL BOOLEAN DEFAULT FALSE,

    ONLINE BOOLEAN DEFAULT FALSE,

    AR_CONDICIONADO BOOLEAN DEFAULT FALSE

);

CREATE TABLE TBL_TRANSACAO (

    ID_TRANSACAO INT AUTO_INCREMENT PRIMARY KEY,

    ID_USUARIO INT,

    VALOR_TRANSACAO DECIMAL(10,2) NOT NULL,

    DATA_TRANSACAO DATETIME,

    CONSTRAINT FK_TRANSACAO_USUARIO

    FOREIGN KEY (ID_USUARIO)

    REFERENCES TBL_USUARIO(ID_USUARIO)

);

CREATE TABLE TBL_AGENDA (

    ID_AGENDAMENTO INT AUTO_INCREMENT PRIMARY KEY,

    ID_SALA INT NOT NULL,

    ID_USUARIO INT,

    DATA_AGENDAMENTO DATETIME,

DURACAO_ATENDIMENTO DECIMAL(5,1),

    STATUS ENUM(

    'PENDENTE',

    'CONFIRMADO',

    'CANCELADO',

    'REALIZADO',

    'BLOQUEADO'

    ),

    CREATED_AT DATETIME DEFAULT CURRENT_TIMESTAMP,

    UPDATED_AT DATETIME DEFAULT CURRENT_TIMESTAMP

    ON UPDATE CURRENT_TIMESTAMP,

    ID_USUARIO_UPDATE INT,

    ID_TRANSACAO INT,

    CONSTRAINT FK_AGENDA_SALA

    FOREIGN KEY (ID_SALA)

    REFERENCES TBL_SALA(ID_SALA),

    CONSTRAINT FK_AGENDA_USUARIO

    FOREIGN KEY (ID_USUARIO)

    REFERENCES TBL_USUARIO(ID_USUARIO),

    CONSTRAINT FK_AGENDA_TRANSACAO

    FOREIGN KEY (ID_TRANSACAO)

    REFERENCES TBL_TRANSACAO(ID_TRANSACAO),

    CONSTRAINT FK_AGENDA_USUARIO_UPDATE

    FOREIGN KEY (ID_USUARIO_UPDATE)

    REFERENCES TBL_USUARIO(ID_USUARIO)

);

CREATE TABLE TBL_CONTATO (

    ID_CONTATO INT AUTO_INCREMENT PRIMARY KEY,

    ID_USUARIO INT,

    CONTATO VARCHAR(255),

    TIPO VARCHAR(30),

    CONTATO_NOTIFICACAO BOOLEAN DEFAULT FALSE,

    CONSTRAINT FK_CONTATO_USUARIO

    FOREIGN KEY (ID_USUARIO)

    REFERENCES TBL_USUARIO(ID_USUARIO)

);

CREATE TABLE TBL_PSICOLOGO (

    ID_PSICOLOGO INT AUTO_INCREMENT PRIMARY KEY,

    ID_USUARIO INT UNIQUE,

    CRP VARCHAR(20),

    CONSTRAINT FK_PSICOLOGO_USUARIO

    FOREIGN KEY (ID_USUARIO)

    REFERENCES TBL_USUARIO(ID_USUARIO)

);

CREATE TABLE TBL_ADMINISTRATIVO (

    ID_SETOR INT AUTO_INCREMENT PRIMARY KEY,

    ID_USUARIO INT UNIQUE,

    NOME_SETOR VARCHAR(100),

    CONSTRAINT FK_ADMIN_USUARIO

    FOREIGN KEY (ID_USUARIO)

    REFERENCES TBL_USUARIO(ID_USUARIO)

);

CREATE TABLE TBL_PACIENTE (

    ID_PACIENTE INT AUTO_INCREMENT PRIMARY KEY,

    ID_USUARIO INT UNIQUE,

    CONSTRAINT FK_PACIENTE_USUARIO

    FOREIGN KEY (ID_USUARIO)

    REFERENCES TBL_USUARIO(ID_USUARIO)

);

CREATE TABLE TBL_PACIENTE_ATENDIMENTO (

    ID_ATENDIMENTO INT AUTO_INCREMENT PRIMARY KEY,

    ID_AGENDAMENTO INT,

    ID_PACIENTE INT NOT NULL,

    ID_PSICOLOGO INT NOT NULL,

    CONSTRAINT FK_ATENDIMENTO_AGENDAMENTO

    FOREIGN KEY (ID_AGENDAMENTO)

    REFERENCES TBL_AGENDA(ID_AGENDAMENTO),

    CONSTRAINT FK_ATENDIMENTO_PACIENTE

    FOREIGN KEY (ID_PACIENTE)

    REFERENCES TBL_PACIENTE(ID_PACIENTE),

    CONSTRAINT FK_ATENDIMENTO_PSICOLOGO

    FOREIGN KEY (ID_PSICOLOGO)

    REFERENCES TBL_PSICOLOGO(ID_PSICOLOGO)

);

CREATE TABLE TBL_PAGAMENTOS (

    ID_PAGAMENTO INT AUTO_INCREMENT PRIMARY KEY,

    ID_TRANSACAO INT NOT NULL,

    FORMA_PAGAMENTO VARCHAR(50) NOT NULL,

    DATA_PAGAMENTO DATETIME NOT NULL,

    VALOR_PAGAMENTO DECIMAL(10,2) NOT NULL,

    CONSTRAINT FK_PAGAMENTO_TRANSACAO

    FOREIGN KEY (ID_TRANSACAO)

    REFERENCES TBL_TRANSACAO(ID_TRANSACAO)

);

5 Consultas SQL

5.1 INNER JOIN: Psicólogos com seus dados cadastrais e CRP

SELECT

    U.ID_USUARIO,

    U.NOME,

    U.CPF,

    P.ID_PSICOLOGO,

    P.CRP

FROM TBL_USUARIO U

INNER JOIN TBL_PSICOLOGO P ON U.ID_USUARIO = P.ID_USUARIO

ORDER BY U.NOME;

O que faz: Une TBL_USUARIO com TBL_PSICOLOGO pela FK ID_USUARIO. O INNER JOIN garante que somente usuários que possuem registro na tabela TBL_PSICOLOGO aparecem — ou seja, apenas os psicólogos cadastrados. Usuários que são apenas pacientes ou administradores não aparecem.

Resultado esperado: Lista de todos os psicólogos ativos no sistema, exibindo nome completo, CPF e número do CRP profissional. Útil para o gestor da clínica verificar o quadro de profissionais cadastrados.

5.2 GROUP BY com funções agregadas: Total de agendamentos por usuário e por status

SELECT

    U.NOME,

    COUNT(A.ID_AGENDAMENTO)                                               AS TOTAL_AGENDAMENTOS,

    SUM(CASE WHEN A.STATUS = 'REALIZADO'  THEN 1 ELSE 0 END)             AS REALIZADOS,

    SUM(CASE WHEN A.STATUS = 'CANCELADO'  THEN 1 ELSE 0 END)             AS CANCELADOS,

    SUM(CASE WHEN A.STATUS = 'CONFIRMADO' THEN 1 ELSE 0 END)             AS CONFIRMADOS,

    SUM(CASE WHEN A.STATUS = 'PENDENTE'   THEN 1 ELSE 0 END)             AS PENDENTES

FROM TBL_USUARIO U

INNER JOIN TBL_AGENDA A ON U.ID_USUARIO = A.ID_USUARIO

GROUP BY U.ID_USUARIO, U.NOME

ORDER BY TOTAL_AGENDAMENTOS DESC;

O que faz: Agrupa os agendamentos por usuário e conta o total geral e o total por status (REALIZADO, CANCELADO, CONFIRMADO, PENDENTE) usando COUNT e SUM com expressões CASE WHEN. Os valores de STATUS seguem o ENUM definido no DDL.

Resultado esperado: Uma linha por usuário com ao menos um agendamento. Mostra o volume total de atendimentos e a distribuição por status. Útil para o gestor identificar a taxa de ocupação de cada psicólogo e o índice de cancelamentos.

5.3 LEFT JOIN: Usuários com seus contatos (inclusive quem não tem contato cadastrado)

SELECT

    U.ID_USUARIO,

    U.NOME,

    C.CONTATO,

    C.TIPO,

    C.CONTATO_NOTIFICACAO

FROM TBL_USUARIO U

LEFT JOIN TBL_CONTATO C ON U.ID_USUARIO = C.ID_USUARIO

ORDER BY U.NOME, C.TIPO;

O que faz: O LEFT JOIN preserva todos os registros de TBL_USUARIO, independentemente de possuírem ou não um contato cadastrado. Para usuários sem contato, as colunas de CONTATO aparecem como NULL.

Resultado esperado: Lista completa de todos os usuários. Usuários com múltiplos contatos (e-mail, telefone, WhatsApp) aparecem em múltiplas linhas. Usuários sem nenhum contato cadastrado também aparecem — com NULL nas colunas de contato — o que permite identificar cadastros incompletos que precisam de atenção.

5.4 RIGHT JOIN: Agendamentos com suas salas (inclusive agendamentos sem sala vinculada)

SELECT

    S.ID_SALA,

    S.NOME_SALA,

    S.INFANTIL,

    S.ONLINE,

    S.AR_CONDICIONADO,

    A.ID_AGENDAMENTO,

    A.DATA_AGENDAMENTO,

    A.STATUS

FROM TBL_SALA S

RIGHT JOIN TBL_AGENDA A ON S.ID_SALA = A.ID_SALA

ORDER BY A.DATA_AGENDAMENTO;

O que faz: O RIGHT JOIN preserva todos os registros de TBL_AGENDA (tabela da direita), mesmo que não haja uma sala correspondente em TBL_SALA. A junção é feita pelo campo ID_SALA, que é a chave estrangeira de TBL_AGENDA referenciando TBL_SALA.

Resultado esperado: Todos os agendamentos aparecem no resultado. Para aqueles com sala vinculada, as informações da sala são exibidas. Para agendamentos sem sala alocada, as colunas de sala ficam NULL — alertando o gestor sobre reservas pendentes de alocação física.

5.5 Complexa (múltiplos JOINs + GROUP BY): Receita por psicólogo e forma de pagamento

SELECT

    U.NOME                        AS NOME_PSICOLOGO,

    P.CRP,

    PG.FORMA_PAGAMENTO,

    COUNT(T.ID_TRANSACAO)         AS QUANTIDADE_TRANSACOES,

    SUM(PG.VALOR_PAGAMENTO)       AS VALOR_TOTAL,

    AVG(PG.VALOR_PAGAMENTO)       AS TICKET_MEDIO,

    MIN(T.DATA_TRANSACAO)         AS PRIMEIRA_TRANSACAO,

    MAX(T.DATA_TRANSACAO)         AS ULTIMA_TRANSACAO

FROM TBL_USUARIO U

INNER JOIN TBL_PSICOLOGO P   ON U.ID_USUARIO    = P.ID_USUARIO

INNER JOIN TBL_TRANSACAO T   ON U.ID_USUARIO    = T.ID_USUARIO

INNER JOIN TBL_PAGAMENTOS PG ON T.ID_TRANSACAO  = PG.ID_TRANSACAO

GROUP BY

    U.ID_USUARIO,

    U.NOME,

    P.CRP,

    PG.FORMA_PAGAMENTO

ORDER BY VALOR_TOTAL DESC;

O que faz: Combina quatro tabelas com três INNER JOINs encadeados: une USUARIO com PSICOLOGO (filtrando apenas psicólogos), depois com TRANSACAO (transações feitas pelo psicólogo) e por fim com PAGAMENTOS (detalhe da forma de pagamento). O agrupamento por ID_USUARIO + FORMA_PAGAMENTO produz uma linha para cada combinação de psicólogo e modalidade de pagamento.

Resultado esperado: Uma linha por par (psicólogo × forma de pagamento). Exibe quantidade de transações, valor total pago, ticket médio e o intervalo temporal das transações. Útil para o gestor financeiro da clínica analisar a preferência de pagamento de cada profissional e acompanhar a evolução do faturamento.

5.6 Selecionando os agendamentos por dia por sala

SELECT ts.NOME_SALA, CAST(ta.DATA_AGENDAMENTO AS DATE) DATA_AGENDAMENTO, COUNT(1) QUANTIDADE_AGENDAMENTOS

FROM TBL_AGENDA ta

JOIN TBL_SALA ts ON ta.ID_SALA = ts.ID_SALA

GROUP BY NOME_SALA, CAST(ta.DATA_AGENDAMENTO AS DATE)

ORDER BY 1,2

O que faz: Combina duas tabelas com um INNER JOINs encadeado: une AGENDA com SALA. O agrupamento por NOME_SALA e DATA_AGENDAMENTO produz uma linha para cada combinação de sala e atendimentos por dia.

Resultado esperado: Uma linha por sala. Exibe quantidade de agendamentos por dia. Útil para o gestor financeiro da clínica analisar o quantidade de uso de cada sala por dia e poder agendar manutenção (como limpeza) por quantidade de agendamento.

5.7 Selecionando os agendamentos por psicologo por paciente

SELECT tupsi.NOME psicologo, tupac.NOME paciente, ts.NOME_SALA,  ta.DATA_AGENDAMENTO, ta.STATUS

FROM TBL_AGENDA ta

JOIN TBL_PACIENTE_ATENDIMENTO tpa ON ta.ID_AGENDAMENTO = tpa.ID_AGENDAMENTO

JOIN TBL_SALA ts ON ta.ID_SALA = ts.ID_SALA

JOIN TBL_PSICOLOGO tp ON tpa.ID_PSICOLOGO = tp.ID_PSICOLOGO

JOIN TBL_USUARIO tupsi ON tp.ID_USUARIO = tupsi.ID_USUARIO

JOIN TBL_PACIENTE tp2 ON tpa.ID_PACIENTE = tp2.ID_PACIENTE

JOIN TBL_USUARIO tupac on tp2.ID_USUARIO = tupac.ID_USUARIO

order by 1,2,3

O que faz: Combina diversas tabelas com um INNER JOINs encadeado: une AGENDA -> PACIENTE_ATENDIMENTO -> SALA -> PSICOLOGO -> PACIENTE. Ordernar por psicólogo, paciente e sala.

Resultado esperado: Uma linha por psicólogo, paciente, sala e agendamento. Exibe a data e o status do agendamente e quem irá parcipar. Útil para o gestor financeiro da clínica estar preparado para saber quais consultas irão acontecer no dia e quem serão os envolvidos.

5.8 Selecionando dados gerais para o adm

SELECT SUM(COALESCE(tp.VALOR_PAGAMENTO,0)) RECEITAS_TOTAIS, SUM(tt.VALOR_TRANSACAO ) RECEITAS_PENDENTES

FROM TBL_TRANSACAO tt

LEFT JOIN (

SELECT tp.ID_TRANSACAO , SUM(tp.VALOR_PAGAMENTO) VALOR_PAGAMENTO

FROM TBL_PAGAMENTOS tp

GROUP BY tp.ID_TRANSACAO

) tp ON tt.ID_TRANSACAO = tp.ID_TRANSACAO

WHERE YEAR(DATA_TRANSACAO) = YEAR(NOW())

AND MONTH(DATA_TRANSACAO) = MONTH(NOW())

O que faz: Combina uma tabela com um subselect, usando INNER JOIN: unindo TRANSACOES -> PAGAMENTOS. Filtrado pelo mês atual.

Resultado esperado: Uma linha por Transação e sues pegamentos. O total de transações daquele mês e o total realmente pago. Útil para que o administrador saiba quanto das transação ainda não foram pagar naquele mês.

6 Análise

6.1 O modelo atende bem ao problema proposto?

De forma geral, sim. O modelo captura os três perfis de usuário identificados — Administrativo (gestor), Psicólogo e Paciente — a partir de uma única entidade central TBL_USUARIO, com especializações em tabelas separadas. Essa estratégia é coerente com sistemas multiusuário onde o login é unificado, mas os papéis diferem.

As entidades TBL_AGENDA e TBL_SALA endereçam diretamente as principais dores identificadas: o agendamento de horários e a alocação de espaço físico. A entidade TBL_TRANSACAO e TBL_PAGAMENTOS cobrem o controle financeiro, e TBL_CONTATO + TBL_ENDERECO completam o cadastro necessário para notificações e relacionamento com o usuário.

Entretanto, o modelo apresenta algumas limitações em relação ao problema completo: não há tabela para PRONTUARIO (exigência da LGPD e do sigilo profissional do CFP), não há entidade de NOTIFICACAO autônoma, e o vínculo entre TBL_AGENDA e os perfis de PSICOLOGO/PACIENTE não é explícito — TBL_AGENDA aponta apenas para TBL_USUARIO genérico, sem distinguir o papel do usuário no agendamento.

6.2 Houve necessidade de normalização? Onde?

Sim. O modelo aplica as três primeiras formas normais:

1FN: Todos os atributos são atômicos e sem grupos repetidos. Os contatos de um usuário, por exemplo, não ficam em colunas separadas (telefone1, telefone2) mas em registros distintos na tabela TBL_CONTATO — eliminando grupos repetidos.

2FN: Todas as dependências funcionais parciais foram eliminadas. O uso de chaves primárias surrogates simples (INT AUTO_INCREMENT) em todas as tabelas garante automaticamente a 2FN, pois não há chave composta da qual dependências parciais possam ocorrer.

3FN: O principal ponto de normalização foi a extração da tabela TBL_CEP. No modelo original sem esta tabela, os campos CIDADE, BAIRRO e UF estariam em TBL_ENDERECO, criando uma dependência transitiva: ID_ENDERECO → CEP → {CIDADE, BAIRRO, UF}. Ao extrair TBL_CEP com CEP como PK, essa dependência transitiva é eliminada — cada CEP é armazenado uma única vez.

6.3 Quais consultas foram mais complexas?

A consulta 5 foi a mais complexa, por combinar três INNER JOINs encadeados com agrupamento multidimensional (GROUP BY em quatro colunas) e cinco funções agregadas simultâneas (COUNT, SUM, AVG, MIN, MAX). A leitura do resultado exige entender que cada linha representa um par único (psicólogo × forma de pagamento), não apenas um psicólogo.

A consulta 2 também apresentou complexidade moderada pelo uso de SUM com expressões CASE WHEN para criar colunas pivô de status — uma técnica menos trivial do que um COUNT simples.

A consulta 8, por haver a necessidade de realizar dois INNER JOIN na mesma tabela usuário, para buscar e diferenciar paciente de psicólogo, se mostrou uma consulta de alta complexidade também.

6.4 O uso de JOINs foi adequado?

Sim. Cada tipo de JOIN foi escolhido de acordo com a necessidade semântica da consulta:

INNER JOIN nas Consultas 1 e 5: adequado quando se quer apenas registros com correspondência nas duas tabelas (ex.: apenas usuários que são psicólogos).

LEFT JOIN na Consulta 3: adequado para preservar todos os usuários mesmo sem contatos cadastrados, identificando cadastros incompletos.

RIGHT JOIN na Consulta 4: adequado para preservar todos os agendamentos mesmo sem sala vinculada, identificando reservas sem espaço alocado.

A escolha de INNER vs. OUTER JOIN determina diretamente quais registros "sem par" aparecem ou não — e isso foi respeitado em todas as consultas, alinhando o tipo de JOIN ao objetivo analítico de cada uma.

6.5 Que melhorias poderiam ser feitas no modelo?

a) Separar os papéis em TBL_AGENDA: A tabela associa apenas ID_USUARIO ao agendamento, sem distinguir se é o psicólogo ou o paciente responsável pela reserva. Uma melhoria seria adicionar colunas ID_PSICOLOGO FK e ID_PACIENTE FK explícitas, tornando o relacionamento mais claro e consultável diretamente — sem precisar cruzar com TBL_PACIENTE_ATENDIMENTO para descobrir quem atendeu quem.

b) Adicionar tabela TBL_PRONTUARIO: O problema de domínio exige sigilo clínico (CFP / LGPD). Uma tabela de prontuários com acesso restrito ao psicólogo responsável seria essencial para um sistema real de gestão clínica.

c) Adicionar tabela TBL_NOTIFICACAO: O sistema prevê notificações automáticas (lembretes, confirmações, alertas de cancelamento). Uma tabela autônoma de notificações permitiria rastrear envios, reenvios e status de entrega sem acoplamento direto à TBL_AGENDA.

d) Restringir o tipo em TBL_CONTATO: O campo TIPO VARCHAR(30) aceita qualquer valor textual. Recomenda-se substituir por ENUM('email', 'telefone', 'whatsapp', 'outro') para garantir consistência dos dados e evitar variações como 'Email', 'EMAIL', 'e-mail'.

e) Adicionar UNIQUE em CRP (TBL_PSICOLOGO): O CRP é um registro profissional único por psicólogo — assim como o CPF é único em TBL_USUARIO. Adicionar UNIQUE KEY uq_crp (CRP) evita cadastros duplicados e garante integridade referencial do modelo.
