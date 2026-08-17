# Regras de Negócio — ESEIS

> Este documento parte das regras fornecidas inicialmente e as detalha em condições, exceções e comportamentos esperados, para servir de base à modelagem e ao desenvolvimento. Pontos marcados como **[A CONFIRMAR]** são suposições feitas para preencher lacunas e precisam de validação com o time/cliente antes da implementação.

## Perfis de usuário

| Perfil | Descrição |
|---|---|
| **Psicólogo** | Profissional que utiliza as salas da clínica para atendimento. Agenda, paga e acompanha seus próprios agendamentos e créditos. |
| **Gestor da clínica** (Admin) | Responsável pela operação geral: agenda em nome de psicólogos, ajusta e exclui agendamentos, tem visão completa da agenda. |
| **[A CONFIRMAR] Paciente** | O documento de arquitetura menciona um módulo de Pacientes, mas as regras abaixo não descrevem ações do paciente no sistema. Confirmar se o paciente é apenas uma entidade referenciada no atendimento (sem login/acesso ao sistema) ou se terá interações próprias (ex: confirmação de presença, cancelamento). |

---

## 1. Módulo de Créditos

### RN-01 — Compra de créditos
- O psicólogo pode comprar créditos a qualquer momento, independentemente de ter ou não um agendamento em andamento.
- Cada compra de créditos gera uma **transação financeira** registrada em auditoria (entrada de crédito).
- **[A CONFIRMAR]** Forma de pagamento aceita para compra de créditos (cartão, PIX, boleto) e se há integração com gateway de pagamento externo.
- **[A CONFIRMAR]** Existe valor mínimo de compra, pacotes fixos de créditos, ou o psicólogo escolhe o valor livremente?
- **[A CONFIRMAR]** Créditos comprados possuem validade (expiram após X tempo) ou permanecem válidos indefinidamente?

### RN-02 — Saldo de créditos
- O saldo de créditos do psicólogo é sempre a soma de: (créditos comprados) − (créditos utilizados em agendamentos) − (multas debitadas).
- O saldo nunca pode ficar negativo — uma tentativa de uso que resultaria em saldo negativo deve ser bloqueada antes da confirmação.
- Toda alteração de saldo (compra, uso, multa, estorno) deve gerar um registro de transação, garantindo rastreabilidade total (auditável, conforme já definido na arquitetura).

---

## 2. Módulo de Agendamento

### RN-03 — Psicólogo realiza agendamento de sala
- O psicólogo escolhe sala, data e horário disponíveis.
- Ao selecionar um horário, o sistema cria a reserva em estado de **HOLD** (reserva temporária), conforme o fluxo já definido na arquitetura: o slot é travado no banco (constraint de unicidade composta `sala_id` + `horario_inicio`) e recebe um `hold_expires_at`.
- O HOLD garante que dois psicólogos não consigam reservar a mesma sala no mesmo horário simultaneamente (proteção contra condição de corrida).
- Se o pagamento (RN-04) não for confirmado antes de `hold_expires_at`, a reserva é automaticamente liberada pelo Command agendado (scheduler), voltando o horário a ficar disponível.
- **[A CONFIRMAR]** Duração do HOLD — o documento de arquitetura já menciona um exemplo de 120 minutos; confirmar se esse é o valor definitivo de negócio ou apenas um valor de exemplo usado durante os estudos.
- **[A CONFIRMAR]** Existe antecedência mínima para agendar (ex: não pode agendar para os próximos 30 minutos) ou limite máximo de antecedência (ex: não pode agendar com mais de 60 dias de antecedência)?
- **[A CONFIRMAR]** O psicólogo pode ter múltiplos agendamentos simultâneos em salas diferentes, ou existe algum limite de agendamentos ativos/por dia?

### RN-04 — Psicólogo realiza pagamento pelo agendamento
- O pagamento pode ser feito de duas formas: **RN-05 (uso de créditos)** ou **[A CONFIRMAR] pagamento direto** (cartão/PIX no momento do agendamento, sem passar por créditos).
- A confirmação do pagamento é o evento que transforma a reserva de **HOLD** para **CONFIRMADA**.
- Caso o pagamento seja recusado/falhe, a reserva permanece em HOLD até expirar naturalmente (não é liberada instantaneamente, para permitir nova tentativa dentro da janela).
- **[A CONFIRMAR]** É permitido pagamento parcial com créditos + complemento em outro meio de pagamento?

### RN-05 — Psicólogo utiliza créditos para pagar o agendamento
- No momento da confirmação do agendamento, o sistema verifica se o saldo de créditos é suficiente para cobrir o valor da sessão/sala.
- Se suficiente: debita o valor do saldo, gera a transação de débito, e confirma o agendamento (HOLD → CONFIRMADO).
- Se insuficiente: bloqueia a confirmação e orienta o psicólogo a comprar créditos adicionais (RN-01) ou escolher outro meio de pagamento (RN-04).
- **[A CONFIRMAR]** O valor debitado é fixo por sala/horário, ou varia conforme sala, duração ou horário (ex: horário de pico mais caro)?

---

## 3. Módulo de Gestão (Gestor da Clínica)

### RN-06 — Gestor realiza agendamento por um psicólogo
- O gestor pode criar um agendamento em nome de qualquer psicólogo cadastrado, selecionando o psicólogo, sala e horário.
- O agendamento criado dessa forma segue o mesmo fluxo de HOLD/confirmação (RN-03), mas:
  - **[A CONFIRMAR]** o pagamento é obrigatório no ato (mesmo fluxo do psicólogo) ou o gestor pode confirmar o agendamento sem exigir pagamento imediato (ex: acerto posterior, cortesia, convênio)?
