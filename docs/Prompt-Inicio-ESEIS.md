# Prompt para iniciar o desenvolvimento do ESEIS

> Cole este texto como primeira mensagem da nova conversa, junto com os anexos indicados no final.

---

Olá! Vou iniciar o desenvolvimento do **ESEIS**, um sistema de gestão para clínica de psicologia (reserva de salas, agenda, créditos/financeiro, multas por cancelamento). Este é meu projeto principal de estudo em Laravel, e quero que você me acompanhe como fez em uma conversa anterior — me ensinando conceitos, revisando meu código, e me ajudando a debugar problemas reais, sem fazer o trabalho inteiro por mim.

Eu prefeiro respostas curtas e diretas, a menos que eu peça o contrário, sempre envie perguntas curtas e diretas. Não quero várias resposta a cada pergunta, apenas duas é o suficiente. Não quero resumo no fim de cada resposta, a menos que eu peça.

Vamos avançar bem devargar, em pequenos passos, conforme eu for pedindo.

## Sobre mim

- Já sei lógica de programação, Python, JavaScript, banco de dados e noções de MVC.
- Completei um projeto de estudo em Laravel (CRUD completo de Pessoas/Telefones) cobrindo: Route Model Binding, Form Requests, Policies, Middleware customizado, Model Observers, Jobs assíncronos com Queue, Task Scheduling, e testes automatizados (Feature Tests). Todos esses conceitos foram aplicados na prática e funcionando, não apenas estudados em teoria.
- **Importante:** entendo a teoria de tudo isso bem, mas ainda tive bastante apoio de IA na prática — não tenho 100% de autonomia ainda para implementar sozinho. Prefiro que você me explique e me guie passo a passo, não que só entregue código pronto.
- Prefiro respostas **curtas e diretas**, avançando devagar, um conceito de cada vez. Não me sobrecarregue com informação demais de uma vez.
- Estou confortável corrigindo você se algo não fizer sentido para a minha realidade — por favor, também me corrija quando eu errar, com explicação de causa raiz, não só a correção.

## Ambiente técnico

- **SO:** Ubuntu no WSL2 (Windows 11)
- **Editor:** VS Code
- **Containers Docker** (4 serviços separados, já validados em projeto anterior):
  - `laravel` — Apache + PHP, aplicação principal
  - `scheduler` — `php artisan schedule:work`, `restart: unless-stopped`
  - `queue` — `php artisan queue:work`, `restart: unless-stopped`
  - `mariadb` — banco de dados
- Usuário do container alinhado por UID com o usuário do WSL (evita problemas de permissão entre WSL/Docker/VS Code — já enfrentamos e resolvemos isso antes).

## Decisões de arquitetura já tomadas (não renegociar sem motivo forte)

1. **Backend: Laravel MVC + Blade puro.** Sem React/SPA, sem API REST separada. Motivo: o servidor de produção (HomeHost, shared hosting) **não roda Node.js**. Controllers retornam views Blade diretamente.
2. **Autenticação: Laravel Breeze (stack Blade), sessão/cookie.** Sem JWT, sem Sanctum — desnecessários nesse cenário.
3. **Tailwind CSS** compilado **localmente** (`npm run dev` durante desenvolvimento, `npm run build` antes de cada commit relevante). Os arquivos finais (`public/build/`) são **commitados no repositório** — não usar `.gitignore` nessa pasta, já que a HomeHost só serve arquivos estáticos.
4. **Autorização em camadas:** Middleware por rota/grupo (bloqueia áreas inteiras por perfil) + Policy por entidade (autorização fina por registro) + Mass Assignment Protection (`$fillable` nunca inclui `role`) + reconfirmação sempre no backend (a UI só esconde botões via `@can`, nunca é a única barreira).
5. **Padrão de aprovação/expiração assíncrona:** Job (fila) + Command agendado (scheduler) + testes com `Queue::fake()` e `Sleep::fake()` (usando a classe `Illuminate\Support\Sleep`, não a função nativa `sleep()`, para manter os testes rápidos). Esse é o padrão que vou reaplicar no mecanismo de HOLD de reserva.
6. **Segurança operacional já validada:** `.env` nunca commitado; nenhuma credencial/chave SSH no repositório; validar sempre com `git log --all --full-history -- <arquivo>` antes de considerar um vazamento resolvido.
7. **Testes:** Feature Tests como padrão principal, com `RefreshDatabase`, cobrindo autorização + regra de negócio central + caminho feliz de cada funcionalidade.
8. **Consideração futura em aberto (não decidida):** avaliar Livewire caso alguma tela precise de interatividade sem reload completo (ex: verificação de disponibilidade em tempo real na agenda). Não usar Inertia.js/React — reintroduziria dependência de Node em produção.

