# Projeto Symfony + Vue (Vite) + MySQL + Nginx

Este projeto combina um backend em Symfony 7.3 com um frontend em Vue.js (Vite), orquestrados via Docker Compose.  

O objetivo do projeto é fornecer um ambiente de desenvolvimento e produção consistente, com containers para cada serviço, simplificando o deploy e a manutenção.

---

## Tecnologias

- **Backend:** Symfony 7.3, PHP 8.2
- **Frontend:** Vue 3 + Vite, Node 20
- **Banco de Dados:** MySQL 8.0
- **Servidor Web:** Nginx
- **Orquestração:** Docker Compose
- **Build e Dependências:** Composer, npm

---

## Dockerização

O projeto foi dockerizado em **3 containers principais**:

1. **Backend (Symfony)**  
   - Dependências instaladas via Composer
   - Build otimizado com `--no-dev --optimize-autoloader`

2. **Frontend (Vue + Vite)**  
   - Node 20-slim
   - Build do frontend realizado dentro do container (`npm install` + `npm run build`)
   - O conteúdo final do `dist/` é copiado para o Nginx

3. **Nginx**  
   - Serve o frontend (`/usr/share/nginx/html`)
   - Faz proxy para o backend em `/backend/`  
   - Configuração via `nginx.conf` com `proxy_pass http://backend:8000;`

4. **MySQL**  
   - Usuário e banco configurados via variáveis de ambiente
   - Volume persistente para dados

---

## Configuração do Frontend/Backend

- **Backend:**  
  - Variáveis de ambiente definidas no `docker-compose.yaml`
  - `APP_ENV` e `APP_DEBUG` controlam o ambiente Symfony
  - Dockerfile garante permissões corretas (`www-data`)

- **Frontend:**  
  - Build feito dentro do container para evitar inconsistências de dependências
  - Vite está configurado para acessar o backend via `/backend/`
  - Exemplo de chamada API no frontend: `fetch('/backend/api/user/v1')`

- **Nginx:**  
  - `/backend/` é redirecionado para o container do backend

---

## Decisões Arquiteturais de Infraestrutura

1. **Separação de containers por serviço**: permite escalabilidade independente e isolação de problemas.  
2. **Frontend build no Docker**: evita problemas de permissões, inconsistências de Node/npm e garante que a build seja idêntica em todos os ambientes.  
3. **Proxy via Nginx**: simplifica a comunicação frontend-backend sem expor portas adicionais.

---

### Decisões Arquiteturais — Backend

O backend foi estruturado seguindo **boas práticas de design em camadas**, buscando clareza, manutenção facilitada e isolamento de responsabilidades:

1. **Exception Handlers Globais**  
   - Todos os erros e exceções da aplicação são tratados centralizadamente.  
   - Isso garante que respostas HTTP consistentes sejam retornadas ao frontend e facilita logging e monitoramento.

2. **Camada Controller**  
   - Responsável por receber requisições HTTP e devolver respostas.  
   - Contém o **versionamento de API**, permitindo diferentes versões de endpoints coexistirem (ex.: `/api/v1/user`, `/api/v2/user`).  
   - Não contém lógica de negócio; apenas valida dados de entrada, gerencia versão da rota e delega para a camada de Service.  
   - Ex.: `UserController` trata rotas como `/api/v1/user`.  
   - **Autenticação:** feita via sessão e cookies, mantendo o backend **stateful**.

3. **Camada DTO (Data Transfer Object)**  
   - Objetos de transferência de dados entre controller e service.  
   - Garantem que apenas os campos necessários sejam enviados ou recebidos, mantendo encapsulamento e consistência.  
   - Ex.: `UserDTO` para criar usuários.

4. **Camada Service**  
   - Contém a **lógica de negócio** da aplicação.  
   - Recebe DTOs, valida regras de negócio e coordena chamadas aos Repositories e outras dependências.  
   - Isola a lógica do controller.
   - **Criptografia de senhas:** todas as senhas de usuários são criptografadas utilizando o padrão **bcrypt**, garantindo segurança e consistência.

5. **Camada Repository (Doctrine)**  
   - Interface entre as entidades e o banco de dados.  
   - Responsável por consultas complexas e persistência de dados, sem expor detalhes da infraestrutura para o Service.  
   - Ex.: `UserRepository` gerencia CRUD e consultas específicas de usuários.

6. **Camada Entity (Doctrine)**  
   - Representa os **modelos de dados** da aplicação.  
   - Cada entidade reflete uma tabela no banco de dados.  
   - Ex.: `User` com atributos como `id`, `name`, `email`.

7. **Value Objects (VOs)**  
   - Objetos imutáveis que representam **conceitos de domínio** dentro da aplicação.  
   - Ex.: `EmailVO` encapsula validação e formatação de emails, garantindo que cada usuário tenha um email válido e consistente, evitando lógica repetida na camada de Service ou Entity.

8. **CORS (Nelmio CORS Bundle)**  
   - Configurado globalmente para permitir que o frontend Vue (Vite) faça requisições HTTP ao backend sem problemas de política de origem cruzada.  
   - A configuração define quais domínios, métodos e headers são permitidos.

---

### Decisões Arquiteturais — Frontend

O frontend foi estruturado para promover **reutilização, manutenção e consistência na comunicação com o backend**:

1. **Handler HTTP Global**  
   - Todas as requisições HTTP para o backend passam por um **handler central**, evitando duplicidade de código.  
   - Esse handler gerencia headers, autenticação, tratamento de erros e contexto da integração com o backend.

2. **Redirecionamento de Rotas Não Permitidas**  
   - Rotas protegidas redirecionam automaticamente usuários não autorizados para páginas públicas ou de login.  
   - Mensagens de alerta, como `"Você precisa estar logado para acessar essa página."`, são enviadas via query params para exibição ao usuário.

3. **Criação de Componentes Reutilizáveis**  
   - Componentes são estruturados de forma modular para permitir reaproveitamento em diferentes páginas e funcionalidades.  
   - Facilita manutenção e padronização da interface.

4. **Componente Toast para Alertas e Erros**  
   - Implementado para exibir mensagens de alerta e erro de forma consistente.  
   - Utiliza **estado interno** para controlar a exibição, duração e tipo da mensagem, garantindo feedback visual imediato ao usuário.

5. **Armazenamento de Sessão do Usuário**  
   - Informações da sessão são armazenadas em **localStorage**.  
   - Permite persistência do estado do usuário mesmo após recarregamento da página, mantendo a experiência de login stateful junto ao backend.

---

## Comandos Úteis

- **Subir containers**
```bash
docker compose -f docker-compose.prod.yaml up --build
```
