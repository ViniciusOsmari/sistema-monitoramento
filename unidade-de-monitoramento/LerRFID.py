# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Leitura de usuários e equipamentos com etiquetas RFID
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian versão 2.8.2
Data: 26/04/2019

"""

print ("= = = Início LerRFID.py = = =")

# Carrega módulos do painel do sistema
import Painel
# /Carrega módulos do painel do sistema

# Carrega módulos de leitura e escrita no banco de dados do Rpi
from LerTabelas import LerUsuario, LerEquipamento, LerSituacaoEquipamento
from SalvarTabelas import SalvarEquipamentosLog, SalvarEquipamentosLogDevolucao
from SincronizarTabelas import SincronizarEquipamentoLog
# /Carrega módulos de leitura e escrita no banco de dados do Rpi

# Função para leitura de crachá de usuário e tag de equipamento
def lerRFID(sala_id, tipo_rfid):
        # Verifica se o leitor RFID é o RDM 6300
        if tipo_rfid == 9:
                # Carrega módulos do leitor RFID
                from RFID_RDM6300 import Leitor6300
                leitor_rfid = Leitor6300()
                # /Carrega módulos do leitor RFID

                Painel.Mensagem("    Aproxime    ","   seu crachá   ")
                while True:
                        Painel.led1.on()
                        usuario_rfid=leitor_rfid.leitura_rfid(tempo_timeout=120)
                        if usuario_rfid == 0:
                                # Desliga a luz de fundo do display após 120 segundo sem atividade
                                try:
                                        Painel.display.backlight(0)
                                except:
                                        print ("Não foi possível desativar o backlight")
                        else:
                                # Busca na tabela usuarios o id e o nome através do RFID lido
                                usuario_id, usuario_nome = LerUsuario(usuario_rfid)
                                # Verifica erros
                                if usuario_id == -1:
                                        # Erro de usuário desativado
                                        Painel.NotificaErro()
                                        Painel.Mensagem("  Este usuario  ","não esta ativado", 5)
                                elif usuario_id == 0:
                                        # Erro de usuário não encontrado na tabela usuarios
                                        Painel.NotificaErro()
                                        Painel.Mensagem("   Usuario não  "," foi encontrado ", 5)
                                elif usuario_id == 1:
                                        # Modo devolução de equipamento
                                        Painel.NotificaOK()
                                        Painel.NotificaOK()
                                        Painel.led1.on()
                                        Painel.Mensagem(" Modo devolução "," de equipamento ")
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
                                                        # Equipamento não encontrado na tabela equipamentos
                                                        Painel.NotificaErro()
                                                        Painel.Mensagem("Equipamento não "," foi encontrado ", 5)
                                                else:
                                                        id, data_devolucao = LerSituacaoEquipamento(equip_id)
                                                        if id == -1:
                                                                # Equipamento não possui saídas em aberto
                                                                Painel.NotificaErro()
                                                                Painel.Mensagem("Equipamento não ","está emprestado ", 5)
                                                        else:
                                                                if data_devolucao == None:
                                                                        # Equipamento marcado como devolvido na tabela equipamento_log
                                                                        SalvarEquipamentosLogDevolucao(id)
                                                                        Painel.NotificaOK()
                                                                        Painel.Mensagem("  Equipamento   ","   devolvido    ", 5)
                                                                else:
                                                                        # Equipamento não está emprestado
                                                                        Painel.NotificaErro()
                                                                        Painel.Mensagem("Equipamento não ","está emprestado ", 5)
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
                                # Usuário encontrado, passa para a leitura de equipamento
                                        Painel.Mensagem("Usuario encontr.",str(usuario_nome), 3)
                                        Painel.Mensagem("   Aproxime o   ","  equipamento   ")
                                        # Tenta ler o equipamento por 15 segundos
                                        equip_rfid=leitor_rfid.leitura_rfid(tempo_timeout=15)
                                        if equip_rfid == 0:
                                                # Tempo de leitura expirado
                                                Painel.NotificaErro()
                                                Painel.Mensagem("Tempo de leitura","    expirado    ", 5)
                                        else:
                                                # Busca na tabela equipamentos o id e o nome através do RFID lido
                                                equip_id, equip_nome = LerEquipamento(equip_rfid)
                                                if equip_id == 0:
                                                        # Equipamento não encontrado na tabela equipamentos
                                                        Painel.NotificaErro()
                                                        Painel.Mensagem("Equipamento não "," foi encontrado ", 5)
                                                else:
                                                        # Equipamento encontrado na tabela equipamentos
                                                        id, data_devolucao = LerSituacaoEquipamento(equip_id)
                                                        if id == -1:
                                                                # Equipamento não possui saídas em aberto
                                                                Painel.NotificaErro()
                                                                Painel.Mensagem("Equipamento não ","está emprestado ", 5)
                                                        else:
                                                                if data_devolucao == None:
                                                                        # Equipamento já está emprestado
                                                                        Painel.NotificaErro()
                                                                        Painel.Mensagem(" Equipamento já ", "está emprestado ", 3)
                                                                else:
                                                                        # Equipamento marcado como emprestado
                                                                        Painel.Mensagem("Equip encontrado", str(equip_nome), 3)
                                                                        SalvarEquipamentosLog(equip_id,usuario_id,sala_id)
                                                                        Painel.NotificaOK()
                                                                        Painel.Mensagem("   Empréstimo   ", "   confirmado   ", 3)
                                Painel.Mensagem("    Aproxime    ","   seu crachá   ")
# /Função para leitura de crachá de usuário e tag de equipamento

print ("= = = Fim LerRFID.py = = =")
