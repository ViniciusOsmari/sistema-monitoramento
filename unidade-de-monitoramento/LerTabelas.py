# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Ler tabelas (usuarios e equipamentos) do banco de dados do Raspberry Pi
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian versão 2.8.2
Data: 30/04/2019

"""

print ("= = = Início LerTabelas.py = = =")

# Carrega módulos do sistema
from ConexaoBD_Rpi import ConectarBDRpi
# /Carrega módulos do sistema

# Função para identificar o nome e o id do usuario através do rfid na tabela usuarios
def LerUsuario(tag):
        bd_rpi = ConectarBDRpi()
        if bd_rpi != None:
                cursor_rpi = bd_rpi.cursor()
                try:
                        validacao = cursor_rpi.execute("SELECT id, nome, desativado FROM usuario WHERE rfid = %s", tag)
                except:
                        user_id, user_nome = (0,)*2
                cursor_rpi.close()
                bd_rpi.close()
                if validacao == 1:
                        # Tag encontrada na tabela usuário
                        user_id, user_nome, user_desativado = cursor_rpi.fetchone()
                        if user_desativado == 1:
                                # O usuário está desativado do sistema
                                user_id, user_nome = (-1,)*2
                else:
                        # Tag não foi encontrada na tabela usuário
                        user_id, user_nome = (0,)*2
        else:
                # Não foi possível conectar ao banco de dados do Raspberry Pi
                user_id, user_nome = (-2,)*2
        return user_id, user_nome
# /Função para identificar o nome e o id do usuario através do rfid na tabela usuarios

# Função para identificar o nome e o gepoc_id do equipamento através do rfid na tabela equipamentos
def LerEquipamento(tag):
        bd_rpi = ConectarBDRpi()
        if bd_rpi != None:
                cursor_rpi = bd_rpi.cursor()
                try:
                        validacao = cursor_rpi.execute("SELECT gepoc_id, equipamento FROM equipamento WHERE rfid = %s", tag)
                except:
                        equip_id, equip_nome = (0,)*2
                cursor_rpi.close()
                bd_rpi.close()
                if validacao == 1:
                        # Tag encontrada na tabela equipamento
                        equip_id, equip_nome = cursor_rpi.fetchone()
                else:
                        # Tag não foi encontrada na tabela equipamento
                        equip_id, equip_nome = (0,)*2
        else:
                # Não foi possível conectar ao banco de dados do Raspberry Pi
                equip_id, equip_nome = (0,)*2
        return equip_id, equip_nome
# /Função para identificar o nome e o gepoc_id do equipamento através do rfid na tabela equipamentos

# Função para ler a tabela configuracao, selecionando a configuração específica da máquina
def LerConfig():
        import socket
        from os import popen
        ip = ""
        try:
                # Obter o endereço de IP do Raspberry Pi
                gw=popen("ip -4 route show default").read().split()
                s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
                s.connect((gw[2],0))
                ip = s.getsockname()[0]
                s.close()
                # /Obter o endereço de IP do Raspberry Pi
                bd_rpi = ConectarBDRpi()
                if bd_rpi != None:
                        cursor_rpi = bd_rpi .cursor()
                        try:
                                sql_importar = "SELECT sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 FROM configuracao_rpi WHERE ip = %s"
                                validacao = cursor_rpi.execute(sql_importar, ip)
                        except:
                                sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 = (0,)*21
                        cursor_rpi.close()
                        bd_rpi.close()
                        if validacao == 1:
                                sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 = cursor_rpi.fetchone()
                        else:
                                # O IP não foi encontrado na tabela configuracao_rpi
                                sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 = (0,)*21
                else:
                        # Não foi possível conectar ao banco de dados do Servidor
                        sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 = (-2,)*21
        except:
                # Equipamento não conectado à rede
                sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 = (-1,)*21
        return sala_id , dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17
# /Função para ler a tabela configuracao, selecionando a configuração específica da máquina

# Função para identificar o nome e o gepoc_id do equipamento através do rfid na tabela equipamentos
def LerSituacaoEquipamento(equipamento_id):
        bd_rpi = ConectarBDRpi()
        if bd_rpi != None:
                cursor_rpi = bd_rpi.cursor()
                try:
                        sql_ler = "SELECT id, data_devolucao FROM equipamento_log WHERE equipamento_id = %s ORDER BY id DESC"
                        validacao = cursor_rpi.execute(sql_ler, equipamento_id)
                except:
                        id, data_devolucao = (-1,)*2
                cursor_rpi.close()
                bd_rpi.close()
                if validacao == 0:
                        # Equipamento não foi encontrado na tabela equipamento_log
                        id, data_devolucao = (-1,)*2
                else:
                        # Equipamento encontrado na tabela equipamento_log
                        id, data_devolucao = cursor_rpi.fetchone()
        else:
                # Não foi possível conectar ao banco de dados do Raspberry Pi
                id, data_devolucao = (-1,)*2
        return id, data_devolucao
# /Função para identificar o nome e o gepoc_id do equipamento através do rfid na tabela equipamentos

print ("= = = Fim LerTabelas.py = = =")
