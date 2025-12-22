from datetime import date, timedelta

# variáveis mensais
digital_ocean_reais = 193.58
digital_ocean_dolares = 31.00
g_suit_profinanc = 135.23
do_profinanc_dolares = 10
do_bkp_profinanc_dolares = 2
do_lemp_dolares = 10

# variáveis acordadas
mensalidade_profinanc = 1436.77
mensalidade_consorcio = 212.60
mensalidade_consorcio_novo_acordo = 700.00

# cálculos
dolar_digital_ocean = digital_ocean_reais / digital_ocean_dolares
g_suit_profinanc_tiago = g_suit_profinanc / 2
do_profinanc_reais_tiago = do_profinanc_dolares * dolar_digital_ocean / 2
do_bkp_profinanc_reais = do_bkp_profinanc_dolares * dolar_digital_ocean
do_lemp_reais = do_lemp_dolares * dolar_digital_ocean
parte_tiago = mensalidade_profinanc + mensalidade_consorcio + g_suit_profinanc_tiago + do_profinanc_reais_tiago
parte_profinanc = do_bkp_profinanc_reais + do_lemp_reais + mensalidade_consorcio_novo_acordo
total = parte_tiago + parte_profinanc
meses = {
    1: 'Janeiro', 2: 'Fevereiro', 3: 'Março', 4: 'Abril', 5: 'Maio', 6: 'Junho',
    7: 'Julho', 8: 'Agosto', 9: 'Setembro', 10: 'Outubro', 11: 'Novembro', 12: 'Dezembro'
}
hoje = date.today()
mes_anterior = hoje - timedelta(days=15)

# apresentação
print(f'Recebimentos + Custos Host & E-mail ({meses[mes_anterior.month]}/{mes_anterior.year})\n')
print(f'Vencimento: 15/{hoje.strftime("%m/%Y")}\n')
print(f'A) Mensalidade Profinanc: R${mensalidade_profinanc:.2f}')
print(f'B) Mensalidade Consórcio: R${mensalidade_consorcio:.2f}\n')
print('Custos G Suite Basic')
print(f'C) profinanc.com.br: R${g_suit_profinanc:.2f} / 2 = R${g_suit_profinanc_tiago:.2f}\n')
print('Custos Digital Ocean')
print(f'D) profinanc: US${do_profinanc_dolares:.2f} / 2 = R${do_profinanc_reais_tiago:.2f}')
print(f'E) bkp profinanc:  US${do_bkp_profinanc_dolares:.2f} = R${do_bkp_profinanc_reais:.2f}')
print(f'F) lemp: US${do_lemp_dolares:.2f} = R${do_lemp_reais:.2f}')
print(f'G) Mensalidade Consórcio Novo Acordo: R${mensalidade_consorcio_novo_acordo:.2f}\n')
print(f'Parte Tiago (A+B+C+D): R${parte_tiago:.2f}')
print(f'Parte Profinanc (E+F+G): R${parte_profinanc:.2f}\n')
print(f'Total: R${total:.2f}')
