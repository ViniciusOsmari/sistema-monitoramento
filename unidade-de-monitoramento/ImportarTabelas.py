# -*- coding: cp1252 -*-
#!/usr/bin/python3

"""

Programa: Importar tabelas (usuario e equipamento) do servidor para o Raspberry Pi
Desenvolvido em Python 3.5.3
Testado com Raspberry Pi modelo 3B, Raspbian versão 2.8.2
Data: 23/04/2019

"""

print ("= = = Início ImportarTabelas.py = = =")

# Carrega módulos do sistema
from ConexaoBD_Rpi import ConectarBDRpi
from ConexaoBD_Servidor import ConectarBDServidor
# /Carrega módulos do sistema

# Carrega módulos do painel do sistema
import Painel
# /Carrega módulos do painel do sistema

# Função para importar a tabela usuario do servidor para o Raspberry Pi
def ImportarUsuarios():
        bd_server=ConectarBDServidor()
        Usuario_server = 0
        Usuario_rpi = 0
        if bd_server != None:
                Painel.Mensagem("   Importando   ","    usuários    ")
                cursor_server = bd_server .cursor()
                try:
                        cursor_server.execute("SELECT id, nome, rfid, desativado FROM usuario")
                        Usuario_server = cursor_server.fetchall()
                except:
                        Painel.NotificaErro()
                        Painel.Mensagem("Erro ao importar","usuarios (erro4)", 15)
                cursor_server.close()
                bd_server.close()
                bd_rpi = ConectarBDRpi()
                if bd_rpi != None:
                        cursor_rpi = bd_rpi .cursor()
                        try:
                                cursor_rpi.execute("SELECT id, nome, rfid, desativado FROM usuario")
                                Usuario_rpi = cursor_rpi.fetchall()
                        except:
                                Painel.NotificaErro()
                                Painel.Mensagem("Erro ao importar","usuários (erro3)", 15)
                else:
                        Painel.NotificaErro()
                        Painel.Mensagem("Erro ao importar","usuários (erro3)", 15)
        else:
                Painel.NotificaErro()
                Painel.Mensagem("Erro ao importar","usuários (erro4)", 15)

        # Sincronizar banco de dados no Raspberry Pi (envia novos dados e atualiza dados do servidor para o raspberry pi)
        flag_novo_registro = 0
        if Usuario_server != 0:
                for dado_server in Usuario_server:
                        if Usuario_rpi != 0:
                                for dado_rpi in Usuario_rpi:
                                        flag_novo_registro = 0
                                        if dado_server[0] == dado_rpi[0]:
                                                flag_novo_registro = 1
                                                if dado_server[1] != dado_rpi[1]:
                                                        try:
                                                                cursor_rpi.execute("UPDATE usuario SET nome=%s WHERE id = %s", (dado_server[1], dado_rpi[0]))
                                                                bd_rpi.commit()
                                                        except:
                                                                continue
                                                if dado_server[2] != dado_rpi[2]:
                                                        try:
                                                                cursor_rpi.execute("UPDATE usuario SET rfid=%s WHERE id = %s", (dado_server[2], dado_rpi[0]))
                                                                bd_rpi.commit()
                                                        except:
                                                                continue
                                                if dado_server[3] != dado_rpi[3]:
                                                        try:
                                                                cursor_rpi.execute("UPDATE usuario SET desativado=%s WHERE id = %s", (dado_server[3], dado_rpi[0]))
                                                                bd_rpi.commit()
                                                        except:
                                                                continue
                                                break
                        if flag_novo_registro == 0:
                                try:
                                        cursor_rpi.execute("INSERT INTO usuario (id, nome, rfid, desativado) VALUE (%s, %s, %s, %s)", (dado_server[0], dado_server[1], dado_server[2], dado_server[3]))
                                        bd_rpi.commit()
                                except:
                                        continue
        # /Sincronizar banco de dados no Raspberry Pi

        if Usuario_rpi != 0:
                # Finaliza conexão com o Raspberry Pi
                cursor_rpi.close()
                bd_rpi.close()

                Painel.NotificaOK()
                Painel.Mensagem("    Usuários    ", "   importados   ",5)
