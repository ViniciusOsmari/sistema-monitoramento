# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Carrega bibliotecas e variáveis do painel do sistema
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian versão 2.8.2
Data: 13/05/2019

"""

print ("= = = Início Painel.py = = =")

# Configuração do Rasbian/Raspberry Pi
import time
import RPi.GPIO as GPIO
from unicodedata import normalize
from signal import pause
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
# /Configuração do Rasbian/Raspberry Pi

# Configuração e declaração dos LEDs
from gpiozero import LED
led1 = LED(16)
led2 = LED(12)
led3 = LED(7)
# /Configuração e declaração dos LEDs

# Configuração e declaração do buzzer
from gpiozero import Buzzer
buzzer = Buzzer(24)
# /Configuração e declaração do buzzer

# Funções para notificação com LED e buzzer
def NotificaOK():
    led1.blink(on_time=0.25,off_time=0.1,n=2)
    buzzer.blink(on_time=0.25,off_time=0.1,n=2,background=False)

def NotificaLeitura():
    led2.blink(on_time=0.5,off_time=0.5,n=1)
    buzzer.blink(on_time=0.25,off_time=0.25,n=1,background=False)

def NotificaErro():
    led3.blink(on_time=0.5,off_time=0.1,n=2)
    buzzer.blink(on_time=0.5,off_time=0.1,n=2,background=False)
# /Funções para notificação com LED e buzzer

# Configuração e declaração do display LCD com módulo I2C
import I2C_LCD_driver
try:
    display = I2C_LCD_driver.lcd()
except:
    NotificaErro()
    display = 0
    print("Erro na configuração do display!")
# /Configuração e declaração do display LCD com módulo I2C

# Funções para mostrar mensagem no display LCD
def Mensagem(linha1 = "", linha2 = "", tempo = 0):
    # linha 1: Texto da primeira linha, default é ""
    # linha 2: Texto da segunda linha, default é ""
    # tempo: tempo que a mensagem aparece na tela, default é 0 segundos
    print("|",linha1,"| |",linha2,"|")

    # Remove os acentos para uma melhor visualização no display
    linha1d = normalize('NFKD',linha1).encode('ASCII','ignore').decode('ASCII')
    linha2d = normalize('NFKD',linha2).encode('ASCII','ignore').decode('ASCII')

    try:
        display.lcd_clear()
        display.lcd_display_string(linha1d, 1,0)
        display.lcd_display_string(linha2d, 2,0)
    except:
        print("Erro na comunicação com o display!")
    time.sleep(tempo)
# /Funções para mostrar mensagem no display LCD

print ("= = = Fim Painel.py = = =")
