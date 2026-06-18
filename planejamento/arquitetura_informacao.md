# Arquitetura de Informação — Riffly

Este documento mapeia as telas essenciais do ecossistema mobile do Riffly e como o usuário navega entre elas. Como o Riffly é uma plataforma de dois lados, a navegação muda dependendo do tipo de perfil (Banda ou Produtor), mas ambos compartilham a mesma estrutura de entrada e algumas telas em comum.

## Visão geral da navegação

```
                              [Splash / Abertura]
                                      |
                          [Login ou Cadastro]
                                      |
                    Escolha de perfil: Banda ou Produtor
                                      |
                 -----------------------------------------------
                 |                                             |
        [Navegação Banda]                              [Navegação Produtor]
```

A navegação principal, depois do login, acontece por uma barra de abas fixa na parte inferior da tela (bottom navigation), padrão consolidado em apps mobile-first. As abas mudam de acordo com o tipo de perfil logado.

## Navegação do lado Banda

```
[Feed de Produtores] -- [Filtros] 
        |
        v
[Perfil do Produtor] -- [Player de áudio do portfólio]
        |
        v
[Tela de Contato / Mensagem]
        |
        v
[Conversas]


Abas inferiores (Banda):
Feed | Favoritos | Conversas | Perfil próprio
```

### Telas essenciais — Banda

| Tela | Função |
|---|---|
| Feed de Produtores | Tela inicial após login. Lista de cards de produtores, com player de áudio embutido em cada card |
| Filtros | Acionada a partir do Feed (geralmente como bottom sheet). Filtra por gênero, preço, localização, avaliação |
| Perfil do Produtor | Visualização completa: portfólio, avaliações, preço, disponibilidade, botão de contato |
| Favoritos | Lista de produtores salvos pela banda para comparar depois |
| Conversas | Lista de conversas iniciadas com produtores |
| Perfil próprio (Banda) | Dados da banda, edição de informações |

## Navegação do lado Produtor

```
[Painel do Produtor] 
        |
        v
[Edição de Perfil / Portfólio]
        |
[Interesses Recebidos] -- [Perfil da Banda interessada]
        |
        v
[Conversas]


Abas inferiores (Produtor):
Painel | Interesses | Conversas | Perfil próprio
```

### Telas essenciais — Produtor

| Tela | Função |
|---|---|
| Painel do Produtor | Tela inicial após login. Resumo de atividade: visualizações recentes, novas mensagens |
| Edição de Perfil / Portfólio | Onde o produtor define gêneros, sobe áudios, define preço e disponibilidade |
| Interesses Recebidos | Lista de bandas que visualizaram ou favoritaram o perfil do produtor |
| Conversas | Lista de conversas com bandas interessadas |
| Perfil próprio (Produtor) | Visualização do próprio perfil como as bandas o veem |

## Telas compartilhadas

Independente do tipo de perfil, as seguintes telas existem para os dois lados:

- Splash/Abertura
- Login e Cadastro
- Escolha de tipo de perfil (apenas no primeiro acesso)
- Conversas (estrutura de tela igual, conteúdo diferente)
- Configurações de conta

## Princípio de navegação mobile-first

A escolha por bottom navigation (em vez de menu lateral ou hambúrguer) é intencional: em telas pequenas, manter as opções principais sempre visíveis e ao alcance do polegar reduz fricção e número de toques necessários para tarefas frequentes, como checar uma nova mensagem ou voltar ao feed de produtores.