## Documentos anexados (leia antes de começarmos)

Estou anexando 3 documentos que já produzimos juntos e que servem de fonte da verdade para este projeto:

1. **Arquitetura.md** — arquitetura de software completa: camadas, stack, estrutura de pastas, Docker, segurança, fluxos críticos (HOLD, cancelamento, financeiro).
2. **Regras-de-Negocio.md** — regras de negócio detalhadas (créditos, agendamento, gestão, multas, auditoria), incluindo uma lista de **13 pontos marcados como "A CONFIRMAR"** que ainda precisam de decisão antes de modelar certas partes do banco.
3. **Banco_de_Dados_ESEIS_v2.docx** — modelo de dados (DER, DDL completo, consultas SQL), já revisado para incluir HOLD, sistema de créditos/multas, prontuário e notificações.

## O que peço que você faça primeiro

1. Leia os 3 documentos anexados por completo antes de sugerir qualquer código.
2. Não comece a implementar nada ainda. Primeiro, me ajude a **resolver os pontos "A CONFIRMAR"** do `Regras-de-Negocio.md` — são decisões de negócio que vão impactar o modelo de dados e não quero modelar em cima de suposição errada.
3. Depois de resolvidos os pontos em aberto, proponha comigo um **roteiro de desenvolvimento em etapas** (parecido com o cronograma que seguimos no projeto de estudo), adaptado à ordem de dependência real das entidades do ESEIS (ex: Usuários/Perfis antes de Salas, Salas antes de Agenda, Agenda antes de Financeiro).
4. A partir daí, seguimos etapa por etapa, sempre no mesmo formato do projeto anterior: você explica o conceito, eu implemento, testamos juntos, corrigimos o que der errado.

---

## O que anexar nesta nova conversa

Recomendo anexar **os 3 documentos**, sem exceção:

| Documento                              | Por que é necessário                                                                                                                                                                                                              |
| -------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Arquitetura.md**               | Sem ele, o próximo chat não sabe que React foi descartado, não conhece a estrutura de pastas/Docker, nem os padrões de segurança já validados — corre o risco de sugerir SPA ou Sanctum, contrariando decisões já tomadas. |
| **Regras-de-Negocio.md**         | É a base de todo o modelo de dados e das regras de autorização. Sem ele, o próximo chat teria que reconstruir do zero as regras de HOLD, multa e créditos — e provavelmente de forma inconsistente com o que já decidimos.   |
| **Banco_de_Dados_ESEIS_v2.docx** | É o contrato de dados. Sem ele, corre-se o risco de modelar tabelas incompatíveis com o DDL já validado (nomes de coluna, constraints de unicidade compostas, etc.), gerando retrabalho de migration mais tarde.                 |

Os três documentos são complementares — cada um cobre uma camada diferente (arquitetura de software, regra de negócio, modelo de dados) e o prompt acima referencia os três diretamente. Não recomendo anexar apenas um ou dois: o próximo chat perderia contexto crítico e você teria que ficar preenchendo lacunas na conversa, que é exatamente o retrabalho que este prompt busca evitar.

**Não é necessário** anexar o histórico completo desta conversa — o prompt acima já resume as decisões e o estilo de trabalho que importam. Anexar a conversa inteira só adicionaria ruído (todo o processo de debug de Docker/permissões, por exemplo, é interessante como histórico, mas irrelevante para o próximo chat agir).
