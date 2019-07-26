# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Carrega bibliotecas e vari�veis do bot�o do painel do sistema
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian vers�o 2.8.2
Data: 13/05/2019

"""

print ("= = = In�cio Painel_Botao.py = = =")
# Carrega m�dulos do painel do sistema
import Painel
# /Carrega m�dulos do painel do sistema

# Configura��o do Rasbian/Raspberry Pi
import time
import os
import RPi.GPIO as GPIO
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
# /Configura��o do Rasbian/Raspberry Pi

# Configura��o e declara��o do bot�o
from gpiozero import Button
botao = Button(14, hold_time=1.0, hold_repeat=True)
# /Configura��o e declara��o do bot�o

# Fun��es do bot�o para reiniciar/desligar o Raspberry Pi
botao_pressionado_por=0.0
def botao_liberado():
	global botao_pressionado_por
	if (botao_pressionado_por > 10.0):
                Painel.NotificaOK()
                Painel.Mensagem("Central de monit"," ser� desligada ", 20)
                Painel.led1.off()
                Painel.NotificaErro()
                Painel.NotificaErro()
                Painel.Mensagem("                ","                ")
                os.system("sudo shutdown -h now")  
	elif (botao_pressionado_por > 2.0):
                Painel.NotificaOK()
                Painel.Mensagem("Central de monit","ser� reiniciada ", 20)
                Painel.NotificaErro()
                Painel.led1.off()
                Painel.Mensagem("  Reiniciando   ","central de monit")
                os.system("sudo shutdown -r now")
	else:
                botao_pressionado_por = 0.0

def botao_pressionado():
	global botao_pressionado_por
	botao_pressionado_por = max(botao_pressionado_por, botao.held_time + botao.hold_time)
# /Fun��es do bot�o para reiniciar/desligar o Raspberry Pi

# Execu��o das fun��es do bot�o
botao.when_held = botao_pressionado
botao.when_released = botao_liberado
# /Execu��o das fun��es do bot�o

print ("= = = Fim Painel_Botao.py = = =")