- Toda ação do gestor sobre um agendamento de terceiro deve ser registrada em auditoria, identificando o gestor responsável pela ação (não apenas o psicólogo dono do agendamento).

### RN-07 — Gestor realiza ajuste em um agendamento específico
- O gestor pode alterar sala, data ou horário de um agendamento já existente (independente de quem o criou).
- Ao ajustar, o sistema deve revalidar a disponibilidade do novo slot (mesma constraint de unicidade da RN-03) — não é permitido mover um agendamento para um horário já ocupado por outra reserva confirmada.
- **[A CONFIRMAR]** Um ajuste de horário/sala gera nova cobrança de multa ou é tratado como remarcação isenta, diferente de um cancelamento (RN-11)?
- Toda alteração deve manter histórico do valor anterior (auditoria), não apenas sobrescrever o dado.

### RN-08 — Gestor exclui um agendamento
- O gestor pode excluir/cancelar qualquer agendamento, de qualquer psicólogo.
- **[A CONFIRMAR]** A exclusão pelo gestor segue a mesma regra de multa por cancelamento tardio (RN-11, menos de 3 horas de antecedência) aplicada ao psicólogo, ou o gestor tem poder de isentar a multa nesse caso (ex: cancelamento por problema da clínica, não do psicólogo)?
- Recomendação: a exclusão não deve ser um `DELETE` físico do registro — usar exclusão lógica (soft delete / status `cancelado`), preservando o histórico para auditoria e para o financeiro (estorno de créditos, se aplicável).
- Se o agendamento estava confirmado e pago com créditos, e o cancelamento foi isento de multa, o valor deve ser estornado ao saldo do psicólogo.

### RN-09 — Gestor verifica os agendamentos gerais
- O gestor tem acesso a uma visão consolidada de todos os agendamentos da clínica (todas as salas, todos os psicólogos), com filtros por período, sala, psicólogo e status (HOLD, confirmado, cancelado, concluído).
- Essa visão é somente leitura por padrão, com ações de edição/exclusão disponíveis a partir dela (RN-07, RN-08).
- **[A CONFIRMAR]** Existe necessidade de visão em formato calendário (grade de horários por sala) além de listagem tabular?

---

## 4. Módulo de Consulta (Psicólogo)

### RN-10 — Psicólogo verifica seus horários agendados, saldo de créditos e transações
- O psicólogo tem acesso apenas aos **próprios** dados: seus agendamentos, seu saldo de créditos, seu histórico de transações (compras, usos, multas, estornos).
- Essa é uma regra de autorização, não apenas de interface: o backend deve garantir que o psicólogo nunca acesse (nem por manipulação de URL) agendamentos ou transações de outro psicólogo — mesmo padrão de Policy já validado no projeto de estudo (`admin OR dono do registro`).
- A listagem de agendamentos deve permitir filtro por status (futuros, passados, cancelados) e por período.
- O extrato de transações deve ser cronológico e mostrar saldo corrente, similar a um extrato bancário.

---

## 5. Regras transversais (aplicam-se a múltiplos módulos)

### RN-11 — Cancelamento com multa
- Já definida na arquitetura: se o cancelamento ocorrer com menos de 3 horas de antecedência do horário de início do atendimento, aplica-se multa, debitada do saldo de créditos do psicólogo.
- **[A CONFIRMAR]** Valor/percentual da multa (fixo, percentual do valor da sessão, etc.).
- **[A CONFIRMAR]** O que acontece se o saldo de créditos for insuficiente para cobrir a multa no momento do cancelamento (fica devedor, é bloqueado de novos agendamentos até quitar, etc.)?

### RN-12 — Auditoria
- Toda ação relevante (criar, ajustar, cancelar agendamento; comprar, usar, estornar crédito) deve gerar um registro de auditoria contendo: quem executou a ação, quando, e o que mudou (valor anterior → valor novo).
- Ações executadas pelo gestor **em nome de** um psicólogo devem deixar claro os dois atores envolvidos (quem agiu vs. quem foi afetado).

### RN-13 — Consentimento de dados (LGPD)
- Já registrado no documento de arquitetura: por envolver dado sensível de saúde, o cadastro de paciente exige termo de consentimento explícito para tratamento de dados pessoais, distinto de um simples aceite de cookies.
- **[A CONFIRMAR]** Se o psicólogo também precisa aceitar termos específicos (ex: termo de uso da plataforma, política de cancelamento) no primeiro acesso.

### RN-14 — Notificações
- **[A CONFIRMAR — não mencionado nas regras originais, mas presente na arquitetura]** Quais eventos disparam notificação automática? Sugestões a validar:
  - confirmação de agendamento;
  - lembrete de sessão próxima;
  - HOLD prestes a expirar (evitar perda acidental da reserva);
  - aplicação de multa;
  - saldo de créditos baixo.

---

## Pontos em aberto para validação (resumo)

1. Papel do Paciente no sistema (tem login ou é só referência no atendimento?)
2. Regras de compra de créditos: forma de pagamento, pacotes, validade
3. Duração definitiva do HOLD
4. Limites de antecedência mínima/máxima para agendamento
5. Possibilidade de pagamento parcial (créditos + outro meio)
6. Valor da sessão: fixo ou variável por sala/horário
7. Ajuste de agendamento pelo gestor: gera multa ou é isento?
8. Poder do gestor de isentar multa em exclusões
9. Necessidade de visão em calendário/grade, além de listagem
10. Regra para multa não coberta por saldo insuficiente
11. Valor/percentual da multa por cancelamento tardio
12. Termos de uso específicos para o psicólogo
13. Lista definitiva de eventos que disparam notificação