# /Função para importar a tabela usuario do servidor para o Raspberry Pi

# Função para importar a tabela equipamento do servidor para o Raspberry Pi
def ImportarEquipamentos():
        bd_server=ConectarBDServidor()
        Equipamento_server = 0
        Equipamento_rpi = 0
        if bd_server != None:
                Painel.Mensagem("   Importando   ","  equipamentos  ")
                cursor_server = bd_server .cursor()
                try:
                        cursor_server.execute("SELECT gepoc_id, equipamento, rfid FROM equipamento")
                        Equipamento_server = cursor_server.fetchall()
                except:
                        Painel.NotificaErro()
                        Painel.Mensagem("Erro ao importar"," equip. (erro6) ", 15)
                cursor_server.close()
                bd_server.close()
                bd_rpi = ConectarBDRpi()
                if bd_rpi != None:
                        cursor_rpi = bd_rpi .cursor()
                        try:
                                cursor_rpi.execute("SELECT gepoc_id, equipamento, rfid FROM equipamento")
                                Equipamento_rpi = cursor_rpi.fetchall()
                        except:
                                Painel.NotificaErro()
                                Painel.Mensagem("Erro ao importar"," equip. (erro5) ", 15)
                else:
                        Painel.NotificaErro()
                        Painel.Mensagem("Erro ao importar"," equip. (erro5) ", 15)
        else:
                Painel.NotificaErro()
                Painel.Mensagem("Erro ao importar"," equip. (erro6) ", 15)

        # Sincronizar banco de dados no Raspberry Pi (envia novos dados e atualiza dados do servidor para o raspberry pi)
        flag_novo_registro = 0
        if Equipamento_server != 0:
                for dado_server in Equipamento_server:
                        if Equipamento_rpi != 0:
                                for dado_rpi in Equipamento_rpi:
                                        flag_novo_registro = 0
                                        if dado_server[0] == dado_rpi[0]:
                                                flag_novo_registro = 1
                                                if dado_server[1] != dado_rpi[1]:
                                                        try:
                                                                cursor_rpi.execute("UPDATE equipamento SET equipamento=%s WHERE gepoc_id = %s", (dado_server[1], dado_rpi[0]))
                                                                bd_rpi.commit()
                                                        except:
                                                                continue
                                                if dado_server[2] != dado_rpi[2]:
                                                        try:
                                                                cursor_rpi.execute("UPDATE equipamento SET rfid=%s WHERE gepoc_id = %s", (dado_server[2], dado_rpi[0]))
                                                                bd_rpi.commit()
                                                        except:
                                                                continue
                                                break
                        if flag_novo_registro == 0:
                                try:
                                        cursor_rpi.execute("INSERT INTO equipamento (gepoc_id, equipamento, rfid) VALUE (%s, %s, %s)", (dado_server[0], dado_server[1], dado_server[2]))
                                        bd_rpi.commit()
                                except:
                                        continue
        # /Sincronizar banco de dados no Raspberry Pi

        if Equipamento_rpi != 0:
                # Finaliza conexão com o Raspberry Pi
                cursor_rpi.close()
                bd_rpi.close()

                Painel.NotificaOK()
                Painel.Mensagem("  Equipamentos  ","   importados   ", 5)
# /Função para importar a tabela equipamento do servidor para o Raspberry Pi

