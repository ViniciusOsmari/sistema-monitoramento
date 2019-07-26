# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Conexão ao banco de dados do servidor para o Raspberry Pi
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian versão 2.8.2
Data: 22/04/2019

"""

print ("= = = Início ConexaoBD_Servidor.py = = =")

# Configuração do MySQL
import pymysql
from pymysql import Error
bd_server = None
# /Configuração do MySQL

# Informações de conexão
_HOST     = 'nome/ip-do-servidor'
_USER     = 'nome-do-usuario'
_PASSWORD = 'senha-do-usuario'
_PORT     = 3306
_DB       = 'nome-do-banco-de-dados'
# /Informações de conexão GEPOC


# Função para conexão do Raspberry Pi ao banco de dados do servidor
def ConectarBDServidor():
    global bd_server
    try:
            if(bd_server is None):
                    bd_server=pymysql.connect(host=_HOST, port=_PORT, db=_DB, user=_USER, passwd=_PASSWORD)
            elif(not bd_server.open):
                    bd_server=pymysql.connect(host=_HOST, port=_PORT, db=_DB, user=_USER, passwd=_PASSWORD)
    except Error as e:
            print("ERRO: Não foi possível conectar ao banco de dados no servidor.\n",e)
    return bd_server    
# /Função para conexão do Raspberry Pi ao banco de dados do servidor

print ("= = = Fim ConexaoBD_Servidor.py = = =")
