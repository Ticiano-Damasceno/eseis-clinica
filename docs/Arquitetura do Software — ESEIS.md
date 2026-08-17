# Arquitetura do Software — ESEIS

> Monólito Laravel (MVC + Blade), sem frontend separado

Um monólito modular, organizado por domínios de negócio, e não por "tipo de arquivo" apenas.

**Por quê?**<br>
`Porque o sistema tem escopo bem definido;
depende fortemente de dados compartilhados;
precisa de consistência transacional;
terá uma equipe pequena no início;
e deve rodar bem em ambiente de hospedagem compartilhada.
Os documentos inclusive defendem a abordagem monolítica para o MVP, justamente por a agenda, o financeiro e as notificações compartilharem muitos dados e por o escopo ainda ser controlado.`

## Decisão de stack: Laravel + Blade (sem React/SPA)

**Mudança importante em relação ao rascunho original:** o plano de hospedagem definido (HomeHost, shared hosting) **não oferece Node.js em produção**. Por isso, o frontend React foi descartado em favor de uma arquitetura **100% Laravel MVC com views Blade**, eliminando a necessidade de uma API separada consumida por SPA.

**Consequências dessa decisão:**
- Não existe camada de API REST separada — Controllers retornam views Blade diretamente
- Autenticação via **sessão** (cookies HTTP-only), não JWT/token — mais simples e mais segura nesse cenário, já que frontend e backend estão no mesmo domínio
- Tailwind CSS é usado para estilização, mas **compilado localmente** (ambiente de desenvolvimento, via Docker/Node) — nunca em produção
- Os arquivos finais compilados (`public/build/`) são **commitados no repositório**, já que a HomeHost apenas serve arquivos estáticos, sem rodar `npm run build` no servidor
- Fluxo de trabalho: `npm run dev` durante desenvolvimento (watch mode) → `npm run build` antes de cada commit/deploy relevante

**Consideração futura (não decidida ainda):** para telas que exigirem maior interatividade sem reload completo de página (ex: tela de agendamento com verificação de disponibilidade em tempo real), avaliar **Livewire** como alternativa — mantém tudo em PHP puro, sem exigir Node em produção, diferente de Inertia.js (que reintroduziria a dependência de Node).

## Camadas da arquitetura

**1) Apresentação (Blade)**

- views Blade organizadas por domínio (`resources/views/{modulo}/`);
- componentização via Blade Components (`x-page-layout`, `x-page-shell`, componentes de formulário reutilizáveis);
- Tailwind CSS para estilização;
- validações básicas de interface (HTML5 + feedback de erros do Laravel via `@error`);
- flash messages (`session()->with(...)`) para feedback de ações.

**2) Controllers / Casos de uso**

- autenticação (Laravel Breeze, sessão);
- autorização (Policies + Middleware por perfil);
- orquestração dos casos de uso;
- validação de entrada via Form Requests;
- retorno de views Blade (não JSON, exceto se surgir necessidade futura de API para app mobile).

**3) Domínio**

- entidades (Models Eloquent);
- regras centrais do negócio;
- políticas de reserva;
- multas;
- HOLD;
- transações financeiras;
- Model Events / Observers para reações automáticas do ciclo de vida das entidades (ex: cálculo de `hold_expires_at` no evento `creating` da reserva).

**4) Persistência**

Responsável por:
- acesso ao MariaDB via Eloquent ORM;
- consultas;
- inserts/updates;
- transações;
- integridade relacional (foreign keys com `onDelete` explícito);
- constraints de unicidade — inclusive **compostas**, essenciais para o HOLD (ver seção de fluxos críticos).

**5) Infraestrutura e integrações**

- envio de e-mail;
- SMS;
- WhatsApp;
- geração de PDF;
- cron jobs / scheduler;
- filas (Queue) para processamento assíncrono;
- logs;
- armazenamento de arquivos.

## Backend em PHP (Laravel)

**Vantagens confirmadas na prática, durante o projeto de estudo:**
- estrutura pronta (Route Model Binding, Form Requests, Policies);
- migrations, inclusive com constraints avançadas (unicidade composta);
- ORM (Eloquent) com relacionamentos (`hasMany`/`belongsTo`);
- validação centralizada e reutilizável (Form Requests);
- scheduler (`schedule:work`) rodando em container próprio;
- jobs assíncronos (Queue com driver `database`), rodando em container próprio (`queue:work`);
- autenticação organizada (Breeze, sessão);
- testes automatizados (Feature Tests) cobrindo autorização, filas e execução de jobs.

## Autenticação e Autorização

**Autenticação:** Laravel Breeze (stack Blade), autenticação por sessão/cookie. Sem JWT, sem Sanctum — desnecessários já que não há frontend separado.

**Autorização em camadas** (validado na prática durante o projeto de estudo):

