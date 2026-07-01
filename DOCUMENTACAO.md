# AppAuto — Sistema de Gestão Automotiva (SaaS)

## Visão Geral

O **AppAuto** é um SaaS (Software as a Service) voltado para o setor automotivo, desenvolvido para atender tanto Pessoas Físicas (PF) quanto Pessoas Jurídicas (PJ). O sistema permite a gestão de veículos, clientes e serviços, com foco em múltiplas contas e acessos. Esta versão foi construída sobre uma base PHP MVC pura, sem frameworks externos, garantindo alta performance e facilidade de hospedagem em servidores compartilhados, como a Hostgator.

## Arquitetura e Tecnologias

A arquitetura do projeto foi desenhada para ser leve e eficiente. O backend utiliza PHP 8.x, sendo retrocompatível com PHP 7.4+, seguindo o padrão de projeto MVC (Model-View-Controller) com Autoload PSR-4. O banco de dados escolhido é o MySQL 5.7 / MariaDB, ideal para a infraestrutura da Hostgator. No frontend, foram empregados HTML5, CSS3, JavaScript Vanilla e FontAwesome, sem dependência de bibliotecas pesadas. A segurança é garantida através de sessões nativas do PHP, proteção contra CSRF (Cross-Site Request Forgery) e senhas criptografadas.

## Estrutura de Banco de Dados

O banco de dados foi modelado para suportar múltiplos tenants (inquilinos), separando de forma lógica os dados de pessoas físicas e jurídicas. O schema completo, que pode ser encontrado no arquivo `database/schema.sql`, foi estruturado para garantir a integridade relacional.

| Tabela | Descrição Principal |
| :--- | :--- |
| **usuarios** | Armazena todos os usuários do sistema, incluindo perfis PF, PJ e Administradores. |
| **negocios** | Guarda os dados específicos de empresas vinculadas aos usuários PJ. |
| **ramos_atividade** | Contém uma lista pré-cadastrada de 25 ramos de negócios automotivos (ex: Oficina, Lava Jato). |
| **veiculos** | Cadastro completo de veículos, suportando dados extraídos via OCR e consultas de placa. |
| **veiculo_fotos** | Gerencia as imagens vinculadas a cada veículo cadastrado. |
| **consultas_placa** | Mantém o histórico e auditoria das consultas realizadas nas APIs externas gratuitas. |

## Funcionalidades Implementadas

A plataforma conta com um sistema de autenticação robusto. O login é unificado, permitindo que administradores, pessoas físicas e negócios acessem a plataforma pela mesma interface. O cadastro rápido foi desenhado para facilitar a entrada de novos usuários, exigindo a seleção do tipo de conta (Pessoal ou Negócio) logo no início. Para garantir a veracidade dos dados, o sistema envia um token de 6 dígitos por e-mail, que deve ser validado antes do primeiro acesso.

O painel administrativo, acessível pelas credenciais padrão (`admin@appauto.com.br` / `Admin259087@`), oferece uma visão gerencial completa. O dashboard apresenta estatísticas em tempo real, enquanto a gestão de clientes separa visualmente as pessoas físicas das jurídicas. Uma funcionalidade de destaque é a "Impersonação" (Acessar Como), que permite ao administrador navegar no sistema exatamente como um usuário ou negócio específico, facilitando imensamente o suporte técnico sem a necessidade de solicitar senhas.

O módulo de veículos é o coração do sistema para o usuário final. Ele integra consultas gratuitas de placa através da BrasilAPI e Parallelum, preenchendo automaticamente os dados do veículo. O sistema é inteligente o suficiente para detectar se a placa inserida pertence ao formato Mercosul ou ao formato antigo. Além disso, foi implementada a funcionalidade de OCR (Reconhecimento Óptico de Caracteres), que permite ao usuário enviar uma foto do documento do veículo (CRLV) para que o sistema extraia e preencha dados como Placa, RENAVAM, Chassi, Ano e Cor de forma automática.

## Instruções de Instalação e Configuração

Para instalar o AppAuto em um ambiente de hospedagem como a Hostgator, o processo é direto. Primeiramente, todos os arquivos da pasta `appauto` devem ser enviados para a raiz do domínio (geralmente `public_html`). É crucial garantir que o diretório `public/assets/uploads` tenha permissões de escrita (CHMOD 755 ou 777) para permitir o upload de fotos de veículos e documentos.

Em seguida, um banco de dados MySQL deve ser criado através do painel de controle da hospedagem. O arquivo `database/schema.sql` deve ser importado via phpMyAdmin para construir toda a estrutura de tabelas necessária. Por fim, o arquivo `config/database.php` precisa ser editado com as credenciais do banco de dados recém-criado. Embora o sistema utilize a função `mail()` nativa do PHP para o envio de tokens, recomenda-se a configuração de um servidor SMTP no arquivo `AuthController.php` para ambientes de produção.

## Próximos Passos

As futuras atualizações do AppAuto devem focar na expansão das ferramentas de gestão. A integração de um módulo financeiro completo permitirá que os negócios controlem seu fluxo de caixa diretamente pela plataforma. A criação de um sistema de agendamento de serviços conectará diretamente os clientes PF às oficinas e lava-jatos PJ cadastrados. Além disso, a estruturação de endpoints REST/JSON preparará o terreno para o lançamento do aplicativo mobile nativo.
ivo.
