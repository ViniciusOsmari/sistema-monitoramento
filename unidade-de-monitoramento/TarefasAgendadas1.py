# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Rotina que � executada todos os dias �s 04:00
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian vers�o 2.8.2
Data: 17/05/2019

"""

# Carrega a biblioteca do sistema
import os

# Reinicia o sistema
os.system("sudo shutdown -r now")


"""
   Este c�digo � executado pela fun��o contrab do Raspbian
   Para editar o hor�rio siga os seguintes passos:
   - Abra o LXTerminal;
   - Execute o comando "sudo contrab -e"
   - Nas �ltimas linhas estar�o os comandos que ser�o executado, ele ser� da seguinte forma:
       1 2 3 4 5 comando
       onde:
       1 � o minuto (entre 0 e 59)
       2 � a hora (entre 0 e 23)
       3 dia (entre 1 e 31)
       4 m�s (entre 1 e 12)
       5 dia da semana (entre 0 e 6, come�ando no domingo)
       OBS.: Pode ser utilizado * no lugar de algum n�mero, para ser executado em todo o intervalo
   - Apertar <Ctrl + O> para salvar;
   - Apertar <Enter> para confirmar o nome do arquivo;
   - Apertar <Ctrl + X> para sair do editor

-> Exemplos de comando:
   "0 3 * * * /usr/bin/python3 /home/pi/codigo123.py"
   O script 'codigo123.py' ser� executado �s 03:00 todos os dias;

   "25 5 3 * * /usr/bin/python3 /home/pi/codigoABC.py"
   O script 'codigoABC.py' ser� executado �s 05:25 no dia 3 de todos os meses;
"""