1. **Middleware por rota/grupo** — bloqueia acesso a áreas inteiras por perfil (ex: middleware `admin` protegendo rotas administrativas), sempre combinado com `auth` (`['auth', 'admin']`)
2. **Policies por entidade** — autorização fina, no nível do registro específico (ex: um Psicólogo só pode editar seus próprios agendamentos, Admin pode editar qualquer um)
3. **Mass Assignment Protection** (`$fillable`) — impede que campos sensíveis (como `role`) sejam alterados via formulário, mesmo que manipulado
4. **Reconfirmação no servidor** — a UI pode esconder botões via `@can`, mas a autorização real sempre roda no backend; a interface nunca é a única barreira

**Padrão de perfis (roles):** coluna `role` na tabela `users`, com valor padrão restritivo (menor privilégio por padrão). Nenhuma rota permite que um usuário altere seu próprio `role` — essa é sempre uma ação administrativa, protegida por middleware dedicado.

## LGPD e dados sensíveis

Como o sistema lida com **dados de saúde** (agendamento com psicólogo é considerado dado sensível pela LGPD), é necessário:

1. **Termo de consentimento para tratamento de dados pessoais** no fluxo de cadastro do paciente (diferente de um simples banner de cookies)
2. **Política de privacidade** explicando o que é coletado, por quê, e por quanto tempo
3. Cookies estritamente técnicos (sessão, CSRF) não exigem banner de consentimento — mas qualquer cookie de analytics/marketing futuro exigiria

**Ação pendente:** incluir tela de Termo de Consentimento no fluxo de cadastro do paciente como parte do desenho de telas do MVP.

## Segurança operacional (lições aplicadas do projeto de estudo)

- `.env` **nunca** commitado (confirmado no `.gitignore` desde o início do projeto);
- chaves SSH/credenciais nunca commitadas no repositório — verificação de histórico do Git (`git log --all --full-history -- <arquivo>`) antes de considerar um vazamento resolvido, já que remover o arquivo não apaga o histórico;
- `APP_DEBUG=false` obrigatório em produção (evita exposição de stack trace/queries para o usuário final);
- containers Docker rodando com UID alinhado ao usuário do host (evita problemas de permissão entre WSL e container, e entre container e volume);
- containers de longa duração (`scheduler`, `queue`) configurados com `restart: unless-stopped`, para não pararem silenciosamente em caso de falha.

## Arquitetura lógica do sistema

**Módulos principais**
- Auth
- Usuários
- Psicólogos
- Pacientes
- Salas
- Agenda / Reservas
- Financeiro / Créditos
- Notificações
- Auditoria
- Configurações

Isso está alinhado com o modelo conceitual e relacional dos documentos, que centraliza o sistema em entidades como `USUÁRIO`, `PSICÓLOGO`, `PACIENTE`, `SALA`, `AGENDA`, `TRANSAÇÃO`, `CONTATO` e `ENDEREÇO`, além da entidade associativa de atendimento.

## Arquitetura de pastas

Estrutura de **projeto único** (monólito Laravel), sem separação `frontend/`/`backend/` — o frontend vive dentro do próprio projeto Laravel, como views Blade.

```bash
eseis-clinica/
├── README.md
├── .gitignore
├── .env.example
├── docker-compose.yml
├── Dockerfile
├── docs/
│   ├── arquitetura/
│   ├── banco-de-dados/
│   ├── regras-de-negocio/
│   └── api/
├── docker/
│   └── apache/
└── src/                          # projeto Laravel
    ├── app/
    │   ├── Console/
    │   │   └── Commands/
    │   ├── Http/
    │   │   ├── Controllers/
    │   │   ├── Middleware/
    │   │   └── Requests/
    │   ├── Models/
    │   ├── Services/
    │   ├── Repositories/
    │   ├── Jobs/
    │   ├── Mail/
    │   ├── Notifications/
    │   ├── Observers/
    │   ├── Policies/
    │   └── Helpers/
    ├── bootstrap/
    ├── config/
    ├── database/
    │   ├── migrations/
    │   ├── seeders/
    │   └── factories/
    ├── public/
    │   └── build/                # assets Tailwind compilados — versionado
    ├── resources/
    │   ├── views/
    │   │   ├── components/       # Blade Components reutilizáveis
    │   │   └── {modulo}/         # views por domínio (agenda, salas, financeiro...)
    │   ├── css/
    │   └── js/
    ├── routes/
    │   ├── web.php
    │   └── console.php           # agendamento de comandos (Schedule::command)
    ├── storage/
    └── tests/
        └── Feature/
```

