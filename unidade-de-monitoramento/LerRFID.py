# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Leitura de usu�rios e equipamentos com etiquetas RFID
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian vers�o 2.8.2
Data: 26/04/2019

"""

print ("= = = In�cio LerRFID.py = = =")

# Carrega m�dulos do painel do sistema
import Painel
# /Carrega m�dulos do painel do sistema

# Carrega m�dulos de leitura e escrita no banco de dados do Rpi
from LerTabelas import LerUsuario, LerEquipamento, LerSituacaoEquipamento
from SalvarTabelas import SalvarEquipamentosLog, SalvarEquipamentosLogDevolucao
from SincronizarTabelas import SincronizarEquipamentoLog
# /Carrega m�dulos de leitura e escrita no banco de dados do Rpi

# Fun��o para leitura de crach� de usu�rio e tag de equipamento
def lerRFID(sala_id, tipo_rfid):
        # Verifica se o leitor RFID � o RDM 6300
        if tipo_rfid == 9:
                # Carrega m�dulos do leitor RFID
                from RFID_RDM6300 import Leitor6300
                leitor_rfid = Leitor6300()
                # /Carrega m�dulos do leitor RFID

                Painel.Mensagem("    Aproxime    ","   seu crach�   ")
                while True:
                        Painel.led1.on()
                        usuario_rfid=leitor_rfid.leitura_rfid(tempo_timeout=120)
                        if usuario_rfid == 0:
                                # Desliga a luz de fundo do display ap�s 120 segundo sem atividade
                                try:
                                        Painel.display.backlight(0)
                                except:
                                        print ("N�o foi poss�vel desativar o backlight")
                        else:
                                # Busca na tabela usuarios o id e o nome atrav�s do RFID lido
                                usuario_id, usuario_nome = LerUsuario(usuario_rfid)
                                # Verifica erros
                                if usuario_id == -1:
                                        # Erro de usu�rio desativado
                                        Painel.NotificaErro()
                                        Painel.Mensagem("  Este usuario  ","n�o esta ativado", 5)
                                elif usuario_id == 0:
                                        # Erro de usu�rio n�o encontrado na tabela usuarios
                                        Painel.NotificaErro()
                                        Painel.Mensagem("   Usuario n�o  "," foi encontrado ", 5)
                                elif usuario_id == 1:
                                        # Modo devolu��o de equipamento
                                        Painel.NotificaOK()
                                        Painel.NotificaOK()
                                        Painel.led1.on()
                                        Painel.Mensagem(" Modo devolu��o "," de equipamento ")
                                        SincronizarEquipamentoLog(0)
                                        Painel.Mensagem("   Aproxime o   ","  equipamento   ")
                                        equip_rfid=leitor_rfid.leitura_rfid(tempo_timeout=30)
                                        if equip_rfid == 0:
                                                # Tempo de leitura expirado
                                                Painel.NotificaErro()
                                                Painel.Mensagem("Tempo de leitura","    expirado    ", 5)
                                        else:
                                                equip_id, equip_nome = LerEquipamento(equip_rfid)
                                                if equip_id == 0:
                                                        # Equipamento n�o encontrado na tabela equipamentos
                                                        Painel.NotificaErro()
                                                        Painel.Mensagem("Equipamento n�o "," foi encontrado ", 5)
                                                else:
                                                        id, data_devolucao = LerSituacaoEquipamento(equip_id)
                                                        if id == -1:
                                                                # Equipamento n�o possui sa�das em aberto
                                                                Painel.NotificaErro()
                                                                Painel.Mensagem("Equipamento n�o ","est� emprestado ", 5)
                                                        else:
                                                                if data_devolucao == None:
                                                                        # Equipamento marcado como devolvido na tabela equipamento_log
                                                                        SalvarEquipamentosLogDevolucao(id)
                                                                        Painel.NotificaOK()
                                                                        Painel.Mensagem("  Equipamento   ","   devolvido    ", 5)
                                                                else:
                                                                        # Equipamento n�o est� emprestado
                                                                        Painel.NotificaErro()
                                                                        Painel.Mensagem("Equipamento n�o ","est� emprestado ", 5)
                                elif usuario_id == 2:
                                        Painel.NotificaOK()
                                        Painel.NotificaOK()
                                        Painel.led1.on()
                                        Painel.Mensagem("  Modo leitura  ","  de tags RFID  ", 3)
                                        Painel.Mensagem("   Aproxime a   "," etiqueta RFID  ")
                                        tag_rfid=leitor_rfid.leitura_rfid(tempo_timeout=30)
                                        if tag_rfid == 0:
                                                # Tempo de leitura expirado
                                                Painel.NotificaErro()
                                                Painel.Mensagem("Tempo de leitura","    expirado    ", 5)
                                        else:
                                                Painel.Mensagem("   ID da tag    ","   "+str(tag_rfid)+"   ", 25)
                                else:
                                # Usu�rio encontrado, passa para a leitura de equipamento
                                        Painel.Mensagem("Usuario encontr.",str(usuario_nome), 3)
                                        Painel.Mensagem("   Aproxime o   ","  equipamento   ")
                                        # Tenta ler o equipamento por 15 segundos
                                        equip_rfid=leitor_rfid.leitura_rfid(tempo_timeout=15)
                                        if equip_rfid == 0:
                                                # Tempo de leitura expirado
                                                Painel.NotificaErro()
                                                Painel.Mensagem("Tempo de leitura","    expirado    ", 5)
                                        else:
                                                # Busca na tabela equipamentos o id e o nome atrav�s do RFID lido
                                                equip_id, equip_nome = LerEquipamento(equip_rfid)
                                                if equip_id == 0:
                                                        # Equipamento n�o encontrado na tabela equipamentos
                                                        Painel.NotificaErro()
                                                        Painel.Mensagem("Equipamento n�o "," foi encontrado ", 5)
                                                else:
                                                        # Equipamento encontrado na tabela equipamentos
                                                        id, data_devolucao = LerSituacaoEquipamento(equip_id)
                                                        if id == -1:
                                                                # Equipamento n�o possui sa�das em aberto
                                                                Painel.NotificaErro()
                                                                Painel.Mensagem("Equipamento n�o ","est� emprestado ", 5)
                                                        else:
                                                                if data_devolucao == None:
                                                                        # Equipamento j� est� emprestado
                                                                        Painel.NotificaErro()
                                                                        Painel.Mensagem(" Equipamento j� ", "est� emprestado ", 3)
                                                                else:
                                                                        # Equipamento marcado como emprestado
                                                                        Painel.Mensagem("Equip encontrado", str(equip_nome), 3)
                                                                        SalvarEquipamentosLog(equip_id,usuario_id,sala_id)
                                                                        Painel.NotificaOK()
                                                                        Painel.Mensagem("   Empr�stimo   ", "   confirmado   ", 3)
                                Painel.Mensagem("    Aproxime    ","   seu crach�   ")
# /Fun��o para leitura de crach� de usu�rio e tag de equipamento

print ("= = = Fim LerRFID.py = = =")
