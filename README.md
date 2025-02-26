<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Votação Personalizado para Drupal</title>
</head>
<body>
    <header>
        <h1>Sistema de Votação Personalizado para Drupal</h1>
    </header>

    <section>
        <h2>Instalação</h2>
        <p><strong>Pré-requisitos:</strong></p>
        <ul>
            <li>Instale o Lando, uma ferramenta de desenvolvimento local, que facilita a configuração e execução do ambiente.</li>
            <li>Certifique-se de ter o Drupal 9 ou superior instalado.</li>
        </ul>

        <p><strong>Instalação do Repositório:</strong></p>
        <pre><code>git clone https://seurepositorio.com/voting-system.git
cd voting-system</code></pre>

        <p><strong>Iniciar o Lando:</strong></p>
        <pre><code>lando start</code></pre>
        <p>Isso iniciará o ambiente de desenvolvimento local, configurando o Drupal, o banco de dados e outros serviços necessários.</p>

        <h3>Banco de Dados:</h3>
        <p>O banco de dados de exemplo já está incluído na raiz do projeto como <code>bd.sql</code>. Após iniciar o Lando, o banco de dados pode ser acessado localmente através do <code>http://drupal-votacao.lndo.site/</code> (ou a URL do banco configurada pelo Lando).</p>
        <p><strong>Nota:</strong> Em ambiente de produção, o banco de dados deve ser configurado de maneira diferente.</p>
    </section>

    <section>
        <h2>Funcionalidades</h2>
        <h3>Módulo Customizado: Voting System</h3>
        <p>O módulo customizado Voting System já está habilitado e disponível após a instalação. Ele proporciona duas funcionalidades principais através de menus:</p>
        
        <h4>Menu de Votação:</h4>
        <ul>
            <li>Criar novas perguntas, editar perguntas existentes, visualizar a contagem de votos para cada pergunta e compartilhar links de votação.</li>
            <li>Visualizar os detalhes de cada pergunta, suas respostas e a quantidade de votos recebidos.</li>
        </ul>

        <h4>Menu de Configuração de Votação:</h4>
        <ul>
            <li>Permite desabilitar o sistema de votação globalmente, caso necessário, sem excluir dados ou desinstalar o módulo.</li>
        </ul>

        <h3>Como Criar e Gerenciar Perguntas e Respostas:</h3>
        <ul>
            <li><strong>Criar uma Pergunta:</strong> Navegue até o menu de "Votação", onde você pode adicionar novas perguntas.</li>
            <li><strong>Editar uma Pergunta:</strong> As perguntas podem ser editadas diretamente no menu de "Votação".</li>
            <li><strong>Visualizar Contagem de Votos:</strong> A contagem de votos para cada pergunta pode ser visualizada diretamente no sistema.</li>
            <li><strong>Compartilhar Links de Votação:</strong> Após criar uma pergunta, você pode gerar e compartilhar links de votação com usuários autenticados.</li>
        </ul>
    </section>

    <section>
        <h2>API RESTful</h2>
        <p>A API permite interagir com o sistema de votação programaticamente, usando autenticação básica (Basic Authentication).</p>

        <h3>Autenticação</h3>
        <p>A API requer autenticação básica. Para testes, use as credenciais abaixo:</p>
        <ul>
            <li><strong>Admin:</strong> Usuário: admin / Senha: Admin@123456</li>
            <li><strong>Usuário convencional:</strong> Usuário: usuario / Senha: Usuario@123456</li>
        </ul>
        <p>Para autenticação via cURL, utilize o seguinte formato de header:</p>
        <pre><code>curl -X GET "http://drupal-votacao.lndo.site/api/voting/questions" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2"</code></pre>

        <h3>Endpoints da API</h3>
        <h4>Perguntas</h4>
        <p><strong>Listar todas as perguntas:</strong> Método: GET - Rota: /api/voting/questions</p>
        <pre><code>curl -X GET "http://drupal-votacao.lndo.site/api/voting/questions" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2"</code></pre>
        <p>Resposta: Retorna uma lista de todas as perguntas.</p>

        <p><strong>Detalhes de uma pergunta específica:</strong> Método: GET - Rota: /api/voting/questions/{question_id}</p>
        <pre><code>curl -X GET "http://drupal-votacao.lndo.site/api/voting/questions/1" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2"</code></pre>
        <p>Resposta: Retorna os detalhes da pergunta especificada.</p>

        <p><strong>Criar uma nova pergunta:</strong> Método: POST - Rota: /api/voting/questions</p>
        <pre><code>curl -X POST "http://drupal-votacao.lndo.site/api/voting/questions" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2" -d '{"label": "Qual é sua cor favorita?"}'</code></pre>
        <p>Resposta: Retorna os dados da nova pergunta criada.</p>

        <h4>Respostas</h4>
        <p><strong>Listar todas as respostas de uma pergunta:</strong> Método: GET - Rota: /api/voting/answers/list/{question_id}</p>
        <pre><code>curl -X GET "http://drupal-votacao.lndo.site/api/voting/answers/list/1" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2"</code></pre>
        <p>Resposta: Retorna uma lista de respostas associadas à pergunta.</p>

        <p><strong>Detalhes de uma resposta específica:</strong> Método: GET - Rota: /api/voting/answers/{answer_id}</p>
        <pre><code>curl -X GET "http://drupal-votacao.lndo.site/api/voting/answers/1" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2"</code></pre>
        <p>Resposta: Retorna os detalhes da resposta especificada.</p>

        <p><strong>Criar uma nova resposta:</strong> Método: POST - Rota: /api/voting/answers</p>
        <pre><code>curl -X POST "http://drupal-votacao.lndo.site/api/voting/answers" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2" -d '{"label": "Azul", "question_id": 1}'</code></pre>
        <p>Resposta: Retorna os dados da nova resposta criada.</p>

        <h4>Votos</h4>
        <p><strong>Criar um novo voto:</strong> Método: POST - Rota: /api/voting/votes</p>
        <pre><code>curl -X POST "http://drupal-votacao.lndo.site/api/voting/votes" -H "Authorization: Basic YWRtaW46QWRtaW5AMTIzNDU2" -d '{"user_id": 1, "question_id": 1, "answer_id": 1}'</code></pre>
        <p>Resposta: Retorna os dados do voto criado.</p>
    </section>

    <section>
        <h2>Como Usar o Sistema</h2>
        <ul>
            <li><strong>Acesse o Menu de Votação:</strong> Crie, edite e visualize perguntas e respostas.</li>
            <li><strong>Compartilhe os Links de Votação:</strong> Permita que os usuários votem em suas perguntas.</li>
            <li><strong>Use a API para Interagir Programaticamente:</strong> Crie, edite e recupere perguntas, respostas e votos utilizando a API RESTful.</li>
        </ul>
    </section>
</body>
</html>