**Separação por responsabilidade (dentro do `app/`):**
- **Controllers**: recebem requests e retornam views Blade
- **Requests**: validação de entrada (Form Requests)
- **Services**: regras de negócio mais complexas, quando o Controller começa a crescer demais
- **Repositories**: acesso aos dados (opcional — avaliar se agrega valor real ou se o Eloquent já resolve diretamente nos Controllers/Services)
- **Models**: entidades persistidas (Eloquent), incluindo relacionamentos
- **Jobs**: tarefas assíncronas (ex: aprovação de cadastro, expiração de HOLD)
- **Observers**: reações automáticas a eventos do ciclo de vida dos Models
- **Policies**: autorização por entidade
- **Notifications**: envio multicanal (e-mail, SMS, WhatsApp)

## Infraestrutura Docker

Baseado no ambiente validado durante o projeto de estudo, com serviços separados por responsabilidade:

```yaml
services:
  laravel:      # aplicação principal (Apache + PHP)
  scheduler:    # php artisan schedule:work — restart: unless-stopped
  queue:        # php artisan queue:work --tries=3 --timeout=90 — restart: unless-stopped
  mariadb:      # banco de dados
```

**Pontos de atenção:**
- `scheduler` e `queue` rodam em containers **separados** do container principal da aplicação — evita que um travamento de fila derrube o site
- `--timeout` no `queue:work` deve ter um valor definido (não `0`), para evitar que um Job travado bloqueie o worker indefinidamente
- Todos os containers de longa duração usam `restart: unless-stopped`
- Usuário do container alinhado por UID com o host, para evitar conflitos de permissão entre WSL/Docker/VS Code

## Desenho dos fluxos críticos

### Fluxo de reserva com HOLD

1. Psicólogo escolhe sala e horário.
2. Sistema cria reserva em HOLD.
3. Banco trava o slot via **constraint de unicidade composta** (`sala_id` + `horario_inicio`), impedindo dupla reserva mesmo em caso de concorrência real (duas requisições simultâneas).
4. Registra `hold_expires_at` — preenchido automaticamente via **Model Observer** (evento `creating`), não manualmente em cada ponto do código que cria uma reserva.
5. Pagamento confirmado?
   - sim: confirma reserva;
   - não: **Task Scheduler** (`schedule:work`, rodando em container dedicado) dispara periodicamente um Command que libera reservas com HOLD expirado, atualizando o status automaticamente.

Os documentos deixam claro que o HOLD é central e precisa de concorrência controlada para evitar dupla reserva. A validação de disponibilidade deve ocorrer em duas camadas: (1) validação de aplicação, via regra customizada de unicidade filtrada (equivalente a `Rule::unique()->where()`), retornando erro amigável ao usuário; e (2) constraint física no banco de dados, como última linha de defesa contra condição de corrida.

### Fluxo de cancelamento com multa

1. Usuário cancela a reserva.
2. Sistema compara horário atual com início do atendimento.
3. Se faltarem menos de 3 horas, aplica multa.
4. Multa é debitada dos créditos.
5. Registro vai para auditoria e financeiro.

### Fluxo financeiro

- cada recarga vira uma transação;
- cada atendimento concluído debita saldo;
- cada multa vira lançamento financeiro;
- todo movimento precisa ficar auditável.

### Fluxo de aprovação assíncrona (padrão validado no projeto de estudo)

Padrão a ser reaplicado sempre que uma ação precisar de processamento demorado ou expiração automática (ex: expiração de HOLD, aprovação de cadastro):

1. Ação inicial marca o registro com status intermediário (ex: `pendente`, `processando`).
2. Um **Job** é despachado para a fila (`Queue::dispatch`), processado de forma assíncrona por um worker dedicado.
3. Um **Command agendado** (via `Schedule::command`) varre periodicamente registros pendentes vencidos, aplicando a regra de expiração/rejeição automaticamente.
4. Cada etapa é coberta por **Feature Tests**, incluindo simulação de fila (`Queue::fake()`) e simulação de tempo de espera (`Sleep::fake()`, usando a classe `Illuminate\Support\Sleep` no código de produção — não a função nativa `sleep()` — para que o comportamento seja testável sem tornar a suíte de testes lenta).

## Testes automatizados

Estratégia validada no projeto de estudo, a ser mantida no ESEIS:

- **Feature Tests** como padrão principal — testam o fluxo completo (rota → autorização → controller → banco), simulando o uso real do sistema;
- cobertura mínima esperada por módulo: autorização (quem pode/não pode acessar), regra de negócio central, e o "caminho feliz" da funcionalidade;
- uso de `RefreshDatabase` para isolar o estado do banco entre testes;
- fakes do Laravel (`Queue::fake()`, `Sleep::fake()`, `Mail::fake()`) para evitar efeitos colaterais reais e manter a suíte rápida;
- testes servem como documentação viva das regras de negócio — especialmente relevante para o HOLD e para a lógica de multas, onde o comportamento esperado precisa estar explícito e protegido contra regressão.