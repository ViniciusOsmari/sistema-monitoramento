# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Leitura de tags RFID 125KHz com módulo RDM6300
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian versão 2.8.2
Data: 11/02/2019

"""

print ("= = = Início RFID.py = = =")

# Carrega módulos do sistema
import Painel
# /Carrega módulos do sistema

# Configuração do modo serial
import serial
# /Configuração do modo serial

# Funções para leitura de RFID
class Leitor6300:
        ReadStart = 2
        ReadEnd = 3
        @staticmethod
        def __verify_checksum(data, checksum):
                try:
                        result = int(data[0:2], 16) \
                                ^ int(data[2:4], 16) \
                                ^ int(data[4:6], 16) \
                                ^ int(data[6:8], 16) \
                                ^ int(data[8:10], 16)
                        result = format(result, 'x')
                except ValueError:
                        return False
                if result.lower() != checksum.lower():
                        return False
                return True

        @staticmethod
        def __fix_zeros(data):
                return data.replace(' ', '0')

        def __read_sequence(self, conexao_serial):
                tag_string = ''
                byte_read = conexao_serial.read()
                if len(byte_read) == 0:
                        return False
                else:
                        if int(ord(byte_read)) != self.ReadStart:
                                return False
                        expected_len = 12
                        while expected_len is not 0:
                                expected_len -= 1
                                byte_read = conexao_serial.read()
                                if int(ord(byte_read)) == self.ReadStart:
                                        expected_len = 12
                                        continue
                                if ord(byte_read) != self.ReadEnd:
                                        tag_string += chr(ord(byte_read))
                                        continue
                                break
                        data = tag_string[0:len(tag_string) - 2]
                        checksum = tag_string[len(tag_string) - 2:len(tag_string)]
                        checksum_ok = self.__verify_checksum(data, checksum)
                        if not checksum_ok:
                                return False
                        return self.__fix_zeros(data)

        # Função para leitura de crachá e tag RFID
        def leitura_rfid(self, tempo_timeout = 300):
                conexao_serial = serial.Serial('/dev/ttyAMA0', baudrate=9600, timeout=tempo_timeout)
                try:
                        data = self.__read_sequence(conexao_serial)
                        conexao_serial.flushInput()
                        if data == False:
                                data=0
                        else:
                                Painel.NotificaLeitura()
                except:
                        data=0
                return data
        # /Função para leitura de crachá e tag RFID
# /Funções para leitura de RFID

print ("= = = Fim RFID.py = = =")
