# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Carrega bibliotecas e variáveis do botão do painel do sistema
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian versão 2.8.2
Data: 13/05/2019

"""

print ("= = = Início Painel_Botao.py = = =")
# Carrega módulos do painel do sistema
import Painel
# /Carrega módulos do painel do sistema

# Configuração do Rasbian/Raspberry Pi
import time
import os
import RPi.GPIO as GPIO
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
# /Configuração do Rasbian/Raspberry Pi

# Configuração e declaração do botão
from gpiozero import Button
botao = Button(14, hold_time=1.0, hold_repeat=True)
# /Configuração e declaração do botão

# Funções do botão para reiniciar/desligar o Raspberry Pi
botao_pressionado_por=0.0
def botao_liberado():
	global botao_pressionado_por
	if (botao_pressionado_por > 10.0):
                Painel.NotificaOK()
                Painel.Mensagem("Central de monit"," será desligada ", 20)
                Painel.led1.off()
                Painel.NotificaErro()
                Painel.NotificaErro()
                Painel.Mensagem("                ","                ")
                os.system("sudo shutdown -h now")  
	elif (botao_pressionado_por > 2.0):
                Painel.NotificaOK()
                Painel.Mensagem("Central de monit","será reiniciada ", 20)
                Painel.NotificaErro()
                Painel.led1.off()
                Painel.Mensagem("  Reiniciando   ","central de monit")
                os.system("sudo shutdown -r now")
	else:
                botao_pressionado_por = 0.0

def botao_pressionado():
	global botao_pressionado_por
	botao_pressionado_por = max(botao_pressionado_por, botao.held_time + botao.hold_time)
# /Funções do botão para reiniciar/desligar o Raspberry Pi

# Execução das funções do botão
botao.when_held = botao_pressionado
botao.when_released = botao_liberado
# /Execução das funções do botão

print ("= = = Fim Painel_Botao.py = = =")