# Função para importar a tabela configuracao_rpi do servidor para o Raspberry Pi
def ImportarConfig():
        bd_server=ConectarBDServidor()
        Config_server = 0
        Config_rpi = 0
        if bd_server != None:
                Painel.Mensagem("   Importando   "," configurações  ")
                cursor_server = bd_server .cursor()
                try:
                        cursor_server.execute("SELECT id, data_modificacao, ip, sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 FROM configuracao_rpi")
                        Config_server = cursor_server.fetchall()
                except:
                        Painel.NotificaErro()
                        Painel.Mensagem("Erro ao importar","config. (erro8) ", 15)
                cursor_server.close()
                bd_server.close()
                bd_rpi = ConectarBDRpi()
                if bd_rpi != None:
                        cursor_rpi = bd_rpi .cursor()
                        try:
                                cursor_rpi.execute("SELECT id, data_modificacao, ip, sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17 FROM configuracao_rpi")
                                Config_rpi = cursor_rpi.fetchall()
                        except:
                                Painel.NotificaErro()
                                Painel.Mensagem("Erro ao importar","config. (erro7) ", 15)
                else:
                        Painel.NotificaErro()
                        Painel.Mensagem("Erro ao importar","config. (erro7) ", 15)
        else:
                Painel.NotificaErro()
                Painel.Mensagem("Erro ao importar","config. (erro8) ", 15)

        # Sincronizar banco de dados no Raspberry Pi (envia novos dados e atualiza dados do servidor para o raspberry pi)
        flag_novo_registro = 0
        if Config_server != 0:
                for dado_server in Config_server:
                        if Config_rpi != 0:
                                for dado_rpi in Config_rpi:
                                        flag_novo_registro = 0
                                        if dado_server[0] == dado_rpi[0]:
                                                flag_novo_registro = 1
                                                if dado_server[1] != dado_rpi[1]:
                                                        try:
                                                                sql_update = ("UPDATE configuracao_rpi SET data_modificacao=%s, ip=%s, sala_id=%s, dht=%s, luminosidade=%s, rfid=%s, porta1=%s, porta2=%s, porta3=%s, porta4=%s, porta5=%s, porta6=%s, porta7=%s, porta8=%s, porta9=%s, porta10=%s, porta11=%s, porta12=%s, porta13=%s, porta14=%s, porta15=%s, porta16=%s, porta17=%s WHERE id = %s")
                                                                valor_update = (dado_server[1], dado_server[2], dado_server[3], dado_server[4], dado_server[5], dado_server[6], dado_server[7], dado_server[8], dado_server[9], dado_server[10], dado_server[11], dado_server[12], dado_server[13], dado_server[14], dado_server[15], dado_server[16], dado_server[17], dado_server[18], dado_server[19], dado_server[20], dado_server[21], dado_server[22], dado_server[23], dado_server[0])
                                                                cursor_rpi.execute(sql_update, valor_update)
                                                                bd_rpi.commit()
                                                        except:
                                                                continue
                                                break
                        if flag_novo_registro == 0:
                                try:
                                        sql_salvar = "INSERT INTO configuracao_rpi (id, data_modificacao, ip, sala_id, dht, luminosidade, rfid, porta1, porta2, porta3, porta4, porta5, porta6, porta7, porta8, porta9, porta10, porta11, porta12, porta13, porta14, porta15, porta16, porta17) VALUE (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
                                        valor_salvar = (dado_server[0], dado_server[1], dado_server[2], dado_server[3], dado_server[4], dado_server[5], dado_server[6], dado_server[7], dado_server[8], dado_server[9], dado_server[10], dado_server[11], dado_server[12], dado_server[13], dado_server[14], dado_server[15], dado_server[16], dado_server[17], dado_server[18], dado_server[19], dado_server[20], dado_server[21], dado_server[22], dado_server[23])
                                        cursor_rpi.execute(sql_salvar, valor_salvar)
                                        bd_rpi.commit()
                                except:
                                        continue
        # /Sincronizar banco de dados no Raspberry Pi

        if Config_rpi != 0:
                # Finaliza conexão com o Raspberry Pi
                cursor_rpi.close()
                bd_rpi.close()

                Painel.NotificaOK()
                Painel.Mensagem(" Configurações  ","   importadas   ", 5)
# /Função para importar a tabela configuracao_rpi do servidor para o Raspberry Pi

print ("= = = Fim ImportarTabelas.py = = =")
