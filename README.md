# Desafio Monks

## Autor: Pedro Henrique Sousa Pires

Olá, eu me chamo Pedro Henrique, sou desenvolvedor full-stack com mais de 2 anos de experiência. Esse projeto foi desenvolvido como parte do processo seletivo da empresa Monks, muito bem conceituada no mercado de Marketing Digital. Espero que gostem e segue uma documentação básica do projeto.

## Configuração

1. O projeto utiliza wordpress para servir algumas RestAPIs para o front feito em React. Edite o nome do arquivo `docker-compose.yml.example` para `docker-compose.yml` e altere a senha desejada para o banco de dados MySQL.

2. Após isso, basta executar o comando abaixo para subir as imagens necessárias.

```
docker-compose up -d
```

Isso criará todo o ambiente de desenvolvimento.

3. Configurações adicionais do MySQL.

Caso não consiga logar no MySQL com a senha especificada no docker-compose.yml, será necessário alterá-la manualmente.
Primeiro execute o comando para logar como root sem senha:

```
docker exec -it wordpress_db mysql -u root
```

Em seguida altere a senha do usuário root:

```
ALTER USER 'root'@'%' IDENTIFIED BY 'rootpassword';
FLUSH PRIVILEGES;
EXIT;
```

4. Para rodar o front é bem simples, instale as dependências necessárias com:

```
npm i
```

5. Por fim, rode o comando para iniciar o servidor:

```
npm run dev
```

Pronto! O projeto já está executando.

<p>
  <img src="src\assets\images\image1.png" alt="Logo do Laravel" width="50%">
  <img src="src\assets\images\image3.png" alt="Logo do Laravel" width="49%">
</p>

![](src\assets\images\image2.png)

## RestAPI

Um dos requisitos é usar o wordpress para servir algumas APIs para o front em React. Foram criados três `custom_post_type` para as seguintes finalidades:

1. `sections`: como o site é formado por sections em sequencia, resolvi criar um `custom_post_type` para armazenar essas sections, que possuem `title`, `content`, `slug` (para identificar o tipo de section), `order` (ordem que deve aparecer no site) e alguns campos de imagens e outros itens particulares de algumas `sections`.
2. `cards`: os cards seguem o mesmo princípio das `sections`. Basicamente armazenam um `title`, `content`, `image` e um possível botão. Além disso, possuem um aninhamento com as `sections`, para que possam ser exibidas no front, dentro das mesmas.
3. `contact`: esse `custom_post_type` é específico para o submit do formulário na última section do site. É possível criar um novo `contact` pelo site e visualizar na tela de admin do wordpress.

## URLs

1. `http://[host]/wp-json/wp/v2/sections`
2. `http://[host]/wp-json/wp/v2/cards`
3. `http://[host]/wp-json/wp/v2/contact`
