# Max Late Game

Plataforma para gestão de torneios, notificações e operação de assinaturas voltada ao universo de poker. O foco deste README é funcionar também como material de portfólio: explicar o produto com clareza, mostrar os principais fluxos e deixar registrado o resultado visual da aplicação. ✨

## Visão Geral

O projeto oferece uma experiência em três camadas:

- Público, com landing page, planos e entrada para a plataforma.
- Admin, com gestão de usuários, torneios, pagamentos, anúncios e configurações.
- Usuário, com acesso ao painel, perfil, torneios e notificações.

Para esta documentação, a base foi semeada com dados reais de demonstração, incluindo:

- `Portfolio User`
- `Portfolio Open Night`
- `Highlight Banner Portfolio`
- um pagamento manual para o fluxo financeiro
- notificações para o usuário de portfólio

## Tecnologias

- Laravel
- Vue 3
- Vue Router
- Pinia
- Bootstrap
- Playwright para captura dos screenshots desta documentação

## Fluxo da Experiência

```mermaid
flowchart LR
	A["Landing page pública"] --> B["Login / autenticação"]
	B --> C["Painel admin"]
	B --> D["Área do usuário"]
	C --> C1["Cadastros e aprovações"]
	C --> C2["Pagamentos e banners"]
	C1 --> D
	C2 --> D
	D --> D1["Perfil e notificações"]
	D --> D2["Consulta de torneios"]
```

## Execução Local

```bash
php artisan storage:link
php artisan key:generate
php artisan serve --host=127.0.0.1 --port=8000
```

## Credenciais de Demonstração

Use estas contas para navegar pelos fluxos documentados aqui:

- Admin: `admin@teste.com` / `password`
- Usuário de portfólio: `portfolio@teste.com` / `password`

## Capturas de Tela

Todas as imagens abaixo foram salvas em `doc/screenshots` com viewport consistente de `1440 x 1200`.

### Público

Landing page com o posicionamento da plataforma e acesso rápido aos planos.

![Home pública](doc/screenshots/01-public-home-hero.png)

Visão dos planos disponíveis, útil para comunicar o modelo comercial da plataforma.

![Planos públicos](doc/screenshots/02-public-home-plans.png)

### Admin

Painel principal com métricas operacionais e visão geral da operação.

![Dashboard do admin](doc/screenshots/03-admin-dashboard.png)

Lista de usuários cadastrados com o usuário de portfólio em evidência.

![Gestão de usuários](doc/screenshots/04-admin-users.png)

Gestão de torneios com o torneio `Portfolio Open Night` aprovado.

![Gestão de torneios](doc/screenshots/05-admin-tournaments.png)

Fluxo financeiro com o pagamento manual registrado para a conta de demonstração.

![Pagamentos](doc/screenshots/06-admin-payments.png)

Área de configurações com o banner criado para reforçar a comunicação visual da plataforma.

![Banners](doc/screenshots/07-admin-banners.png)

### Usuário

Perfil do usuário de demonstração, útil para mostrar a experiência individual pós-login.

![Perfil do usuário](doc/screenshots/08-user-profile.png)

Centro de notificações com os eventos semeados durante a documentação.

![Notificações do usuário](doc/screenshots/09-user-notifications.png)

## Observações de Portfólio

- A documentação foi pensada para deixar a navegação clara em poucos segundos.
- Os screenshots priorizam estados reais da aplicação, não mockups.
- A base de teste foi enriquecida durante a captura para mostrar melhor os fluxos críticos.
- O resultado final equilibra operação, produto e apresentação visual sem exageros.