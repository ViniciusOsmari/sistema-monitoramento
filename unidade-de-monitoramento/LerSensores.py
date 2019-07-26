# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Leitura dos sensores (internos e externos)
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian vers�o 2.8.2
Data: 02/05/2019

"""

print ("= = = In�cio LerSensores.py = = =")

# Configura��es das portas GPIO                                 # ver GPIOzero
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
# /Configura��es das portas GPIO

from threading import Timer

# Carrega m�dulos de escrita no banco de dados do Rpi
from SalvarTabelas import SalvarSalasLog, SalvarAlarme
# /Carrega m�dulos de escrita no banco de dados do Rpi

# Fun��o para ler a temperatura e umidade
def lerTemperaturaUmidade(dht, sala_id):
        import Adafruit_DHT
        dht_pino = 17 # GPIO espec�fico para o sensor de temperatura e umidade
        if dht == 10:
                # Nenhum sensor de temperatura e umidade
                return "N�o usado", "N�o usado"
                exit()
        elif dht == 11:
                # Sensor de temperatura e umidade DHT11
                dht_sensor = Adafruit_DHT.DHT11
        elif dht == 12:
                # Sensor de temperatura e umidade DHT22
                dht_sensor = Adafruit_DHT.DHT22
        else:
                # Caso seja passado um par�metro incorreto
                return "Erro de Config", "Erro de Config"
                exit()
        umidade, temperatura = Adafruit_DHT.read_retry(dht_sensor, dht_pino);
        if umidade is None or temperatura is None or umidade > 100:
                umidade = "Erro de leitura"
                temperatura = "Erro de leitura"
        elif temperatura >= 70:
                SalvarAlarme(sala_id, "Temperatura maior que 70�C")
        return umidade, temperatura
# /Fun��o para ler a temperatura e umidade

# Fun��o para ler a luminosidade
def lerLuminosidade(luminosidade, sala_id):
        if luminosidade == 6:
                # Nenhum sensor
                nivel_luminosidade = "N�o usado"
        elif luminosidade == 7:
                # Sensor de luminosidade BH1750
                import smbus
                DEVICE     = 0x23 # Endere�o I2C para o sensor de luminosidade
                POWER_DOWN = 0x00
                POWER_ON   = 0x01
                RESET      = 0x07
                CONTINUOUS_LOW_RES_MODE    = 0x13
                CONTINUOUS_HIGH_RES_MODE_1 = 0x10
                CONTINUOUS_HIGH_RES_MODE_2 = 0x11
                ONE_TIME_HIGH_RES_MODE_1   = 0x20
                ONE_TIME_HIGH_RES_MODE_2   = 0x21
                ONE_TIME_LOW_RES_MODE      = 0x23
                bus = smbus.SMBus(1)
                try:
                        leitura = bus.read_i2c_block_data(DEVICE,ONE_TIME_HIGH_RES_MODE_1)
                        nivel_luminosidade = ((leitura[1] + (256 * leitura[0])) / 1.2)
                except:
                        nivel_luminosidade = "Erro de leitura"
        else:
                # Caso seja passado um par�metro incorreto
                nivel_luminosidade = "Erro de Config"
        return nivel_luminosidade
# /Fun��o para ler a luminosidade

# Fun��o para ler as portas de expans�o
def lerPortaExpansao(porta, funcao, sala_id):
        if funcao == 1:
                # Nenhum sensor instalado
                status = "N�o usado"
        elif funcao == 2:
                # Sensor de abertura de janela (Magn�tico)
                GPIO.setup(porta, GPIO.IN, pull_up_down=GPIO.PUD_UP)
                if GPIO.input(porta) == False:
                        status = "Janela aberta"
                else:
                        status = "Janela fechada"
        elif funcao == 3:
                # Sensor de abertura de porta (Magn�tico)
                GPIO.setup(porta, GPIO.IN, pull_up_down=GPIO.PUD_UP) 
                if GPIO.input(porta) == False:
                        status = "Porta aberta"
                else:
                        status = "Porta fechada"
        elif funcao == 4:
                # Sensor de g�s e fuma�a (MQ-2)
                GPIO.setup(porta, GPIO.IN)
                if GPIO.input(porta) == False:
                        status = "Fuma�a detectada"
                        SalvarAlarme(sala_id, "Fuma�a detectada")
                else:
                        status = "Sem fuma�a"
        elif funcao == 5:
                # Sensor de movimenta��o (HC-SR501)
                GPIO.setup(porta, GPIO.IN)
                # GPIO.add_event_detect(pino_PIR, GPIO.RISING, callback=LIGHTS)
                if GPIO.input(porta) == True:
                        status = "Presen�a detectada"
                else:
                        status = "Sem movimenta��o"
        else:
                # Caso seja passado um par�metro incorreto
                status = "Erro de Config"
        return status
# /Fun��o para ler as portas de expans�o

# Fun��o para ler os sensores internos e as portas de expans�o
def lerSensores(sala_id, dht, luminosidade, funcao1, funcao2, funcao3, funcao4, funcao5, funcao6, funcao7, funcao8, funcao9, funcao10, funcao11, funcao12, funcao13, funcao14, funcao15, funcao16, funcao17, intervalo_leitura):
        Timer(intervalo_leitura , lerSensores, args=(sala_id, dht, luminosidade, funcao1, funcao2, funcao3, funcao4, funcao5, funcao6, funcao7, funcao8, funcao9, funcao10, funcao11, funcao12, funcao13, funcao14, funcao15, funcao16, funcao17,intervalo_leitura,)).start()

        umidade,temperatura=lerTemperaturaUmidade(dht, sala_id)      # Leitura de temperatura e umidade
        nivel_luminosidade = lerLuminosidade(luminosidade, sala_id)  # Leitura da luminosidade

        PortasExpansao = {
                #"porta": [GPIO,fun��o da porta]
                "porta01": [26,funcao1],
                "porta02": [19,funcao2],
                "porta03": [6,funcao3],
                "porta04": [13,funcao4],
                "porta05": [5,funcao5],
                "porta06": [11,funcao6],
                "porta07": [9,funcao7],
                "porta08": [10,funcao8],
                "porta09": [22,funcao9],
                "porta10": [27,funcao10],
                "porta11": [8,funcao11],
                "porta12": [25,funcao12],
                "porta13": [23,funcao13],
                "porta14": [18,funcao14],
                "porta15": [4,funcao15],
                "porta16": [21,funcao16],
                "porta17": [20,funcao17]
        }
        status = list()  # vari�vel para armazenar o status das 17 portas de expans�o
        status.append(0) # entre status[1] e status[17]
        for porta,funcao in sorted(PortasExpansao.items()):
                status.append(lerPortaExpansao(funcao[0],funcao[1],sala_id)) # Leitura das 17 portas de expans�o

        """
        # Print de confer�ncia
        print (" ____________________")
        print (" | - Resultado -")
        print (" | Sala ID:",sala_id)
        print (" | Umidade:",umidade,"%UR")
        print (" | Temperatura:",temperatura,"�C")
        print (" | Luminosidade: ",nivel_luminosidade)
        for i in range(1,18):
                print (" | Sensor %d: %s" %(i,status[i]))
        print (" |___________________")
        """

        # Salva valores lidos dos sensores no banco de dados local
        SalvarSalasLog(sala_id, umidade, temperatura, nivel_luminosidade, status[1], status[2], status[3], status[4], status[5], status[6], status[7], status[8], status[9], status[10], status[11], status[12], status[13], status[14], status[15], status[16], status[17])
# /Fun��o para ler os sensores internos e as portas de expans�o

print ("= = = Fim LerSensores.py = = =")
