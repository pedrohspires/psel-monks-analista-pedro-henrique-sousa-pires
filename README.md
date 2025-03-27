# Desafio Monks

## Configuração

1. Configurações adicionais do MySQL.

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
