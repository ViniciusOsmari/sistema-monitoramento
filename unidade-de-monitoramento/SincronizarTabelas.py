# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Sincronizar tabelas entre o servidor e o Raspberry Pi
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian versão 2.8.2
Data: 29/04/2019

"""

print ("= = = Início SincronizarTabelas.py = = =")

# Carrega módulos do sistema
from ConexaoBD_Rpi import ConectarBDRpi
from ConexaoBD_Servidor import ConectarBDServidor
# /Carrega módulos do sistema

# Carrega módulos do painel do sistema
import Painel
# /Carrega módulos do painel do sistema

# /Função para sincronizar a tabela equipamento_log entre o servidor e o Raspberry Pi
def SincronizarEquipamentoLog(display = 1):
        # SincronizarEquipamentoLog(0) executa a função sem exibir mensagem no display
        # SincronizarEquipamentoLog() executa a função exibindo mensagens no display
        bd_server=ConectarBDServidor()
        EquipamentoLog_server = 0
        EquipamentoLog_rpi = 0
        if bd_server != None:
                if display == 1:
                        Painel.Mensagem("  Sincronizando ","equipamento log ")
                cursor_server = bd_server .cursor()
                try:
                        cursor_server.execute("SELECT id, data_retirada, data_devolucao, equipamento_id, usuario_id, sala_id FROM equipamento_log ORDER BY id")
                        EquipamentoLog_server = cursor_server.fetchall()
                except:
                        if display == 1:
                                Painel.NotificaErro()
                                Painel.Mensagem("Erro ao sincron.","equi.log(erro16)", 15)
                bd_rpi = ConectarBDRpi()
                if bd_rpi != None:
                        cursor_rpi = bd_rpi .cursor()
                        try:
                                cursor_rpi.execute("SELECT id, data_retirada, data_devolucao, equipamento_id, usuario_id, sala_id FROM equipamento_log ORDER BY id")
                                EquipamentoLog_rpi = cursor_rpi.fetchall()
                        except:
                                if display == 1:
                                        Painel.NotificaErro()
                                        Painel.Mensagem("Erro ao sincron.","equi.log(erro15)", 15)
                else:
                        if display == 1:
                                Painel.NotificaErro()
                                Painel.Mensagem("Erro ao sincron.","equi.log(erro15)", 15)
        else:
                if display == 1:
                        Painel.NotificaErro()
                        Painel.Mensagem("Erro ao sincron.","equi.log(erro16)", 15)

        flag_novo_registro = 0
        if EquipamentoLog_server != 0:
                # Sincronizar banco de dados no Servidor (envia novos dados do raspberry pi para o servidor)
                if len(EquipamentoLog_rpi) != 0:
                        for dado_rpi in EquipamentoLog_rpi:
                                for dado_server in EquipamentoLog_server:
                                        flag_novo_registro = 0
                                        if dado_rpi[0] == dado_server[0]:
                                                flag_novo_registro = 1
                                                if dado_rpi[2] != dado_server[2] and dado_rpi[2] != None:
                                                        try:
                                                                cursor_server.execute("UPDATE equipamento_log SET data_devolucao=%s WHERE id = %s", (dado_rpi[2], dado_rpi[0]))
                                                                bd_server.commit()
                                                        except:
                                                                continue
                                                break
                                if flag_novo_registro == 0:
                                        try:
                                                cursor_server.execute("INSERT INTO equipamento_log (id, data_retirada, data_devolucao, equipamento_id, usuario_id, sala_id) VALUE (%s, %s, %s, %s, %s, %s)", (dado_rpi[0], dado_rpi[1], dado_rpi[2], dado_rpi[3], dado_rpi[4], dado_rpi[5]))
                                                bd_server.commit()
                                        except:
                                                continue
                # /Sincronizar banco de dados no Servidor

                # Sincronizar banco de dados no Raspberry Pi (envia novos dados do servidor para o raspberry pi)
                for dado_server in EquipamentoLog_server:
                        if len(EquipamentoLog_rpi) != 0:
                                for dado_rpi in EquipamentoLog_rpi:
                                        flag_novo_registro = 0
                                        if dado_server[0] == dado_rpi[0]:
                                                flag_novo_registro = 1
                                                if dado_server[2] != dado_rpi[2] and dado_server[2] != None:
                                                        try:
                                                                cursor_rpi.execute("UPDATE equipamento_log SET data_devolucao=%s WHERE id = %s", (dado_server[2], dado_rpi[0]))
                                                                bd_rpi.commit()
                                                        except:
                                                                continue
                                                break
                        if flag_novo_registro == 0:
                                try:
                                        cursor_rpi.execute("INSERT INTO equipamento_log (id, data_retirada, data_devolucao, equipamento_id, usuario_id, sala_id) VALUE (%s, %s, %s, %s, %s, %s)", (dado_server[0], dado_server[1], dado_server[2], dado_server[3], dado_server[4], dado_server[5]))
                                        bd_rpi.commit()
                                except:
                                        continue
                # /Sincronizar banco de dados no Raspberry Pi
                
                # Finaliza conexão com o servidor
                cursor_server.close()
                bd_server.close()

                # Finaliza conexão com o Raspberry Pi
                cursor_rpi.close()
                bd_rpi.close()

                if display == 1:
                        Painel.NotificaOK()
                        Painel.Mensagem("Equipamento log ","  sincronizada  ", 5)
# /Função para sincronizar a tabela equipamento_log entre o servidor e o Raspberry Pi

# Função para sincronizar a tabela sala_log do Raspberry Pi para o servidor
def SincronizarSalaLog(parametro_sala_id, display = 1):
        bd_server=ConectarBDServidor()
        SalaLog_server = 0
        SalaLog_rpi = 0
        if bd_server != None:
                if display == 1:
                        Painel.Mensagem("  Sincronizando ","    sala log    ")
                cursor_server = bd_server .cursor()
                try:
                        sql_importar = "SELECT id, data, sala_id, umidade, temperatura, luminosidade, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 FROM sala_log WHERE sala_id = %s ORDER BY id"
                        cursor_server.execute(sql_importar, parametro_sala_id)
                        SalaLog_server = cursor_server.fetchall()
                except:
                        if display == 1:
                                Painel.NotificaErro()
                                Painel.Mensagem("Erro ao sincron.","sala log(erro14)", 15)
                bd_rpi = ConectarBDRpi()
                if bd_rpi != None:
                        cursor_rpi = bd_rpi .cursor()
                        try:
                                cursor_rpi.execute("SELECT id, data, sala_id, umidade, temperatura, luminosidade, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 FROM sala_log ORDER BY id")
                                SalaLog_rpi = cursor_rpi.fetchall()
                        except:
                                if display == 1:
                                        Painel.NotificaErro()
                                        Painel.Mensagem("Erro ao sincron.","sala log(erro13)", 15)
                else:
                        if display == 1:
                                Painel.NotificaErro()
                                Painel.Mensagem("Erro ao sincron.","sala log(erro13)", 15)
        else:
                if display == 1:
                        Painel.NotificaErro()
                        Painel.Mensagem("Erro ao sincron.","sala log(erro14)", 15)

        flag_novo_registro = 0
        if SalaLog_server != 0:
                # Sincronizar banco de dados no Servidor (envia novos dados do raspberry pi para o servidor)
                if len(SalaLog_rpi) != 0:
                        for dado_rpi in SalaLog_rpi:
                                for dado_server in SalaLog_server:
                                        flag_novo_registro = 0
                                        if dado_rpi[0] == dado_server[0]:
                                                flag_novo_registro = 1
                                                break
                                if flag_novo_registro == 0:
                                        try:
                                                cursor_server.execute("INSERT INTO sala_log (id, data, sala_id, umidade, temperatura, luminosidade, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17) VALUE (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", (dado_rpi[0], dado_rpi[1], dado_rpi[2], dado_rpi[3], dado_rpi[4], dado_rpi[5], dado_rpi[6], dado_rpi[7], dado_rpi[8], dado_rpi[9], dado_rpi[10], dado_rpi[11], dado_rpi[12], dado_rpi[13], dado_rpi[14], dado_rpi[15], dado_rpi[16], dado_rpi[17], dado_rpi[18], dado_rpi[19], dado_rpi[20], dado_rpi[21], dado_rpi[22]))
                                                bd_server.commit()
                                        except:
                                                continue
                # /Sincronizar banco de dados no Servidor

                # Sincronizar banco de dados no Raspberry Pi (envia novos dados do servidor para o raspberry pi)
                for dado_server in SalaLog_server:
                        if len(SalaLog_rpi) != 0:
                                for dado_rpi in SalaLog_rpi:
                                        flag_novo_registro = 0
                                        if dado_server[0] == dado_rpi[0]:
                                                flag_novo_registro = 1
                                                break
                        if flag_novo_registro == 0:
                                try:
                                        cursor_rpi.execute("INSERT INTO sala_log (id, data, sala_id, umidade, temperatura, luminosidade, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17) VALUE (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", (dado_server[0], dado_server[1], parametro_sala_id, dado_server[2], dado_server[3], dado_server[4], dado_server[5], dado_server[6], dado_server[7], dado_server[8], dado_server[9], dado_server[10], dado_server[11], dado_server[12], dado_server[13], dado_server[14], dado_server[15], dado_server[16], dado_server[17], dado_server[18], dado_server[19], dado_server[20], dado_server[21]))
                                        bd_rpi.commit()
                                except:
                                        continue
                # /Sincronizar banco de dados no Raspberry Pi

                # Finaliza conexão com o servidor
                cursor_server.close()
                bd_server.close()

                # Finaliza conexão com o Raspberry Pi
                cursor_rpi.close()
                bd_rpi.close()

                if display == 1:
                        Painel.NotificaOK()
                        Painel.Mensagem("    Sala log    ","  sincronizada  ", 5)
# /Função para sincronizar a tabela sala_log do Raspberry Pi para o servidor

# /Função para sincronizar a tabela alarme entre o servidor e o Raspberry Pi
def SincronizarAlarme(display = 1):
        bd_server=ConectarBDServidor()
        Alarme_server = 0
        Alarme_rpi = 0
        if bd_server != None:
                if display == 1:
                        Painel.Mensagem("  Sincronizando ","  tabela alarme ")
                cursor_server = bd_server .cursor()
                try:
                        cursor_server.execute("SELECT id, data, sala_id, motivo FROM alarme ORDER BY id")
                        Alarme_server = cursor_server.fetchall()
                except:
                        if display == 1:
                                Painel.NotificaErro()
                                Painel.Mensagem("Erro ao sincron.","alarme (erro23) ", 15)
                bd_rpi = ConectarBDRpi()
                if bd_rpi != None:
                        cursor_rpi = bd_rpi .cursor()
                        try:
                                cursor_rpi.execute("SELECT id, data, sala_id, motivo FROM alarme ORDER BY id")
                                Alarme_rpi = cursor_rpi.fetchall()
                        except:
                                if display == 1:
                                        Painel.NotificaErro()
                                        Painel.Mensagem("Erro ao sincron.","alarme (erro22) ", 15)
                else:
                        if display == 1:
                                Painel.NotificaErro()
                                Painel.Mensagem("Erro ao sincron.","alarme (erro22) ", 15)
        else:
                if display == 1:
                        Painel.NotificaErro()
                        Painel.Mensagem("Erro ao sincron.","alarme (erro23) ", 15)

        flag_novo_registro = 0
        if Alarme_server != 0 or Alarme_rpi != 0:
                # Sincronizar banco de dados no Raspberry Pi (envia novos dados do servidor para o raspberry pi)
                for dado_server in Alarme_server:
                        if Alarme_rpi != 0:
                                for dado_rpi in Alarme_rpi:
                                        flag_novo_registro = 0
                                        if dado_server[0] == dado_rpi[0]:
                                                flag_novo_registro = 1
                                                if dado_server[1] != dado_rpi[1]:
                                                        try:
                                                                cursor_server.execute("UPDATE alarme SET data=%s, sala_id=%s, motivo=%s WHERE id = %s", (dado_server[1], dado_server[2], dado_server[3], dado_server[0]))
                                                                bd_rpi.commit()
                                                        except:
                                                                continue
                                                break
                        if flag_novo_registro == 0:
                                try:
                                        cursor_rpi.execute("INSERT INTO alarme (id, data, sala_id, motivo) VALUE (%s, %s, %s, %s)", (dado_server[0], dado_server[1], dado_server[2], dado_server[3]))
                                        bd_rpi.commit()
                                except:
                                        continue
                # /Sincronizar banco de dados no Raspberry Pi

                # Sincronizar banco de dados no Servidor (envia novos dados do raspberry pi para o servidor)
                if Alarme_rpi != 0:
                        for dado_rpi in Alarme_rpi:
                                for dado_server in Alarme_server:
                                        flag_novo_registro = 0
                                        if dado_rpi[0] == dado_server[0]:
                                                flag_novo_registro = 1
                                                break
                                if flag_novo_registro == 0:
                                        try:
                                                cursor_server.execute("INSERT INTO alarme (id, data, sala_id, motivo) VALUE (%s, %s, %s, %s)", (dado_rpi[0], dado_rpi[1], dado_rpi[2], dado_rpi[3]))
                                                bd_server.commit()
                                        except:
                                                continue
                # /Sincronizar banco de dados no Servidor

                # Finaliza conexão com o servidor
                cursor_server.close()
                bd_server.close()

                # Finaliza conexão com o Raspberry Pi
                cursor_rpi.close()
                bd_rpi.close()

                if display == 1:
                        Painel.NotificaOK()
                        Painel.Mensagem("  Tabela alarme ","  sincronizada  ", 5)
# /Função para sincronizar a tabela alarme entre o servidor e o Raspberry Pi

print ("= = = Fim SincronizarTabelas.py = = =")
