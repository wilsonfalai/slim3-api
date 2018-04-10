# Comandos básicos do Vagrant

## Init
*  _vagrant init [box-name] [box-url]_
  * Cria o arquivo [Vagrantfile](http://docs.vagrantup.com/v2/vagrantfile/) com as configurações do box que você informou no [box-name], caso você não tenha o box ainda adicionado é obrigatório passar o [box-url] para que ele possa baixa-lo

## Up
* _vagrant up_
  * Cria e inicia a instancia após o comando _vagrant init_

## Reload
* _vagrant reload_
  * Reinicia a instancia do box ativo

## Suspend
* _vagrant suspend_
  * Stopa a instancia ativa, congelando seu estado atual

## Resume
* _vagrant resume_
  * Ativa a instancia suspensa, até então, pelo comando _vagrant suspend_

## Halt
* _vagrant halt_
  * Manda um comando para desligar a instancia ativa, finalizando todos os processos antes de finalizar

## Destroy
* _vagrant destroy_
  * Destroy a instancia ativa

## SSH
* _vagrant ssh_
  * Acessa a instancia ativa via ssh

## Status
* _vagrant status_
  * Informa o status atual da instancia

## Box
* _vagrant box add name url_
  * Adiciona um novo box a sua lista de box
* _vagrant box list_
  * Lista suas box para utilização
* _vagrant box remove name_
  * Remove um box da lista de box