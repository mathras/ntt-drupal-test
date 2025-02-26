# Sistema de Votacão Personalizado para Drupal

## Instalação

### Pré-requisitos:
- Instale o Lando, uma ferramenta de desenvolvimento local que facilita a configuração e execução do ambiente.
- Certifique-se de ter o Drupal 9 ou superior instalado.

### Instalação do Repositório:
```sh
git clone https://seurepositorio.com/voting-system.git
cd voting-system
```

### Iniciar o Lando:
```sh
lando start
```
Isso iniciará o ambiente de desenvolvimento local, configurando o Drupal, o banco de dados e outros serviços necessários.

#### Banco de Dados:
O banco de dados de exemplo já está incluído na raiz do projeto como `bd.sql`. Após iniciar o Lando, o banco de dados pode ser acessado localmente através do `http://drupal-votacao.lndo.site/` (ou a URL do banco configurada pelo Lando).

> **Nota:** Em ambiente de produção, o banco de dados deve ser configurado de maneira diferente.

## Funcionalidades

### Módulo Customizado: Voting System
O módulo customizado **Voting System** já está habilitado e disponível após a instalação. Ele proporciona duas funcionalidades principais através de menus:

#### Menu de Votação:
- Criar novas perguntas, editar perguntas existentes, visualizar a contagem de votos para cada pergunta e compartilhar links de votação.
- Visualizar os detalhes de cada pergunta, suas respostas e a quantidade de votos recebidos.

#### Menu de Configuração de Votação:
- Permite desabilitar o sistema de votação globalmente, caso necessário, sem excluir dados ou desinstalar o módulo.

### Como Criar e Gerenciar Perguntas e Respostas:
- **Criar uma Pergunta:** Navegue até o menu de "Votação", onde você pode adicionar novas perguntas.
- **Editar uma Pergunta:** As perguntas podem ser editadas diretamente no menu de "Votação".
- **Visualizar Contagem de Votos:** A contagem de votos para cada pergunta pode ser visualizada diretamente no sistema.
- **Compartilhar Links de Votação:** Após criar uma pergunta, você pode gerar e compartilhar links de votação com usuários autenticados.

## API RESTful
A API permite interagir com o sistema de votação programaticamente, usando autenticação básica (Basic Authentication).

### Autenticação
A API requer autenticação básica. Para testes, use as credenciais abaixo:

- **Admin:** Usuário: `admin` / Senha: `Admin@123456`
- **Usuário convencional:** Usuário: `usuario` / Senha: `Usuario@123456`

Para autenticação via cURL:
```sh
curl -X GET "http://drupal-votacao.lndo.site/api/voting/questions" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2"
```

### Endpoints da API

#### Perguntas
- **Listar todas as perguntas:**
  ```sh
  curl -X GET "http://drupal-votacao.lndo.site/api/voting/questions" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2"
  ```
- **Detalhes de uma pergunta específica:**
  ```sh
  curl -X GET "http://drupal-votacao.lndo.site/api/voting/questions/{question_id}" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2"
  ```
- **Criar uma nova pergunta:**
  ```sh
  curl -X POST "http://drupal-votacao.lndo.site/api/voting/questions" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2" -d '{"label": "Qual é sua cor favorita?"}'
  ```

#### Respostas
- **Listar todas as respostas de uma pergunta:**
  ```sh
  curl -X GET "http://drupal-votacao.lndo.site/api/voting/answers/list/{question_id}" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2"
  ```
- **Detalhes de uma resposta específica:**
  ```sh
  curl -X GET "http://drupal-votacao.lndo.site/api/voting/answers/{answer_id}" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2"
  ```
- **Criar uma nova resposta:**
  ```sh
  curl -X POST "http://drupal-votacao.lndo.site/api/voting/answers" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2" -d '{"label": "Azul", "question_id": 1}'
  ```

#### Votos
- **Criar um novo voto:**
  ```sh
  curl -X POST "http://drupal-votacao.lndo.site/api/voting/votes" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2" -d '{"user_id": 1, "question_id": 1, "answer_id": 1}'
  ```

## Como Usar o Sistema
- **Acesse o Menu de Votação:** Crie, edite e visualize perguntas e respostas.
- **Compartilhe os Links de Votação:** Permita que os usuários votem em suas perguntas.
- **Use a API para Interagir Programaticamente:** Crie, edite e recupere perguntas, respostas e votos utilizando a API RESTful.

