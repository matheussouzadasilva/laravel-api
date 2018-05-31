# laravel-api
RestFull Api em Laravel 5.6

# instalação
- Criar uma pasta chamada "Models" na pasta "app" 
- Criar uma pasta chamada "API" e depois dentro da pasta "API" uma chamada "V1" na pasta "app/Http/Controllers"
- Exemplo de como criar um controller , executar o comando "php artisan make:controller API\\V1\\CategoriaController --resource"
- Quando for criar uma API RESTful com rotas do tipo resource excluir as funções "create e edit" pois as mesmas não serão utilizadas
- Exemplo de como criar uma model junto com uma migration executar o comando "php artisan make:model  Models\\Categoria -m"
- Para criar uma model sem migration executar o comando "php artisan make:model  Models\\Divisao"
- Para criar uma migration com o nome da tabela no singular executar o comando "php artisan make:migration create_divisao_table --create=divisao"
- Exemplo de como criar uma seeder da tabela users, executar o comando "php artisan make:seeder UsersTableSeeder"
- Exemplo de como criar uma seeder da tabela categorias, executar o comando "php artisan make:seeder CategoriasTableSeeder"
- Exemplo de como criar uma seeder da tabela divisao, executar o comando "php artisan make:seeder DivisaoTableSeeder"
- Para criar a chave da aplicação executar o comando "php artisan key:generate"
- Para criar um token(jwt - json web token) para a validação da api executar o comando "php artisan jwt:generate"
- Criar banco de dados com nome qualquer com a codificação "utf8mb4_unicode_ci" e fazer todas as configurações necessárias no arquivo .env
- Para criar as tabelas executar o comando "php artisan migrate" no diretório principal da aplicação
- Caso precisar criar de novo as tabelas e preencher com dados , executar o comando "php artisan migrate:refresh --seed" no diretório principal da aplicação
- Para para inserir um usuário, produtos, categorias nas tabelas executar o comando  "php artisan db:seed"  no diretório principal da aplicação
