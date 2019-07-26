# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Salvar tabelas no banco de dados do Raspberry Pi
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian versão 2.8.2
Data: 17/05/2019

"""

print ("= = = Início SalvarTabelas.py = = =")

# Configuração do Rasbian/Raspberry Pi
import datetime
# /Configuração do Rasbian/Raspberry Pi

# Carrega módulos do sistema
from ConexaoBD_Rpi import ConectarBDRpi
from ConexaoBD_Servidor import ConectarBDServidor
import Painel
# /Carrega módulos do sistema

# Carrega e executa a sincronização das tabelas entro o Rpi e o servidor
import SincronizarTabelas
# /Carrega e executa a sincronização das tabelas entro o Rpi e o servidor

# Função para inserir registro na tabela equipamento_log no Banco de Dados do Raspberry Pi
def SalvarEquipamentosLog(parametro_equipamento_id, parametro_usuario_id, parametro_sala_id):
        bd_rpi = ConectarBDRpi()
        if bd_rpi != None:
                cursor_rpi = bd_rpi.cursor()
                try:
                        cursor_rpi.execute("INSERT INTO equipamento_log (equipamento_id, usuario_id, sala_id) VALUES (%s, %s, %s)", (parametro_equipamento_id, parametro_usuario_id, parametro_sala_id))
                        bd_rpi.commit()
                except:
                        Painel.NotificaErro()
                        Painel.Mensagem(" Erro ao salvar ","    (erro 9)    ", 10)
                cursor_rpi.close()
                bd_rpi.close()
        else:
                Painel.NotificaErro()
                Painel.Mensagem(" Erro ao salvar ","    (erro 9)    ", 10)
        SincronizarTabelas.SincronizarEquipamentoLog(0)
# /Função para inserir registro na tabela equipamento_log no Banco de Dados do Raspberry Pi

# Função para marcar equipamento como devolvido na tabela equipamento_log no Banco de Dados do Raspberry Pi
def SalvarEquipamentosLogDevolucao(parametro_id):
        bd_rpi = ConectarBDRpi()
        if bd_rpi != None:
                cursor_rpi = bd_rpi.cursor()
                try:
                        cursor_rpi.execute("UPDATE equipamento_log SET data_devolucao=NOW() WHERE id = %s", parametro_id)
                        bd_rpi.commit()
                except:
                        Painel.NotificaErro()
                        Painel.Mensagem(" Erro ao salvar ","   (erro 12)    ", 10)
                cursor_rpi.close()
                bd_rpi.close()
        else:
                Painel.NotificaErro()
                Painel.Mensagem(" Erro ao salvar ","   (erro 12)    ", 10)
        SincronizarTabelas.SincronizarEquipamentoLog(0)
# /Função para marcar equipamento como devolvido na tabela equipamento_log no Banco de Dados do Raspberry Pi

# Função para inserir registro na tabela sala_log no Banco de Dados do Raspberry Pi
def SalvarSalasLog(par_sala_id, par_umidade, par_temperatura, par_luminosidade, par_porta1, par_porta2, par_porta3, par_porta4, par_porta5, par_porta6, par_porta7, par_porta8, par_porta9, par_porta10, par_porta11, par_porta12, par_porta13, par_porta14, par_porta15, par_porta16, par_porta17):
        bd_rpi = ConectarBDRpi()
        if bd_rpi != None:
                cursor_rpi = bd_rpi.cursor()
                try:
                        sql_inserir = "INSERT INTO sala_log (sala_id, umidade, temperatura, luminosidade, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
                        cursor_rpi.execute(sql_inserir, (par_sala_id, par_umidade, par_temperatura, par_luminosidade, par_porta1, par_porta2, par_porta3, par_porta4, par_porta5, par_porta6, par_porta7, par_porta8, par_porta9, par_porta10, par_porta11, par_porta12, par_porta13, par_porta14, par_porta15, par_porta16, par_porta17))
                        bd_rpi.commit()
                except:
                        Painel.NotificaErro()
                        Painel.Mensagem(" Erro ao salvar ","   (erro 10)    ", 10)
                cursor_rpi.close()
                bd_rpi.close()
        else:
                Painel.NotificaErro()
                Painel.Mensagem(" Erro ao salvar ","   (erro 10)    ", 10)
        SincronizarTabelas.SincronizarSalaLog(par_sala_id, 0)
# /Função para inserir registro na tabela sala_log no Banco de Dados do Raspberry Pi

# Função para marcar equipamento como devolvido na tabela alarme no Banco de Dados do Raspberry Pi
def SalvarAlarme(parametro_sala_id, parametro_motivo):
        bd_rpi = ConectarBDRpi()
        if bd_rpi != None:
                cursor_rpi = bd_rpi.cursor()
                try:
                        cursor_rpi.execute("INSERT INTO alarme (sala_id, motivo) VALUES (%s, %s)", (parametro_sala_id, parametro_motivo))
                        bd_rpi.commit()
                except:
                        Painel.NotificaErro()
                        Painel.Mensagem(" Erro ao salvar ","   (erro 20)    ", 10)
                cursor_rpi.close()
                bd_rpi.close()
        else:
                Painel.NotificaErro()
                Painel.Mensagem(" Erro ao salvar ","   (erro 20)    ", 10)
        SincronizarTabelas.SincronizarAlarme(0)
# /Função para marcar equipamento como devolvido na tabela alarme no Banco de Dados do Raspberry Pi

print ("= = = Fim SalvarTabelas.py = = =")
