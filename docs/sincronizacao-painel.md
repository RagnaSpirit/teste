# Auditoria de sincronização com o painel (Admin)

## Conclusão rápida

Pelo código, **o projeto está estruturado para sincronizar o site/apps com o painel**: a maior parte do conteúdo e das configurações é lida de banco (`BusinessSetting`, `DataSetting`) e exposta em páginas web e APIs.

## Ponto crítico de instalação

Existe um detalhe importante: o `RouteServiceProvider.php` atual carrega apenas `routes/install.php`.
Durante a instalação, o sistema copia `RouteServiceProvider.txt` para `RouteServiceProvider.php` para habilitar web/admin/vendor/api.

Se essa cópia não tiver sido executada no ambiente, várias rotas de painel e API não entram em produção.

## O que está vinculado ao painel

### 1) Landing page e páginas públicas institucionais
- Home (`/`) e páginas institucionais usam dados do banco (`DataSetting`/`BusinessSetting`) e variam conforme configuração de landing.
- O painel tem telas dedicadas para:
  - Admin Landing Page
  - React Landing Page
  - Flutter Landing Page
  - Páginas de negócio (termos, privacidade, sobre, refund, cancelation, shipping)

### 2) Configurações globais consumidas por site e apps
- Configuração de pagamento, e-mail, recaptcha, storage, OTP/firebase e OpenAI são carregadas dinamicamente via `ConfigServiceProvider` a partir do banco.
- Isso permite alterar no painel sem mexer em código.

### 3) APIs para sincronização com apps
- API v1 expõe endpoints de:
  - landing page (`/landing-page`, `/react-landing-page`, `/flutter-landing-page`)
  - políticas (`/terms-and-conditions`, `/privacy-policy`, etc.)
  - configurações externas (`/configurations/...`)

## Caminhos do painel (onde ir para cada função)

> Prefixo base do painel: `/admin`

### Conteúdo público / institucional
- **Landing Admin (site principal)**: `/admin/business-settings/pages/admin-landing-page-settings/{tab}`
- **Landing React**: `/admin/business-settings/pages/react-landing-page-settings/{tab}`
- **Landing Flutter**: `/admin/business-settings/pages/flutter-landing-page-settings/{tab}`
- **Termos e Condições**: `/admin/business-settings/pages/business-page/terms-and-conditions`
- **Privacidade**: `/admin/business-settings/pages/business-page/privacy-policy`
- **Sobre nós**: `/admin/business-settings/pages/business-page/about-us`
- **Política de Reembolso**: `/admin/business-settings/pages/business-page/refund`
- **Política de Cancelamento**: `/admin/business-settings/pages/business-page/cancelation`
- **Política de Entrega (shipping)**: `/admin/business-settings/pages/business-page/shipping-policy`

### Operação e sistema
- **Business setup geral**: `/admin/business-settings/business-setup/{tab}`
- **App settings**: `/admin/business-settings/app-settings`
- **Configurações de login**: `/admin/business-settings/login-settings`
- **Idioma/traduções**: `/admin/business-settings/language`
- **Banco (limpeza)**: `/admin/business-settings/db-index`

### Integrações (3rd party)
- **Gateways de pagamento / 3rd party**: `/admin/business-settings/third-party/payment-method`
- **FCM/Firebase notifications**: `/admin/business-settings/fcm`
- **Offline payment setup**: `/admin/business-settings/offline-payment`
- **Email setup**: `/admin/business-settings/email-setup/{type}/{tab?}`
- **Recaptcha**: `/admin/business-settings/third-party/recaptcha`
- **Firebase OTP**: `/admin/business-settings/third-party/firebase-otp`
- **Storage connection (S3/local)**: `/admin/business-settings/third-party/storage-connection`
- **Social login**: `/admin/business-settings/third-party/social-login/view`
- **OpenAI setup**: `/admin/business-settings/open-ai`

### Marketing e conteúdo dinâmico
- **Funil (Funnel landing)**: `/admin/business-settings/marketing/funnel-landing`
- **Notificações (canais)**: `/admin/business-settings/notification-setup`
- **Banners promocionais**: `/admin/promotional-banner/...`

## Checklist prático (para validar no seu ambiente)

1. Acesse `/admin` e tente abrir as telas acima.
2. Edite um texto na landing admin e valide no front (`/`).
3. Edite política (ex.: privacidade) e valide na página pública.
4. Altere um gateway de pagamento e valide em checkout/sandbox.
5. Chame API `/api/v1/landing-page` e confirme retorno atualizado.

## Riscos/atenções

- Se o ambiente estiver em modo de instalação (rotas não trocadas), o painel não refletirá no site.
- Algumas funcionalidades dependem de módulos e permissões (`module_permission_check` e middlewares de módulo).
- Sem `vendor/` instalado, não dá para validar runtime com `php artisan route:list`; esta análise é estática.
