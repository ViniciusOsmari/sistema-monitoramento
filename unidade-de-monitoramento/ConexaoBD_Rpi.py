# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Conex�o ao banco de dados do servidor para o Raspberry Pi
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian vers�o 2.8.2
Data: 22/04/2019

"""

print ("= = = In�cio ConexaoBD_Rpi.py = = =")

# Configura��o do MySQL
import pymysql
from pymysql import Error
bd_rpi = None
# /Configura��o do MySQL

# Informa��es de conex�o
_HOST     = 'localhost'
_USER     = 'nome-do-usuario'
_PASSWORD = 'senha-do-usuario'
_PORT     = 3306
_DB       = 'nome-do-banco-de-dados'
# /Informa��es de conex�o

# Fun��o para conex�o do Raspberry Pi ao banco de dados do servidor
def ConectarBDRpi():
# Conex�o do Raspberry Pi ao banco de dados do Raspberry Pi
    global bd_rpi
    try:
            if(bd_rpi is None):
                    bd_rpi=pymysql.connect(host=_HOST, db=_DB, user=_USER, passwd=_PASSWORD)
            elif(not bd_rpi.open):
                    bd_rpi=pymysql.connect(host=_HOST, db=_DB, user=_USER, passwd=_PASSWORD)
    except Error as e:
            print("ERRO: N�o foi poss�vel conectar ao banco de dados no Raspberry Pi.\n",e)
    return bd_rpi
# /Conex�o do Raspberry Pi ao banco de dados do Raspberry Pi

print ("= = = Fim ConexaoBD_Rpi.py = = =")
