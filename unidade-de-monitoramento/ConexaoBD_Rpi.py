# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Conexão ao banco de dados do servidor para o Raspberry Pi
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian versão 2.8.2
Data: 22/04/2019

"""

print ("= = = Início ConexaoBD_Rpi.py = = =")

# Configuração do MySQL
import pymysql
from pymysql import Error
bd_rpi = None
# /Configuração do MySQL

# Informações de conexão
_HOST     = 'localhost'
_USER     = 'nome-do-usuario'
_PASSWORD = 'senha-do-usuario'
_PORT     = 3306
_DB       = 'nome-do-banco-de-dados'
# /Informações de conexão

# Função para conexão do Raspberry Pi ao banco de dados do servidor
def ConectarBDRpi():
# Conexão do Raspberry Pi ao banco de dados do Raspberry Pi
    global bd_rpi
    try:
            if(bd_rpi is None):
                    bd_rpi=pymysql.connect(host=_HOST, db=_DB, user=_USER, passwd=_PASSWORD)
            elif(not bd_rpi.open):
                    bd_rpi=pymysql.connect(host=_HOST, db=_DB, user=_USER, passwd=_PASSWORD)
    except Error as e:
            print("ERRO: Não foi possível conectar ao banco de dados no Raspberry Pi.\n",e)
    return bd_rpi
# /Conexão do Raspberry Pi ao banco de dados do Raspberry Pi

print ("= = = Fim ConexaoBD_Rpi.py = = =")
