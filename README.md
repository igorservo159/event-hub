# 🎉 Event Hub

Event Hub é uma plataforma de gerenciamento de eventos desenvolvida com PHP Laravel, Blade e MariaDB. Esse projeto foi criado com fins educacionais, com o objetivo de explorar ao máximo os recursos oferecidos pelo Laravel, desde os fundamentos até funcionalidades mais avançadas.

## 🚀 Visão Geral

O Event Hub permite que diferentes tipos de usuários interajam com eventos de formas específicas, criando uma experiência completa e dinâmica, desde a criação até a participação e o gerenciamento de disputas.

### 👥 Tipos de Usuários

* **Inscrito**:  
    Pode se inscrever em eventos, realizar pagamentos e solicitar reembolsos.

* **Organizador**:  
    Tem permissão para **criar, editar e gerenciar eventos**, receber pagamentos, aprovar ou negar reembolsos, e enviar notificações.

* **Administrador**:  
    Coordena disputas entre usuários, gerencia reembolsos, pode transformar inscritos em organizadores, e possui permissões completas sobre a plataforma.

## ⚙️ Funcionalidades

* ✅ Cadastro e login de usuários

* 🗓️ Criação, edição e visualização de eventos

* 📩 Notificações por e-mail (incluindo cadastro de SPDM)

* 💳 Pagamentos e reembolsos de eventos

* 🧾 Gestão de permissões baseada em **Policies**

* 🔄 Sistema de eventos e listeners personalizados

* ⚖️ Administração de disputas e resolução de conflitos

* 🔐 Controle de acesso detalhado com middleware

* 📦 Uso completo de recursos como:

    * **Events & Listeners**
    * **Migrations**
    * **Notifications**
    * **Exception Handling**
    * **Providers**
    * **Policies**
    * ...e muito mais!

## 🛠️ Tecnologias Utilizadas

* **Laravel**  
    PHP Framework principal da aplicação.

* **Blade**  
    Engine de templates usada para renderização das views.

* **MariaDB**  
    Banco de dados relacional utilizado para persistência dos dados.

* **Bootstrap**  
    Framework de UI para responsividade e estilos.

* **Composer**, **Artisan**, **Laravel Mix**, entre outros  
    Ferramentas auxiliares para gerenciamento de pacotes, comandos CLI e assets.

## 📚 Propósito do Projeto

Este projeto é puramente educacional. O objetivo principal foi estudar e aplicar os conceitos oferecidos pelo Laravel em um contexto realista de uma aplicação com múltiplos tipos de usuários e funcionalidades robustas. Ao longo do desenvolvimento, explorei:

* Boas práticas de arquitetura Laravel
* Tratamento e escalabilidade de eventos
* Sistema de permissões e autenticação
* Notificações assíncronas
* Integração com Blade e rotas seguras

## 🧪 Como Rodar Localmente
```bash
# Clone o repositório
git clone https://github.com/seu-usuario/event-hub.git
cd event-hub

# Instale as dependências
composer install

# Configure o .env
cp .env.example .env
php artisan key:generate

# Configure o banco de dados MariaDB no .env
# E rode as migrations
php artisan migrate

# Rode o servidor de desenvolvimento
php artisan serve
```

## 🤝 Contribuição
Este é um projeto pessoal e educacional, mas se quiser sugerir algo, sinta-se à vontade para abrir uma issue ou pull request!

## 📝 Licença
Este projeto está sob a licença MIT.
