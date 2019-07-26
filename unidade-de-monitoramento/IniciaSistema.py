# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Inicializa��o do sistema
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian vers�o 2.8.2
Data: 25/04/2019

"""

print ("= = = In�cio IniciaSistema.py = = =")

# Carrega bibliotecas do Rasbian/Raspberry Pi
import datetime
import threading
# /Carrega bibliotecas do Rasbian/Raspberry Pi

# Carrega m�dulos do painel do sistema
import Painel
import Painel_Botao
# /Carrega m�dulos do painel do sistema

# Sinaliza��o do carregamento do sistema
Painel.NotificaOK()
Painel.Mensagem("   Carregando   ","    Sistema     ", 2)
# /Sinaliza��o do carregamento do sistema

# Carrega e executa a importa��o das tabelas do servidor para o Rpi
import ImportarTabelas
ImportarTabelas.ImportarUsuarios()
ImportarTabelas.ImportarEquipamentos()
ImportarTabelas.ImportarConfig()
# /Carrega e executa a importa��o das tabelas do servidor para o Rpi

# Carrega m�dulos de leitura no banco de dados do Rpi
from LerTabelas import LerConfig
# /Carrega m�dulos de leitura no banco de dados do Rpi

# Carrega configura��es do banco de dados do Rpi
sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 = LerConfig()
erro = 0 # N�mero de erros
while sala_id == -2 or sala_id == -1 or sala_id == 0:
        # Verifica��o de erros (1, 2 e 11) ao carregar sistema
        erro += 1
        Painel.NotificaErro()
        Painel.led3.on()
        if sala_id == -2:
                # Erro de comunica��o com o banco de dado local
                Painel.Mensagem("Erro ao carregar","sistema (erro11)", 20)
                Painel.Mensagem(" Nova tentativa "," em 10 minutos  ", 600)
        elif sala_id == -1:
                # Erro de falha na conex�o com a internet
                Painel.Mensagem("Erro ao carregar","sistema (erro1) ", 20)
                Painel.Mensagem(" Nova tentativa ","  em 5 minutos  ", 300)
        elif sala_id == 0:
                # Erro de falta de configura��o para o IP atual
                Painel.Mensagem("Erro ao carregar","sistema (erro2) ", 20)
                Painel.Mensagem(" Nova tentativa "," em 10 minutos  ", 600)
        Painel.NotificaErro()
        if erro >= 3:
                Painel.Mensagem("Central de monit","ser� reiniciada ", 10)
                os.system("sudo shutdown -r now")
        sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 = LerConfig()
# /Carrega configura��es do banco de dados do Rpi

# Carrega e executa a sincroniza��o das tabelas entro o Rpi e o servidor
import SincronizarTabelas
SincronizarTabelas.SincronizarSalaLog(sala_id)
SincronizarTabelas.SincronizarEquipamentoLog()
SincronizarTabelas.SincronizarAlarme()
# /Carrega e executa a sincroniza��o das tabelas entro o Rpi e o servidor

# Carrega fun��o para leitura de crach� de usu�rio e tag de equipamento
from LerRFID import lerRFID
# /Carrega fun��o para leitura de crach� de usu�rio e tag de equipamento

# Carrega fun��o para leitura dos sensores
from LerSensores import lerSensores
# /Carrega fun��o para leitura dos sensores

# Sinaliza��o de sistema ligado
Painel.NotificaOK()
Painel.Mensagem("Sistema iniciado",str(datetime.datetime.now().strftime(" %d/%m %H:%M:%S ")), 5)
Painel.led1.on()
# /Sinaliza��o de sistema ligado

# Loop de fun��es do sistema
if __name__ == "__main__":
        # as duas fun��es principais (lerRFID e lerSensores) s�o executadas simultaneamente
        processos = list() #ou processos = []
        processos.append(threading.Thread(target=lerRFID, args=(sala_id, rfid,)))
        processos.append(threading.Thread(target=lerSensores, args=(sala_id, dht, luminosidade, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17, 600,)))

        for processo in processos:
                processo.start()
# /Loop de fun��es do sistema

print ("= = = Fim IniciaSistema.py = = =")
