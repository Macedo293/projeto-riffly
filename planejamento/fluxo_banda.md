# Fluxo de Usuário — Banda Independente

Este fluxo descreve o caminho da Banda desde o primeiro acesso até conseguir contratar um produtor, com foco em poucos toques e telas limpas, característico de uma experiência mobile-first.

## Passo a passo

**1. Abertura do app**
A banda abre o Riffly pela primeira vez e vê a tela de splash, seguida da opção de Login ou Cadastro.

**2. Cadastro**
A banda escolhe "Criar conta" e preenche dados básicos (nome da banda, e-mail, senha) ou opta por cadastro rápido via rede social, reduzindo fricção logo na entrada.

**3. Escolha de tipo de perfil**
Imediatamente após o cadastro, é perguntado: "Você é uma Banda ou um Produtor?". A banda seleciona "Banda".

**4. Configuração inicial do perfil**
Tela curta e objetiva pedindo apenas o essencial para começar a usar: gênero musical da banda, cidade/região (para futura referência de filtro) e, opcionalmente, foto da banda. Esse passo é intencionalmente leve — não exige preencher tudo de uma vez, podendo ser completado depois.

**5. Chegada ao Feed de Produtores**
A banda já entra direto na tela principal: o feed de produtores, com cards deslizáveis. Não há tela de boas-vindas longa ou tutorial obrigatório, para já entregar valor rapidamente.

**6. Exploração do feed**
A banda rola o feed e visualiza cards de produtores, cada um com foto, gênero musical de especialidade, faixa de preço resumida e um player de áudio embutido.

**7. Reprodução de amostra**
Direto no card, sem precisar abrir outra tela, a banda toca um trecho curto de uma produção anterior daquele produtor para ter uma primeira impressão sonora.

**8. Aplicação de filtros (opcional)**
Caso o feed geral seja muito amplo, a banda toca em "Filtros", que abre como uma bottom sheet (sem trocar de tela), permitindo refinar por gênero, faixa de preço, localização e avaliação.

**9. Abertura do perfil completo do produtor**
Ao se interessar por um card, a banda toca para abrir o perfil completo daquele produtor, vendo portfólio completo, avaliações de outras bandas, faixa de preço detalhada e disponibilidade de agenda.

**10. Favoritar (opcional)**
Se a banda quiser comparar com outras opções antes de decidir, ela favorita o perfil, que fica salvo na aba "Favoritos" para revisão posterior.

**11. Início de contato**
Decidida pelo produtor, a banda toca em "Entrar em contato", que abre uma tela simples de mensagem com um campo de texto e, opcionalmente, sugestões rápidas de mensagem (ex: "Quero saber mais sobre disponibilidade").

**12. Envio da mensagem inicial**
A banda envia a mensagem. A partir daqui, a conversa passa a aparecer na aba "Conversas" do app.

**13. Negociação via chat**
Banda e produtor conversam pelo chat dentro do app para alinhar detalhes como prazo, valor final e formato da gravação.

**14. Contratação confirmada (fora do app, nesta versão)**
Como o MVP não inclui pagamento ou contrato digital integrado, a contratação em si (combinação final de valor e agendamento) é fechada via conversa, e o app serve como o canal que viabilizou esse encontro.

**15. Avaliação pós-serviço**
Após o serviço ser concluído, a banda recebe uma notificação incentivando a avaliar o produtor, fechando o ciclo e alimentando a confiança de futuras bandas que passarem pelo mesmo perfil.

## Pontos de atenção mobile-first

- Cadastro inicial pede o mínimo possível de informação para reduzir abandono.
- Reprodução de áudio acontece sem trocar de tela, mantendo o fluxo de exploração fluido.
- Filtros usam bottom sheet em vez de nova tela, preservando o contexto do feed.
- Nenhuma etapa obrigatória deste fluxo depende de uso do teclado físico ou ações complexas de mouse/trackpad, já que tudo é pensado para toque.